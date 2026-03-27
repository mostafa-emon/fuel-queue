<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QueueBooking extends Model
{
    protected $fillable = ['user_id', 'fuel_type_id', 'vehicle_type_id', 'serial_number', 'qr_token', 'status'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function fuelType()
    {
        return $this->belongsTo(FuelType::class);
    }

    public function vehicleType()
    {
        return $this->belongsTo(VehicleType::class);
    }
}
