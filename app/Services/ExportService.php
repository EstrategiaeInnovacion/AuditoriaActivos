<?php

namespace App\Services;

use App\Exports\DeviceExport;
use App\Jobs\GenerateDeviceExport;
use App\Models\Device;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportService
{
    public function exportExcel(Request $request): StreamedResponse
    {
        return Excel::download(
            new DeviceExport(
                $request->input('search'),
                $request->input('type'),
                $request->input('status'),
                $request->has('include_credentials')
            ),
            'inventario-activos-'.now()->format('Y-m-d').'.xlsx'
        );
    }

    public function exportPdf(Request $request): \Barryvdh\DomPDF\PDF
    {
        $query = $this->buildDeviceQuery($request);

        if ($request->has('include_credentials')) {
            $query->with('credential');
        }

        $devices = $query->take(1000)->get();
        $includeCredentials = $request->has('include_credentials');

        return Pdf::loadView('exports.devices-pdf', compact('devices', 'includeCredentials'))
            ->setPaper('letter', 'landscape');
    }

    public function downloadPdf(Request $request): BinaryFileResponse
    {
        $pdf = $this->exportPdf($request);

        return $pdf->download('inventario-activos-'.now()->format('Y-m-d').'.pdf');
    }

    public function queueExport(Request $request, int $userId): void
    {
        GenerateDeviceExport::dispatch(
            $userId,
            $request->input('format', 'excel'),
            [
                'search' => $request->input('search'),
                'type' => $request->input('type'),
                'status' => $request->input('status'),
                'include_credentials' => $request->has('include_credentials'),
            ]
        );
    }

    public function buildDeviceQuery(Request $request): Builder
    {
        return Device::query()
            ->search($request->input('search'))
            ->type($request->input('type'))
            ->status($request->input('status'));
    }

    public function getExportFilename(string $format): string
    {
        return 'inventario-activos-'.now()->format('Y-m-d').'.'.$format;
    }

    public function storeExportFile(string $content, string $filename): bool
    {
        return Storage::disk('exports')->put($filename, $content) !== false;
    }
}
