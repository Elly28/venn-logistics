<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Trip;
use App\Models\VehicleInspection;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FleetManagementController extends Controller
{
    public function index(Request $request): View
    {
        $query = Trip::with(['vehicle', 'driver.user']);

        // Filter by date range
        if ($request->filled(['start_date', 'end_date'])) {
            $query->whereBetween('trip_date', [$request->start_date, $request->end_date]);
        }

        // Filter by vehicle
        if ($request->filled('vehicle_id')) {
            $query->where('vehicle_id', $request->vehicle_id);
        }

        // Filter by driver
        if ($request->filled('driver_id')) {
            $query->where('driver_id', $request->driver_id);
        }

        $trips = $query->latest('trip_date')->paginate(15);

        // Immediate inspection failure alerts for admin dashboard
        $alertInspections = VehicleInspection::with(['vehicle', 'driver.user'])
            ->whereIn('status', ['fail', 'requires_attention'])
            ->latest('inspection_date')
            ->take(10)
            ->get();

        return view('admin.fleet.index', compact('trips', 'alertInspections'));
    }
}
