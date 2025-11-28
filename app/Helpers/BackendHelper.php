<?php

namespace App\Helpers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class BackendHelper
{
    public static function resApi($status = 'error', $message = 'Có lỗi xảy ra', array $extra = [], $json = true)
    {
        if ($json) {
            return response()->json(array_merge([
                'status' => $status,
                'message' => $message,
            ], $extra));
        } else {
            return array_merge([
                'status' => $status,
                'message' => $message,
            ], $extra);
        }
    }
    public static function uploadPublicFile($file, string $folder, string $oldFile = null, string $prefix = 'file')
    {
        if (!$file) {
            return $oldFile;
        }
        $uploadPath = public_path($folder);
        if (!File::exists($uploadPath)) {
            File::makeDirectory($uploadPath, 0755, true);
        }
        if ($oldFile && File::exists(public_path($oldFile))) {
            File::delete(public_path($oldFile));
        }
        $extension = $file->getClientOriginalExtension();
        $filename = $prefix . "-" . Str::random(10) . '.' . $extension;
        $file->move($uploadPath, $filename);
        return '/' . trim($folder, '/') . '/' . $filename;
    }
    public static function sanitize(mixed $value, string $type = 'string', array $options = []): mixed
    {
        if ($value === null) {
            return $options['default'] ?? match ($type) {
                'int' => 0,
                'float' => 0.0,
                'bool' => false,
                default => '',
            };
        }

        if (is_string($value)) {
            $value = str_replace("\0", '', $value);
            $value = trim($value);
        }

        return match ($type) {
            'int' => (int) (is_numeric($value) ? $value : 0),
            'float' => round((float) (is_numeric($value) ? $value : 0), $options['precision'] ?? 2),
            'bool' => filter_var($value, FILTER_VALIDATE_BOOL, FILTER_NULL_ON_FAILURE) ?? false,
            'email' => filter_var($value, FILTER_VALIDATE_EMAIL) ?: ($options['default'] ?? ''),
            'url' => filter_var($value, FILTER_VALIDATE_URL) ?: ($options['default'] ?? ''),
            'array' => is_array($value) ? $value : [],
            default => is_array($value) || is_object($value)
            ? json_encode($value, JSON_UNESCAPED_UNICODE)
            : strip_tags((string) $value, $options['allowedTags'] ?? ''),
        };
    }
    public static function formatProxy(?string $proxy): ?string
    {
        if (empty($proxy)) {
            return null;
        }
        $proxy = trim($proxy);
        if (str_starts_with($proxy, 'http://')) {
            return $proxy;
        }
        $parts = preg_split('/[|:]/', $proxy);
        $ip = $parts[0] ?? '';
        if (!filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            return null;
        }
        if (count($parts) === 2) {
            [$ip, $port] = $parts;
            return "http://{$ip}:{$port}";
        }
        if (count($parts) === 4) {
            [$ip, $port, $user, $pass] = $parts;
            return "http://{$user}:{$pass}@{$ip}:{$port}";
        }
        return null;
    }
    public static function multiRequest(array $requests, int $timeout = 30, int $connectTimeout = 5): array
    {
        if (empty($requests)) {
            return [];
        }
        $responses = Http::pool(function (\Illuminate\Http\Client\Pool $pool) use ($requests, $timeout, $connectTimeout) {
            $calls = [];
            foreach ($requests as $req) {
                $name = $req['name'] ?? uniqid('req_');
                $url = trim($req['url'] ?? '');
                $method = strtoupper($req['method'] ?? 'GET');
                $data = $req['data'] ?? [];
                $headers = $req['headers'] ?? [];
                $proxy = self::formatProxy($req['proxy'] ?? null);
                if (empty($url) || !filter_var($url, FILTER_VALIDATE_URL)) {
                    continue;
                }
                $builder = $pool->as($name)
                    ->timeout($timeout)
                    ->connectTimeout($connectTimeout)
                    ->withHeaders($headers);
                if ($proxy) {
                    $builder = $builder->withOptions(['proxy' => $proxy]);
                }
                if ($method === 'POST') {
                    $calls[] = $builder->asForm()->post($url, $data);
                } else {
                    $calls[] = $builder->get($url, $data);
                }
            }
            return $calls;
        });
        $results = [];
        foreach ($requests as $req) {
            $name = $req['name'] ?? null;
            if ($name && isset($responses[$name])) {
                $results[$name] = $responses[$name];
            }
        }
        return $results;
    }
    public static function ipBlockCheck($ip, $reason)
    {
        $ipLog = \App\Models\BlockedIp::firstOrCreate(
            ['ip_address' => $ip],
            ['reason' => null, 'attempts' => 0, 'banned' => 0]
        );
        $ipLog->attempts++;
        if ($ipLog->attempts >= siteSetting('max_ip_attempts')) {
            $ipLog->reason = $reason;
            $ipLog->banned = 1;
        }
        $ipLog->save();
        \Cache::forget('blocked-ips-list');
    }
    public static function sendMessTelegram(string $type, array $data = [])
    {
        $token = env("TELEGRAM_BOT_TOKEN");
        $chatID = env("TELEGRAM_CHAT_ID");
        $viewMap = [
            'balance_low' => 'telegram.balance.low',
            'balance_insufficient' => 'telegram.balance.insufficient',
        ];
        $prefix = match ($type) {
            'balance_low' => '⚠️ <b>[CẢNH BÁO SỐ DƯ THẤP]</b>',
            'balance_insufficient' => '❌ <b>[SỐ DƯ KHÔNG ĐỦ]</b>',
            default => '📢 <b>[THÔNG BÁO]</b>',
        };
        $message = \View::make($viewMap[$type], $data)->render();
        if (mb_strlen($message) < 4000) {
            $url = "https://api.telegram.org/bot{$token}/sendMessage";
            $response = Http::post($url, [
                'chat_id' => $chatID,
                'text' => $message,
                'parse_mode' => 'HTML',
            ]);
            return $response->successful();
        }
        $tempFilePath = storage_path('app/telegram-' . Str::random(8) . '.txt');
        file_put_contents($tempFilePath, strip_tags($message));
        $url = "https://api.telegram.org/bot{$token}/sendDocument";
        $response = Http::attach(
            'document',
            file_get_contents($tempFilePath),
            basename($tempFilePath)
        )->post($url, [
                    'chat_id' => $chatID,
                    'caption' => $prefix,
                    'parse_mode' => 'HTML',
                ]);
        @unlink($tempFilePath);
        return $response->successful();
    }
}
