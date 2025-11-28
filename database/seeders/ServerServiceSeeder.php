<?php

namespace Database\Seeders;

use App\Models\ServerService;
use App\Models\ServerSupplier;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServerServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $totalServices = 30;
        $serversPerService = 5;
        // 1. Tắt kiểm tra khóa ngoại và dọn dẹp các bảng
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        ServerSupplier::truncate(); // Xóa bảng con trước
        ServerService::truncate();  // Xóa bảng cha
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        for ($serviceId = 1; $serviceId <= $totalServices; $serviceId++) {
            $servers = ServerService::factory($serversPerService)->create([
                'service_id' => $serviceId,
            ]);
            $servers->each(function ($server) {
                ServerSupplier::factory()->create([
                    'server_id' => $server->id
                ]);
            });
        }
    }
}
