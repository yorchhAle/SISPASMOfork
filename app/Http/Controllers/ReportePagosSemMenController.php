<?php

namespace App\Http\Controllers;

use App\Models\Recibo;
use App\Exports\PagosExport; 
use Illuminate\Http\Request;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;

class ReportePagosSemMenController extends Controller
{
    /*
     * Muestra la vista del reporte de pagos agrupados por período (semanal o mensual).
    */
    public function mostrarReporte(Request $request)
    {
        /* Filtra los recibos 'validado' en un rango de fechas (`fecha_inicio` y `fecha_fin`). */
        $fechaInicio = $request->input('fecha_inicio');
        $fechaFin = $request->input('fecha_fin');
        $periodo = $request->input('periodo');

        /* Agrupa los pagos encontrados */
        $pagos = collect();

        if ($fechaInicio && $fechaFin) {
            $query = Recibo::with(['alumno', 'alumno.diplomado'])
                ->whereBetween('fecha_pago', [$fechaInicio, $fechaFin])
                ->where('estatus', 'validado')
                ->orderBy('fecha_pago');

            $pagosEncontrados = $query->get();

            if ($pagosEncontrados->isNotEmpty()) {
                /* Si el período es 'semanal', agrupa por el inicio de la semana. */
                if ($periodo === 'semanal') {
                    $pagos = $pagosEncontrados->groupBy(function($item) {
                        return Carbon::parse($item->fecha_pago)->startOfWeek()->format('Y-m-d');
                    })->map(function($semana) {
                        return [
                            'fecha_pago' => $semana->first()->fecha_pago,
                            'monto' => $semana->sum('monto')
                        ];
                    });
                /* Si el período es 'mensual', agrupa por el mes y año. */
                } elseif ($periodo === 'mensual') {
                    $pagos = $pagosEncontrados->groupBy(function($item) {
                        return Carbon::parse($item->fecha_pago)->format('Y-m');
                    })->map(function($mes) {
                        return [
                            'fecha_pago' => $mes->first()->fecha_pago,
                            'monto' => $mes->sum('monto')
                        ];
                    });
                }
            }
        }

        return view('administrador.reportes.pagosSemMen.pagos', compact('pagos'));
    }

    /*
     * Exporta el reporte de pagos validados en un rango de fechas a un archivo Excel.
        Utiliza la clase `PagosExport` para generar el archivo.
    */
    public function exportarExcel(Request $request)
    {
        $fechaInicio = $request->input('fecha_inicio');
        $fechaFin = $request->input('fecha_fin');
        $fileName = 'reporte_pagos_' . $fechaInicio . '_a_' . $fechaFin . '.xlsx';

        return Excel::download(new PagosExport($fechaInicio, $fechaFin), $fileName);
    }
}