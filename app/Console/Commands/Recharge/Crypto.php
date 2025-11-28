<?php

namespace App\Console\Commands\Recharge;


use App\Models\RechargeLog;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class Crypto extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'recharge:internal:crypto';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cron Check Recharge Crypto';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            $rechargeCheck = new \App\Http\Controllers\Api\Website\RechargeController();
            $crypto = $rechargeCheck->recharge->where("method_type", "crypto")->first();
            $promotionLevels = siteSetting("promotion_show") ? json_decode(siteSetting("promotion_levels"), true) : [];
            $cryptoHistory = $rechargeCheck->checkTransactionCrypto();
            if (!$cryptoHistory['status'] || empty($cryptoHistory['data'])) {
                $this->info($cryptoHistory['message']);
                return self::FAILURE;
            }
            \DB::transaction(function () use ($cryptoHistory, $crypto, $promotionLevels) {
                foreach ($cryptoHistory['data'] as $tx) {
                    $money = round($tx['amount'] * $crypto->exchange_rate, 2);
                    $promotionPercent = 0.0;
                    foreach ($promotionLevels as $level) {
                        $levelMoney = (float) $level['money'];
                        $levelPromo = (float) $level['promotion'];
                        if ($money >= $levelMoney) {
                            $promotionPercent = $levelPromo;
                        }
                    }
                    $moneyPromotion = round(($money * $promotionPercent) / 100, 2);
                    $moneyAdd = round($money + $moneyPromotion, 2);
                    $invoice = RechargeLog::where('trans_id', $tx['trans_id'])->lockForUpdate()->first();
                    $user = User::where('id', $invoice->user_id)->lockForUpdate()->first();
                    if (!$user) {
                        return;
                    }
                    if ($invoice->status != 0) {
                        return;
                    }
                    $invoice->update([
                        'amount' => $tx['amount'],
                        'promotion' => $moneyPromotion,
                        'amount_received' => $moneyAdd,
                        'status' => 1,
                        'note' => 'Nạp Tiền Thành Công',
                    ]);
                    $user->increment('balance', $moneyAdd);
                    $user->increment('total_recharge', $moneyAdd);
                    $user->increment('promotion_recharge', $moneyPromotion);

                    $cacheKey = "detected-recharge-user-{$user->id}";
                    $currentDetected = Cache::get($cacheKey, 0);
                    $newDetected = $currentDetected + $moneyAdd;
                    Cache::put($cacheKey, $newDetected, now()->addHours(24));

                    $this->info("Cộng Thành Công: {$user->id}");
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
