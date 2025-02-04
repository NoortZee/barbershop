<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Barber extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'specialization',
        'bio',
        'photo',
        'working_hours',
        'is_active'
    ];

    protected $casts = [
        'working_hours' => 'array',
        'is_active' => 'boolean'
    ];

    // Дефолтный график работы
    public static $defaultWorkingHours = [
        'mon' => '09:00-19:00',
        'tue' => '09:00-19:00',
        'wed' => '09:00-19:00',
        'thu' => '09:00-19:00',
        'fri' => '09:00-19:00',
        'sat' => '09:00-19:00',
        'sun' => null
    ];

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function services()
    {
        return $this->belongsToMany(Service::class);
    }

    public function blockedTimes()
    {
        return $this->hasMany(BarberBlockedTime::class);
    }

    public function getWorkingHoursForDay($day)
    {
        return $this->working_hours[$day] ?? self::$defaultWorkingHours[$day];
    }

    public function isAvailable($date)
    {
        $dayOfWeek = strtolower(Carbon::parse($date)->format('D'));
        return !empty($this->getWorkingHoursForDay($dayOfWeek)) && $this->is_active;
    }

    public function getWorkingHoursArray()
    {
        $hours = [];
        foreach (self::$defaultWorkingHours as $day => $defaultTime) {
            $hours[$day] = $this->working_hours[$day] ?? $defaultTime;
        }
        return $hours;
    }

    public function getAppointmentsForDate($date)
    {
        $appointments = $this->appointments()
            ->whereDate('appointment_time', $date)
            ->where('status', '!=', 'cancelled')
            ->get();

        $blockedTimes = $this->blockedTimes()
            ->whereDate('start_time', $date)
            ->get()
            ->map(function($blockedTime) {
                return [
                    'id' => 'blocked_' . $blockedTime->id,
                    'title' => 'Занято',
                    'start' => $blockedTime->start_time,
                    'end' => $blockedTime->end_time,
                    'backgroundColor' => '#dc3545',
                    'type' => 'blocked'
                ];
            });

        return $appointments->concat($blockedTimes);
    }

    public function getTimeSlots($date)
    {
        $dayOfWeek = strtolower(Carbon::parse($date)->format('D'));
        $workingHours = $this->getWorkingHoursForDay($dayOfWeek);
        
        if (!$workingHours) {
            return [];
        }

        list($start, $end) = explode('-', $workingHours);
        
        // Создаем дату без времени
        $baseDate = Carbon::parse($date)->startOfDay();
        
        // Добавляем время к дате
        $startTime = $baseDate->copy()->addHours((int)substr($start, 0, 2))->addMinutes((int)substr($start, 3, 2));
        $endTime = $baseDate->copy()->addHours((int)substr($end, 0, 2))->addMinutes((int)substr($end, 3, 2));
        
        $slots = [];
        $current = clone $startTime;

        while ($current < $endTime) {
            $slots[] = $current->format('H:i');
            $current->addMinutes(30);
        }

        return $slots;
    }
}
