<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class DashboardCacheService
{
    public const STATS_CACHE_KEY = 'dashboard_stats';

    public const MONTHLY_TREND_CACHE_KEY = 'dashboard_monthly_trend';

    public const CACHE_TTL = 300;

    public function invalidateAll(): void
    {
        Cache::forget(self::STATS_CACHE_KEY);
        Cache::forget(self::MONTHLY_TREND_CACHE_KEY);
    }

    public function invalidateStats(): void
    {
        Cache::forget(self::STATS_CACHE_KEY);
    }

    public function invalidateMonthlyTrend(): void
    {
        Cache::forget(self::MONTHLY_TREND_CACHE_KEY);
    }
}
