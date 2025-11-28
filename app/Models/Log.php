<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    use HasFactory;

    protected $table = 'logs';

    protected $fillable = [
        'user_id',
        'action_type',
        'old_value',
        'value',
        'new_value',
        'ip_address',
        'useragent',
        'description',
    ];

    protected $casts = [
        'old_value' => 'decimal:2',
        'value' => 'decimal:2',
        'new_value' => 'decimal:2',
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
