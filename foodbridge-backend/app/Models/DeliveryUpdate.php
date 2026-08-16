<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DeliveryUpdate extends Model
{
    protected $fillable = [
        'delivery_id',
        'update_status',
        'update_time',
        'location_note',
        'updated_by',
    ];

    protected $casts = [
        'update_time' => 'datetime',
    ];

    public function delivery(): BelongsTo
    {
        return $this->belongsTo(Delivery::class);
    }
}