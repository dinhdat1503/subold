<?php

namespace App\Console\Commands\Website;

use Illuminate\Console\Command;

class CloudflareIps extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'website:cloudflareips';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch and cache Cloudflare IP ranges';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            $cloudlfareIps = \App\Http\Controllers\Api\Website\CloudflareController::getIPs();
            if ($cloudlfareIps['status'] != 'success' || empty($cloudlfareIps['data'])) {
                $this->info('Cloudflare IPs error: ' . ($cloudlfareIps['message'] ?? 'Unknown error'));
                return self::FAILURE;
            }
            \Cache::put('cloudflare-proxies', $cloudlfareIps['data'], 86400);
            $this->info('Cloudflare IPs updated successfully. (' . count($cloudlfareIps['data']) . ' ranges)');
            return self::SUCCESS;
        } catch (\Exception $e) {
            \Log::warning('Cloudflare IP update failed', [
                'error' => $e->getMessage(),
            ]);
            $this->error('Update failed, fallback used.');
            return self::FAILURE;
        }
    }
}
