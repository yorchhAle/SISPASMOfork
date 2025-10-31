<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Cargo;
use App\Notifications\AlertaAdeudo;

class EnviarAlertasAdeudos extends Command
{
    /*
     * El nombre y la firma del comando de consola.
        Define cómo se llamará el comando en la línea de comandos (ej. `php artisan alertas:adeudos`).
     */
    protected $signature = 'alertas:adeudos';
    protected $description = 'Envía notificaciones de adeudos vencidos o próximos a vencer';


    /*
     * Ejecuta el comando de consola.
    */
    public function handle()
    {
        $this->info('Buscando adeudos pendientes...');

        $hoy = now();

        /* Busca todos los registros de 'Cargo' con estatus 'pendiente' cuya fecha límite (`fecha_limite`) es igual o 
            anterior a la fecha actual.*/
        $adeudos = Cargo::with('alumno.usuario')
            ->where('estatus', 'pendiente')
            ->whereDate('fecha_limite', '<=', $hoy)
            ->get();

        /* Itera sobre cada adeudo encontrado. */
        foreach ($adeudos as $cargo) {
            $usuario = $cargo->alumno->usuario;
            /* Envía una notificación (`AlertaAdeudo`) al usuario asociado al alumno con el detalle del cargo. */
            $usuario->notify(new AlertaAdeudo($cargo->alumno, $cargo));
        }

        $this->info('Se enviaron ' . $adeudos->count() . ' alertas de adeudo.');
    }
}