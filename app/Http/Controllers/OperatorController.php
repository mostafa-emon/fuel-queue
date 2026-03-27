<?php

namespace App\Http\Controllers;

use App\Models\QueueBooking;
use Illuminate\Http\Request;

class OperatorController extends Controller
{
    public function index()
    {
        $pendingBookings = QueueBooking::with(['user', 'fuelType', 'vehicleType'])
            ->where('status', 'pending')
            ->orderBy('created_at', 'asc')
            ->get();

        return view('operator.dashboard', compact('pendingBookings'));
    }

    public function verify(Request $request)
    {
        $request->validate([
            'qr_token' => 'required|string',
        ]);

        $booking = QueueBooking::where('qr_token', $request->qr_token)
            ->where('status', 'pending')
            ->first();

        if (!$booking) {
            return back()->with('error', 'Invalid or expired QR token.');
        }

        $booking->update([
            'status' => 'completed',
        ]);

        return back()->with('success', 'Booking verified and completed for serial ' . $booking->serial_number);
    }
}
