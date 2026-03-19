<?php

namespace App\Events;

use App\Models\Assignment;
use App\Models\Device;
use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DeviceAssigned
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public Device $device,
        public Assignment $assignment
    ) {}

    public function getAssignedUser(): User
    {
        return $this->assignment->user;
    }
}
