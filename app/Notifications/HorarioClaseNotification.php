<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Horario;
use Carbon\Carbon;

class HorarioClaseNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $horario;
    protected $observacion_personalizada;

    public function __construct(Horario $horario, string $observacion)
    {
        $this->horario = $horario;
        $this->observacion_personalizada = $observacion;
    }

    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable)
    {
        $materia = optional($this->horario->modulo)->nombre_modulo ?? 'Módulo no disponible';
        $fecha_formateada = Carbon::parse($this->horario->fecha)->locale('es')->isoFormat('dddd, D [de] MMMM');
        $diplomado = optional($this->horario->diplomado)->nombre ?? 'N/A';
        $docente = optional(optional($this->horario->docente)->usuario)->nombre ?? 'N/A';


        return (new MailMessage)
                    ->subject("Notificación de Clase: {$materia} - {$diplomado}")
                    ->greeting("Estimado(a) {$notifiable->nombre},")
                    ->line($this->observacion_personalizada)
                    ->line("Aquí están los detalles de la clase:")
                    ->line(" **Módulo:** {$materia}")
                    ->line(" **Día y Hora:** {$fecha_formateada} de {$this->horario->hora_inicio} a {$this->horario->hora_fin} hrs.")
                    ->line(" **Aula/Enlace:** {$this->horario->aula} ({$this->horario->modalidad})")
                    ->line(" **Docente:** {$docente}")
                    ->line(" **Observaciones:** {$this->observacion_personalizada}")
                    ->action('Ver Notificaciones', url('/notificaciones'));
    }

    public function toDatabase($notifiable)
    {
        return [
            'tipo' => 'horario',
            'titulo' => optional($this->horario->modulo)->nombre_modulo,
            'fecha_clase' => $this->horario->fecha,
            'hora_inicio' => $this->horario->hora_inicio,
            'aula_enlace' => $this->horario->aula,
            'observaciones' => $this->observacion_personalizada,
        ];
    }
}