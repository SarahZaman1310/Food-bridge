<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class FoodRequest extends Model
{
    protected $fillable = [
        'ngo_id',
        'donation_id',
        'requested_qty',
        'requested_at',
        'request_status',
    ];

    protected $casts = [
        'requested_at' => 'datetime',
    ];

    public function ngo(): BelongsTo
    {
        return $this->belongsTo(Ngo::class);
    }

    public function donation(): BelongsTo
    {
        return $this->belongsTo(FoodDonation::class, 'donation_id');
    }

    public function delivery(): HasOne
    {
        return $this->hasOne(Delivery::class, 'request_id');
    }
}