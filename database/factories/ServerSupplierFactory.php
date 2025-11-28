<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ServerSupplier>
 */
class ServerSupplierFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = \App\Models\ServerSupplier::class;
    public function definition()
    {
        return [
            'service' => $this->faker->numberBetween(1000, 9999), // ID dịch vụ của nhà cung cấp
            'title' => $this->faker->sentence(3) . ' Config',
            'description' => $this->faker->text(100),
            'status_off' => "off", // 20% bị lỗi
            'cost' => 0.00, // 20% bị lỗi
            'update_minmax' => 1,
        ];
    }
}
