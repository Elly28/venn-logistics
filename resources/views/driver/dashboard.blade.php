<!-- resources/views/driver/dashboard.blade.php -->
@extends('layouts.app')

@section('content')
    <!-- Flash Messages & Validation Errors -->
    @if (session('success'))
        <div class="mb-6 rounded-lg bg-emerald-50 border border-emerald-200 p-4 flex items-center text-emerald-800">
            <svg class="w-5 h-5 mr-3 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-6 rounded-lg bg-red-50 border border-red-200 p-4 text-red-800">
            <p class="font-semibold mb-1">Please fix the following errors:</p>
            <ul class="list-disc list-inside text-sm space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

        <!-- CARD 1: PRE-TRIP VEHICLE INSPECTION -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="bg-slate-800 px-6 py-4 border-b border-slate-200">
                <h2 class="text-lg font-semibold text-white">1. Pre-Trip Vehicle Inspection</h2>
                <p class="text-xs text-slate-300 mt-0.5">Complete safety checks before starting your first trip.</p>
            </div>

            <form action="{{ route('driver.inspections.store') }}" method="POST" class="p-6 space-y-5">
                @csrf

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Vehicle Selector -->
                    <div>
                        <label for="inspection_vehicle_id" class="block text-sm font-medium text-slate-700 mb-1">Select Vehicle</label>
                        <select name="vehicle_id" id="inspection_vehicle_id" required
                                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm bg-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none">
                            <option value="">-- Choose Vehicle --</option>
                            @foreach($vehicles as $vehicle)
                                <option value="{{ $vehicle->id }}" {{ old('vehicle_id') == $vehicle->id ? 'selected' : '' }}>
                                    {{ $vehicle->registration_number }} ({{ $vehicle->make }} {{ $vehicle->model }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Inspection Date -->
                    <div>
                        <label for="inspection_date" class="block text-sm font-medium text-slate-700 mb-1">Inspection Date</label>
                        <input type="date" name="inspection_date" id="inspection_date"
                               value="{{ old('inspection_date', date('Y-m-d')) }}" required
                               class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm bg-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none">
                    </div>
                </div>

                <!-- Dynamic Checklist Items -->
                <div>
                    <span class="block text-sm font-medium text-slate-700 mb-2">Safety Checklist (Check all items that pass)</span>
                    <div class="bg-slate-50 rounded-lg border border-slate-200 divide-y divide-slate-200 overflow-hidden">
                        @foreach($checklistItems as $index => $item)
                            <label class="flex items-center justify-between p-3 hover:bg-slate-100 cursor-pointer transition">
                                <span class="text-sm text-slate-700 font-medium">{{ $item }}</span>
                                <div class="flex items-center">
                                    <input type="hidden" name="items_checked[{{ $index }}][item]" value="{{ $item }}">
                                    <input type="hidden" name="items_checked[{{ $index }}][passed]" value="0">
                                    <input type="checkbox" name="items_checked[{{ $index }}][passed]" value="1" checked
                                           class="h-5 w-5 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- Overall Status -->
                <div>
                    <label for="status" class="block text-sm font-medium text-slate-700 mb-1">Overall Vehicle Condition</label>
                    <select name="status" id="status" required
                            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm bg-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none">
                        <option value="pass" {{ old('status') == 'pass' ? 'selected' : '' }}>Pass - Safe to Drive</option>
                        <option value="requires_attention" {{ old('status') == 'requires_attention' ? 'selected' : '' }}>Requires Attention - Minor Issue</option>
                        <option value="fail" {{ old('status') == 'fail' ? 'selected' : '' }}>Fail - Unsafe / Do Not Drive</option>
                    </select>
                </div>

                <!-- Notes -->
                <div>
                    <label for="notes" class="block text-sm font-medium text-slate-700 mb-1">Defects / Notes (Optional)</label>
                    <textarea name="notes" id="notes" rows="2" placeholder="Describe any scratches, warning lights, or low fluids..."
                              class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm bg-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none">{{ old('notes') }}</textarea>
                </div>

                <div class="pt-2">
                    <button type="submit"
                            class="w-full bg-slate-800 hover:bg-slate-700 text-white font-semibold py-2.5 px-4 rounded-lg shadow-sm transition cursor-pointer">
                        Submit Pre-Trip Inspection
                    </button>
                </div>
            </form>
        </div>

        <!-- CARD 2: LOG DAILY TRIP -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="bg-indigo-600 px-6 py-4 border-b border-indigo-700">
                <h2 class="text-lg font-semibold text-white">2. Log Daily Trip</h2>
                <p class="text-xs text-indigo-100 mt-0.5">Record destinations and odometer readings after completing a trip.</p>
            </div>

            <form action="{{ route('driver.trips.store') }}" method="POST" class="p-6 space-y-5">
                @csrf

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Vehicle Selector with data-mileage attribute -->
                    <div>
                        <label for="trip_vehicle_id" class="block text-sm font-medium text-slate-700 mb-1">Select Vehicle</label>
                        <select name="vehicle_id" id="trip_vehicle_id" required
                                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm bg-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none">
                            <option value="" data-mileage="0">-- Choose Vehicle --</option>
                            @foreach($vehicles as $vehicle)
                                <option value="{{ $vehicle->id }}" data-mileage="{{ $vehicle->current_mileage }}"
                                    {{ old('vehicle_id') == $vehicle->id ? 'selected' : '' }}>
                                    {{ $vehicle->registration_number }} ({{ $vehicle->make }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Trip Date -->
                    <div>
                        <label for="trip_date" class="block text-sm font-medium text-slate-700 mb-1">Trip Date</label>
                        <input type="date" name="trip_date" id="trip_date"
                               value="{{ old('trip_date', date('Y-m-d')) }}" max="{{ date('Y-m-d') }}" required
                               class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm bg-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none">
                    </div>
                </div>

                <!-- Destinations -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="start_destination" class="block text-sm font-medium text-slate-700 mb-1">Departure Point</label>
                        <input type="text" name="start_destination" id="start_destination"
                               value="{{ old('start_destination') }}" placeholder="e.g. Sandton Depot" required
                               class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm bg-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none">
                    </div>

                    <div>
                        <label for="end_destination" class="block text-sm font-medium text-slate-700 mb-1">Destination</label>
                        <input type="text" name="end_destination" id="end_destination"
                               value="{{ old('end_destination') }}" placeholder="e.g. Pretoria Client Site" required
                               class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm bg-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none">
                    </div>
                </div>

                <!-- Odometer Readings & Live Distance Display -->
                <div class="bg-slate-50 p-4 rounded-lg border border-slate-200 space-y-3">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="start_mileage" class="block text-sm font-medium text-slate-700 mb-1">Start Odometer (km)</label>
                            <input type="number" name="start_mileage" id="start_mileage"
                                   value="{{ old('start_mileage') }}" min="0" required
                                   class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm bg-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none font-mono">
                        </div>

                        <div>
                            <label for="end_mileage" class="block text-sm font-medium text-slate-700 mb-1">End Odometer (km)</label>
                            <input type="number" name="end_mileage" id="end_mileage"
                                   value="{{ old('end_mileage') }}" min="0" required
                                   class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm bg-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none font-mono">
                        </div>
                    </div>

                    <div class="flex justify-between items-center pt-1 text-sm">
                        <span class="text-slate-600">Calculated Trip Distance:</span>
                        <span id="distance_preview" class="font-bold text-indigo-600 font-mono text-base">0 km</span>
                    </div>
                </div>

                <!-- Purpose -->
                <div>
                    <label for="purpose" class="block text-sm font-medium text-slate-700 mb-1">Trip Purpose</label>
                    <textarea name="purpose" id="purpose" rows="2" placeholder="Client delivery, site visit, equipment transfer..." required
                              class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm bg-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none">{{ old('purpose') }}</textarea>
                </div>

                <div class="pt-2">
                    <button type="submit"
                            class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2.5 px-4 rounded-lg shadow-sm transition cursor-pointer">
                        Log Completed Trip
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- RECENT TRIPS LOG -->
    <div class="mt-8 bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-200 flex justify-between items-center">
            <h3 class="text-base font-semibold text-slate-800">Your Recent Trips</h3>
            <span class="text-xs text-slate-500">Last 5 entries</span>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead class="bg-slate-50">
                <tr>
                    <th class="px-6 py-3 text-left font-medium text-slate-500">Date</th>
                    <th class="px-6 py-3 text-left font-medium text-slate-500">Vehicle</th>
                    <th class="px-6 py-3 text-left font-medium text-slate-500">Route</th>
                    <th class="px-6 py-3 text-right font-medium text-slate-500">Odometer</th>
                    <th class="px-6 py-3 text-right font-medium text-slate-500">Distance</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 bg-white">
                @forelse($recentTrips as $trip)
                    <tr>
                        <td class="px-6 py-3 whitespace-nowrap text-slate-700">{{ $trip->trip_date->format('d M Y') }}</td>
                        <td class="px-6 py-3 whitespace-nowrap font-medium text-slate-900">{{ $trip->vehicle->registration_number }}</td>
                        <td class="px-6 py-3 text-slate-600">{{ $trip->start_destination }} &rarr; {{ $trip->end_destination }}</td>
                        <td class="px-6 py-3 whitespace-nowrap text-right font-mono text-slate-600">
                            {{ number_format($trip->start_mileage) }} - {{ number_format($trip->end_mileage) }}
                        </td>
                        <td class="px-6 py-3 whitespace-nowrap text-right font-semibold text-indigo-600">
                            {{ number_format($trip->total_distance) }} km
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-6 text-center text-slate-500">No trips logged yet.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Vanilla JS for Odometer Auto-Fill & Distance Calculation -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const vehicleSelect = document.getElementById('trip_vehicle_id');
            const startInput = document.getElementById('start_mileage');
            const endInput = document.getElementById('end_mileage');
            const distancePreview = document.getElementById('distance_preview');

            function updateDistance() {
                const start = parseInt(startInput.value) || 0;
                const end = parseInt(endInput.value) || 0;
                const diff = end - start;

                if (diff >= 0 && end > 0) {
                    distancePreview.textContent = diff.toLocaleString() + ' km';
                    distancePreview.classList.remove('text-red-600');
                    distancePreview.classList.add('text-indigo-600');
                } else if (end > 0 && diff < 0) {
                    distancePreview.textContent = 'Invalid (End < Start)';
                    distancePreview.classList.remove('text-indigo-600');
                    distancePreview.classList.add('text-red-600');
                } else {
                    distancePreview.textContent = '0 km';
                }
            }

            vehicleSelect.addEventListener('change', function () {
                const selectedOption = this.options[this.selectedIndex];
                const currentMileage = selectedOption.getAttribute('data-mileage');
                if (currentMileage && currentMileage !== '0') {
                    startInput.value = currentMileage;
                    endInput.min = currentMileage;
                    updateDistance();
                }
            });

            startInput.addEventListener('input', updateDistance);
            endInput.addEventListener('input', updateDistance);
        });
    </script>
@endsection
