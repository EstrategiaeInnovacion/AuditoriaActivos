<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Device;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $stats = Cache::remember('dashboard_stats', 300, function () {
            $statusCounts = Device::query()
                ->selectRaw('status, count(*) as total')
                ->groupBy('status')
                ->pluck('total', 'status');

            return [
                'totalDevices' => $statusCounts->sum(),
                'assignedDevices' => $statusCounts->get('assigned', 0),
                'availableDevices' => $statusCounts->get('available', 0),
                'maintenanceDevices' => $statusCounts->get('maintenance', 0),
                'brokenDevices' => $statusCounts->get('broken', 0),
                'warrantyExpiring' => Device::whereNotNull('warranty_expiration')
                    ->whereBetween('warranty_expiration', [now(), now()->addDays(30)])
                    ->count(),
                'overdueLoans' => Assignment::with(['device', 'user'])
                    ->whereNull('returned_at')
                    ->where('assigned_at', '<', now()->subDays(30))
                    ->count(),
            ];
        });

        $monthlyTrend = Cache::remember('dashboard_monthly_trend', 300, function () {
            return Assignment::query()
                ->selectRaw("strftime('%Y-%m', assigned_at) as month, count(*) as total")
                ->where('assigned_at', '>=', now()->subMonths(6))
                ->groupByRaw("strftime('%Y-%m', assigned_at)")
                ->orderByRaw("strftime('%Y-%m', assigned_at)")
                ->pluck('total', 'month');
        });

        return view('dashboard', [
            'totalDevices' => $stats['totalDevices'],
            'assignedDevices' => $stats['assignedDevices'],
            'availableDevices' => $stats['availableDevices'],
            'maintenanceDevices' => $stats['maintenanceDevices'],
            'brokenDevices' => $stats['brokenDevices'],
            'warrantyExpiring' => Device::whereNotNull('warranty_expiration')
                ->whereBetween('warranty_expiration', [now(), now()->addDays(30)])
                ->get(),
            'overdueLoans' => Assignment::with(['device', 'user'])
                ->whereNull('returned_at')
                ->where('assigned_at', '<', now()->subDays(30))
                ->get(),
            'monthlyTrend' => $monthlyTrend,
        ]);
    }
}
