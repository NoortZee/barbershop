<?php

namespace Database\Factories;

use App\Models\Barber;
use Illuminate\Database\Eloquent\Factories\Factory;

class BarberFactory extends Factory
{
    protected $model = Barber::class;

    public function definition(): array
    {
        $barbers = [
            [
                'name' => 'Александр',
                'photo' => 'images/barber-1.jpg',
                'bio' => 'Топ-барбер с 10-летним опытом работы. Победитель международных конкурсов барберов. Специализируется на классических мужских стрижках и королевском бритье.',
                'specialization' => 'Топ-барбер',
                'working_hours' => json_encode([
                    'mon' => ['10:00-20:00'],
                    'tue' => ['10:00-20:00'],
                    'wed' => ['10:00-20:00'],
                    'thu' => ['10:00-20:00'],
                    'fri' => ['10:00-20:00'],
                    'sat' => ['11:00-18:00'],
                    'sun' => []
                ])
            ],
            [
                'name' => 'Максим',
                'photo' => 'images/barber-2.jpg',
                'bio' => 'Стилист-барбер с уникальным видением современных трендов. Специализируется на креативных стрижках и сложных техниках окрашивания.',
                'specialization' => 'Стилист-барбер',
                'working_hours' => json_encode([
                    'mon' => ['12:00-22:00'],
                    'tue' => ['12:00-22:00'],
                    'wed' => ['12:00-22:00'],
                    'thu' => ['12:00-22:00'],
                    'fri' => ['12:00-22:00'],
                    'sat' => ['11:00-18:00'],
                    'sun' => []
                ])
            ],
            [
                'name' => 'Дмитрий',
                'photo' => 'images/barber-3.jpg',
                'bio' => 'Мастер-барбер с международными сертификатами. Эксперт по моделированию бороды и созданию целостного образа.',
                'specialization' => 'Мастер-барбер',
                'working_hours' => json_encode([
                    'mon' => ['10:00-20:00'],
                    'tue' => ['10:00-20:00'],
                    'wed' => ['10:00-20:00'],
                    'thu' => ['10:00-20:00'],
                    'fri' => ['10:00-20:00'],
                    'sat' => ['11:00-18:00'],
                    'sun' => []
                ])
            ],
            [
                'name' => 'Артём',
                'photo' => 'images/barber-4.jpg',
                'bio' => 'Креативный барбер с художественным образованием. Создает уникальные образы на стыке классики и современных трендов.',
                'specialization' => 'Креативный барбер',
                'working_hours' => json_encode([
                    'mon' => ['12:00-22:00'],
                    'tue' => ['12:00-22:00'],
                    'wed' => ['12:00-22:00'],
                    'thu' => ['12:00-22:00'],
                    'fri' => ['12:00-22:00'],
                    'sat' => ['11:00-18:00'],
                    'sun' => []
                ])
            ]
        ];

        return $barbers[array_rand($barbers)];
    }
} 