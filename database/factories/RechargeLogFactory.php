<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory <\App\Models\ReachargeLog>
 */
class RechargeLogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = \App\Models\RechargeLog::class;

    public function definition()
    {
        return [
            'user_id' => 1, // cố định user_id = 1
            'recharge_id' => $this->faker->randomElement([1, 2]),
            'trans_id' => strtoupper($this->faker->bothify('TRANS###??')),
            'amount' => $this->faker->randomFloat(2, 10000, 500000),
            'promotion' => $this->faker->randomFloat(2, 0, 5000),
            'amount_received' => $this->faker->randomFloat(2, 10000, 500000),
            'status' => $this->faker->randomElement([0, 1, 2]),
            'note' => $this->faker->sentence(),
        ];
    }
}
