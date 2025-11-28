<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServerSupplier extends Model
{
    use HasFactory;

    protected $table = 'server_suppliers';

    protected $fillable = [
        'server_id',
        'service',
        'title',
        'description',
        'cost',
        'status_off',
        'update_minmax',
    ];
    protected $casts = [
        'cost' => 'decimal:2',
        'update_minmax' => 'boolean',
    ];
    public function server()
    {
        return $this->belongsTo(
            ServerService::class,
            'server_id', // Tên Khóa ngoại trên bảng server_suppliers
            'id'         // Khóa cục bộ trên bảng server_services
        );
    }
}
