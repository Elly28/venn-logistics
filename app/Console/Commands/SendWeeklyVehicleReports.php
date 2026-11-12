<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\VehicleTrackingService;
use App\Mail\WeeklyVehicleReportMail;
use Illuminate\Support\Facades\Mail;

class SendWeeklyVehicleReports extends Command
{
    protected $signature = 'vehicles:send-weekly-reports';
    protected $description = 'Compile and email the weekly vehicle usage and inspection report to fleet managers.';

    public function handle(VehicleTrackingService $trackingService): int
    {
        $this->info('Compiling weekly vehicle usage data...');

        $reportData = $trackingService->getWeeklyReportData();

        // Replace with your fleet manager's email or configuration config('mail.fleet_manager')
        $managerEmail = config('mail.fleet_manager_address', 'fleet-manager@example.com');

        Mail::to($managerEmail)->send(new WeeklyVehicleReportMail($reportData));

        $this->info('Weekly vehicle report successfully sent.');

        return Command::SUCCESS;
    }
}
