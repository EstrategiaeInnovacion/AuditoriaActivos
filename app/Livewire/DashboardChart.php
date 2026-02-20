<?php

namespace App\Livewire;

use App\Models\Device;
use Livewire\Component;

class DashboardChart extends Component
{
    public function render()
    {
        $data = [
            'available' => Device::where('status', 'available')->count(),
            'assigned' => Device::where('status', 'assigned')->count(),
            'maintenance' => Device::where('status', 'maintenance')->count(),
            'broken' => Device::where('status', 'broken')->count(),
        ];

        return view('livewire.dashboard-chart', [
            'data' => $data
        ]);
    }
}
