<?php

namespace App\Console\Commands\Suppliers\Smm;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class Balance extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'smm:internal:balance';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command Task SMM';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            $smm = new \App\Http\Controllers\Api\Suppliers\SmmController();
            $balanceCheck = $smm->getBalance();
            if ($balanceCheck['status'] && !empty($balanceCheck['data'])) {
                $currency = $smm->suppliers->pluck('currency', 'id')->toArray();
                $link = $smm->suppliers->pluck('base_url', 'id')->toArray();
                $moneyMap = [
                    "VND" => 100000,
                    "USD" => 5,
                    "Xu" => 9000000,
                ];
                $data = collect($balanceCheck['data'])->map(function ($item) use ($currency, $link, $moneyMap) {
                    $id = $item['id'];
                    $money = $item['money'] ?? 0;
                    $threshold = $moneyMap[$currency[$id]] ?? 0;
                    if ($money < $threshold) {
                        $cacheKey = "smm-balance-low-" . $id;
                        if (!Cache::has($cacheKey)) {
                            \App\Helpers\BackendHelper::sendMessTelegram('balance_low', [
                                'balance' => $money,
                                'unit' => $currency[$id],
                                'decimals' => 0,
                                'link' => $link[$id],
                            ]);
                            Cache::put($cacheKey, true, now()->addHour());
                        }
                    }
                    return [
                        'id' => $id,
                        'money' => $money,
                        'last_synced_at' => now(),
                    ];
                })->toArray();
                \App\Helpers\DBHelper::bulkUpdateJoin('suppliers', $data, 'id');
            } else {
                $this->info($balanceCheck['message']);
                return self::FAILURE;
            }
            $this->info("Success");
            return self::SUCCESS;
        } catch (\Throwable $th) {
            \Log::error("❌ Lỗi CRON: " . $th->getMessage(), ['trace' => $th->getTraceAsString()]);
            $this->error('Lỗi: ' . $th->getMessage());
            return self::FAILURE;
        }
    }
}
