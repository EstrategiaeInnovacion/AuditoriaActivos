<?php

namespace App\Livewire;

use App\Models\Assignment;
use Livewire\Component;

class RecentActivity extends Component
{
    public function render()
    {
        return view('livewire.recent-activity', [
            'assignments' => Assignment::with(['device', 'user'])
            ->latest('assigned_at')
            ->take(5)
            ->get()
        ]);
    }
}
