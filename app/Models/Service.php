<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $table = 'services';

    protected $fillable = [
        'social_id',
        'name',
        'slug',
        'image',
        'note',
        'status',
    ];
    protected $casts = [
        'status' => 'boolean',
    ];
    public function social()
    {
        return $this->belongsTo(SocialService::class, 'social_id');
    }
    public function servers()
    {
        return $this->hasMany(ServerService::class, 'service_id');
    }
}
