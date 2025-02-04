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
        try {
            $validated = $request->validate([
                'barber_id' => 'required|exists:barbers,id',
                'service_id' => 'required|exists:services,id',
                'appointment_date' => 'required|date|after_or_equal:today',
                'appointment_time' => ['required', 'date_format:H:i']
            ]);

            $appointmentDateTime = Carbon::parse($request->appointment_date . ' ' . $request->appointment_time);
            
            // Проверяем доступность времени
            $isAvailable = $this->checkTimeAvailability(
                $request->barber_id,
                $request->service_id,
                $appointmentDateTime
            );

            if (!$isAvailable) {
                return response()->json([
                    'success' => false,
                    'message' => 'Выбранное время уже занято. Пожалуйста, выберите другое время.'
                ], 422);
            }

            $appointment = Appointment::create([
                'user_id' => auth()->id(),
                'barber_id' => $request->barber_id,
                'service_id' => $request->service_id,
                'appointment_time' => $appointmentDateTime,
                'status' => 'pending'
            ]);

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Запись успешно создана',
                    'appointment' => $appointment
                ]);
            }

            return redirect()->route('appointments.show', $appointment)
                ->with('success', 'Запись успешно создана');
        } catch (\Exception $e) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Произошла ошибка при создании записи',
                    'errors' => ['general' => [$e->getMessage()]]
                ], 422);
            }

            return back()->withErrors(['error' => 'Произошла ошибка при создании записи'])->withInput();
        }
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
        $dayOfWeek = strtolower($appointmentDateTime->format('D')); // Получаем сокращенное название дня недели (mon, tue, etc.)
        $workingHours = $barber->getWorkingHoursForDay($dayOfWeek);
        
        if (empty($workingHours)) {
            return false;
        }

        // Парсим рабочие часы
        list($startTime, $endTime) = explode('-', $workingHours);
        $workStart = Carbon::parse($appointmentDateTime->format('Y-m-d') . ' ' . trim($startTime));
        $workEnd = Carbon::parse($appointmentDateTime->format('Y-m-d') . ' ' . trim($endTime));

        // Проверяем, попадает ли время записи в рабочие часы
        $appointmentEnd = (clone $appointmentDateTime)->addMinutes($service->duration);
        if ($appointmentDateTime < $workStart || $appointmentEnd > $workEnd) {
            return false;
        }

        // Проверяем, нет ли пересечений с другими записями
        $conflictingAppointments = Appointment::where('barber_id', $barberId)
            ->where('status', '!=', 'cancelled')
            ->where(function ($query) use ($appointmentDateTime, $appointmentEnd) {
                $query->whereBetween('appointment_time', [
                    $appointmentDateTime,
                    $appointmentEnd
                ])
                ->orWhere(function ($q) use ($appointmentDateTime, $appointmentEnd) {
                    $q->where('appointment_time', '<', $appointmentEnd)
                        ->whereRaw('DATE_ADD(appointment_time, INTERVAL (SELECT duration FROM services WHERE id = appointments.service_id) MINUTE) > ?', [
                            $appointmentDateTime
                        ]);
                });
            })
            ->exists();

        // Проверяем, нет ли блокировок времени
        $conflictingBlocks = $barber->blockedTimes()
            ->where(function ($query) use ($appointmentDateTime, $appointmentEnd) {
                $query->whereBetween('start_time', [$appointmentDateTime, $appointmentEnd])
                    ->orWhereBetween('end_time', [$appointmentDateTime, $appointmentEnd])
                    ->orWhere(function ($q) use ($appointmentDateTime, $appointmentEnd) {
                        $q->where('start_time', '<=', $appointmentDateTime)
                            ->where('end_time', '>=', $appointmentEnd);
                    });
            })
            ->exists();

        return !$conflictingAppointments && !$conflictingBlocks;
    }

    public function getAvailableTimes(Request $request)
    {
        try {
            \Log::info('getAvailableTimes request:', $request->all());

            $request->validate([
                'date' => 'required|date',
                'barber_id' => 'required|exists:barbers,id',
                'service_id' => 'required|exists:services,id'
            ]);

            $date = $request->get('date');
            $barberId = $request->get('barber_id');
            $serviceId = $request->get('service_id');
            
            // Получаем день недели из даты
            $dayOfWeek = strtolower(Carbon::parse($date)->format('D'));
            
            \Log::info('Date info:', [
                'date' => $date,
                'day_of_week' => $dayOfWeek,
                'barber_id' => $barberId,
                'service_id' => $serviceId
            ]);

            // Получаем текущее время
            $now = Carbon::now();
            
            \Log::info('Time info:', [
                'current_time' => $now->toDateTimeString(),
                'timezone' => config('app.timezone'),
                'php_timezone' => date_default_timezone_get()
            ]);

            // Проверяем корректность даты
            try {
                $requestDate = Carbon::parse($date);
                $now = Carbon::now();
                
                \Log::info('Date comparison:', [
                    'request_date' => $requestDate->toDateString(),
                    'now' => $now->toDateString(),
                    'is_past' => $requestDate->isPast(),
                    'is_today' => $requestDate->isToday(),
                    'comparison' => $requestDate->toDateString() . ' vs ' . $now->toDateString()
                ]);

                // Изменяем проверку даты
                if ($requestDate->lt($now->startOfDay())) {
                    return response()->json([
                        'error' => 'Выбранная дата уже прошла',
                        'available_times' => []
                    ], 200);
                }
            } catch (\Exception $e) {
                \Log::error('Error parsing request date:', [
                    'date' => $date,
                    'error' => $e->getMessage()
                ]);
                return response()->json([
                    'error' => 'Неверный формат даты',
                    'available_times' => []
                ], 200);
            }

            $barber = Barber::findOrFail($barberId);
            $service = Service::findOrFail($serviceId);

            // Получаем рабочие часы мастера на этот день недели
            $workingHours = $barber->getWorkingHoursForDay($dayOfWeek) ?? $barber::$defaultWorkingHours[$dayOfWeek];

            \Log::info('Working hours:', [
                'day_of_week' => $dayOfWeek,
                'working_hours' => $workingHours,
                'default_hours' => $barber::$defaultWorkingHours[$dayOfWeek]
            ]);

            if (empty($workingHours)) {
                return response()->json([
                    'error' => 'В этот день мастер не работает',
                    'available_times' => []
                ], 200);
            }

            // Разбираем рабочие часы
            try {
                list($startTime, $endTime) = explode('-', $workingHours);
                $startTime = Carbon::parse($date . ' ' . trim($startTime));
                $endTime = Carbon::parse($date . ' ' . trim($endTime));

                \Log::info('Parsed working hours:', [
                    'start_time' => $startTime->toDateTimeString(),
                    'end_time' => $endTime->toDateTimeString(),
                    'raw_start' => trim($startTime),
                    'raw_end' => trim($endTime)
                ]);

                if ($startTime->gt($endTime)) {
                    throw new \Exception('Invalid working hours range');
                }
            } catch (\Exception $e) {
                \Log::error('Error parsing working hours:', [
                    'working_hours' => $workingHours,
                    'error' => $e->getMessage()
                ]);
                return response()->json([
                    'error' => 'Ошибка при обработке рабочих часов',
                    'available_times' => []
                ], 200);
            }

            // Получаем все записи мастера на этот день
            $appointments = Appointment::where('barber_id', $barberId)
                ->whereDate('appointment_time', $date)
                ->where('status', '!=', 'cancelled')
                ->get();

            // Получаем все блокировки времени на этот день
            $blockedTimes = $barber->blockedTimes()
                ->whereDate('start_time', $date)
                ->get();

            // Генерируем все возможные временные слоты
            $timeSlots = [];
            $currentTime = clone $startTime;

            while ($currentTime->lt($endTime)) {
                $slotEndTime = (clone $currentTime)->addMinutes($service->duration);
                
                // Если слот выходит за пределы рабочего дня, прекращаем
                if ($slotEndTime->gt($endTime)) {
                    break;
                }

                // Для текущего дня пропускаем прошедшее время
                if ($requestDate->isToday() && $currentTime->lt($now)) {
                    $currentTime->addMinutes(15);
                    continue;
                }

                $isAvailable = true;

                // Проверяем пересечения с существующими записями
                foreach ($appointments as $appointment) {
                    $appointmentStart = Carbon::parse($appointment->appointment_time);
                    $appointmentEnd = (clone $appointmentStart)->addMinutes($appointment->service->duration);

                    if ($currentTime->lt($appointmentEnd) && $slotEndTime->gt($appointmentStart)) {
                        $isAvailable = false;
                        break;
                    }
                }

                // Проверяем пересечения с блокировками времени
                if ($isAvailable) {
                    foreach ($blockedTimes as $blockedTime) {
                        $blockedStart = Carbon::parse($blockedTime->start_time);
                        $blockedEnd = Carbon::parse($blockedTime->end_time);

                        if ($currentTime->lt($blockedEnd) && $slotEndTime->gt($blockedStart)) {
                            $isAvailable = false;
                            break;
                        }
                    }
                }

                if ($isAvailable) {
                    $timeSlots[] = $currentTime->format('H:i');
                }

                $currentTime->addMinutes(15);
            }

            \Log::info('Generated available time slots:', [
                'count' => count($timeSlots),
                'slots' => $timeSlots
            ]);

            // После получения рабочих часов
            \Log::info('Working hours details:', [
                'barber_id' => $barberId,
                'working_hours' => $workingHours,
                'start_time' => isset($startTime) ? $startTime->toDateTimeString() : null,
                'end_time' => isset($endTime) ? $endTime->toDateTimeString() : null,
                'service_duration' => $service->duration
            ]);

            return response()->json([
                'available_times' => $timeSlots
            ], 200);

        } catch (\Exception $e) {
            \Log::error('Error in getAvailableTimes:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'error' => 'Произошла ошибка при проверке доступного времени',
                'available_times' => []
            ], 200);
        }
    }
}
