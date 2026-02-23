<?php

namespace App\Notifications;

use App\Models\Assignment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DeviceAssigned extends Notification
{
    use Queueable;

    public Assignment $assignment;

    public function __construct(Assignment $assignment)
    {
        $this->assignment = $assignment;
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $device = $this->assignment->device;

        return (new MailMessage)
            ->subject('Se te ha asignado un equipo')
            ->greeting("¡Hola {$notifiable->name}!")
            ->line("Se te ha asignado el equipo **{$device->name}** ({$device->brand} {$device->model}).")
            ->line("Número de serie: {$device->serial_number}")
            ->line("Fecha de asignación: {$this->assignment->assigned_at->format('d/m/Y H:i')}")
            ->action('Ver Equipo', url(route('devices.show', $device)))
            ->line('Por favor cuida el equipo asignado.');
    }

    public function toArray(object $notifiable): array
    {
        $device = $this->assignment->device;

        return [
            'title' => 'Equipo asignado',
            'message' => "Se te asignó: {$device->name} ({$device->serial_number})",
            'device_id' => $device->id,
            'assignment_id' => $this->assignment->id,
        ];
    }
}
