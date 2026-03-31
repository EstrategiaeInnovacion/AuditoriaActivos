<?php

namespace App\Services;

use App\Models\Assignment;
use App\Models\Device;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class DashboardStatsService
{
    public function __construct(
        protected DashboardCacheService $cacheService
    ) {}

    public function getStats(): array
    {
        return Cache::remember(
            DashboardCacheService::STATS_CACHE_KEY,
            DashboardCacheService::CACHE_TTL,
            function (): array {
                try {
                    return $this->calculateStats();
                } catch (\Throwable $e) {
                    Log::error('Failed to calculate dashboard stats', [
                        'error' => $e->getMessage(),
                    ]);

                    return $this->getDefaultStats();
                }
            }
        );
    }

    public function getMonthlyTrend(): array
    {
        return Cache::remember(
            DashboardCacheService::MONTHLY_TREND_CACHE_KEY,
            DashboardCacheService::CACHE_TTL,
            function (): array {
                try {
                    return $this->calculateMonthlyTrend();
                } catch (\Throwable $e) {
                    Log::error('Failed to calculate monthly trend', [
                        'error' => $e->getMessage(),
                    ]);

                    return [];
                }
            }
        );
    }

    public function getWarrantyExpiring(): Collection
    {
        return Device::warrantyExpiring(30)->with(['currentAssignment.user'])->get();
    }

    public function getOverdueLoans(): Collection
    {
        return Assignment::overdue()
            ->with(['device', 'user'])
            ->get();
    }

    protected function calculateStats(): array
    {
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
            'warrantyExpiringCount' => Device::warrantyExpiring(30)->count(),
            'overdueLoansCount' => Assignment::overdue()->count(),
        ];
    }

    protected function calculateMonthlyTrend(): array
    {
        return Assignment::query()
            ->selectRaw("DATE_FORMAT(assigned_at, '%Y-%m') as month, count(*) as total")
            ->where('assigned_at', '>=', now()->subMonths(6))
            ->groupByRaw("DATE_FORMAT(assigned_at, '%Y-%m')")
            ->orderByRaw("DATE_FORMAT(assigned_at, '%Y-%m')")
            ->pluck('total', 'month')
            ->toArray();
    }

    protected function getDefaultStats(): array
    {
        return [
            'totalDevices' => 0,
            'assignedDevices' => 0,
            'availableDevices' => 0,
            'maintenanceDevices' => 0,
            'brokenDevices' => 0,
            'warrantyExpiringCount' => 0,
            'overdueLoansCount' => 0,
        ];
    }
}
