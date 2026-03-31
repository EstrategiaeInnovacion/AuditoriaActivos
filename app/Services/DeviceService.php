<?php

namespace App\Services;

use App\Enums\DeviceStatus;
use App\Events\DeviceCreated;
use App\Events\DeviceStatusChanged;
use App\Models\Credential;
use App\Models\Device;
use App\Models\DeviceDocument;
use App\Models\DevicePhoto;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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
            'broken' => Device::query()->where('status', DeviceStatus::Broken->value)->count(),
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
        return DB::transaction(function () use ($device): bool {
            if ($device->assignments()->whereNull('returned_at')->exists()) {
                Log::warning('Attempted to delete device with active assignments', [
                    'device_id' => $device->id,
                    'device_name' => $device->name,
                ]);
                throw new \RuntimeException('No se puede eliminar un dispositivo con asignaciones activas.');
            }

            $this->deleteAllPhotos($device);
            $this->deleteAllDocuments($device);
            $device->credential()?->delete();

            Log::info('Device deleted', [
                'device_id' => $device->id,
                'device_name' => $device->name,
                'deleted_by' => auth()->id(),
            ]);

            $device->delete();

            return true;
        });
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
        $maxSize = 20 * 1024 * 1024;
        $allowedMimes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];

        foreach ($files as $photo) {
            $this->validatePhotoUpload($photo, $maxSize, $allowedMimes);

            $path = $photo->store('device-photos/'.$device->id, 'private');

            $device->photos()->create([
                'file_path' => $path,
                'uploaded_by' => auth()->id(),
            ]);

            Log::info('Photo uploaded for device', [
                'device_id' => $device->id,
                'file_path' => $path,
                'uploaded_by' => auth()->id(),
            ]);
        }
    }

    public function validatePhotoUpload($file, int $maxSize, array $allowedMimes): void
    {
        if ($file->getSize() > $maxSize) {
            throw new \InvalidArgumentException('El archivo excede el tamaño máximo permitido de 20MB.');
        }

        if (! in_array($file->getMimeType(), $allowedMimes)) {
            throw new \InvalidArgumentException('Tipo de archivo no permitido. Solo se aceptan: JPEG, PNG, GIF, WebP.');
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
        DevicePhoto::where('device_id', $device->id)->delete();
    }

    protected function deleteAllDocuments(Device $device): void
    {
        foreach ($device->documents as $document) {
            Storage::disk('private')->delete($document->file_path);
        }
        DeviceDocument::where('device_id', $device->id)->delete();
    }

    public function getDevicesForQrPrint(array $ids): Collection
    {
        return Device::whereIn('id', array_slice($ids, 0, 50))->get();
    }
}
