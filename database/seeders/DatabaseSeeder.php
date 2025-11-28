<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            UserSeeder::class,
            SiteSettingsSeeder::class,
            SupplierSeeder::class,
            SocialServiceSeeder::class,
            RechargeMethodsSeeder::class,
            LogSeeder::class,
            OrderSeeder::class,
            RechargeLogSeeder::class,
        ]);
    }
}
