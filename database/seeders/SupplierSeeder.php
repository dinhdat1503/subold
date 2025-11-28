<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Supplier::insert([
            [
                'name' => 'minsocial',
                'base_url' => 'https://api.minsocial.vn',
                'api_key' => "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwczpcL1wvYXBpLm1pbnNvY2lhbC52blwvYXBpXC92MVwvYXV0aFwvcmVnaXN0ZXItYXR0ZW1wIiwiaWF0IjoxNzU4MzU2MDc4LCJleHAiOjE4NTI5NjQwNzgsIm5iZiI6MTc1ODM1NjA3OCwianRpIjoiamdVZFhZcU00TEcwUmJkeiIsInN1YiI6MjAxNDAsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjciLCJkb21haW5faWQiOjF9.x3TSX7keXj2rEsDQXK_-GQY0qy0DI5J8ajNKe3M5Lh4",
                'proxy' => null,
                'price_percent' => 25,
                'price_unit_value' => 1000,
                'api' => 'SMM',
                'username' => 'subold',
                'money' => 0,
                'currency' => 'VND',
                'exchange_rate' => 1.0000,
                'status' => 1,
                'last_synced_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'songhong',
                'base_url' => 'https://songhong.net',
                'api_key' => "bd553e69422782b52715610cb3eddd28",
                'proxy' => null,
                'price_percent' => 25,
                'price_unit_value' => 1000,
                'api' => 'SMM',
                'username' => 'subold',
                'money' => 0,
                'currency' => 'VND',
                'exchange_rate' => 1.0000,
                'status' => 1,
                'last_synced_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'tuongtacsale',
                'base_url' => 'https://tuongtacsale.com',
                'api_key' => "2acf220d9735e141ddefbffa5131ab493f105d20d0f788af6c2cb5dbdc94ce40",
                'proxy' => null,
                'price_percent' => 25,
                'price_unit_value' => 1000,
                'api' => 'SMM',
                'username' => 'subold',
                'money' => 0,
                'currency' => 'VND',
                'exchange_rate' => 1.0000,
                'status' => 1,
                'last_synced_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'onedg',
                'base_url' => 'https://1dg.me',
                'api_key' => "46cf53b6eaffd8d8a398e798020369e3",
                'proxy' => null,
                'price_percent' => 25,
                'price_unit_value' => 1000,
                'api' => 'SMM',
                'username' => 'subold',
                'money' => 0,
                'currency' => 'USD',
                'exchange_rate' => 28000.0000,
                'status' => 1,
                'last_synced_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'x999',
                'base_url' => 'https://x999.vn',
                'api_key' => "d9cf3f8a3d4914546cd2f8a54740544ee5544076141794e6ed9178556602a51a",
                'proxy' => null,
                'price_percent' => 25,
                'price_unit_value' => 1000,
                'api' => 'SMM',
                'username' => 'subold',
                'money' => 0,
                'currency' => 'VND',
                'exchange_rate' => 1.0000,
                'status' => 1,
                'last_synced_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'saintpanel',
                'base_url' => 'https://saintpanel.com',
                'api_key' => "680743817f42a6ea674fa7b86e19bef3",
                'proxy' => null,
                'price_percent' => 25,
                'price_unit_value' => 1000,
                'api' => 'SMM',
                'username' => 'subold',
                'money' => 0,
                'currency' => 'USD',
                'exchange_rate' => 28000.0000,
                'status' => 1,
                'last_synced_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
             [
                'name' => 'smmcenter',
                'base_url' => 'https://smm-center.com',
                'api_key' => "0018014b13833d0c8d1540674978bbc3",
                'proxy' => null,
                'price_percent' => 25,
                'price_unit_value' => 1000,
                'api' => 'SMM',
                'username' => 'subold',
                'money' => 0,
                'currency' => 'USD',
                'exchange_rate' => 28000.0000,
                'status' => 1,
                'last_synced_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
