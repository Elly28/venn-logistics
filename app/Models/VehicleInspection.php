<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VehicleInspection extends Model
{
    protected $fillable = [
        'vehicle_id', 'driver_id', 'inspection_date', 'items_checked', 'status', 'notes'
    ];

    protected $casts = [
        'inspection_date' => 'date',
        'items_checked' => 'array', // Automatically serializes/unserializes JSON
    ];

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function driver(): BelongsTo
    {
        return $this->belongsTo(Driver::class);
    }
}
