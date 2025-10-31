<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AlumnosEdadExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;


class ReporteAlumnosEdadController extends Controller
{
    private string $dobColumn = 'u.fecha_nac';

    public function index()
    {
        $diplomados = \DB::table('diplomados')->orderBy('nombre')->get(['id_diplomado', 'nombre']);
        return view('administrador.reportes.alumnosEdad.index', compact('diplomados'));
    }

    public function chartData(Request $request)
    {
    $dob = $this->dobColumn;
    $diplomadoId = $request->get('diplomado_id'); 

    $query = \DB::table('alumnos as a')
        ->join('usuarios as u', 'u.id_usuario', '=', 'a.id_usuario')
        ->whereNotNull($dob);
    
    if ($diplomadoId) {
        $query->where('a.id_diplomado', $diplomadoId);
    }

    $row = $query->selectRaw("
            SUM(CASE WHEN TIMESTAMPDIFF(YEAR, {$dob}, CURDATE()) BETWEEN 17 AND 21 THEN 1 ELSE 0 END) AS r_17_21,
            SUM(CASE WHEN TIMESTAMPDIFF(YEAR, {$dob}, CURDATE()) BETWEEN 22 AND 26 THEN 1 ELSE 0 END) AS r_22_26,
            SUM(CASE WHEN TIMESTAMPDIFF(YEAR, {$dob}, CURDATE()) BETWEEN 27 AND 31 THEN 1 ELSE 0 END) AS r_27_31,
            SUM(CASE WHEN TIMESTAMPDIFF(YEAR, {$dob}, CURDATE()) BETWEEN 32 AND 35 THEN 1 ELSE 0 END) AS r_31_35,
            SUM(CASE WHEN TIMESTAMPDIFF(YEAR, {$dob}, CURDATE()) BETWEEN 36 AND 40 THEN 1 ELSE 0 END) AS r_36_40,
            SUM(CASE WHEN TIMESTAMPDIFF(YEAR, {$dob}, CURDATE()) BETWEEN 41 AND 45 THEN 1 ELSE 0 END) AS r_41_45,
            SUM(CASE WHEN TIMESTAMPDIFF(YEAR, {$dob}, CURDATE()) >= 50 THEN 1 ELSE 0 END) AS r_50p
        ")
        ->first();

    return response()->json([
        'labels' => ['17-21','22-26','27-31','31-35','36-40','41-45','50+'],
        'data'   => [
            (int)($row->r_17_21 ?? 0),
            (int)($row->r_22_26 ?? 0),
            (int)($row->r_27_31 ?? 0),
            (int)($row->r_31_35 ?? 0),
            (int)($row->r_36_40 ?? 0),
            (int)($row->r_41_45 ?? 0),
            (int)($row->r_50p   ?? 0),
        ],
    ]);
}

    public function table()
    {
    $dob = $this->dobColumn;

    $alumnos = \DB::table('alumnos as a')
        ->join('usuarios as u', 'u.id_usuario', '=', 'a.id_usuario')
        ->whereNotNull($dob)
        ->selectRaw("
            a.id_alumno,
            u.nombre, u.apellidoP, u.apellidoM,
            TIMESTAMPDIFF(YEAR, {$dob}, CURDATE()) AS edad,
            a.matriculaA, a.id_diplomado
        ")
        ->orderBy('edad')
        ->orderBy('apellidoP')
        ->orderBy('nombre')
        ->paginate(20);

        return view('administrador.reportes.alumnosEdad.table', compact('alumnos'));
    }

    public function excel()
    {
        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\AlumnosEdadExport($this->dobColumn),
            'alumnosEdad.xlsx'
        );
    }

    public function pdf(Request $request)
    {
        $dob = $this->dobColumn;

        $alumnos = \DB::table('alumnos as a')
            ->join('usuarios as u', 'u.id_usuario', '=', 'a.id_usuario')
            ->whereNotNull($dob)
            ->selectRaw("
                a.id_alumno,
                u.nombre, u.apellidoP, u.apellidoM,
                TIMESTAMPDIFF(YEAR, {$dob}, CURDATE()) AS edad,
                a.matriculaA, a.id_diplomado
            ")
            ->orderBy('edad')->orderBy('apellidoP')->orderBy('nombre')
            ->get();

        $chart_data_url = $request->input('chart_data_url');
        $titulo         = $request->input('titulo', 'Reporte de Alumnos por Edad');

        if (!$chart_data_url) {
            $chartDataResponse = $this->chartData($request); 
            $chartData = json_decode($chartDataResponse->getContent(), true);

            $config = [
                'type' => 'bar',
                'data' => [
                    'labels'   => $chartData['labels'],
                    'datasets' => [[
                        'label' => 'Total de Alumnos',
                        'data'  => $chartData['data'],
                        'backgroundColor' => 'rgba(54, 162, 235, 0.5)',
                        'borderColor'     => 'rgba(54, 162, 235, 1)',
                        'borderWidth'     => 1
                    ]]
                ],
                'options' => [
                    'responsive' => true,
                    'plugins' => [
                        'legend' => ['position' => 'top'],
                        'title'  => ['display' => true, 'text' => $titulo]
                    ]
                ]
            ];
            $chartUrl = 'https://quickchart.io/chart?width=500&height=300&c=' . urlencode(json_encode($config));
            $chart_image = \Illuminate\Support\Facades\Http::get($chartUrl)->body();
            $chart_data_url = 'data:image/png;base64,' . base64_encode($chart_image);
        }
        
        $subtitulo = $request->input('subtitulo', ''); 

        $fecha = \Carbon\Carbon::now()->isoFormat('D MMMM YYYY');

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView(
            'administrador.reportes.alumnosEdad.pdf_grafica',
            compact('alumnos', 'chart_data_url', 'titulo', 'fecha', 'subtitulo')
        );

        return $pdf->download('alumnosEdad.pdf');
    }

    public function chartDataExact(Request $request) 
    {
        $diplomadoId = $request->get('diplomado_id');

        $query = DB::table('alumnos as a')
            ->join('usuarios as u', 'u.id_usuario', '=', 'a.id_usuario')
            ->whereNotNull('u.fecha_nac');
        
        if ($diplomadoId) {
            $query->where('a.id_diplomado', $diplomadoId);
        }

        $rows = $query->select(DB::raw('TIMESTAMPDIFF(YEAR, u.fecha_nac, CURDATE()) as edad'), DB::raw('COUNT(*) as total'))
            ->groupBy('edad')
            ->orderBy('edad', 'asc')
            ->get();

        $labels = $rows->pluck('edad')->map(fn($e) => (string)$e)->toArray();
        $data   = $rows->pluck('total')->toArray();

        return response()->json([
            'labels' => $labels,
            'data'   => $data,
            'title'  => 'Alumnos por edad exacta',
        ]);
    }

}