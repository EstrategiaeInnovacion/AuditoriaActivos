<?php

namespace App\Http\Controllers;

use App\Models\Device;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class DeviceController extends Controller
{
    public function index(Request $request)
    {
        $devices = Device::query()
            ->when($request->search, fn($q, $s) => $q->where(function ($q) use ($s) {
            $q->where('name', 'like', "%{$s}%")
                ->orWhere('serial_number', 'like', "%{$s}%")
                ->orWhere('brand', 'like', "%{$s}%");
        }
        ))
            ->when($request->type, fn($q, $t) => $q->where('type', $t))
            ->when($request->status, fn($q, $s) => $q->where('status', $s))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('devices.index', compact('devices'));
    }

    public function create()
    {
        return view('devices.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'brand' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'serial_number' => 'required|string|unique:devices',
            'type' => 'required|in:computer,peripheral,printer,other',
            'status' => 'required|in:available,assigned,maintenance,broken',
            'purchase_date' => 'nullable|date',
            'warranty_expiration' => 'nullable|date',
            'notes' => 'nullable|string',
            'username' => 'nullable|string|max:255',
            'password' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'email_password' => 'nullable|string|max:255',
            'photos.*' => 'nullable|image|max:5120',
        ]);

        $device = Device::create($validated);

        if ($request->filled('username') || $request->filled('email')) {
            $device->credential()->create([
                'username' => $request->username,
                'password' => $request->password,
                'email' => $request->email,
                'email_password' => $request->email_password,
            ]);
        }

        // Upload photos
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('device-photos/' . $device->id, 'private');
                $device->photos()->create([
                    'file_path' => $path,
                    'uploaded_by' => auth()->id(),
                ]);
            }
        }

        return redirect()->route('devices.index')->with('success', 'Dispositivo creado exitosamente.');
    }

    public function show(Device $device)
    {
        $device->load(['credential', 'assignments.user', 'photos', 'documents']);
        return view('devices.show', compact('device'));
    }

    public function edit(Device $device)
    {
        $device->load('credential');
        return view('devices.edit', compact('device'));
    }

    public function update(Request $request, Device $device)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'brand' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'serial_number' => 'required|string|unique:devices,serial_number,' . $device->id,
            'type' => 'required|in:computer,peripheral,printer,other',
            'status' => 'required|in:available,assigned,maintenance,broken',
            'purchase_date' => 'nullable|date',
            'warranty_expiration' => 'nullable|date',
            'notes' => 'nullable|string',
            'username' => 'nullable|string|max:255',
            'password' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'email_password' => 'nullable|string|max:255',
            'photos.*' => 'nullable|image|max:5120',
            'delete_photos' => 'nullable|array',
        ]);

        $device->update($validated);

        if ($request->filled('username') || $request->filled('email')) {
            $device->credential()->updateOrCreate(
            ['device_id' => $device->id],
            [
                'username' => $request->username,
                'password' => $request->password,
                'email' => $request->email,
                'email_password' => $request->email_password,
            ]
            );
        }
        elseif ($device->credential) {
            $device->credential()->delete();
        }

        // Delete selected photos
        if ($request->filled('delete_photos')) {
            $photosToDelete = $device->photos()->whereIn('id', $request->delete_photos)->get();
            foreach ($photosToDelete as $photo) {
                Storage::disk('private')->delete($photo->file_path);
                $photo->delete();
            }
        }

        // Upload new photos
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('device-photos/' . $device->id, 'private');
                $device->photos()->create([
                    'file_path' => $path,
                    'uploaded_by' => auth()->id(),
                ]);
            }
        }

        return redirect()->route('devices.index')->with('success', 'Dispositivo actualizado exitosamente.');
    }

    public function destroy(Device $device)
    {
        $device->delete();
        return redirect()->route('devices.index')->with('success', 'Device deleted successfully.');
    }

    public function showPhoto(\App\Models\DevicePhoto $photo)
    {
        if (!Storage::disk('private')->exists($photo->file_path)) {
            abort(404);
        }
        return Storage::disk('private')->response($photo->file_path);
    }

    public function printQr(Device $device)
    {
        $qrCode = QrCode::size(200)->generate(route('devices.show', $device->uuid));
        return view('devices.print-qr', compact('device', 'qrCode'));
    }

    public function printMultipleQrs(Request $request)
    {
        $ids = $request->input('ids');
        if (!$ids) {
            return redirect()->route('devices.index')->with('error', 'No se seleccionaron dispositivos para imprimir.');
        }

        $idArray = explode(',', $ids);
        $devices = Device::whereIn('id', $idArray)->get();

        if ($devices->isEmpty()) {
            return redirect()->route('devices.index')->with('error', 'Los dispositivos seleccionados no existen.');
        }

        // Generate QRs for each device
        $devicesWithQr = $devices->map(function ($device) {
            $device->qrCode = QrCode::size(200)->generate(route('devices.show', $device->uuid));
            return $device;
        });

        return view('devices.print-multiple-qrs', compact('devicesWithQr'));
    }

    public function exportExcel(Request $request)
    {
        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\DeviceExport($request->search, $request->type, $request->status),
            'inventario-activos-' . now()->format('Y-m-d') . '.xlsx'
        );
    }

    public function exportPdf(Request $request)
    {
        $query = Device::query();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                    ->orWhere('serial_number', 'like', "%{$request->search}%")
                    ->orWhere('brand', 'like', "%{$request->search}%");
            });
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $devices = $query->get();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('exports.devices-pdf', compact('devices'))
            ->setPaper('letter', 'landscape');

        return $pdf->download('inventario-activos-' . now()->format('Y-m-d') . '.pdf');
    }
}
