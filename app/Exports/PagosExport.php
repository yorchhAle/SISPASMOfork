<?php

namespace App\Exports;

use App\Models\Recibo;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;


/*
 * Clase de exportación para generar un reporte detallado de los pagos (Recibos) que han sido marcados como 
    'validado' dentro de un rango de fechas específico.
    Implementa FromCollection, WithHeadings y WithMapping.
*/
class PagosExport implements FromCollection, WithHeadings, WithMapping
{
    protected $fechaInicio;
    protected $fechaFin;

    public function __construct($fechaInicio, $fechaFin)
    {
        $this->fechaInicio = $fechaInicio;
        $this->fechaFin = $fechaFin;
    }


    /*
     * Define la colección de datos que se exportará.
        Consulta los Recibos que están 'validado' y cuya `fecha_pago` se encuentra dentro del rango especificado, 
        cargando las relaciones de `alumno` y `diplomado`.
    */
    public function collection()
    {
        return Recibo::with(['alumno', 'alumno.diplomado'])
            ->whereBetween('fecha_pago', [$this->fechaInicio, $this->fechaFin])
            ->where('estatus', 'validado')
            ->orderBy('fecha_pago')
            ->get();
    }


    /*
     * Define los encabezados de las columnas del archivo Excel.
    */
    public function headings(): array
    {
        return [
            'Alumno',
            'Matrícula',
            'Diplomado',
            'Concepto',
            'Monto',
            'Fecha de Pago',
        ];
    }


    /*
     * Mapea cada objeto Recibo de la colección a una fila del archivo Excel.
    */
    public function map($pago): array
    {
        /* Formatea los datos del alumno, el diplomado, el concepto, el monto (a dos decimales)
            y la fecha de pago. */
        return [
            $pago->alumno->nombre ?? 'N/A',
            $pago->alumno->matriculaA ?? 'N/A',
            $pago->alumno->diplomado->nombre_diplomado ?? 'N/A',
            $pago->concepto,
            number_format($pago->monto, 2),
            $pago->fecha_pago->format('Y-m-d'),
        ];
    }
}