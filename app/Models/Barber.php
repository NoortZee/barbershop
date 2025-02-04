<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function services()
    {
        return $this->belongsToMany(Service::class);
    }

    public function getWorkingHoursForDay($day)
    {
        return $this->working_hours[$day] ?? null;
    }

    public function isAvailable($date)
    {
        $dayOfWeek = strtolower(date('D', strtotime($date)));
        return isset($this->working_hours[$dayOfWeek]) && $this->is_active;
    }
}
