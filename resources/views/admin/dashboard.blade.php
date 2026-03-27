<x-app-layout>
    <x-slot name="header">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin: Manage Daily Queues') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-bold mb-6">Set New Daily Acceptance</h3>

                <!-- Dynamic Warning -->
                <div id="duplicate-warning"
                    class="hidden mb-4 bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4" role="alert">
                    <p><strong>Warning!</strong> A capacity entry for this combination already exists.
                        Updating will overwrite the current
                        capacity.</p>
                </div>

                <form id="acceptance-form" action="{{ route('admin.acceptance.store') }}" method="POST"
                    class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Date</label>
                        <input type="text" name="date" id="input-date" value="{{ date('d/m/Y') }}"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Fuel Type</label>
                        <select name="fuel_type_id" id="input-fuel" required
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="" disabled selected>-- select fuel type --</option>
                            @foreach($fuelTypes as $type)
                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Vehicle Type</label>
                        <select name="vehicle_type_id" id="input-vehicle" required
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="" disabled selected>-- select vehicle type --</option>
                            @foreach($vehicleTypes as $type)
                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Max Capacity</label>
                        <input type="number" name="max_capacity" min="0" value="50"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div class="md:col-span-4 flex justify-end">
                        <x-primary-button id="submit-btn">Update Acceptance</x-primary-button>
                    </div>
                </form>
            </div>

            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    const dateInput = document.getElementById('input-date');
                    const fuelInput = document.getElementById('input-fuel');
                    const vehicleInput = document.getElementById('input-vehicle');
                    const warning = document.getElementById('duplicate-warning');
                    const submitBtn = document.getElementById('submit-btn');

                    // Initialize Flatpickr
                    const fp = flatpickr(dateInput, {
                        dateFormat: "d/m/Y",
                        defaultDate: "today",
                        onChange: function (selectedDates, dateStr, instance) {
                            checkDuplicate();
                        }
                    });

                    // Store existing data in a more accessible format (convert Y-m-d to d/m/Y for comparison)
                    const existingData = @json($dailyAcceptances).map(item => {
                        const [y, m, d] = item.date.split('-');
                        return {
                            date: `${d}/${m}/${y}`,
                            fuel_id: item.fuel_type_id.toString(),
                            vehicle_id: item.vehicle_type_id.toString()
                        };
                    });

                    function checkDuplicate() {
                        if (!fuelInput.value || !vehicleInput.value) {
                            warning.classList.add('hidden');
                            submitBtn.innerHTML = 'Set New Capacity';
                            return;
                        }

                        const isMatch = existingData.some(item =>
                            item.date === dateInput.value &&
                            item.fuel_id === fuelInput.value &&
                            item.vehicle_id === vehicleInput.value
                        );

                        if (isMatch) {
                            warning.classList.remove('hidden');
                            submitBtn.innerHTML = 'Update Existing Capacity';
                        } else {
                            warning.classList.add('hidden');
                            submitBtn.innerHTML = 'Set New Capacity';
                        }
                    }

                    [fuelInput, vehicleInput].forEach(input => {
                        input.addEventListener('change', checkDuplicate);
                    });

                    // Initial check
                    checkDuplicate();
                });
            </script>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-bold mb-6">Current Acceptances</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Date</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Fuel</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Vehicle</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Capacity</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Booked</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Completed</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($dailyAcceptances as $acceptance)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ \Carbon\Carbon::parse($acceptance->date)->format('d-m-Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $acceptance->fuelType->name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $acceptance->vehicleType->name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-bold text-center">
                                        {{ $acceptance->max_capacity }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-600 font-bold text-center">
                                        {{ $acceptance->booked_count }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600 font-bold text-center">
                                        {{ $acceptance->completed_count }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>