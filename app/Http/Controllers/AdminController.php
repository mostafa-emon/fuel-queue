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
            ->get();

        return view('admin.dashboard', compact('fuelTypes', 'vehicleTypes', 'dailyAcceptances'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'fuel_type_id' => 'required|exists:fuel_types,id',
            'vehicle_type_id' => 'required|exists:vehicle_types,id',
            'max_capacity' => 'required|integer|min:0',
        ]);

        DailyAcceptance::updateOrCreate(
            [
                'date' => $request->date,
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
