<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyAcceptance extends Model
{
    protected $fillable = ['date', 'fuel_type_id', 'vehicle_type_id', 'max_capacity'];

    public function fuelType()
    {
        return $this->belongsTo(FuelType::class);
    }

    public function vehicleType()
    {
        return $this->belongsTo(VehicleType::class);
    }
}
