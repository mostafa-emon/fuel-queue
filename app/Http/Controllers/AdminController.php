<?php

namespace App\Http\Controllers;

use App\Models\DailyAcceptance;
use App\Models\FuelType;
use App\Models\VehicleType;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $fuelTypes = FuelType::all();
        $vehicleTypes = VehicleType::all();
        $dailyAcceptances = DailyAcceptance::with(['fuelType', 'vehicleType'])
            ->where('date', '>=', now()->format('Y-m-d'))
            ->orderBy('date', 'asc')
            ->get()
            ->map(function($item) {
                $bookings = \App\Models\QueueBooking::whereDate('created_at', $item->date)
                    ->where('fuel_type_id', $item->fuel_type_id)
                    ->where('vehicle_type_id', $item->vehicle_type_id)
                    ->get();
                $item->booked_count = $bookings->count();
                $item->completed_count = $bookings->where('status', 'completed')->count();
                return $item;
            });

        return view('admin.dashboard', compact('fuelTypes', 'vehicleTypes', 'dailyAcceptances'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|string',
            'fuel_type_id' => 'required|exists:fuel_types,id',
            'vehicle_type_id' => 'required|exists:vehicle_types,id',
            'max_capacity' => 'required|integer|min:0',
        ]);

        $formattedDate = \Carbon\Carbon::createFromFormat('d/m/Y', $request->date)->format('Y-m-d');

        DailyAcceptance::updateOrCreate(
            [
                'date' => $formattedDate,
                'fuel_type_id' => $request->fuel_type_id,
                'vehicle_type_id' => $request->vehicle_type_id,
            ],
            [
                'max_capacity' => $request->max_capacity,
            ]
        );

        return back()->with('success', 'Daily acceptance updated successfully.');
    }
}
