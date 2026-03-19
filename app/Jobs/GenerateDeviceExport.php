<?php

namespace App\Jobs;

use App\Exports\DeviceExport;
use App\Models\Device;
use App\Models\User;
use App\Notifications\ExportReadyNotification;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class GenerateDeviceExport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public int $timeout = 120;

    public function __construct(
        public int $userId,
        public string $format,
        public array $filters
    ) {}

    public function handle(): void
    {
        $user = User::find($this->userId);

        if (! $user) {
            return;
        }

        $filename = 'export_'.$this->userId.'_'.now()->format('Y-m-d_His').'.'.$this->format;
        $disk = Storage::disk('exports');

        if ($this->format === 'excel') {
            $this->generateExcelExport($user, $filename, $disk);
        } elseif ($this->format === 'pdf') {
            $this->generatePdfExport($user, $filename, $disk);
        }
    }

    protected function generateExcelExport(User $user, string $filename, $disk): void
    {
        $search = $this->filters['search'] ?? null;
        $type = $this->filters['type'] ?? null;
        $status = $this->filters['status'] ?? null;
        $includeCredentials = $this->filters['include_credentials'] ?? false;

        $export = new DeviceExport($search, $type, $status, $includeCredentials);

        $tempPath = storage_path('app/temp/'.$filename);
        Excel::store($export, 'temp/'.$filename, 'local');

        $disk->put($filename, file_get_contents($tempPath));

        if (file_exists($tempPath)) {
            unlink($tempPath);
        }

        $user->notify(new ExportReadyNotification(
            route('exports.download', ['filename' => $filename]),
            'Excel',
            $filename
        ));
    }

    protected function generatePdfExport(User $user, string $filename, $disk): void
    {
        $search = $this->filters['search'] ?? null;
        $type = $this->filters['type'] ?? null;
        $status = $this->filters['status'] ?? null;
        $includeCredentials = $this->filters['include_credentials'] ?? false;

        $query = Device::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('serial_number', 'like', "%{$search}%")
                    ->orWhere('brand', 'like', "%{$search}%");
            });
        }

        if ($type) {
            $query->where('type', $type);
        }

        if ($status) {
            $query->where('status', $status);
        }

        if ($includeCredentials) {
            $query->with('credential');
        }

        $devices = $query->take(1000)->get();

        $pdf = Pdf::loadView('exports.devices-pdf', [
            'devices' => $devices,
            'includeCredentials' => $includeCredentials,
        ])->setPaper('letter', 'landscape');

        $disk->put($filename, $pdf->output());

        $user->notify(new ExportReadyNotification(
            route('exports.download', ['filename' => $filename]),
            'PDF',
            $filename
        ));
    }

    public function failed(\Throwable $exception): void
    {
        $user = User::find($this->userId);

        if ($user) {
            $user->notify(new ExportReadyNotification(
                null,
                strtoupper($this->format),
                '',
                true,
                'La generación del reporte falló: '.$exception->getMessage()
            ));
        }
    }
}
