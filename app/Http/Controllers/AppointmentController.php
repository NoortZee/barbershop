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
    public function create(Request $request)
    {
        $step = $request->query('step', 1);
        $appointmentData = $request->session()->get('appointment_data');

        switch ($step) {
            case 1:
                return view('appointments.step1');
            
            case 2:
                if (!$appointmentData || !isset($appointmentData['step1'])) {
                    return redirect()->route('appointments.create');
                }

                $step1Data = $appointmentData['step1'];
                if ($step1Data['choice'] === 'service') {
                    $services = Service::where('is_active', true)->get();
                    return view('appointments.step2_services', compact('services'));
                } else {
                    $barbers = Barber::where('is_active', true)->get();
                    return view('appointments.step2_barbers', compact('barbers'));
                }
            
            case 3:
                if (!$appointmentData || !isset($appointmentData['step1']) || !isset($appointmentData['step2'])) {
                    return redirect()->route('appointments.create');
                }

                $step1Data = $appointmentData['step1'];
                $step2Data = $appointmentData['step2'];

                if ($step1Data['choice'] === 'service') {
                    // Получаем все выбранные услуги
                    $services = Service::findOrFail($step2Data['service_ids']);
                    // Получаем барберов, которые могут выполнить все выбранные услуги
                    $barbers = Barber::where('is_active', true)
                        ->whereHas('services', function($query) use ($services) {
                            $query->whereIn('services.id', collect($services)->pluck('id'));
                        }, '=', count($services))
                        ->get();
                    
                    return view('appointments.step3_barbers', compact('services', 'barbers'));
                } else {
                    $barber = Barber::findOrFail($step2Data['barber_id']);
                    $services = Service::where('is_active', true)
                        ->whereHas('barbers', function($query) use ($barber) {
                            $query->where('barbers.id', $barber->id);
                        })
                        ->get();
                    return view('appointments.step3_services', compact('barber', 'services'));
                }
            
            default:
                return redirect()->route('appointments.create', ['step' => 1]);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $step = $request->input('step', 1);
        $data = $request->except('_token', 'step');

        if ($step < 3) {
            // Для первого шага проверяем только выбор направления
            if ($step == 1) {
                if (!isset($data['choice']) || !in_array($data['choice'], ['service', 'barber'])) {
                    return redirect()->route('appointments.create')
                        ->with('error', 'Пожалуйста, выберите способ записи');
                }
            }
            
            // Для второго шага проверяем наличие выбранных услуг
            if ($step == 2 && isset($data['service_ids'])) {
                $services = Service::findOrFail($data['service_ids']);
                $data['total_duration'] = $services->sum('duration');
                $data['total_price'] = $services->sum('price');
            }
            
            $request->session()->put("appointment_data.step{$step}", $data);
            return redirect()->route('appointments.create', ['step' => $step + 1]);
        }

        // Шаг 3 - финальное сохранение
        $appointmentData = $request->session()->get('appointment_data');
        
        // Создаем дату и время записи
        $appointmentDateTime = Carbon::parse($data['appointment_date'] . ' ' . $data['appointment_time']);
        
        // Если выбрано несколько услуг, создаем отдельную запись для каждой
        if ($appointmentData['step1']['choice'] === 'service') {
            $services = Service::findOrFail($appointmentData['step2']['service_ids']);
            $barber_id = $data['barber_id'];
            
            foreach ($services as $service) {
                // Проверяем доступность времени для каждой услуги
                $isTimeAvailable = $this->checkTimeAvailability(
                    $barber_id,
                    $service->id,
                    $appointmentDateTime
                );

                if (!$isTimeAvailable) {
                    return back()->withErrors(['appointment_time' => 'Выбранное время недоступно']);
                }

                // Создаем запись
                Appointment::create([
                    'user_id' => auth()->id(),
                    'service_id' => $service->id,
                    'barber_id' => $barber_id,
                    'appointment_time' => $appointmentDateTime,
                    'notes' => $data['notes'] ?? null,
                    'status' => 'pending'
                ]);

                // Увеличиваем время для следующей услуги
                $appointmentDateTime->addMinutes($service->duration);
            }
        } else {
            // Если выбран барбер сначала, то создаем одну запись
            $isTimeAvailable = $this->checkTimeAvailability(
                $appointmentData['step2']['barber_id'],
                $data['service_id'],
                $appointmentDateTime
            );

            if (!$isTimeAvailable) {
                return back()->withErrors(['appointment_time' => 'Выбранное время недоступно']);
            }

            Appointment::create([
                'user_id' => auth()->id(),
                'service_id' => $data['service_id'],
                'barber_id' => $appointmentData['step2']['barber_id'],
                'appointment_time' => $appointmentDateTime,
                'notes' => $data['notes'] ?? null,
                'status' => 'pending'
            ]);
        }

        // Очищаем данные сессии
        $request->session()->forget('appointment_data');

        return redirect()->route('appointments.index')
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

    public function step1()
    {
        return view('appointments.step1');
    }

    public function step2(Request $request)
    {
        $step1Data = $request->session()->get('appointment_data.step1');
        
        if (!$step1Data) {
            return redirect()->route('appointments.step1');
        }

        if ($step1Data['choice'] === 'service') {
            $barbers = Barber::where('is_active', true)
                ->whereHas('services', function($query) use ($step1Data) {
                    $query->where('services.id', $step1Data['service_id']);
                })
                ->get();
            return view('appointments.step2', compact('barbers'));
        } else {
            if (!isset($step1Data['barber_id'])) {
                return redirect()->route('appointments.create')
                    ->with('error', 'Пожалуйста, выберите барбера');
            }
            
            $services = Service::where('is_active', true)
                ->whereHas('barbers', function($query) use ($step1Data) {
                    $query->where('barbers.id', $step1Data['barber_id']);
                })
                ->get();
            return view('appointments.step2', compact('services'));
        }
    }

    public function step3(Request $request)
    {
        $appointmentData = $request->session()->get('appointment_data');
        
        if (!$appointmentData || !isset($appointmentData['step1']) || !isset($appointmentData['step2'])) {
            return redirect()->route('appointments.step1');
        }

        $barber = Barber::findOrFail($appointmentData['step2']['barber_id'] ?? $appointmentData['step1']['barber_id']);
        $service = Service::findOrFail($appointmentData['step2']['service_id'] ?? $appointmentData['step1']['service_id']);

        return view('appointments.step3', compact('barber', 'service'));
    }

    public function storeStep(Request $request)
    {
        $step = $request->input('step');
        $data = $request->except('_token', 'step');

        $request->session()->put("appointment_data.step{$step}", $data);

        if ($step == 1) {
            return redirect()->route('appointments.step2');
        } elseif ($step == 2) {
            return redirect()->route('appointments.step3');
        } elseif ($step == 3) {
            // Собираем все данные из сессии
            $appointmentData = $request->session()->get('appointment_data');
            
            // Создаем запись
            $appointmentDateTime = Carbon::parse($data['appointment_date'] . ' ' . $data['appointment_time']);
            
            $appointment = Appointment::create([
                'user_id' => auth()->id(),
                'service_id' => $appointmentData['step2']['service_id'] ?? $appointmentData['step1']['service_id'],
                'barber_id' => $appointmentData['step2']['barber_id'] ?? $appointmentData['step1']['barber_id'],
                'appointment_time' => $appointmentDateTime,
                'notes' => $data['notes'] ?? null,
                'status' => 'pending'
            ]);

            // Очищаем данные сессии
            $request->session()->forget('appointment_data');

            return redirect()->route('appointments.show', $appointment)
                ->with('success', 'Запись успешно создана');
        }
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
