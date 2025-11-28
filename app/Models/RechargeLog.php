<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RechargeLog extends Model
{
    use HasFactory;

    protected $table = 'recharge_logs';

    protected $fillable = [
        'user_id',
        'recharge_id',
        'trans_id',
        'amount',
        'promotion',
        'amount_received',
        'status',
        'note',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'promotion' => 'decimal:2',
        'amount_received' => 'decimal:2',
        'status' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function recharge()
    {
        return $this->belongsTo(RechargeMethod::class, 'recharge_id');
    }
}
