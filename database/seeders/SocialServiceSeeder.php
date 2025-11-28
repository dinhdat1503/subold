<?php

namespace Database\Seeders;

use App\Models\ServerService;
use App\Models\ServerSupplier;
use App\Models\Service;
use App\Models\SocialService;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SocialServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Service::truncate();
        SocialService::truncate();
        ServerSupplier::truncate(); // Xóa bảng con trước
        ServerService::truncate();  // Xóa bảng cha
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        SocialService::factory(10)->has(Service::factory(3)->has(ServerService::factory(5)->has(ServerSupplier::factory(), 'serverSupplier'), 'servers'), 'services')->create();
        // SocialService::factory(10)->has(Service::factory(3), 'services')->create();
    }
}
