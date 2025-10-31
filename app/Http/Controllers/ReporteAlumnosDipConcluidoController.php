<?php

namespace App\Http\Controllers;

use App\Models\Alumno;
use App\Models\Diplomado;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel; 
use App\Exports\ReporteDipConcluidoExport;

class ReporteAlumnosDipConcluidoController extends Controller
{
    /*
     * Muestra la vista principal del reporte de Alumnos por Diplomado Concluido.
    */
    public function index(Request $request)
    {
        /* Obtiene el año de la solicitud o usa el año actual por defecto. */
        $year = $request->query('year', date('Y'));

        /* Llama a métodos privados para obtener métricas: egresados por año y comparación de estatus 
            (activo vs. egresado).*/
        $egresadosAnual = $this->getEgresadosPorAnio($year);
        $comparacionEstatus = $this->getComparacionEstatus($year);

        /* Recupera los años únicos de finalización de diplomados para el filtro de la vista. */
        $years = Diplomado::selectRaw('YEAR(fecha_fin) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');
        return view('administrador.reportes.alumnosDiplomado.diplomadoEgresados', compact('egresadosAnual', 'comparacionEstatus', 'year', 'years'));
    }


    /*
     * Descarga el reporte seleccionado en formato Excel.
    */
    public function downloadExcel(Request $request)
    {
        $year = $request->query('year', date('Y'));
        $reportType = $request->query('report_type');

        /* Utiliza la clase `ReporteDipConcluidoExport` para generar dos tipos de reportes:
            'egresados' (conteo de egresados) o 'estatus' (comparación activo/egresado). */
        if ($reportType === 'egresados') {
            return Excel::download(new ReporteDipConcluidoExport($year, 'egresados'), "egresadosAnual_$year.xlsx");
        }

        if ($reportType === 'estatus') {
            return Excel::download(new ReporteDipConcluidoExport($year, 'estatus'), "comparacionEstatus_$year.xlsx");
        }

        return redirect()->back()->with('error', 'Tipo de reporte no válido.');
    }


    /*
     * Obtiene el conteo de alumnos con estatus 'egresado' por cada diplomado que finalizó en el año especificado.
    */
    private function getEgresadosPorAnio($year)
    {
        return Diplomado::whereYear('fecha_fin', $year)
            ->withCount(['alumnos as egresados' => function ($query) {
                $query->where('estatus', 'egresado');
            }])
            ->get();
    }


    /*
     * Obtiene el conteo de alumnos 'activos' y 'egresados' por cada diplomado que finalizó en el año especificado, 
        para una vista comparativa.
    */
    private function getComparacionEstatus($year)
    {
        return Diplomado::whereYear('fecha_fin', $year)
            ->withCount(['alumnos as activos' => function ($query) {
                $query->where('estatus', 'activo');
            }])
            ->withCount(['alumnos as egresados' => function ($query) {
                $query->where('estatus', 'egresado');
            }])
            ->get();
    }
}