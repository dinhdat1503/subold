<?php

namespace Database\Seeders;

use App\Models\RechargeMethod;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RechargeMethodsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        $now = \Carbon\Carbon::now();
        RechargeMethod::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        RechargeMethod::insert([
            [
                'method_type' => 'bank',
                'name' => 'MBBANK',
                'exchange_rate' => 1,
                'recharge_min' => 10000,
                'account_name' => 'NGUYEN VAN A',
                'account_index' => '0123456789',
                'wallet_qr' => '/uploads/qr/mbbank_qr.png',
                'network' => 'LOCAL',
                'api_key' => 'bank_api_key_123',
                'note' => '<p>Chuyển khoản ghi rõ nội dung: <b>nap12345</b></p>',
                'status' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'method_type' => 'crypto',
                'name' => 'USDT',
                'exchange_rate' => 1,
                'recharge_min' => 10,
                'account_name' => 'Crypto Wallet',
                'account_index' => '0x123abc456def789',
                'wallet_qr' => '/uploads/qr/usdt_qr.png',
                'network' => 'TRC20',
                'api_key' => 'crypto_api_key_456',
                'note' => '<p>Vui lòng gửi đúng mạng <b>TRC20</b></p>',
                'status' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}
