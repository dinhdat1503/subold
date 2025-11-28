<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */

class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    protected $model = \App\Models\User::class;
    public function definition()
    {
        // Sử dụng biến toàn cục đã băm
        $hashedPassword = \Hash::make('password');
        return [
            'full_name' => $this->faker->name(),
            'username' => $this->faker->unique()->userName(),
            'email' => $this->faker->unique()->safeEmail(),
            'avatar_url' => "/assets/images/client/profile/user-1.jpg",
            'password' => $hashedPassword,
            'balance' => $this->faker->randomFloat(2, 0, 500000),
            'total_recharge' => $this->faker->randomFloat(2, 0, 1000000),
            'total_deduct' => $this->faker->randomFloat(2, 0, 500000),
            'promotion_recharge' => $this->faker->randomFloat(2, 0, 100000),
            'level' => $this->faker->numberBetween(1, 5),
            'last_ip' => null,
            'last_useragent' => null,
            'utm_source' => $this->faker->optional()->word(),
            'role' => 'member',
            'status' => 1,
            'remember_token' => null,
        ];
    }
}
