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
        $validated = $request->validate([
            'service_id' => 'required|exists:services,id',
            'barber_id' => 'required|exists:barbers,id',
            'appointment_date' => 'required|date|after_or_equal:today',
            'appointment_time' => 'required|date_format:H:i',
            'notes' => 'nullable|string'
        ]);

        // Создаем дату и время записи
        $appointmentDateTime = Carbon::parse($validated['appointment_date'] . ' ' . $validated['appointment_time']);

        // Проверяем доступность времени
        $isTimeAvailable = $this->checkTimeAvailability(
            $validated['barber_id'],
            $validated['service_id'],
            $appointmentDateTime
        );

        if (!$isTimeAvailable) {
            return back()->withErrors(['appointment_time' => 'Выбранное время недоступно']);
        }

        $appointment = Appointment::create([
            'user_id' => auth()->id(),
            'service_id' => $validated['service_id'],
            'barber_id' => $validated['barber_id'],
            'appointment_time' => $appointmentDateTime,
            'notes' => $validated['notes'],
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

        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,completed,cancelled'
        ]);

        $appointment->update($validated);

        return redirect()->back()
            ->with('success', 'Статус записи успешно обновлен');
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
}
