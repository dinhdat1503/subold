<?php

namespace Database\Seeders;

use App\Models\Order;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $numOrders = 100000; // Số lượng đơn hàng muốn tạo (10k)
        $batchSize = 2000; // Kích thước lô chèn
        // 1. Dọn dẹp bảng
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Order::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 2. Sử dụng Factory để tạo hàng loạt dữ liệu
        $orders = Order::factory($numOrders)->make();
        // 3. Chia nhỏ và Chèn Hàng loạt
        // Việc chia thành các lô nhỏ (chunk) giúp giảm áp lực bộ nhớ và SQL
        foreach ($orders->chunk($batchSize) as $batch) {

            // Chuyển Collection của Factory sang Array và thêm created_at/updated_at
            $dataToInsert = $batch->map(function ($order) {
                return $order->toArray();
            })->toArray();

            // Thực hiện Bulk Insert
            Order::insert($dataToInsert);
        }
    }
}
