<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Book Your Fuel Slot') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <!-- Messages -->
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif
            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Booking Form -->
                <div class="md:col-span-1 bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 h-fit">
                    <h3 class="text-lg font-bold mb-4">New Booking</h3>
                    <form action="{{ route('user.book') }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Fuel Type</label>
                            <select name="fuel_type_id" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="" disabled selected>-- select fuel type --</option>
                                @foreach($fuelTypes as $type)
                                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Vehicle Type</label>
                            <select name="vehicle_type_id" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="" disabled selected>-- select vehicle type --</option>
                                @foreach($vehicleTypes as $type)
                                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="w-full bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-700 transition">
                            Book Now
                        </button>
                    </form>
                </div>

                <!-- Active Bookings -->
                <div class="md:col-span-2">
                    <h3 class="text-lg font-bold mb-4">My Bookings</h3>
                    @if(count($bookings) > 0)
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @foreach($bookings as $booking)
                                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 relative">
                                    <div class="flex justify-between items-start mb-4">
                                        <div>
                                            <p class="text-xs font-bold text-indigo-600 uppercase">{{ $booking->fuelType->name }}</p>
                                            <p class="text-xl font-bold">{{ $booking->vehicleType->name }}</p>
                                            <p class="text-sm text-gray-500">{{ $booking->serial_number }}</p>
                                        </div>
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $booking->status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                            {{ ucfirst($booking->status) }}
                                        </span>
                                    </div>

                                    @if($booking->status === 'pending')
                                        <div class="flex flex-col items-center justify-center p-4 bg-gray-50 rounded-xl">
                                            {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(150)->generate($booking->qr_token) !!}
                                            <p class="mt-4 text-xs font-mono text-gray-400">{{ $booking->qr_token }}</p>
                                            <p class="mt-2 text-sm text-center text-gray-600">Show this code at the pump</p>
                                        </div>
                                    @else
                                        <div class="flex flex-col items-center justify-center p-8 bg-green-50 rounded-xl opacity-60">
                                            <svg class="w-12 h-12 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            <p class="mt-2 text-sm text-green-700 font-semibold">Verified</p>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-12 text-center text-gray-500">
                            You have no bookings yet.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
