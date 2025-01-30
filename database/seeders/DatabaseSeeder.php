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
                'name' => 'Премиум стрижка',
                'description' => 'Индивидуальный подход к созданию вашего стиля с учетом особенностей внешности и пожеланий. Включает консультацию, мытье головы, массаж головы, укладку.',
                'price' => 3000,
                'duration' => 60,
                'image' => 'images/service-1.jpg'
            ],
            [
                'name' => 'Моделирование бороды',
                'description' => 'Профессиональное оформление бороды с использованием премиальной косметики. Включает консультацию по уходу, горячее полотенце, массаж лица.',
                'price' => 2000,
                'duration' => 45,
                'image' => 'images/service-2.jpg'
            ],
            [
                'name' => 'VIP-комплекс',
                'description' => 'Полный комплекс услуг для создания безупречного образа современного джентльмена. Включает премиум стрижку, моделирование бороды, уход за лицом.',
                'price' => 5000,
                'duration' => 120,
                'image' => 'images/service-3.jpg'
            ],
            [
                'name' => 'Королевское бритье',
                'description' => 'Классическое бритье опасной бритвой с использованием горячих полотенец и премиальных средств для бритья.',
                'price' => 2500,
                'duration' => 45,
                'image' => 'images/service-4.jpg'
            ],
            [
                'name' => 'Детская стрижка',
                'description' => 'Стрижка для юных джентльменов до 12 лет с особым подходом и вниманием к деталям.',
                'price' => 2000,
                'duration' => 45,
                'image' => 'images/service-5.jpg'
            ],
            [
                'name' => 'Окрашивание',
                'description' => 'Профессиональное окрашивание волос премиальными красителями с учетом индивидуальных особенностей.',
                'price' => 4000,
                'duration' => 120,
                'image' => 'images/service-6.jpg'
            ]
        ];

        foreach ($services as $service) {
            Service::create($service);
        }

        // Создаем барберов
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

        foreach ($barbers as $barber) {
            Barber::create($barber);
        }

        // Создаем отзывы
        $this->call(ReviewSeeder::class);
    }
}
