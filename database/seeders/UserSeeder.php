<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserSecurity;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        User::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        $adminPassword = '12345678@'; // Mật khẩu yêu cầu
        $adminUsername = 'huutoan1578242'; // Username yêu cầu
        $numUsers = 100; // Số lượng đơn hàng muốn tạo (10k)
        $batchSize = 1000; // Kích thước lô chèn
        User::create([
            'full_name' => 'Huutoan Admin',
            'username' => $adminUsername,
            'email' => 'huutoan190820062@gmail.com',
            'avatar_url' => "/assets/images/client/profile/user-1.jpg",
            'password' => \Hash::make($adminPassword),
            'balance' => 999999.00,
            'total_recharge' => 1000000.00,
            'role' => 'admin', // Gán quyền admin
            'last_ip' => '127.0.0.1',
            'last_online' => now(),
            'status' => 1,
        ]);
        UserSecurity::create([
            'user_id' => 1,
            'twofa_secret' => "1",
            'twofa_qr' => "1",
            'api_token' => "1",
        ]);
        $users = User::factory($numUsers)->make();
        foreach ($users->chunk($batchSize) as $batch) {
            $dataToInsert = $batch->map(function ($user) {
                return $user->makeVisible('password')->toArray();
            })->toArray();
            User::insert($dataToInsert);
        }

    }
}
