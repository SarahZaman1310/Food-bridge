<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FoodDonation extends Model
{
    protected $fillable = [
        'donor_id',
        'food_name',
        'food_category',
        'quantity',
        'unit',
        'prepared_at',
        'expiry_at',
        'availability_status',
        'donation_status',
    ];

    protected $casts = [
        'prepared_at' => 'datetime',
        'expiry_at' => 'datetime',
    ];

    public function donor(): BelongsTo
    {
        return $this->belongsTo(Donor::class);
    }

    public function foodRequests(): HasMany
    {
        return $this->hasMany(FoodRequest::class, 'donation_id');
    }
}