<?php

namespace App\Enums;

enum DeviceStatus: string
{
    case Available = 'available';
    case Assigned = 'assigned';
    case Maintenance = 'maintenance';
    case Broken = 'broken';

    public function label(): string
    {
        return match ($this) {
            self::Available => 'Disponible',
            self::Assigned => 'Asignado',
            self::Maintenance => 'En Mantenimiento',
            self::Broken => 'Dañado',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
