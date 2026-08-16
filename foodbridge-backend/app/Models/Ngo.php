<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ngo extends Model
{
    protected $fillable = [
        'ngo_name',
        'registration_no',
        'email',
        'phone',
        'address',
        'is_verified',
    ];

    public function foodRequests(): HasMany
    {
        return $this->hasMany(FoodRequest::class);
    }

    public function recipients(): HasMany
    {
        return $this->hasMany(Recipient::class);
    }
}