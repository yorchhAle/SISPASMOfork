<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class CredencialesAspirante extends Notification
{
    use Queueable;

    public function __construct(
        public string $nombreCompleto,
        public string $usuario,
        public string $passwordTemporal,
        public string $loginUrl
    ) {}

    public function via($notifiable): array
    {
        return ['mail', 'database']; 
    }
    
    public function shouldQueue(string $channel): bool
    {
        return $channel !== 'database'; 
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Tus credenciales de acceso a SISPASMO')
            ->greeting("Hola, {$this->nombreCompleto}")
            ->line('Has sido aceptado(a). Estas son tus credenciales de acceso:')
            ->line("**Usuario:** `{$this->usuario}`")
            ->line("**Contraseña temporal:** `{$this->passwordTemporal}`")
            ->action('Ingresar al sistema', $this->loginUrl)
            ->line('Por seguridad, te recomendamos cambiar la contraseña al ingresar.');
    }
    public function toDatabase($notifiable): array
    {
        return [
            'title'   => 'Credenciales de acceso generadas',
            'message' => 'Fuiste aceptado(a) y se generaron tus credenciales de acceso.',
            'nombre'  => $this->nombreCompleto,
            'usuario' => $this->usuario,
            'password_temporal' => $this->passwordTemporal,
            'login_url' => $this->loginUrl,
        ];
    }
}
