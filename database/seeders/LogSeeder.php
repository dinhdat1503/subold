<?php

namespace Database\Seeders;

use App\Models\Log;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $numLogs = 100000;
        $batchSize = 5000;
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Log::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        $logs = Log::factory($numLogs)->make();
        foreach ($logs->chunk($batchSize) as $batch) {
            $dataToInsert = $batch->toArray();
            Log::insert($dataToInsert);
        }
    }
}
