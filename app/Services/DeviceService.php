<?php

namespace App\Services;

use App\Enums\DeviceStatus;
use App\Events\DeviceCreated;
use App\Events\DeviceStatusChanged;
use App\Models\Credential;
use App\Models\Device;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DeviceService
{
    public function getDevicesPaginated(Request $request): LengthAwarePaginator
    {
        $query = Device::query()
            ->where('status', '!=', DeviceStatus::Broken->value)
            ->search($request->search)
            ->type($request->type)
            ->status($request->status)
            ->when($request->sort, function ($q) use ($request): void {
                $allowed = ['name', 'brand', 'serial_number', 'type', 'status', 'created_at'];
                $col = in_array($request->sort, $allowed) ? $request->sort : 'created_at';
                $dir = $request->direction === 'asc' ? 'asc' : 'desc';

                $q->orderBy($col, $dir);
            }, function ($q): void {
                $q->latest();
            });

        return $query->paginate(10)->withQueryString();
    }

    public function getDeviceStats(Request $request): array
    {
        $query = Device::query()
            ->where('status', '!=', DeviceStatus::Broken->value)
            ->search($request->search)
            ->type($request->type)
            ->status($request->status);

        return [
            'total' => (clone $query)->count(),
            'available' => (clone $query)->where('status', DeviceStatus::Available->value)->count(),
            'assigned' => (clone $query)->where('status', DeviceStatus::Assigned->value)->count(),
            'maintenance' => (clone $query)->where('status', DeviceStatus::Maintenance->value)->count(),
            'broken' => (clone $query)->where('status', DeviceStatus::Broken->value)->count(),
        ];
    }

    public function createDevice(array $data): Device
    {
        $deviceData = collect($data)->only([
            'name', 'brand', 'model', 'serial_number', 'type',
            'status', 'purchase_date', 'warranty_expiration', 'notes',
        ])->toArray();

        $device = Device::create($deviceData);

        $this->handleCredentialCreation($device, $data);

        event(new DeviceCreated($device));

        return $device;
    }

    public function updateDevice(Device $device, array $data): Device
    {
        $oldStatus = $device->status;

        $deviceData = collect($data)->only([
            'name', 'brand', 'model', 'serial_number', 'type',
            'status', 'purchase_date', 'warranty_expiration', 'notes',
        ])->toArray();

        $device->update($deviceData);

        $this->handleCredentialUpdate($device, $data);

        if ($oldStatus !== $device->status) {
            event(new DeviceStatusChanged($device, $oldStatus, $device->status));
        }

        return $device->fresh();
    }

    public function deleteDevice(Device $device): bool
    {
        if ($device->assignments()->whereNull('returned_at')->exists()) {
            return false;
        }

        $this->deleteAllPhotos($device);
        $device->credential()?->delete();
        $device->delete();

        return true;
    }

    public function getBrokenDevices(Request $request): LengthAwarePaginator
    {
        return Device::query()
            ->where('status', DeviceStatus::Broken->value)
            ->search($request->search)
            ->type($request->type)
            ->latest()
            ->paginate(10)
            ->withQueryString();
    }

    public function getDeviceForEdit(Device $device): Device
    {
        $device->load('credential');

        return $device;
    }

    public function getDeviceWithRelations(Device $device): Device
    {
        return $device->load(['credential', 'assignments.user', 'photos', 'documents']);
    }

    public function processPhotosUpload(Device $device, array $files): void
    {
        foreach ($files as $photo) {
            $path = $photo->store('device-photos/'.$device->id, 'private');

            $device->photos()->create([
                'file_path' => $path,
                'uploaded_by' => auth()->id(),
            ]);
        }
    }

    public function deletePhotos(Device $device, array $photoIds): void
    {
        $photosToDelete = $device->photos()->whereIn('id', $photoIds)->get();

        foreach ($photosToDelete as $photo) {
            Storage::disk('private')->delete($photo->file_path);
            $photo->delete();
        }
    }

    public function handleCredentialCreation(Device $device, array $data): ?Credential
    {
        if (empty($data['username']) && empty($data['email'])) {
            return null;
        }

        return $device->credential()->create([
            'username' => $data['username'] ?? null,
            'password' => $data['password'] ?? null,
            'email' => $data['email'] ?? null,
            'email_password' => $data['email_password'] ?? null,
        ]);
    }

    public function handleCredentialUpdate(Device $device, array $data): ?Credential
    {
        if (empty($data['username']) && empty($data['email'])) {
            $device->credential()?->delete();

            return null;
        }

        return $device->credential()->updateOrCreate(
            ['device_id' => $device->id],
            [
                'username' => $data['username'] ?? null,
                'password' => $data['password'] ?? null,
                'email' => $data['email'] ?? null,
                'email_password' => $data['email_password'] ?? null,
            ]
        );
    }

    protected function deleteAllPhotos(Device $device): void
    {
        foreach ($device->photos as $photo) {
            Storage::disk('private')->delete($photo->file_path);
        }
    }

    public function getDevicesForQrPrint(array $ids): Collection
    {
        return Device::whereIn('id', array_slice($ids, 0, 50))->get();
    }
}
