<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AlertaAdeudo extends Notification implements ShouldQueue
{
    use Queueable;

    protected $alumno;
    protected $cargo;

    public function __construct($alumno, $cargo)
    {
        $this->alumno = $alumno;
        $this->cargo = $cargo;
    }

    public function via($notifiable)
    {
        return ['mail']; // Solo correo
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Alerta de Adeudo Pendiente')
            ->greeting('Hola ' . $this->alumno->nombre . ' ' . $this->alumno->apellidoP)
            ->line('Te informamos que tienes un adeudo pendiente con la institución.')
            ->line('**Concepto:** ' . $this->cargo->concepto)
            ->line('**Monto:** $' . number_format($this->cargo->monto, 2))
            ->line('**Fecha límite de pago:** ' . $this->cargo->fecha_limite->format('d/m/Y'))
            ->line('Por favor, regulariza tu situación a la brevedad para evitar recargos o restricciones.')
            ->action('Ir a mi Panel', url('/alumno/dashboard'))
            ->line('Gracias por tu atención.');
    }
}
