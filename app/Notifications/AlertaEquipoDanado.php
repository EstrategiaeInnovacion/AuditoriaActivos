<?php

namespace App\Notifications;

use App\Models\Device;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramChannel;
use NotificationChannels\Telegram\TelegramMessage;

class AlertaEquipoDanado extends Notification
{
    use Queueable;

    public Device $device;

    public string $oldStatus;

    public string $newStatus;

    public function __construct(Device $device, string $oldStatus, string $newStatus)
    {
        $this->device = $device;
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus;
    }

    public function via(object $notifiable): array
    {
        return ['database', TelegramChannel::class];
    }

    public function toArray(object $notifiable): array
    {
        $statusLabels = [
            'available' => 'Disponible',
            'assigned' => 'Asignado',
            'maintenance' => 'En Mantenimiento',
            'broken' => 'Dañado',
        ];

        return [
            'title' => 'Alerta: Estado de equipo actualizado',
            'message' => "El equipo {$this->device->name} cambió de {$statusLabels[$this->oldStatus]} a {$statusLabels[$this->newStatus]}",
            'device_id' => $this->device->id,
            'old_status' => $this->oldStatus,
            'new_status' => $this->newStatus,
        ];
    }

    public function toTelegram(object $notifiable)
    {
        $statusLabels = [
            'available' => 'Disponible',
            'assigned' => 'Asignado',
            'maintenance' => 'En Mantenimiento',
            'broken' => 'Dañado',
        ];

        $emoji = $this->newStatus === 'broken' ? '⚠️' : '🔧';

        return TelegramMessage::create()
            ->to('1106493545')
            ->content("{$emoji} *¡Alerta de Estado de Equipo!*\n\nEl equipo *{$this->device->name}* ha cambiado de estado.\n\n*Anterior:* {$statusLabels[$this->oldStatus]}\n*Nuevo:* {$statusLabels[$this->newStatus]}\n\n*No. Serie:* {$this->device->serial_number}\n*Marca:* {$this->device->brand} {$this->device->model}")
            ->button('Ver Equipo', url(route('devices.show', $this->device)));
    }
}
