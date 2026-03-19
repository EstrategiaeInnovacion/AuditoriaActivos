<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ExportReadyNotification extends Notification
{
    use Queueable;

    public function __construct(
        public ?string $downloadUrl,
        public string $format,
        public string $filename,
        public bool $failed = false,
        public ?string $errorMessage = null
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $message = (new MailMessage)
            ->subject('Reporte de exportación listo');

        if ($this->failed) {
            return $message
                ->error()
                ->line('La generación del reporte en formato '.$this->format.' ha fallado.')
                ->line($this->errorMessage ?? 'Por favor, intenta nuevamente.');
        }

        return $message
            ->line('Tu reporte de inventario en formato '.$this->format.' está listo.')
            ->action('Descargar reporte', $this->downloadUrl)
            ->line('El archivo '.$this->filename.' estará disponible por 24 horas.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'download_url' => $this->downloadUrl,
            'format' => $this->format,
            'filename' => $this->filename,
            'failed' => $this->failed,
            'error_message' => $this->errorMessage,
        ];
    }
}
