<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Operator: Verify QR Code') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 max-w-md mx-auto">
                <h3 class="text-lg font-bold mb-6 text-center text-gray-900 uppercase tracking-wider font-mono">Verify Token</h3>
                <form action="{{ route('operator.verify') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Scan or Enter QR Token</label>
                        <input type="text" name="qr_token" required placeholder="Paste or scan token here" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 font-mono text-center text-xl tracking-widest">
                    </div>
                    <button type="submit" class="w-full bg-green-600 text-white font-bold py-4 rounded-xl hover:bg-green-700 transition">
                        Verify & Complete
                    </button>
                </form>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-bold mb-6">Today's Pending Queues</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Serial</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fuel</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vehicle</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Booked At</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($pendingBookings as $booking)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-indigo-600">{{ $booking->serial_number }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $booking->user->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $booking->fuelType->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $booking->vehicleType->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $booking->created_at->format('H:i') }}</td>
                                </tr>
                            @endforeach
                            @if(count($pendingBookings) === 0)
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-gray-500">No pending bookings for today.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
