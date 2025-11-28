<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlockedIp extends Model
{
    use HasFactory;

    protected $table = 'blocked_ips';

    // Các field có thể fill
    protected $fillable = [
        'ip_address',
        'reason',
        'attempts',
        'banned',
        'banned_cloudflare',
    ];
    protected $casts = [
        'attempts' => 'integer',
        'banned' => 'boolean',
        'banned_cloudflare' => 'boolean',
    ];
}
