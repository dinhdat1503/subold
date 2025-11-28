<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';

    protected $fillable = [
        'user_id',
        'server_id',
        'quantity',
        'payment',
        'payment_real',
        'profit',
        'order_link',
        'order_info',
        'count_start',
        'count_buff',
        'time_start',
        'time_end',
        'supplier_id',
        'supplier_order_id',
        'status',
        'logs',
        'note',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'payment' => 'decimal:2',
        'payment_real' => 'decimal:2',
        'profit' => 'decimal:2',
        'order_info' => 'array',
        'count_start' => 'integer',
        'count_buff' => 'integer',
        'logs' => 'array',
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function server()
    {
        return $this->belongsTo(ServerService::class, 'server_id');
    }
    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }
}
