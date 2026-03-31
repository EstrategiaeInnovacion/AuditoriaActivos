<?php

namespace App\Providers;

use App\Events\DeviceAssigned;
use App\Events\DeviceCreated;
use App\Events\DeviceStatusChanged;
use App\Listeners\InvalidateDashboardCache;
use App\Services\DashboardCacheService;
use App\Services\DashboardStatsService;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(DashboardCacheService::class);
        $this->app->singleton(DashboardStatsService::class);
    }

    public function boot(): void
    {
        Event::listen(
            [DeviceCreated::class, DeviceStatusChanged::class, DeviceAssigned::class],
            InvalidateDashboardCache::class
        );
    }
}
