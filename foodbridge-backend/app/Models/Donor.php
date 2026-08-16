<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Donor extends Model
{
    protected $fillable = [
        'donor_name',
        'donor_type',
        'email',
        'phone',
        'address',
    ];

    public function foodDonations(): HasMany
    {
        return $this->hasMany(FoodDonation::class);
    }
}