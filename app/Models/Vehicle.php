<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vehicle extends Model
{
    protected $fillable = [
        'registration_number', 'make', 'model', 'year', 'current_mileage', 'status'
    ];

    public function trips(): HasMany
    {
        return $this->hasMany(Trip::class);
    }

    public function inspections(): HasMany
    {
        return $this->hasMany(VehicleInspection::class);
    }
}
