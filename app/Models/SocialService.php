<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SocialService extends Model
{
    use HasFactory;

    protected $table = 'social_services';

    protected $fillable = [
        'name',
        'slug',
        'image',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];
    public function services()
    {
        return $this->hasMany(Service::class, 'social_id');
    }

    // public function servers()
    // {
    //     return $this->hasMany(ServiceServer::class, 'social_id', 'id');
    // }

}
