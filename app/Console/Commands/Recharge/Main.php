<?php

namespace App\Console\Commands\Recharge;


use Illuminate\Console\Command;

class Main extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'recharge:main';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cron Check Recharge Main';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('1. Kiểm tra bank...');
        $statusServices = $this->call(Bank::class);
        if ($statusServices !== self::SUCCESS) {
            return self::FAILURE;
        }
        $this->info('2. Kiểm tra crypto...');
        $statusBalance = $this->call(Crypto::class);
        if ($statusBalance !== self::SUCCESS) {
            return self::FAILURE;
        }
        return self::SUCCESS;
    }
}
