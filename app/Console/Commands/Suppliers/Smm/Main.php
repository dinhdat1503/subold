<?php

namespace App\Console\Commands\Suppliers\Smm;

use Illuminate\Console\Command;

class Main extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'smm:main';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cron Task SMM';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('1. Kiểm tra dịch vụ...');
        $statusServices = $this->call(Services::class);
        if ($statusServices !== self::SUCCESS) {
            return self::FAILURE;
        }
        // $this->info('2. Xử lý đơn hàng...');
        // $statusOrders = $this->call(Orders::class);
        // if ($statusOrders !== self::SUCCESS) {
        //     return self::FAILURE;
        // }
        $this->info('3. Kiểm tra số dư...');
        $statusBalance = $this->call(Balance::class);
        if ($statusBalance !== self::SUCCESS) {
            return self::FAILURE;
        }
        return self::SUCCESS;
    }
}
