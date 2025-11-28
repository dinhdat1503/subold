<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSecurity extends Model
{
    use HasFactory;

    protected $table = 'user_security';

    protected $fillable = [
        'user_id',
        'attempt_login',
        'attempt_error',
        'banned_reason',
        'twofa_enabled',
        'twofa_secret',
        'twofa_qr',
        'otp_email_enabled',
        'otp_email_code',
        'otp_email_expires',
        'api_token',
    ];

    protected $hidden = [
        'twofa_secret',
        'otp_email_code',
        'api_token',
    ];

    protected $casts = [
        'attempt_login' => 'integer',
        'attempt_error' => 'integer',
        'twofa_enabled' => 'boolean',
        'otp_email_enabled' => 'boolean',
        'otp_email_expires' => 'datetime',
    ];

    // Quan hệ với bảng users
    public function user()
    {
        return $this->belongsTo(User::class, "user_id");
    }
}
