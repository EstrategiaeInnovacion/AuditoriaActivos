<?php

namespace App\Services;

use App\Events\DeviceAssigned;
use App\Models\Assignment;
use App\Models\Device;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class AssignmentService
{
    public function assignDevice(Device $device, User $user, array $data = []): Assignment
    {
        return DB::transaction(function () use ($device, $user, $data) {
            $assignment = Assignment::create([
                'device_id' => $device->id,
                'user_id' => $user->id,
                'assigned_to' => $data['assigned_to'] ?? $user->name,
                'assigned_at' => $data['assigned_at'] ?? now(),
                'notes' => $data['notes'] ?? null,
            ]);

            $device->update(['status' => 'assigned']);

            event(new DeviceAssigned($device, $assignment));

            return $assignment;
        });
    }

    public function returnDevice(Device $device, array $data = []): ?Assignment
    {
        return DB::transaction(function () use ($device, $data) {
            $assignment = $device->currentAssignment;

            if (! $assignment) {
                return null;
            }

            $assignment->update([
                'returned_at' => $data['returned_at'] ?? now(),
                'notes' => $data['notes'] ?? $assignment->notes,
            ]);

            $device->update(['status' => 'available']);

            return $assignment;
        });
    }

    public function getActiveAssignments(Device $device): Collection
    {
        return $device->assignments()
            ->whereNull('returned_at')
            ->with('user')
            ->get();
    }

    public function getAssignmentHistory(Device $device): Collection
    {
        return $device->assignments()
            ->with('user')
            ->orderByDesc('assigned_at')
            ->get();
    }

    public function hasActiveAssignment(Device $device): bool
    {
        return $device->assignments()->whereNull('returned_at')->exists();
    }

    public function getCurrentAssignee(Device $device): ?User
    {
        $assignment = $device->currentAssignment;

        return $assignment?->user;
    }

    public function getUserAssignments(User $user): Collection
    {
        return $user->assignments()
            ->with('device')
            ->whereNull('returned_at')
            ->get();
    }

    public function getUserAssignmentHistory(User $user): Collection
    {
        return $user->assignments()
            ->with('device')
            ->orderByDesc('assigned_at')
            ->get();
    }

    public function bulkAssign(Device $device, array $userIds, array $data = []): Collection
    {
        return DB::transaction(function () use ($device, $userIds, $data) {
            $assignments = collect();

            foreach ($userIds as $userId) {
                $user = User::find($userId);
                if ($user) {
                    $assignments->push($this->assignDevice($device, $user, $data));
                }
            }

            return $assignments;
        });
    }
}
