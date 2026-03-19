<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\DeviceDocument;
use App\Services\AuditService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class DeviceDocumentController extends Controller
{
    public function store(Request $request, Device $device)
    {
        Gate::authorize('manageDocuments', $device);

        $request->validate([
            'document' => 'required|file|max:10240|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png',
            'type' => 'required|in:factura,garantia,contrato,manual,otro',
        ]);

        $file = $request->file('document');
        $path = $file->store('device-documents/'.$device->id, 'private');

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
        $document->loadMissing('device');
        abort_unless($document->device, 404);

        Gate::authorize('manageDocuments', $document->device);

        AuditService::documentDownloaded($document->id, $document->original_name);

        return Storage::disk('private')->download(
            $document->file_path,
            $document->original_name
        );
    }

    public function destroy(DeviceDocument $document)
    {
        $document->loadMissing('device');
        abort_unless($document->device, 404);

        Gate::authorize('manageDocuments', $document->device);

        AuditService::documentDeleted($document->id, $document->original_name);

        Storage::disk('private')->delete($document->file_path);
        $document->delete();

        return back()->with('success', 'Documento eliminado.');
    }
}
