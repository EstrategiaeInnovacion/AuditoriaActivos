<?php

namespace App\Http\Controllers;

use App\Models\Device;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $totalDevices = Device::count();
        $assignedDevices = Device::where('status', 'assigned')->count();
        $availableDevices = Device::where('status', 'available')->count();
        $maintenanceDevices = Device::where('status', 'maintenance')->count();
        $brokenDevices = Device::where('status', 'broken')->count();

        return view('dashboard', compact(
            'totalDevices',
            'assignedDevices',
            'availableDevices',
            'maintenanceDevices',
            'brokenDevices'
        ));
    }
}
