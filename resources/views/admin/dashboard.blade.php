<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin: Manage Daily Queues') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-bold mb-6">Set New Daily Acceptance</h3>
                <form action="{{ route('admin.acceptance.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Date</label>
                        <input type="date" name="date" value="{{ date('Y-m-d') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Fuel Type</label>
                        <select name="fuel_type_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @foreach($fuelTypes as $type)
                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Vehicle Type</label>
                        <select name="vehicle_type_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @foreach($vehicleTypes as $type)
                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Max Capacity</label>
                        <input type="number" name="max_capacity" min="0" value="50" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div class="md:col-span-4 flex justify-end">
                        <x-primary-button>Update Acceptance</x-primary-button>
                    </div>
                </form>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-bold mb-6">Current Acceptances</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fuel</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vehicle</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Capacity</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($dailyAcceptances as $acceptance)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $acceptance->date }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $acceptance->fuelType->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $acceptance->vehicleType->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-bold">{{ $acceptance->max_capacity }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
