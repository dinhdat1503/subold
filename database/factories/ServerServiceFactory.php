<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory <\App\Models\ServerService>
 */
class ServerServiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = \App\Models\ServerService::class;
    public function definition()
    {
        $price = $this->faker->randomFloat(2, 1.2, 2.0);

        return [
            'server' => $this->faker->numberBetween(1000, 9999),
            'price' => $price,
            'min' => $this->faker->numberBetween(10, 50),
            'max' => $this->faker->numberBetween(1000, 10000),
            'title' => $this->faker->unique()->words(3, true) . ' Server',
            'description' => $this->faker->text(150),
            'status' => 1, // Yêu cầu: status ON (1)
            'supplier_id' => $this->faker->randomElement([1, 2]), // Random ID 1 hoặc 2
            'supplier_code' => 0, // Random ID 1 hoặc 2
            // Các cột action_... khác giữ mặc định hoặc null
            'action_reaction' => (object) ["status" => false, "data" => ["like", "love"]],
            'action_time' => (object) ["status" => false],
            'action_comment' => (object) ["status" => false],
            'action_amount' => (object) ["status" => false],
            'action_order' => (object) ["refund" => false, "warranty" => false, "multi_link" => false],
        ];
    }
}
