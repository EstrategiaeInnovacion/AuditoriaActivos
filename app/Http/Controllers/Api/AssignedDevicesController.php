<?php

namespace App\Http\Controllers\Api;

use App\Enums\DeviceStatus;
use App\Enums\DeviceType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\AssignDeviceRequest;
use App\Models\Assignment;
use App\Models\Device;
use App\Models\DevicePhoto;
use App\Models\Employee;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class AssignedDevicesController extends Controller
{
    /**
     * Returns only computer and peripheral device types with their labels.
     */
    public function deviceTypes(): JsonResponse
    {
        $allowed = [DeviceType::Computer, DeviceType::Peripheral];

        $types = collect($allowed)->map(fn (DeviceType $type) => [
            'value' => $type->value,
            'label' => $type->label(),
        ])->values();

        return response()->json([
            'success' => true,
            'data' => $types,
        ]);
    }

    /**
     * Returns all currently active device assignments for the given username.
     *
     * Searches across:
     *   - assignments.assigned_to (free-text name)
     *   - employee.name linked to the assignment
     *   - employee.employee_id linked to the assignment
     */
    public function assignedDevices(Request $request, string $username): JsonResponse
    {
        $username = trim($username);

        if (empty($username)) {
            return response()->json([
                'success' => false,
                'error' => 'El nombre de usuario es requerido.',
            ], 422);
        }

        $assignments = Assignment::query()
            ->with(['device.photos', 'employee'])
            ->whereNull('returned_at')
            ->where(function ($query) use ($username): void {
                $query->where('assigned_to', 'like', "%{$username}%")
                    ->orWhereHas('employee', function ($q) use ($username): void {
                        $q->where('name', 'like', "%{$username}%")
                            ->orWhere('employee_id', $username);
                    });
            })
            ->get();

        if ($assignments->isEmpty()) {
            return response()->json([
                'success' => true,
                'username' => $username,
                'total' => 0,
                'data' => [],
                'message' => 'No se encontraron equipos asignados para este usuario.',
            ]);
        }

        $data = $assignments->map(fn (Assignment $assignment) => [
            'assignment_id' => $assignment->id,
            'assigned_at' => $assignment->assigned_at?->toDateTimeString(),
            'assigned_to' => $assignment->assigned_to,
            'employee' => $assignment->employee ? [
                'id' => $assignment->employee->id,
                'name' => $assignment->employee->name,
                'employee_id' => $assignment->employee->employee_id,
                'department' => $assignment->employee->department,
                'position' => $assignment->employee->position,
            ] : null,
            'notes' => $assignment->notes,
            'device' => [
                'uuid' => $assignment->device->uuid,
                'name' => $assignment->device->name,
                'brand' => $assignment->device->brand,
                'model' => $assignment->device->model,
                'serial_number' => $assignment->device->serial_number,
                'type' => $assignment->device->type,
                'type_label' => DeviceType::tryFrom($assignment->device->type)?->label() ?? $assignment->device->type,
                'status' => $assignment->device->status,
                'photos' => $assignment->device->photos->map(fn (DevicePhoto $photo) => [
                    'id' => $photo->id,
                    'caption' => $photo->caption,
                    'url' => url("/api/v1/device-photos/{$photo->id}"),
                ])->values(),
            ],
        ]);

        return response()->json([
            'success' => true,
            'username' => $username,
            'total' => $data->count(),
            'data' => $data->values(),
        ]);
    }

    /**
     * Returns ALL computer and peripheral devices with their current assignment (if any).
     * Devices with no active assignment are included with assignment = null.
     */
    public function allDevices(): JsonResponse
    {
        $devices = Device::query()
            ->whereIn('type', [DeviceType::Computer->value, DeviceType::Peripheral->value])
            ->where('status', DeviceStatus::Available->value)
            ->with(['photos', 'currentAssignment.employee'])
            ->get();

        $data = $devices->map(fn (Device $device) => [
            'uuid' => $device->uuid,
            'name' => $device->name,
            'brand' => $device->brand,
            'model' => $device->model,
            'serial_number' => $device->serial_number,
            'type' => $device->type,
            'type_label' => DeviceType::tryFrom($device->type)?->label() ?? $device->type,
            'status' => $device->status,
            'photos' => $device->photos->map(fn (DevicePhoto $photo) => [
                'id' => $photo->id,
                'caption' => $photo->caption,
                'url' => url("/api/v1/device-photos/{$photo->id}"),
            ])->values(),
            'assignment' => $device->currentAssignment ? [
                'assignment_id' => $device->currentAssignment->id,
                'assigned_at' => $device->currentAssignment->assigned_at?->toDateTimeString(),
                'assigned_to' => $device->currentAssignment->assigned_to,
                'notes' => $device->currentAssignment->notes,
                'employee' => $device->currentAssignment->employee ? [
                    'id' => $device->currentAssignment->employee->id,
                    'name' => $device->currentAssignment->employee->name,
                    'employee_id' => $device->currentAssignment->employee->employee_id,
                    'department' => $device->currentAssignment->employee->department,
                    'position' => $device->currentAssignment->employee->position,
                ] : null,
            ] : null,
        ]);

        return response()->json([
            'success' => true,
            'total' => $data->count(),
            'data' => $data->values(),
        ]);
    }

    /**
     * Assigns a device to a user from the external ERP system.
     * Looks up the device by UUID. Accepts assigned_to (name), optional employee_id and notes.
     * Returns 409 if the device is already assigned.
     */
    public function assignDevice(AssignDeviceRequest $request, string $uuid): JsonResponse
    {
        $device = Device::whereIn('type', [DeviceType::Computer->value, DeviceType::Peripheral->value])
            ->where('uuid', $uuid)
            ->first();

        if (! $device) {
            return response()->json([
                'success' => false,
                'error' => 'Dispositivo no encontrado.',
            ], 404);
        }

        if ($device->status === DeviceStatus::Assigned->value) {
            return response()->json([
                'success' => false,
                'error' => 'El dispositivo ya tiene una asignación activa.',
                'status' => $device->status,
            ], 409);
        }

        $employee = null;

        if ($request->filled('employee_id')) {
            $employee = Employee::where('employee_id', $request->employee_id)->first();
        }

        $assignment = DB::transaction(function () use ($device, $request, $employee): Assignment {
            $assignment = Assignment::create([
                'device_id' => $device->id,
                'user_id' => null,
                'employee_id' => $employee?->id,
                'assigned_to' => $request->assigned_to,
                'assigned_at' => now(),
                'notes' => $request->notes,
            ]);

            $device->update(['status' => DeviceStatus::Assigned->value]);

            return $assignment;
        });

        return response()->json([
            'success' => true,
            'assigned_to' => $request->assigned_to,
            'status' => DeviceStatus::Assigned->value,
            'assignment_id' => $assignment->id,
            'assigned_at' => $assignment->assigned_at->toDateTimeString(),
            'device' => [
                'uuid' => $device->uuid,
                'name' => $device->name,
                'brand' => $device->brand,
                'model' => $device->model,
                'serial_number' => $device->serial_number,
                'type' => $device->type,
                'type_label' => DeviceType::tryFrom($device->type)?->label() ?? $device->type,
            ],
            'employee' => $employee ? [
                'id' => $employee->id,
                'name' => $employee->name,
                'employee_id' => $employee->employee_id,
                'department' => $employee->department,
                'position' => $employee->position,
            ] : null,
        ], 201);
    }

    /**
     * Serves a device photo file. Protected by the same API key middleware.
     */
    public function showPhoto(DevicePhoto $photo): Response
    {
        abort_unless(Storage::disk('private')->exists($photo->file_path), 404);

        return Storage::disk('private')->response($photo->file_path);
    }
}
