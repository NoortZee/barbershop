<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'barber_id',
        'service_id',
        'appointment_time',
        'status',
        'notes'
    ];

    protected $casts = [
        'appointment_time' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function barber()
    {
        return $this->belongsTo(Barber::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function getFormattedStatusAttribute()
    {
        $statuses = [
            'pending' => 'Ожидает подтверждения',
            'confirmed' => 'Подтверждена',
            'completed' => 'Завершена',
            'cancelled' => 'Отменена'
        ];

        return $statuses[$this->status] ?? $this->status;
    }

    public function getFormattedDateAttribute()
    {
        return $this->appointment_time->format('d.m.Y');
    }

    public function getFormattedTimeAttribute()
    {
        return $this->appointment_time->format('H:i');
    }
}
