<?php

namespace App\Notifications;

use App\Models\Assignment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LoanOverdue extends Notification
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
        $days = $this->assignment->assigned_at->diffInDays(now());

        return (new MailMessage)
            ->subject('Préstamo de equipo vencido')
            ->greeting("¡Hola {$notifiable->name}!")
            ->line("El equipo **{$device->name}** te fue asignado hace **{$days} días** y aún no ha sido devuelto.")
            ->line("Número de serie: {$device->serial_number}")
            ->action('Ver Equipo', url(route('devices.show', $device)))
            ->line('Por favor coordina la devolución lo antes posible.');
    }

    public function toArray(object $notifiable): array
    {
        $device = $this->assignment->device;

        return [
            'title' => 'Préstamo vencido',
            'message' => "El equipo {$device->name} lleva más de 30 días asignado",
            'device_id' => $device->id,
            'assignment_id' => $this->assignment->id,
        ];
    }
}
