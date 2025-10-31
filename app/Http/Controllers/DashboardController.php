<?php

namespace App\Http\Controllers;
use Illuminate\Notifications\DatabaseNotification;
use App\Http\Controllers\Controller;
use App\Models\Alumno;
use App\Models\Docente;
use App\Models\Aspirante;
use App\Models\Taller;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    /*
     * Muestra el dashboard principal para los administradores/coordinadores.
    */
    public function index()
    { 
        /* Recopila varias métricas clave: Totales de Alumnos, Docentes y Aspirantes.*/
        $alumnosTotal    = Alumno::count();
        $docentesTotal   = Docente::count();
        $aspirantesTotal = Aspirante::count();

        /* Desglose del estatus de Alumnos (Activos vs. Baja). */
        $alumnosActivos = Alumno::whereRaw('LOWER(estatus) = ?', ['activo'])->count();
        $alumnosBaja    = Alumno::whereRaw('LOWER(estatus) = ?', ['baja'])->count();
        
        /* Actividades (Talleres) programadas para la semana actual. */
        $actividadesSemanales = Taller::whereBetween('fecha', [now()->startOfWeek(), now()->endOfWeek()])->get();

        $mapaActividades = [];
        foreach ($actividadesSemanales as $actividad) {
            $fecha = \Carbon\Carbon::parse($actividad->fecha)->format('Y-m-d');
            $mapaActividades[$fecha] = strtolower($actividad->tipo);
        }
        
        /* Listado de notificaciones recientes. */
        $notificaciones = DatabaseNotification::orderBy('created_at', 'desc')->get();

        return view('administrador.dashboardadmin', compact(
            'alumnosTotal',
            'docentesTotal',
            'aspirantesTotal',
            'alumnosActivos',
            'alumnosBaja',
            'actividadesSemanales',
            'mapaActividades',
            'notificaciones'
        ));
    }

    
    /*
     * Devuelve las métricas clave de la aplicación como una respuesta JSON. Diseñado para ser utilizado por 
        llamadas asíncronas (AJAX) para actualizar widgets en el dashboard sin recargar la página.
    */
    public function metrics(): JsonResponse
    {
        $alumnosTotal = Alumno::count();
        $docentesTotal = Docente::count();
        $aspirantesTotal = Aspirante::count();

        $activos = Alumno::whereRaw('LOWER(estatus) = ?', ['activo'])->count();
        $baja    = Alumno::whereRaw('LOWER(estatus) = ?', ['baja'])->count();

        return response()->json([
            'alumnos' => ['total' => $alumnosTotal, 'activos' => $activos, 'baja' => $baja],
            'docentes' => $docentesTotal,
            'aspirantes' => $aspirantesTotal,
        ]);
    }
}
