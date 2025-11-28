<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    use HasFactory;

    protected $table = 'site_settings';

    protected $fillable = [
        'setting_key',
        'setting_value',
    ];

    public $timestamps = true;

    /**
     * Lấy setting theo key
     */
    public static function getValue($key, $default = null)
    {
        return static::where('setting_key', $key)->value('setting_value') ?? $default;
    }

    /**
     * Thêm hoặc cập nhật setting
     */
    public static function setValue($key, $value)
    {
        return static::where('setting_key', $key)->update(['setting_value' => $value]);
    }
}
