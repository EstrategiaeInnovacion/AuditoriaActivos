<?php

namespace App\Http\Controllers;

use App\Services\DashboardCacheService;
use App\Services\DashboardStatsService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        try {
            $statsService = app(DashboardStatsService::class);
            $cacheService = app(DashboardCacheService::class);

            $stats = $statsService->getStats();
            $monthlyTrend = $statsService->getMonthlyTrend();
            $warrantyExpiring = $statsService->getWarrantyExpiring();
            $overdueLoans = $statsService->getOverdueLoans();

            return view('dashboard', [
                'totalDevices' => $stats['totalDevices'],
                'assignedDevices' => $stats['assignedDevices'],
                'availableDevices' => $stats['availableDevices'],
                'maintenanceDevices' => $stats['maintenanceDevices'],
                'brokenDevices' => $stats['brokenDevices'],
                'warrantyExpiring' => $warrantyExpiring,
                'overdueLoans' => $overdueLoans,
                'monthlyTrend' => $monthlyTrend,
            ]);
        } catch (\Throwable $e) {
            report($e);

            $cacheService->invalidateAll();

            return view('dashboard', [
                'totalDevices' => 0,
                'assignedDevices' => 0,
                'availableDevices' => 0,
                'maintenanceDevices' => 0,
                'brokenDevices' => 0,
                'warrantyExpiring' => collect(),
                'overdueLoans' => collect(),
                'monthlyTrend' => [],
            ]);
        }
    }
}
