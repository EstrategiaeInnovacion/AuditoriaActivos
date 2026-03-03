<?php

namespace App\Policies;

use App\Models\Device;
use App\Models\User;

class DevicePolicy
{
    /**
     * Any authenticated user can view the device list.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Any authenticated user can view a device.
     */
    public function view(User $user, Device $device): bool
    {
        return true;
    }

    /**
     * Any authenticated user can create devices.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Any authenticated user can update devices.
     */
    public function update(User $user, Device $device): bool
    {
        return true;
    }

    /**
     * Only admins can delete devices.
     */
    public function delete(User $user, Device $device): bool
    {
        return $user->is_admin;
    }
}
