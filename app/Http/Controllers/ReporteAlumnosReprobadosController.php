<?php

namespace App\Http\Controllers;

use App\Models\Diplomado;
use App\Models\Alumno;
use App\Models\Modulo;
use App\Models\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AlumnosReprobadosExport; 
use App\Models\Calificacion;

class ReporteAlumnosReprobadosController extends Controller
{
    /*
     * Muestra la vista principal del reporte de Alumnos Reprobados. Carga todos los diplomados disponibles para 
        que el administrador pueda filtrar.
    */
    public function mostrarReporte()
    {
        $diplomados = Diplomado::orderBy('nombre')->get();
        return view('administrador.reportes.alumnosReprobados.reporte', compact('diplomados'));
    }


    /*
     * Genera datos JSON para una gráfica que muestra el total de alumnos reprobados por módulo
     * dentro de un diplomado específico.
    */
    public function totalReprobados(Request $request)
    {
        $idDiplomado = $request->input('id_diplomado');
        $diplomado   = Diplomado::find($idDiplomado);

        if (!$diplomado) {
            return response()->json(['labels' => ['Error'], 'data' => [0]], 404);
        }

        /* Obtenemos los IDs de los módulos asociados al diplomado a través de los horarios */
        $modulosIds = $diplomado->horarios->pluck('id_modulo')->unique();

        /* Si no se encuentran módulos para este diplomado, devolvemos un gráfico vacío. */
        if ($modulosIds->isEmpty()) {
            return response()->json(['labels' => ['Sin módulos asignados'], 'data' => [0]]);
        }

        $modulos = Modulo::whereIn('id_modulo', $modulosIds)->orderBy('nombre_modulo')->get();
        
        $labels = [];
        $data = [];

        foreach ($modulos as $modulo) {
            /* Contamos los alumnos únicos (distinct) con calificación reprobatoria (< 80) para cada módulo. */
            /* Usar el modelo Calificacion hace la consulta más clara. */
            $reprobadosCount = Calificacion::where('id_modulo', $modulo->id_modulo)
                ->where('calificacion', '<', 80.00)
                ->distinct()
                ->count('id_alumno');
            
            $labels[] = $modulo->nombre_modulo; 
            $data[] = $reprobadosCount;
        }

        return response()->json([
            'labels' => $labels,
            'data'   => $data,
        ]);
    }



    /*
     * Genera datos JSON para una gráfica comparativa de rangos de calificaciones reprobatorias
        (0-59 vs. 60-79) para un diplomado específico.
    */
    public function calificacionesReprobados(Request $request)
    {
        $idDiplomado = $request->input('id_diplomado');
        $diplomado   = Diplomado::find($idDiplomado);

        if (!$diplomado) {
            return response()->json(['labels' => ['Error'], 'data' => [0, 0]], 404);
        }

        $modulosIds = $diplomado->horarios->pluck('id_modulo')->unique();

        if ($modulosIds->isEmpty()) {
            return response()->json(['labels' => ['0-59', '60-79'], 'data' => [0, 0]]);
        }

        /* Contamos alumnos únicos con calificaciones entre 0 y 59.99 en cualquiera de los módulos del diplomado. */
        $reprobadosBajos = Calificacion::whereIn('id_modulo', $modulosIds)
            ->whereBetween('calificacion', [0, 59.99])
            ->distinct()
            ->count('id_alumno');

        /* Contamos alumnos únicos con calificaciones entre 60 y 79.99. */
        $reprobadosAltos = Calificacion::whereIn('id_modulo', $modulosIds)
            ->whereBetween('calificacion', [60, 79.99])
            ->distinct()
            ->count('id_alumno');

        return response()->json([
            'labels' => ['Calificación de 0-59', 'Calificación de 60-79'],
            'data'   => [$reprobadosBajos, $reprobadosAltos],
        ]);
    }


    /*
     * Exporta un listado de Alumnos Reprobados para un diplomado específico a un archivo Excel. Utiliza la clase 
        `AlumnosReprobadosExport` para generar la descarga.
    */
    public function exportarExcel(Request $request)
    {
        $idDiplomado = $request->input('id_diplomado');

        if (!$idDiplomado) {
            return back()->with('error', 'Por favor, selecciona un diplomado para exportar.');
        }

        return Excel::download(new AlumnosReprobadosExport($idDiplomado, true), 'alumnos_reprobados_diplomado_' . $idDiplomado . '.xlsx');
    }
}