<?php

namespace App\Exports;

use App\Models\Device;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class DeviceExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $search;
    protected $type;
    protected $status;

    public function __construct(?string $search = null, ?string $type = null, ?string $status = null)
    {
        $this->search = $search;
        $this->type = $type;
        $this->status = $status;
    }

    public function query()
    {
        $query = Device::query();

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', "%{$this->search}%")
                    ->orWhere('serial_number', 'like', "%{$this->search}%")
                    ->orWhere('brand', 'like', "%{$this->search}%");
            });
        }

        if ($this->type) {
            $query->where('type', $this->type);
        }

        if ($this->status) {
            $query->where('status', $this->status);
        }

        return $query;
    }

    public function headings(): array
    {
        return [
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
    }

    public function map($device): array
    {
        $types = ['computer' => 'Computadora', 'peripheral' => 'Periférico', 'printer' => 'Impresora', 'other' => 'Otro'];
        $statuses = ['available' => 'Disponible', 'assigned' => 'Asignado', 'maintenance' => 'Mantenimiento', 'broken' => 'Averiado'];

        return [
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
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}
