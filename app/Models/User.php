<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'users'; // tên bảng trong DB

    /**
     * Các cột có thể gán giá trị hàng loạt (Mass Assignable).
     */
    protected $fillable = [
        'full_name',
        'email',
        'avatar_url',
        'username',
        'password',
        'total_recharge',
        'balance',
        'total_deduct',
        'promotion_recharge',
        'level',
        'last_ip',
        'last_useragent',
        'last_online',
        'utm_source',
        'role',
        'status',
        'remember_token',
    ];

    /**
     * Các cột ẩn khi trả về JSON.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Kiểu dữ liệu cho các cột.
     */
    protected $casts = [
        'total_recharge' => 'decimal:2',
        'balance' => 'decimal:2',
        'total_deduct' => 'decimal:2',
        'promotion_recharge' => 'decimal:2',
        'level' => 'integer',
        'status' => 'boolean',
        'last_online' => 'datetime'
    ];
    public function security()
    {
        return $this->hasOne(UserSecurity::class, 'user_id');
    }
}
