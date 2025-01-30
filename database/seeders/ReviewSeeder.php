<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Review;
use App\Models\User;
use App\Models\Barber;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        $barbers = Barber::all();
        
        $comments = [
            'Отличный мастер! Очень доволен стрижкой.',
            'Профессиональный подход и приятная атмосфера.',
            'Рекомендую этого барбера, знает своё дело!',
            'Всё было супер, обязательно приду снова.',
            'Качественная работа, приемлемые цены.',
            'Мастер учел все мои пожелания.',
            'Отличный сервис и результат.',
            'Очень внимательный и аккуратный мастер.',
            'Доволен результатом, спасибо!',
            'Прекрасная работа, всем рекомендую.'
        ];

        foreach ($users as $user) {
            foreach ($barbers as $barber) {
                // Создаем от 0 до 3 отзывов для каждой пары пользователь-барбер
                $reviewCount = rand(0, 3);
                for ($i = 0; $i < $reviewCount; $i++) {
                    Review::create([
                        'user_id' => $user->id,
                        'barber_id' => $barber->id,
                        'comment' => $comments[array_rand($comments)],
                        'rating' => rand(3, 5),
                        'created_at' => now()->subDays(rand(1, 30)),
                        'updated_at' => now()->subDays(rand(1, 30))
                    ]);
                }
            }
        }
    }
} 