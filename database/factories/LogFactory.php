<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory <\App\Models\Log>
 */
class LogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = \App\Models\Log::class;
    public function definition()
    {
        $oldValue = $this->faker->randomFloat(2, 100, 10000);
        $newValue = $oldValue * $this->faker->randomFloat(2, 1.05, 1.5);

        return [
            'user_id' => 1, // Yêu cầu: luôn là user_id = 1
            'action_type' => $this->faker->randomElement(['Login', 'Register', 'RequestChangePassword', 'Balance']),
            'old_value' => $oldValue,
            'new_value' => $newValue,
            'value' => $newValue - $oldValue, // Lãi/Lỗ
            'ip_address' => $this->faker->ipv4(),
            'useragent' => $this->faker->userAgent(),
            'description' => $this->faker->sentence(5),
        ];
    }
}
