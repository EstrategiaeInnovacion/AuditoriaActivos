<?php

namespace App\Listeners;

use App\Services\DashboardCacheService;

class InvalidateDashboardCache
{
    public function __construct(
        protected DashboardCacheService $cacheService
    ) {}

    public function handle(): void
    {
        $this->cacheService->invalidateAll();
    }
}
