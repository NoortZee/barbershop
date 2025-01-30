<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Barber;
use App\Models\Service;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $appointments = auth()->user()->is_admin 
            ? Appointment::with(['user', 'barber', 'service'])->latest()->get()
            : auth()->user()->appointments()->with(['barber', 'service'])->latest()->get();

        return view('appointments.index', compact('appointments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $services = Service::where('is_active', true)->get();
        $barbers = Barber::where('is_active', true)->get();
        return view('appointments.create', compact('services', 'barbers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'barber_id' => 'required|exists:barbers,id',
            'service_id' => 'required|exists:services,id',
            'appointment_date' => 'required|date|after_or_equal:today',
            'appointment_time' => 'required'
        ]);

        $appointmentDateTime = Carbon::parse($request->appointment_date . ' ' . $request->appointment_time);
        
        $appointment = Appointment::create([
            'user_id' => auth()->id(),
            'barber_id' => $request->barber_id,
            'service_id' => $request->service_id,
            'appointment_time' => $appointmentDateTime,
            'status' => 'pending'
        ]);

        return redirect()->route('appointments.show', $appointment)
            ->with('success', 'Запись успешно создана');
    }

    /**
     * Display the specified resource.
     */
    public function show(Appointment $appointment)
    {
        $this->authorize('view', $appointment);
        return view('appointments.show', compact('appointment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Appointment $appointment)
    {
        $this->authorize('delete', $appointment);
        $appointment->delete();
        return redirect()->route('appointments.index')
            ->with('success', 'Запись успешно отменена');
    }

    public function updateStatus(Request $request, Appointment $appointment)
    {
        $this->authorize('update', $appointment);
        
        $request->validate([
            'status' => 'required|in:pending,confirmed,completed,cancelled'
        ]);

        $appointment->update([
            'status' => $request->status
        ]);

        return redirect()->route('appointments.index')
            ->with('success', 'Статус записи успешно обновлен');
    }

    public function checkAvailabilityApi(Request $request)
    {
        $request->validate([
            'service_id' => 'required|exists:services,id',
            'barber_id' => 'required|exists:barbers,id',
            'date' => 'required|date|after_or_equal:today',
            'time' => 'required|date_format:H:i'
        ]);

        $appointmentDateTime = Carbon::parse($request->date . ' ' . $request->time);
        
        $isAvailable = $this->checkTimeAvailability(
            $request->barber_id,
            $request->service_id,
            $appointmentDateTime
        );

        return response()->json(['available' => $isAvailable]);
    }

    private function checkTimeAvailability($barberId, $serviceId, $appointmentDateTime)
    {
        // Получаем мастера и услугу
        $barber = Barber::findOrFail($barberId);
        $service = Service::findOrFail($serviceId);

        // Проверяем рабочие часы мастера
        $dayOfWeek = $appointmentDateTime->locale('ru')->isoFormat('dddd');
        $workingHours = json_decode($barber->working_hours, true);
        
        if (!isset($workingHours[$dayOfWeek])) {
            return false;
        }

        // Парсим рабочие часы
        list($startTime, $endTime) = explode(' - ', $workingHours[$dayOfWeek]);
        $workStart = Carbon::parse($appointmentDateTime->format('Y-m-d') . ' ' . $startTime);
        $workEnd = Carbon::parse($appointmentDateTime->format('Y-m-d') . ' ' . $endTime);

        // Проверяем, попадает ли время записи в рабочие часы
        if ($appointmentDateTime < $workStart || $appointmentDateTime->addMinutes($service->duration) > $workEnd) {
            return false;
        }

        // Проверяем, нет ли пересечений с другими записями
        $conflictingAppointments = Appointment::where('barber_id', $barberId)
            ->where('status', '!=', 'cancelled')
            ->where(function ($query) use ($appointmentDateTime, $service) {
                $query->whereBetween('appointment_time', [
                    $appointmentDateTime,
                    $appointmentDateTime->copy()->addMinutes($service->duration)
                ])
                ->orWhere(function ($q) use ($appointmentDateTime, $service) {
                    $q->where('appointment_time', '<=', $appointmentDateTime)
                        ->whereRaw('DATE_ADD(appointment_time, INTERVAL (SELECT duration FROM services WHERE id = appointments.service_id) MINUTE) > ?', [
                            $appointmentDateTime
                        ]);
                });
            })
            ->exists();

        return !$conflictingAppointments;
    }

    public function getAvailableTimes(Request $request)
    {
        $request->validate([
            'date' => 'required|date|after_or_equal:today',
            'barber_id' => 'required|exists:barbers,id',
            'service_id' => 'required|exists:services,id'
        ]);

        $barber = Barber::findOrFail($request->barber_id);
        $service = Service::findOrFail($request->service_id);
        $date = Carbon::parse($request->date);

        // Получаем рабочие часы мастера для выбранного дня
        $workingHours = $barber->working_hours[strtolower($date->format('l'))] ?? null;
        if (!$workingHours) {
            return response()->json(['available_times' => []]);
        }

        // Разбиваем рабочие часы на начало и конец
        list($startTime, $endTime) = explode('-', $workingHours);
        $startDateTime = Carbon::parse($request->date . ' ' . trim($startTime));
        $endDateTime = Carbon::parse($request->date . ' ' . trim($endTime));

        // Получаем все существующие записи на этот день
        $existingAppointments = Appointment::where('barber_id', $barber->id)
            ->whereDate('appointment_time', $date)
            ->where('status', '!=', 'cancelled')
            ->orderBy('appointment_time')
            ->get();

        // Генерируем временные слоты с интервалом 15 минут
        $availableTimes = [];
        $currentTime = clone $startDateTime;

        while ($currentTime->addMinutes(15) <= $endDateTime) {
            $slotEndTime = (clone $currentTime)->addMinutes($service->duration);
            
            // Проверяем, не пересекается ли слот с существующими записями
            $isAvailable = true;
            foreach ($existingAppointments as $appointment) {
                $appointmentEndTime = (clone $appointment->appointment_time)->addMinutes($appointment->service->duration);
                
                if (($currentTime >= $appointment->appointment_time && $currentTime < $appointmentEndTime) ||
                    ($slotEndTime > $appointment->appointment_time && $slotEndTime <= $appointmentEndTime) ||
                    ($currentTime <= $appointment->appointment_time && $slotEndTime >= $appointmentEndTime)) {
                    $isAvailable = false;
                    break;
                }
            }

            if ($isAvailable && $slotEndTime <= $endDateTime) {
                $availableTimes[] = $currentTime->format('H:i');
            }
        }

        return response()->json(['available_times' => $availableTimes]);
    }
}
