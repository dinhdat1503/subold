<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Service>
 */
class ServiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = \App\Models\Service::class;
    public function definition()
    {
        $name = $this->faker->unique()->sentence(2);
        return [
            // social_id sẽ được điền khi liên kết
            'name' => rtrim($name, '.'),
            'slug' => \Illuminate\Support\Str::slug($name),
            'image' => "/assets/images/client/level/level-1.png", // Thay bằng logic image thực tế
            'note' => $this->faker->sentence(5),
            'status' => 1,
        ];
    }
}
