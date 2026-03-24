<?php

namespace App\Http\Controllers;

use App\Enums\DeviceStatus;
use App\Http\Requests\StoreDeviceRequest;
use App\Http\Requests\UpdateDeviceRequest;
use App\Models\Device;
use App\Models\DevicePhoto;
use App\Notifications\AlertaEquipoDanado;
use App\Services\AuditService;
use App\Services\DeviceService;
use App\Services\ExportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class DeviceController extends Controller
{
    public function __construct(
        protected DeviceService $deviceService,
        protected ExportService $exportService
    ) {}

    public function index(Request $request)
    {
        $devices = $this->deviceService->getDevicesPaginated($request);
        $stats = $this->deviceService->getDeviceStats($request);

        return view('devices.index', compact('devices', 'stats'));
    }

    public function brokenIndex(Request $request)
    {
        Gate::authorize('viewAny', Device::class);

        $devices = $this->deviceService->getBrokenDevices($request);
        $total = $devices->total();

        return view('devices.broken', compact('devices', 'total'));
    }

    public function create()
    {
        Gate::authorize('create', Device::class);

        return view('devices.create');
    }

    public function store(StoreDeviceRequest $request)
    {
        Gate::authorize('create', Device::class);

        Log::info('DeviceController@store - Intentando crear dispositivo', [
            'data' => $request->except(['password', 'email_password']),
        ]);

        $validated = $request->validated();

        try {
            $hasPhotos = $request->hasFile('photos');
            $photos = $hasPhotos ? $request->file('photos') : [];

            $device = DB::transaction(function () use ($validated, $hasPhotos, $photos): Device {
                $device = $this->deviceService->createDevice($validated);

                if ($hasPhotos && ! empty($photos)) {
                    $this->deviceService->processPhotosUpload($device, $photos);
                }

                return $device;
            });

            Log::info('DeviceController@store - Dispositivo creado exitosamente', [
                'device_id' => $device->id,
                'uuid' => $device->uuid,
            ]);

            return redirect()->route('devices.index')->with('success', 'Dispositivo creado exitosamente.');
        } catch (\Exception $e) {
            Log::error('DeviceController@store - Error al crear dispositivo', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            report($e);

            return redirect()->back()->with('error', 'Error al crear el dispositivo.')->withInput();
        }
    }

    public function show(Device $device)
    {
        $device->load(['credential', 'assignments.user', 'photos', 'documents']);

        return view('devices.show', compact('device'));
    }

    public function edit(Device $device)
    {
        Gate::authorize('update', $device);

        $device = $this->deviceService->getDeviceForEdit($device);

        return view('devices.edit', compact('device'));
    }

    public function update(UpdateDeviceRequest $request, Device $device)
    {
        Gate::authorize('update', $device);

        $validated = $request->validated();
        $oldStatus = $device->status;

        try {
            DB::transaction(function () use ($validated, $request, $device): void {
                $this->deviceService->updateDevice($device, $validated);

                if ($request->filled('delete_photos')) {
                    $this->deviceService->deletePhotos($device, $request->delete_photos);
                }

                if ($request->hasFile('photos')) {
                    $this->deviceService->processPhotosUpload($device, $request->file('photos'));
                }
            });

            if ($oldStatus !== $device->status && in_array($device->status, [DeviceStatus::Broken->value, DeviceStatus::Maintenance->value])) {
                $device->notify(new AlertaEquipoDanado($device, $oldStatus, $device->status));
            }

            return redirect()->route('devices.index')->with('success', 'Dispositivo actualizado exitosamente.');
        } catch (\Exception $e) {
            report($e);

            return redirect()->back()->with('error', 'Error al actualizar el dispositivo.')->withInput();
        }
    }

    public function destroy(Device $device)
    {
        Gate::authorize('delete', $device);

        if (! $this->deviceService->deleteDevice($device)) {
            return redirect()->route('devices.index')
                ->with('error', 'No se puede eliminar un dispositivo con asignaciones activas. Devuélvelo primero.');
        }

        AuditService::deviceDeleted($device->id, $device->name);

        return redirect()->route('devices.index')->with('success', 'Dispositivo eliminado exitosamente.');
    }

    public function showPhoto(DevicePhoto $photo)
    {
        Gate::authorize('viewPhoto', $photo->device);

        try {
            if (! Storage::disk('private')->exists($photo->file_path)) {
                abort(404);
            }

            return Storage::disk('private')->response($photo->file_path);
        } catch (\Exception $e) {
            report($e);
            abort(404);
        }
    }

    public function printQr(Device $device)
    {
        $qrCode = QrCode::size(200)->generate(route('devices.show', $device->uuid));

        return view('devices.print-qr', compact('device', 'qrCode'));
    }

    public function printMultipleQrs(Request $request)
    {
        $ids = $request->input('ids');

        if (! $ids) {
            return redirect()->route('devices.index')->with('error', 'No se seleccionaron dispositivos para imprimir.');
        }

        $idArray = explode(',', $ids);
        $devices = $this->deviceService->getDevicesForQrPrint($idArray);

        if ($devices->isEmpty()) {
            return redirect()->route('devices.index')->with('error', 'Los dispositivos seleccionados no existen.');
        }

        $devicesWithQr = $devices->map(function ($device) {
            $device->qrCode = QrCode::size(200)->generate(route('devices.show', $device->uuid));

            return $device;
        });

        return view('devices.print-multiple-qrs', compact('devicesWithQr'));
    }

    public function exportBrokenExcel(Request $request)
    {
        return $this->exportService->exportBrokenExcel($request);
    }

    public function exportBrokenPdf(Request $request)
    {
        return $this->exportService->downloadBrokenPdf($request);
    }

    public function exportExcel(Request $request)
    {
        $includeCredentials = $request->has('include_credentials');

        if ($includeCredentials) {
            Gate::authorize('exportCredentials', Device::class);
        }

        return $this->exportService->exportExcel($request);
    }

    public function exportPdf(Request $request)
    {
        if ($request->has('include_credentials')) {
            Gate::authorize('exportCredentials', Device::class);
            AuditService::credentialExport(1);
        }

        return $this->exportService->downloadPdf($request);
    }
}
