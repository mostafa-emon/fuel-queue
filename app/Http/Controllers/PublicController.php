<?php

namespace App\Http\Controllers;

use App\Models\DailyAcceptance;
use App\Models\FuelType;
use App\Models\QueueBooking;
use App\Models\VehicleType;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    public function index()
    {
        $today = now()->format('Y-m-d');
        $fuelTypes = FuelType::all();
        $vehicleTypes = VehicleType::all();
        $dailyAcceptances = DailyAcceptance::with(['fuelType', 'vehicleType'])
            ->where('date', $today)
            ->get();

        $stats = [];
        foreach ($fuelTypes as $fuel) {
            foreach ($vehicleTypes as $vehicle) {
                $acceptance = $dailyAcceptances->where('fuel_type_id', $fuel->id)
                    ->where('vehicle_type_id', $vehicle->id)
                    ->first();
                
                $capacity = $acceptance ? $acceptance->max_capacity : 0;
                
                $bookedCount = QueueBooking::whereDate('created_at', $today)
                    ->where('fuel_type_id', $fuel->id)
                    ->where('vehicle_type_id', $vehicle->id)
                    ->count();
                
                $completedCount = QueueBooking::whereDate('created_at', $today)
                    ->where('fuel_type_id', $fuel->id)
                    ->where('vehicle_type_id', $vehicle->id)
                    ->where('status', 'completed')
                    ->count();

                if ($capacity > 0) {
                    $stats[] = [
                        'fuel' => $fuel->name,
                        'vehicle' => $vehicle->name,
                        'capacity' => $capacity,
                        'booked' => $bookedCount,
                        'remaining' => max(0, $capacity - $bookedCount),
                        'completed' => $completedCount,
                    ];
                }
            }
        }

        return view('status', compact('stats', 'today'));
    }
}
