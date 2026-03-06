<?php

namespace App\Notifications;

use App\Models\Assignment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
// Importaciones para Telegram
use NotificationChannels\Telegram\TelegramChannel;
use NotificationChannels\Telegram\TelegramMessage;

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
        // Se añade el canal de Telegram al arreglo
        return ['mail', 'database', TelegramChannel::class];
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

    // Nuevo método para estructurar el mensaje de Telegram
    public function toTelegram(object $notifiable)
    {
        $device = $this->assignment->device;
        $fecha = $this->assignment->assigned_at->format('d/m/Y H:i');

        return TelegramMessage::create()
            // REEMPLAZA ESTO: Pon el ID de tu chat de Telegram o grupo
            ->to('-1234567890') 
            ->content("*¡Nuevo Equipo Asignado!*\n\nHola {$notifiable->name}, se te ha asignado el equipo *{$device->name}* ({$device->brand} {$device->model}).\n\n*No. Serie:* {$device->serial_number}\n*Fecha:* {$fecha}")
            ->button('Ver Equipo', url(route('devices.show', $device)));
    }
}