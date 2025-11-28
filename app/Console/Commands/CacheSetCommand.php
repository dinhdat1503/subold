<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class CacheSetCommand extends Command
{
    protected $signature = 'cache:set {key} {value} {--ttl=60}';
    protected $description = 'Set tạm cache key với giá trị tuỳ chọn';

    public function handle()
    {
        $key = $this->argument('key');
        $value = $this->argument('value');
        $ttl = (int) $this->option('ttl');

        Cache::put($key, $value, now()->addSeconds($ttl));

        $this->info("✅ Cache key [{$key}] đã được lưu giá trị: {$value} (TTL {$ttl}s)");
    }
}
