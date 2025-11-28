<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RechargeMethod extends Model
{
    use HasFactory;

    protected $table = 'recharge_methods';

    protected $fillable = [
        'method_type',
        'name',
        'exchange_rate',
        'recharge_min',
        'account_name',
        'account_index',
        'wallet_qr',
        'network',
        'api_key',
        'note',
        'status',
    ];
    protected $hidden = [
        'api_key',
    ];
    protected $casts = [
        'exchange_rate' => 'decimal:4',
        'recharge_min' => 'integer',
        'status' => 'boolean',
    ];
    public static function getIdByMethodType(string $type)
    {
        return self::where('method_type', $type)->value('id');
    }
}
