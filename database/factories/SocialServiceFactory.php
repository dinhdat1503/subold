<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SocialService>
 */
class SocialServiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = \App\Models\SocialService::class;

    public function definition()
    {
        $name = $this->faker->unique()->randomElement(['Facebook', 'Instagram', 'TikTok', 'Twitter', 'YouTube', 'Telegram', 'Zalo', 'Shopee', 'Lazada', 'Other']);
        return [
            'name' => $name,
            'slug' => \Illuminate\Support\Str::slug($name),
            'image' => "/assets/images/client/level/level-1.png", // Thay bằng logic image thực tế
            'status' => 1,
        ];
    }
}
