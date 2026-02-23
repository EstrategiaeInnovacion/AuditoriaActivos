<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\Assignment;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $statusCounts = Device::query()
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $totalDevices = $statusCounts->sum();
        $assignedDevices = $statusCounts->get('assigned', 0);
        $availableDevices = $statusCounts->get('available', 0);
        $maintenanceDevices = $statusCounts->get('maintenance', 0);
        $brokenDevices = $statusCounts->get('broken', 0);

        // Warranty expiring soon (next 30 days)
        $warrantyExpiring = Device::whereNotNull('warranty_expiration')
            ->whereBetween('warranty_expiration', [now(), now()->addDays(30)])
            ->get();

        // Overdue loans (assigned > 30 days with no return)
        $overdueLoans = Assignment::with(['device', 'user'])
            ->whereNull('returned_at')
            ->where('assigned_at', '<', now()->subDays(30))
            ->get();

        // Monthly trend (last 6 months)
        $monthlyTrend = Assignment::selectRaw("strftime('%Y-%m', assigned_at) as month, count(*) as total")
            ->where('assigned_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month');

        return view('dashboard', compact(
            'totalDevices',
            'assignedDevices',
            'availableDevices',
            'maintenanceDevices',
            'brokenDevices',
            'warrantyExpiring',
            'overdueLoans',
            'monthlyTrend'
        ));
    }
}
