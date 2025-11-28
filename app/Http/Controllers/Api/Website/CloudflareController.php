<?php

namespace App\Http\Controllers\Api\Website;

use App\Helpers\BackendHelper;
use App\Http\Controllers\Controller;

class CloudflareController extends Controller
{
    public static function getIPs()
    {
        $responses = BackendHelper::multiRequest([
            [
                'name' => 'ipv4',
                'url' => 'https://www.cloudflare.com/ips-v4',
            ],
            [
                'name' => 'ipv6',
                'url' => 'https://www.cloudflare.com/ips-v6',
            ],
        ], 30, 5);
        $cloudflareIps = [];
        foreach ($responses as $response) {
            if (!method_exists($response, 'successful')) {
                return BackendHelper::resApi('error', "Code 1 - Cloudflare Ips {$response->name} gặp lỗi Exception/Error: " . (is_object($response) ? $response->getMessage() : 'Phản hồi không hợp lệ'), [], false);
            }
            if (!$response || !$response->successful()) {
                continue;
            }
            $body = $response->body();
            $ranges = array_filter(explode("\n", $body));
            $cloudflareIps = array_merge($cloudflareIps, $ranges);
        }
        if (empty($cloudflareIps)) {
            return BackendHelper::resApi('error', 'Code 2 - Cả hai yêu cầu Cloudflare IPs đều thất bại hoặc không có dữ liệu.', [], false);
        }
        return BackendHelper::resApi('success', 'Cloudflare IPs fetched successfully', ['data' => $cloudflareIps]);
    }
}
