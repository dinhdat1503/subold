<?php

namespace App\Console\Commands\Recharge;


use App\Models\RechargeLog;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class Bank extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'recharge:internal:bank';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cron Check Recharge Bank';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            $rechargeCheck = new \App\Http\Controllers\Api\Website\RechargeController();
            $promotionLevels = siteSetting("promotion_show") ? json_decode(siteSetting("promotion_levels"), true) : [];
            $bankHistory = $rechargeCheck->getTransactionBank();
            if (!$bankHistory['status'] || empty($bankHistory['data'])) {
                $this->info($bankHistory['message']);
                return self::FAILURE;
            }
            $bankCode = strtolower(siteSetting("bank_code"));
            $pattern = '/' . preg_quote($bankCode, '/') . '(\d+)/';
            \DB::transaction(function () use ($bankHistory, $pattern, $promotionLevels) {
                foreach ($bankHistory['data'] as $tx) {
                    $reDesc = \Str::of($tx['note'])->lower()->ascii()->trim()->value();
                    if (preg_match($pattern, $reDesc, $match)) {
                        $userID = $match[1];
                        $user = \App\Models\User::where("id", $userID)->lockForUpdate()->first();
                        if (!$user) {
                            return;
                        }
                        if (RechargeLog::where("trans_id", $tx['trans_id'])->exists()) {
                            return;
                        }
                        $money = $tx['amount'];
                        $promotionPercent = 0;
                        foreach ($promotionLevels as $level) {
                            if ($money >= $level['money']) {
                                $promotionPercent = $level['promotion'];
                            }
                        }
                        $moneyPromotion = round(($money * $promotionPercent) / 100, 2);
                        $moneyAdd = round($money + $moneyPromotion, 2);
                        RechargeLog::create([
                            'user_id' => $user->id,
                            'recharge_id' => $tx['id'],
                            'trans_id' => $tx['trans_id'],
                            'amount' => $money,
                            'promotion' => $moneyPromotion,
                            'amount_received' => $moneyAdd,
                            'status' => 1,
                            'note' => $tx['note'],
                        ]);
                        $user->increment("balance", $moneyAdd);
                        $user->increment("total_recharge", $moneyAdd);
                        $user->increment("promotion_recharge", $moneyPromotion);

                        $cacheKey = "detected-recharge-user-{$user->id}";
                        $currentDetected = Cache::get($cacheKey, 0);
                        $newDetected = $currentDetected + $moneyAdd;
                        Cache::put($cacheKey, $newDetected, now()->addHours(24));

                        $this->info("Cộng Thành Công: {$user->id}");
                    }
                }
            }, 3);
            $this->info('Hoàn tất kiểm tra giao dịch');
            return self::SUCCESS;
        } catch (\Throwable $e) {
            \Log::error("Lỗi CRON: " . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            $this->error('Lỗi: ' . $e->getMessage());
            return self::FAILURE;
        }

    }
}
