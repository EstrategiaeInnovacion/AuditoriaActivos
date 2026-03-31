<?php

namespace Tests\Unit\Services;

use App\Models\Device;
use App\Services\DashboardCacheService;
use App\Services\DashboardStatsService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class DashboardStatsServiceTest extends TestCase
{
    use RefreshDatabase;

    protected DashboardStatsService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new DashboardStatsService(new DashboardCacheService);
    }

    public function test_get_stats_returns_correct_counts(): void
    {
        Device::factory()->create(['status' => 'available']);
        Device::factory()->create(['status' => 'available']);
        Device::factory()->create(['status' => 'assigned']);
        Device::factory()->create(['status' => 'maintenance']);
        Device::factory()->create(['status' => 'broken']);

        Cache::flush();

        $stats = $this->service->getStats();

        $this->assertEquals(5, $stats['totalDevices']);
        $this->assertEquals(2, $stats['availableDevices']);
        $this->assertEquals(1, $stats['assignedDevices']);
        $this->assertEquals(1, $stats['maintenanceDevices']);
        $this->assertEquals(1, $stats['brokenDevices']);
    }

    public function test_get_stats_handles_database_error_gracefully(): void
    {
        Cache::flush();

        DB::shouldReceive('getDriverName')->andReturn('sqlite');
        DB::shouldReceive('table')->andThrow(new \Exception('Database error'));

        $stats = $this->service->getStats();

        $this->assertEquals(0, $stats['totalDevices']);
        $this->assertEquals(0, $stats['assignedDevices']);
        $this->assertEquals(0, $stats['availableDevices']);
    }

    public function test_cache_is_used_for_subsequent_calls(): void
    {
        Device::factory()->create(['status' => 'available']);

        Cache::flush();

        $firstCall = $this->service->getStats();
        $firstCallTimestamp = now();

        $secondCall = $this->service->getStats();

        $this->assertEquals($firstCall, $secondCall);

        $cachedValue = Cache::get(DashboardCacheService::STATS_CACHE_KEY);
        $this->assertNotNull($cachedValue);
    }
}
