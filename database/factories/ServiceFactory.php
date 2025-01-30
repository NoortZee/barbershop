<?php

namespace Database\Factories;

use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;

class ServiceFactory extends Factory
{
    protected $model = Service::class;

    public function definition(): array
    {
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

        return $services[array_rand($services)];
    }
} 