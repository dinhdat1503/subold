<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServerService extends Model
{
    use HasFactory;

    protected $table = 'server_services';

    protected $fillable = [
        'service_id',
        'server',
        'flag',
        'price',
        'min',
        'max',
        'title',
        'description',
        'status',
        'action_reaction',
        'action_time',
        'action_comment',
        'action_amount',
        'action_order',
        'supplier_id',
        'supplier_code'
    ];

    protected $hidden = [];

    protected $casts = [
        'price' => 'decimal:2',
        'min' => 'integer',
        'max' => 'integer',
        'status' => 'boolean',
        'action_reaction' => 'array',
        'action_time' => 'array',
        'action_comment' => 'array',
        'action_amount' => 'array',
        'action_order' => 'array',
    ];

    /**
     * Lấy danh sách server theo service_id
     */
    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }
    public function serverSupplier()
    {
        return $this->hasOne(ServerSupplier::class, 'server_id', 'id');
    }
    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id', 'id');
    }
}
