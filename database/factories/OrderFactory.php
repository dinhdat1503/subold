<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = \App\Models\Order::class;
    public function definition()
    {
        $quantity = $this->faker->numberBetween(100, 10000); // Số lượng ngẫu nhiên
        $cost = $this->faker->randomFloat(2, 0.1, 1.0); // Giả sử chi phí từ 0.1 đến 1.0
        $price = $cost * $this->faker->randomFloat(2, 1.2, 2.0); // Giá bán (có lợi nhuận)

        $paymentReal = round($quantity * $price, 2);
        $profit = round($paymentReal - ($quantity * $cost), 2);

        $statuses = ['Pending', 'Processing', 'Completed', 'Cancelled', 'Refunded'];
        $status = $this->faker->randomElement($statuses);

        $createdAt = $this->faker->dateTimeBetween('-60 days', 'now');
        $timeStart = $this->faker->dateTimeBetween($createdAt, $createdAt->format('Y-m-d H:i:s') . ' +1 hour');
        $timeEnd = ($status == 'Completed') ? $this->faker->dateTimeBetween($timeStart, $timeStart->format('Y-m-d H:i:s') . ' +2 days') : null;

        return [
            'user_id' => 1, // Yêu cầu: luôn bằng 1
            'server_id' => $this->faker->numberBetween(1, 150), // Yêu cầu: random 1-150
            'supplier_id' => $this->faker->randomElement([1, 2]), // Yêu cầu: random 1 hoặc 2

            'quantity' => $quantity,
            'payment_real' => $paymentReal,
            'profit' => $profit,
            'payment' => $paymentReal,
            'order_link' => $this->faker->url,
            'order_info' => Str::random(15),
            'count_start' => $this->faker->numberBetween(0, 1000),
            'count_buff' => $this->faker->numberBetween(1, 5),
            'time_start' => $timeStart,
            'time_end' => $timeEnd,
            'supplier_order_id' => Str::random(10),
            'status' => $status,
            'logs' => '{"message": "Order created by factory"}',
            'note' => $this->faker->optional()->sentence(),
        ];
    }
}
