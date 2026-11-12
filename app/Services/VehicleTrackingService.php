<?php

namespace App\Services;

use App\Models\Trip;
use App\Models\VehicleInspection;
use App\Models\Vehicle;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class VehicleTrackingService
{
    /**
     * Log a trip and automatically update the vehicle's current mileage.
     */
    public function logTrip(array $data, int $driverId): Trip
    {
        return DB::transaction(function () use ($data, $driverId) {
            $totalDistance = $data['end_mileage'] - $data['start_mileage'];

            $trip = Trip::create([
                'vehicle_id' => $data['vehicle_id'],
                'driver_id' => $driverId,
                'start_destination' => $data['start_destination'],
                'end_destination' => $data['end_destination'],
                'start_mileage' => $data['start_mileage'],
                'end_mileage' => $data['end_mileage'],
                'total_distance' => $totalDistance,
                'purpose' => $data['purpose'],
                'trip_date' => $data['trip_date'],
            ]);

            // Update the vehicle's current mileage if this trip is further
            $vehicle = Vehicle::findOrFail($data['vehicle_id']);
            if ($data['end_mileage'] > $vehicle->current_mileage) {
                $vehicle->update(['current_mileage' => $data['end_mileage']]);
            }

            return $trip;
        });
    }

    /**
     * Log a pre-trip inspection.
     */
    public function logInspection(array $data, int $driverId): VehicleInspection
    {
        return VehicleInspection::create([
            'vehicle_id' => $data['vehicle_id'],
            'driver_id' => $driverId,
            'inspection_date' => $data['inspection_date'],
            'items_checked' => $data['items_checked'],
            'status' => $data['status'],
            'notes' => $data['notes'] ?? null,
        ]);
    }

    /**
     * Compile statistics for the previous week's usage report.
     */
    public function getWeeklyReportData(): array
    {
        $startOfWeek = Carbon::now()->subWeek()->startOfWeek();
        $endOfWeek = Carbon::now()->subWeek()->endOfWeek();

        $trips = Trip::with(['vehicle', 'driver'])
            ->whereBetween('trip_date', [$startOfWeek, $endOfWeek])
            ->get();

        $failedInspections = VehicleInspection::with(['vehicle', 'driver'])
            ->whereBetween('inspection_date', [$startOfWeek, $endOfWeek])
            ->whereIn('status', ['fail', 'requires_attention'])
            ->get();

        // Aggregate total distance per vehicle
        $vehicleSummary = $trips->groupBy('vehicle_id')->map(function ($vehicleTrips) {
            return [
                'vehicle' => $vehicleTrips->first()->vehicle,
                'trip_count' => $vehicleTrips->count(),
                'total_distance' => $vehicleTrips->sum('total_distance'),
            ];
        });

        return [
            'period' => "{$startOfWeek->toDateString()} to {$endOfWeek->toDateString()}",
            'vehicle_summary' => $vehicleSummary,
            'total_trips' => $trips->count(),
            'failed_inspections' => $failedInspections,
        ];
    }
}
