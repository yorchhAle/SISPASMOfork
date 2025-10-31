<?php

namespace App\Http\Controllers;

use App\Models\Alumno;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AdeudosExport;
use App\Models\Diplomado;

class ReporteAdeudosController extends Controller
{
    /*
     * Muestra la vista principal del reporte de adeudos. Carga todos los diplomados disponibles para que 
        el administrador pueda filtrar.
    */
    public function mostrarReporte()
    {
        $diplomados = Diplomado::orderBy('nombre')->get();
        return view('administrador.reportes.adeudos.reporte', compact('diplomados'));
    }


    /*
     * Genera datos JSON para una gráfica de adeudos por diplomado para un mes y año específicos.
    */
    public function generarGraficaMes(Request $request)
    {
        $request->validate([
            'mes' => 'required|integer|between:1,12',
            'anio' => 'required|integer|digits:4',
        ]);

        $mes  = $request->input('mes');
        $anio = $request->input('anio');

        /* Construye el concepto de la colegiatura (`Colegiatura Mes Año`). */
        $concepto_adeudo = 'Colegiatura ' .
            ucfirst(Carbon::createFromDate($anio, $mes, 1)->locale('es')->monthName) .
            ' ' . $anio;

        /*  Consulta cuántos alumnos en cada diplomado no tienen un recibo 'validado'
            con ese concepto (alumnos_adeudos_count).*/
        $diplomados = Diplomado::withCount(['alumnos as alumnos_adeudos_count' => function ($query) use ($concepto_adeudo) {
            $query->whereDoesntHave('recibos', function ($q) use ($concepto_adeudo) {
                $q->where('concepto', $concepto_adeudo)
                  ->where('estatus', 'validado');
            });
        }])->get();

        $labels = $diplomados->pluck('nombre');
        $data   = $diplomados->pluck('alumnos_adeudos_count');

        /* Devuelve los nombres de los diplomados (labels) y los conteos (data). */
        return response()->json([
            'labels' => $labels,
            'data'   => $data,
        ]);
    }


    /*
     * Genera datos JSON para una gráfica simple de 'Adeudo'/'Sin Adeudo' para un Alumno específico.
    */
    public function generarGraficaAlumno(Request $request)
    {
        $request->validate([
            'mes'       => 'required|integer|between:1,12',
            'anio'      => 'required|integer|digits:4',
            'matricula' => 'required|string|max:255',
        ]);

        $mes       = $request->input('mes');
        $anio      = $request->input('anio');
        $matricula = $request->input('matricula');

        /* Construye el concepto de la colegiatura. */
        $concepto_adeudo = 'Colegiatura ' .
            ucfirst(Carbon::createFromDate($anio, $mes, 1)->locale('es')->monthName) .
            ' ' . $anio;

        /* Busca al alumno por matrícula. */
        $alumno = Alumno::where('matriculaA', $matricula)->first();

        $labels = [];
        $data   = [];

        /* Verifica si el alumno tiene un recibo 'validado' para ese concepto. */
        if ($alumno) {
            $tienePago = $alumno->recibos()
                ->where('concepto', $concepto_adeudo)
                ->where('estatus', 'validado')
                ->exists();

            if ($tienePago) {
                $labels = ['Sin Adeudo'];
                $data   = [1];
            } else {
                $labels = ['Adeudo'];
                $data   = [1];
            }
        }

        /* Devuelve el estado (Adeudo/Sin Adeudo) para la gráfica. */
        return response()->json([
            'labels' => $labels,
            'data'   => $data,
        ]);
    }

    /*
     * Exporta el reporte de adeudos a un archivo Excel (.xlsx).
    */
    public function exportarExcel(Request $request)
    {
        $request->validate([
            'mes'       => 'required|integer|between:1,12',
            'anio'      => 'required|integer|digits:4',
            'tipo'      => 'required|string|in:mes,alumno',
            'matricula' => 'nullable|string|max:255',
        ]);

        $mes       = $request->input('mes');
        $anio      = $request->input('anio');
        $tipo      = $request->input('tipo');
        $matricula = $request->input('matricula');

        $fileName = 'adeudos_' . $tipo . '_' . $anio . '_' . $mes . '.xlsx';
        /* Utiliza la clase `AdeudosExport` (de Maatwebsite/Excel) para generar el archivo, 
            basado en el tipo de reporte (`mes` o `alumno`) y los parámetros de fecha/matrícula.*/
        $export = new AdeudosExport($mes, $anio, $matricula);
        
        return Excel::download($export, $fileName);
    }
}