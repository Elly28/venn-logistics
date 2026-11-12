<x-mail::message>
    # Weekly Fleet Usage Report
    **Period:** {{ $reportData['period'] }}

    Total Trips Logged: **{{ $reportData['total_trips'] }}**

    ### Vehicle Distance Summary
    <x-mail::table>
        | Vehicle | Trips | Total Distance (km) |
        | :--- | :---: | :---: |
        @foreach($reportData['vehicle_summary'] as $summary)
            | {{ $summary['vehicle']->make }} {{ $summary['vehicle']->model }} ({{ $summary['vehicle']->registration_number }}) | {{ $summary['trip_count'] }} | {{ $summary['total_distance'] }} |
        @endforeach
    </x-mail::table>

    @if($reportData['failed_inspections']->count() > 0)
        ### ⚠️ Inspection Alerts / Failures Last Week
        @foreach($reportData['failed_inspections'] as $inspection)
            - **Vehicle:** {{ $inspection->vehicle->registration_number }} | **Status:** {{ ucfirst($inspection->status) }} | **Notes:** {{ $inspection->notes ?? 'None' }}
        @endforeach
    @endif

    Thanks,<br>
    {{ config('app.name') }} Fleet Management
</x-mail::message>
