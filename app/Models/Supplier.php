<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $table = 'suppliers'; // đúng tên bảng

    protected $fillable = [
        'name',
        'base_url',
        'api_key',
        'proxy',
        'price_percent',
        'price_unit_value',
        'api',
        'username',
        'money',
        'currency',
        'exchange_rate',
        'status',
        'last_synced_at',
    ];

    protected $hidden = [
        'api_key',
    ];

    protected $casts = [
        'money' => 'decimal:2',
        'price_percent' => 'integer',
        'price_unit_value' => 'integer',
        'exchange_rate' => 'decimal:4',
        'status' => 'boolean',
        'last_synced_at' => 'datetime',
    ];

    public function servers()
    {
        return $this->hasMany(ServerService::class, 'supplier_id', 'id');
    }
}
