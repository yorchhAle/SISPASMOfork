<?php

namespace App\Http\Controllers;

use App\Models\Diplomado;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ReporteAlumnosInscritosController extends Controller
{
    /*
     * Muestra la vista principal del reporte de Alumnos Inscritos. Carga todos los diplomados disponibles para 
        que el administrador pueda utilizarlos como filtros.
    */
    public function mostrarReporte()
    {
        $diplomados = Diplomado::orderBy('nombre')->get();
        
        return view('administrador.reportes.alumnosInscritos.reporte', compact('diplomados'));
    }


    /*
     * Genera datos JSON para una gráfica que muestra el conteo total de alumnos
        inscritos por cada diplomado seleccionado.
    */
    public function alumnosTotales(Request $request)
    {
        $diplomadosIds = $request->input('diplomados', []);

        /* Filtra los diplomados basándose en los IDs proporcionados en la solicitud (o todos si no se pasa filtro). */
        if (empty($diplomadosIds)) {
            $diplomados = Diplomado::all(); 
        } else {
            $diplomados = Diplomado::whereIn('id_diplomado', $diplomadosIds)->get();
        }

        /* Utiliza `loadCount('alumnos')` para obtener eficientemente el total de alumnos por diplomado. */
        $diplomados->loadCount('alumnos');

        $labels = $diplomados->pluck('nombre');
        $data   = $diplomados->pluck('alumnos_count');

        return response()->json([
            'labels' => $labels,
            'data'   => $data,
        ]);
    }


    /*
     * Genera datos JSON para una gráfica comparativa de estatus de alumnos (Activos vs. Baja)
        por cada diplomado seleccionado.
    */
    public function estatusAlumnos(Request $request)
    {
        $diplomadosIds = $request->input('diplomados', []);

        if (empty($diplomadosIds)) {
            $diplomados = Diplomado::all();
        } else {
            $diplomados = Diplomado::whereIn('id_diplomado', $diplomadosIds)->get();
        }

        $labels = $diplomados->pluck('nombre');

        $dataActivos = [];
        $dataBajas   = [];

        /* Itera sobre los diplomados seleccionados y cuenta separadamente los alumnos con estatus 'activo' y 'baja'. */
        foreach ($diplomados as $diplomado) {
            $alumnosActivos = $diplomado->alumnos()->where('estatus', 'activo')->count();
            $alumnosBajas   = $diplomado->alumnos()->where('estatus', 'baja')->count();

            $dataActivos[] = $alumnosActivos;
            $dataBajas[]   = $alumnosBajas;
        }

        return response()->json([
            'labels' => $labels,
            'dataActivos' => $dataActivos,
            'dataBajas'   => $dataBajas,
        ]);
    }


    /*
     * Genera y descarga un PDF que incluye una imagen de gráfica enviada desde el frontend.
    */
    public function generarPdf(Request $request)
    {
        /* Recibe la imagen de la gráfica en formato Data URL, así como un título y subtítulo, y utiliza DomPDF para 
            renderizar y descargar el documento. */
        $imageData = $request->input('chart_data_url');
        $titulo = $request->input('titulo', 'Reporte');
        $subtitulo = $request->input('subtitulo', '');

        $data = [
            'titulo' => $titulo,
            'subtitulo' => $subtitulo,
            'imageData' => $imageData
        ];

        $pdf = Pdf::loadView('administrador.reportes.alumnosInscritos.pdf', $data);
        return $pdf->download('reporte_alumnos.pdf');
    }
}