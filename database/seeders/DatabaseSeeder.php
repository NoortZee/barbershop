<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Service;
use App\Models\Barber;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Создаем администратора
        $this->call(AdminSeeder::class);

        // Создаем тестового пользователя
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password')
        ]);

        // Создаем дополнительных пользователей
        User::factory(5)->create();

        // Создаем услуги
        $services = [
            [
                'name' => 'Мужская стрижка',
                'description' => 'Классическая мужская стрижка',
                'price' => 1000,
                'duration' => 60,
                'image' => 'images/service-1.jpg'
            ],
            [
                'name' => 'Стрижка бороды',
                'description' => 'Моделирование и стрижка бороды',
                'price' => 500,
                'duration' => 30,
                'image' => 'images/service-2.jpg'
            ],
            [
                'name' => 'Королевское бритье',
                'description' => 'Бритье опасной бритвой',
                'price' => 800,
                'duration' => 45,
                'image' => 'images/service-3.jpg'
            ],
            [
                'name' => 'Детская стрижка',
                'description' => 'Стрижка для мальчиков до 12 лет',
                'price' => 700,
                'duration' => 45,
                'image' => 'images/service-4.jpg'
            ],
            [
                'name' => 'Окрашивание',
                'description' => 'Окрашивание волос',
                'price' => 1500,
                'duration' => 90,
                'image' => 'images/service-5.jpg'
            ],
            [
                'name' => 'Укладка',
                'description' => 'Укладка волос',
                'price' => 500,
                'duration' => 30,
                'image' => 'images/service-6.jpg'
            ]
        ];

        foreach ($services as $service) {
            Service::create($service);
        }

        // Создаем барберов
        $barbers = [
            [
                'name' => 'Александр Петров',
                'specialization' => 'Барбер',
                'bio' => 'Опытный мастер с 5-летним стажем',
                'photo' => 'images/barber-1.jpg',
                'working_hours' => json_encode([
                    'mon' => '10:00-19:00',
                    'tue' => '10:00-19:00',
                    'wed' => '10:00-19:00',
                    'thu' => '10:00-19:00',
                    'fri' => '10:00-19:00',
                    'sat' => '10:00-17:00'
                ])
            ],
            [
                'name' => 'Иван Сидоров',
                'specialization' => 'Барбер-стилист',
                'bio' => 'Специалист по сложным стрижкам',
                'photo' => 'images/barber-2.jpg',
                'working_hours' => json_encode([
                    'mon' => '12:00-21:00',
                    'tue' => '12:00-21:00',
                    'wed' => '12:00-21:00',
                    'thu' => '12:00-21:00',
                    'fri' => '12:00-21:00',
                    'sat' => '11:00-18:00'
                ])
            ]
        ];

        foreach ($barbers as $barber) {
            Barber::create($barber);
        }

        // Создаем отзывы
        $this->call(ReviewSeeder::class);
    }
}
