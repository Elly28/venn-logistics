<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTripRequest;
use App\Http\Requests\StoreInspectionRequest;
use App\Services\VehicleTrackingService;
use App\Models\Vehicle;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class DriverPortalController extends Controller
{
    public function __construct(protected VehicleTrackingService $trackingService) {}

    public function index(): View
    {
        $vehicles = Vehicle::where('status', 'active')->get();

        // Ensure the authenticated user has a driver profile.
        // If not, create a default one automatically for smooth testing.
        $driver = auth()->user()->driver()->firstOrCreate(
            ['user_id' => auth()->id()],
            [
                'license_number' => 'PENDING-' . auth()->id(),
                'phone' => '000-000-0000',
            ]
        );

        // Fetch recent trips for this driver
        $recentTrips = $driver->trips()->with('vehicle')->latest('trip_date')->take(5)->get();

        // Checklist items array
        $checklistItems = [
            'Tires & Tread Depth',
            'Brakes & Handbrake',
            'Engine Oil & Coolant Levels',
            'Headlights, Indicators & Brake Lights',
            'Windscreen Wipers & Washer Fluid',
            'Mirrors & Seatbelts',
        ];

        return view('driver.dashboard', compact('vehicles', 'recentTrips', 'checklistItems'));
    }

    public function storeTrip(StoreTripRequest $request): RedirectResponse
    {
        $driver = auth()->user()->driver;

        if (!$driver) {
            return back()->withErrors(['error' => 'No driver profile found for this user account.']);
        }

        $this->trackingService->logTrip($request->validated(), $driver->id);

        return redirect()->route('driver.dashboard')->with('success', 'Trip logged successfully.');
    }

    public function storeInspection(StoreInspectionRequest $request): RedirectResponse
    {
        $driver = auth()->user()->driver;

        if (!$driver) {
            return back()->withErrors(['error' => 'No driver profile found for this user account.']);
        }

        $this->trackingService->logInspection($request->validated(), $driver->id);

        return redirect()->route('driver.dashboard')->with('success', 'Pre-trip inspection submitted.');
    }
}
