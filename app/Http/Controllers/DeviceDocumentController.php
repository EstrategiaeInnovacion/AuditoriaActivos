<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\DeviceDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DeviceDocumentController extends Controller
{
    public function store(Request $request, Device $device)
    {
        $request->validate([
            'document' => 'required|file|max:10240', // 10MB max
            'type' => 'required|in:factura,garantia,contrato,manual,otro',
        ]);

        $file = $request->file('document');
        $path = $file->store('device-documents/' . $device->id, 'private');

        $device->documents()->create([
            'file_path' => $path,
            'original_name' => $file->getClientOriginalName(),
            'type' => $request->type,
            'uploaded_by' => auth()->id(),
        ]);

        return back()->with('success', 'Documento subido correctamente.');
    }

    public function download(DeviceDocument $document)
    {
        return Storage::disk('private')->download(
            $document->file_path,
            $document->original_name
        );
    }

    public function destroy(DeviceDocument $document)
    {
        Storage::disk('private')->delete($document->file_path);
        $document->delete();

        return back()->with('success', 'Documento eliminado.');
    }
}
