<?php

namespace App\Events;

use App\Models\Device;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DeviceStatusChanged
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public Device $device,
        public string $oldStatus,
        public string $newStatus
    ) {}

    public function isCriticalStatus(): bool
    {
        return in_array($this->newStatus, ['broken', 'maintenance']);
    }
}
