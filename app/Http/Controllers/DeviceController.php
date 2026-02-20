<?php

namespace App\Http\Controllers;

use App\Models\Device;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class DeviceController extends Controller
{
    public function index()
    {
        $devices = Device::latest()->paginate(10);
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
            // Credential validation
            'username' => 'nullable|string|max:255',
            'password' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'email_password' => 'nullable|string|max:255',
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

        return redirect()->route('devices.index')->with('success', 'Device created successfully.');
    }

    public function show(Device $device)
    {
        $device->load(['credential', 'assignments.user']);
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
            // Credential validation
            'username' => 'nullable|string|max:255',
            'password' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'email_password' => 'nullable|string|max:255',
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

        return redirect()->route('devices.index')->with('success', 'Device updated successfully.');
    }

    public function destroy(Device $device)
    {
        $device->delete();
        return redirect()->route('devices.index')->with('success', 'Device deleted successfully.');
    }

    public function printQr(Device $device)
    {
        $qrCode = QrCode::size(200)->generate(route('devices.show', $device->uuid));
        return view('devices.print-qr', compact('device', 'qrCode'));
    }
}
