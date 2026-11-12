<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DriverPortalController;

// Redirect root to driver dashboard (optional, for quick testing)
Route::get('/', function () {
    return redirect()->route('driver.dashboard');
});

// Alias 'dashboard' to your driver dashboard route
Route::get('/dashboard', function () {
    return redirect()->route('driver.dashboard');
})->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/driver/dashboard', [DriverPortalController::class, 'index'])->name('driver.dashboard');
    Route::post('/driver/trips', [DriverPortalController::class, 'storeTrip'])->name('driver.trips.store');
    Route::post('/driver/inspections', [DriverPortalController::class, 'storeInspection'])->name('driver.inspections.store');
});

require __DIR__.'/auth.php';
