<?php

namespace Database\Seeders;

use App\Models\RechargeLog;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RechargeLogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $total = 10000;
        $batchSize = 1000;
        for ($i = 0; $i < $total / $batchSize; $i++) {
            $logs = RechargeLog::factory()
                ->count($batchSize)
                ->make()
                ->map(fn($log) => $log->toArray())
                ->toArray();
            RechargeLog::insert($logs);
        }
    }
}
