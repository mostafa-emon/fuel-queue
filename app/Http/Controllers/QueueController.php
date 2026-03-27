<?php

namespace App\Http\Controllers;

use App\Models\DailyAcceptance;
use App\Models\FuelType;
use App\Models\QueueBooking;
use App\Models\VehicleType;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class QueueController extends Controller
{
    public function index()
    {
        $fuelTypes = FuelType::all();
        $vehicleTypes = VehicleType::all();
        $bookings = QueueBooking::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();
        
        $today = now()->format('Y-m-d');
        $availabilities = DailyAcceptance::where('date', $today)->get();

        return view('user.dashboard', compact('fuelTypes', 'vehicleTypes', 'bookings', 'availabilities'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'fuel_type_id' => 'required|exists:fuel_types,id',
            'vehicle_type_id' => 'required|exists:vehicle_types,id',
        ]);

        $today = now()->format('Y-m-d');

        // Check if user already has a pending booking for today
        $existing = QueueBooking::where('user_id', auth()->id())
            ->where('status', 'pending')
            ->whereDate('created_at', $today)
            ->first();

        if ($existing) {
            return back()->with('error', 'You already have a pending booking for today.');
        }

        // Check capacity
        $acceptance = DailyAcceptance::where('date', $today)
            ->where('fuel_type_id', $request->fuel_type_id)
            ->where('vehicle_type_id', $request->vehicle_type_id)
            ->first();

        if (!$acceptance || $acceptance->max_capacity <= 0) {
            return back()->with('error', 'Sorry, no capacity available for this selection today.');
        }

        $currentBookings = QueueBooking::whereDate('created_at', $today)
            ->where('fuel_type_id', $request->fuel_type_id)
            ->where('vehicle_type_id', $request->vehicle_type_id)
            ->count();

        if ($currentBookings >= $acceptance->max_capacity) {
            return back()->with('error', 'Sorry, the queue is full for today.');
        }

        // Create booking
        $serial = 'QN-' . strtoupper(Str::random(6));
        $token = Str::random(32);

        QueueBooking::create([
            'user_id' => auth()->id(),
            'fuel_type_id' => $request->fuel_type_id,
            'vehicle_type_id' => $request->vehicle_type_id,
            'serial_number' => $serial,
            'qr_token' => $token,
            'status' => 'pending',
        ]);

        return back()->with('success', 'Booking successful! Your serial is ' . $serial);
    }
}
