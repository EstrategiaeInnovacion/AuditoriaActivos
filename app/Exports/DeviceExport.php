<?php

namespace App\Exports;

use App\Models\Device;
use Illuminate\Support\Facades\Gate;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class DeviceExport implements FromQuery, ShouldAutoSize, WithHeadings, WithMapping, WithStyles
{
    protected $search;

    protected $type;

    protected $status;

    protected $includeCredentials;

    public function __construct(?string $search = null, ?string $type = null, ?string $status = null, bool $includeCredentials = false)
    {
        $this->search = $search;
        $this->type = $type;
        $this->status = $status;
        $this->includeCredentials = $includeCredentials;
    }

    public static function queryForExport(?string $search = null, ?string $type = null, ?string $status = null, bool $includeCredentials = false)
    {
        $query = Device::query();

        if ($includeCredentials) {
            Gate::authorize('exportCredentials', Device::class);
            $query->with('credential');
        }

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

        return $query;
    }

    public function query()
    {
        return self::queryForExport($this->search, $this->type, $this->status, $this->includeCredentials);
    }

    public function headings(): array
    {
        $headings = [
            'Nombre',
            'Marca',
            'Modelo',
            'No. Serie',
            'Tipo',
            'Estado',
            'Fecha Compra',
            'Venc. Garantía',
            'Notas',
        ];

        if ($this->includeCredentials) {
            Gate::authorize('exportCredentials', Device::class);
            $headings[] = 'Usuario Equipo';
            $headings[] = 'Contraseña Equipo';
            $headings[] = 'Correo';
            $headings[] = 'Contraseña Correo';
        }

        return $headings;
    }

    public function map($device): array
    {
        $types = ['computer' => 'Computadora', 'peripheral' => 'Periférico', 'printer' => 'Impresora', 'other' => 'Otro'];
        $statuses = ['available' => 'Disponible', 'assigned' => 'Asignado', 'maintenance' => 'Mantenimiento', 'broken' => 'Averiado'];

        $row = [
            $device->name,
            $device->brand,
            $device->model,
            $device->serial_number,
            $types[$device->type] ?? $device->type,
            $statuses[$device->status] ?? $device->status,
            $device->purchase_date ?? 'N/A',
            $device->warranty_expiration ?? 'N/A',
            $device->notes ?? '',
        ];

        if ($this->includeCredentials) {
            $row[] = $device->credential->username ?? 'N/A';
            $row[] = $device->credential->password ?? 'N/A';
            $row[] = $device->credential->email ?? 'N/A';
            $row[] = $device->credential->email_password ?? 'N/A';
        }

        return $row;
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}
