<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;


class Kernel extends ConsoleKernel
{
    protected $commands = [
    ];
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('recharge:main')->everyMinute();
        $schedule->command('website:cloudflareips')->hourly();
        $schedule->command('website:sitemap')->hourly();
        $schedule->command('website:user-level')->everyMinute();
        $schedule->command('smm:main')->everyMinute();
        $schedule->command('session:gc')->daily();
    }
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');
        require base_path('routes/console.php');
    }
}
