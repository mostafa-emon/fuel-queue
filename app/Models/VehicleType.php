<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehicleType extends Model
{
    protected $fillable = ['name'];

    public function dailyAcceptances()
    {
        return $this->hasMany(DailyAcceptance::class);
    }
}
