<?php

namespace App\Livewire;

use App\Models\Device;
use Livewire\Component;

class DashboardChart extends Component
{
    public function render()
    {
        $statusCounts = Device::query()
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $data = [
            'available' => $statusCounts->get('available', 0),
            'assigned' => $statusCounts->get('assigned', 0),
            'maintenance' => $statusCounts->get('maintenance', 0),
            'broken' => $statusCounts->get('broken', 0),
        ];

        return view('livewire.dashboard-chart', [
            'data' => $data
        ]);
    }
}
