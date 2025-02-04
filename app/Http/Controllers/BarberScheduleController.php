<?php

namespace App\Http\Controllers;

use App\Models\Barber;
use App\Models\Appointment;
use App\Models\BarberBlockedTime;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BarberScheduleController extends Controller
{
    public function index()
    {
        $barbers = Barber::all();
        return view('admin.barbers.index', compact('barbers'));
    }

    public function edit(Barber $barber)
    {
        $daysOfWeek = [
            'mon' => 'Понедельник',
            'tue' => 'Вторник',
            'wed' => 'Среда',
            'thu' => 'Четверг',
            'fri' => 'Пятница',
            'sat' => 'Суббота',
            'sun' => 'Воскресенье'
        ];

        $currentDate = Carbon::now();
        $appointments = $barber->getAppointmentsForDate($currentDate);
        $timeSlots = $barber->getTimeSlots($currentDate);
        $workingHours = $barber->getWorkingHoursArray();

        return view('admin.barbers.schedule', compact('barber', 'daysOfWeek', 'currentDate', 'appointments', 'timeSlots', 'workingHours'));
    }

    public function update(Request $request, Barber $barber)
    {
        $validated = $request->validate([
            'working_hours' => 'required|array',
            'working_hours.*' => 'nullable|string|regex:/^\d{2}:\d{2}-\d{2}:\d{2}$/'
        ], [
            'working_hours.*.regex' => 'Формат времени должен быть ЧЧ:ММ-ЧЧ:ММ'
        ]);

        $workingHours = array_filter($validated['working_hours'], function($value) {
            return !empty($value);
        });

        $barber->update([
            'working_hours' => $workingHours
        ]);

        return redirect()->route('admin.barbers.index')
            ->with('success', 'График работы барбера успешно обновлен');
    }

    public function getAppointments(Request $request, Barber $barber)
    {
        $date = Carbon::parse($request->date);
        $appointments = $barber->getAppointmentsForDate($date);
        $timeSlots = $barber->getTimeSlots($date);

        return response()->json([
            'appointments' => $appointments,
            'timeSlots' => $timeSlots
        ]);
    }

    public function blockTime(Request $request, Barber $barber)
    {
        $validated = $request->validate([
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'reason' => 'nullable|string|max:255'
        ]);

        // Преобразуем даты в нужный формат
        $validated['start_time'] = Carbon::parse($validated['start_time'])->format('Y-m-d H:i:s');
        $validated['end_time'] = Carbon::parse($validated['end_time'])->format('Y-m-d H:i:s');

        $blockedTime = $barber->blockedTimes()->create($validated);

        return response()->json([
            'success' => true,
            'blocked_time' => [
                'id' => 'blocked_' . $blockedTime->id,
                'title' => 'Занято',
                'start' => $blockedTime->start_time,
                'end' => $blockedTime->end_time,
                'backgroundColor' => '#dc3545',
                'type' => 'blocked'
            ]
        ]);
    }

    public function unblockTime($id)
    {
        $blockedTime = BarberBlockedTime::findOrFail($id);
        $blockedTime->delete();

        return response()->json(['success' => true]);
    }

    public function updateBlockedTime(Request $request, $id)
    {
        $blockedTime = BarberBlockedTime::findOrFail($id);
        
        $validated = $request->validate([
            'start_time' => 'required|date_format:Y-m-d H:i:s',
            'end_time' => 'required|date_format:Y-m-d H:i:s|after:start_time'
        ]);

        $blockedTime->update($validated);

        return response()->json(['success' => true]);
    }
} 