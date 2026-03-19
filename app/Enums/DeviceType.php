<?php

namespace App\Enums;

enum DeviceType: string
{
    case Computer = 'computer';
    case Peripheral = 'peripheral';
    case Printer = 'printer';
    case Other = 'other';

    public function label(): string
    {
        return match ($this) {
            self::Computer => 'Computadora',
            self::Peripheral => 'Periférico',
            self::Printer => 'Impresora',
            self::Other => 'Otro',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
