<?php

namespace App\Exports;

use App\Models\Alumno;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

/*
 * Clase de exportación para generar un listado de Alumnos que tienen un adeudo pendiente de colegiatura para un 
    mes y año específicos.
    Implementa FromCollection, WithHeadings y WithMapping para estructurar el archivo Excel.
 */
class AdeudosExport implements FromCollection, WithHeadings, WithMapping
{
    private $mes;
    private $anio;
    private $matricula;
    private $tipo;

    public function __construct($mes, $anio, $matricula = null, $tipo = 'mes')
    {
        $this->mes = (int) $mes;
        $this->anio = (int) $anio;
        $this->matricula = $matricula;
        $this->tipo = $tipo;
    }

    /*
     * Define la colección de datos que se exportará. Filtra los Alumnos que *no* tienen un recibo 'validado' 
        para el concepto de colegiatura del mes/año especificado. Opcionalmente, filtra por matrícula si el tipo es 
        'alumno'.
    */
    public function collection()
    {
        $mesNombre = Carbon::createFromDate($this->anio, $this->mes, 1)
            ->locale('es')
            ->monthName;

        $concepto_adeudo = 'Colegiatura ' . ucfirst($mesNombre) . ' ' . $this->anio;

        $query = Alumno::with(['diplomado', 'usuario'])
            ->whereDoesntHave('recibos', function ($q) use ($concepto_adeudo) {
                $q->where('concepto', $concepto_adeudo)
                  ->where('estatus', 'validado');
            });

        if ($this->tipo === 'alumno' && !empty($this->matricula)) {
            $query->where('matriculaA', $this->matricula);
        }

        return $query->get();
    }


    /*
     * Define los encabezados de las columnas del archivo Excel.
    */
    public function headings(): array
    {
        return [
            'Matricula',
            'Nombre del alumno',
            'Concepto de adeudo',
            'Grupo',
            'Mes y año del adeudo',
        ];
    }


    /*
     * Mapea cada objeto Alumno de la colección a una fila del archivo Excel. Formatea el nombre completo del alumno 
        y el concepto de adeudo con mes y año.
    */
    public function map($alumno): array
    {
        $mesNombre = Carbon::createFromDate($this->anio, $this->mes, 1)
            ->locale('es')
            ->monthName;

        $nombreCompleto = trim(
            ($alumno->usuario->nombre    ?? '') . ' ' .
            ($alumno->usuario->apellidoP ?? '') . ' ' .
            ($alumno->usuario->apellidoM ?? '')
        );

        $concepto_adeudo = 'Colegiatura ' . ucfirst($mesNombre) . ' ' . $this->anio;

        return [
            $alumno->matriculaA ?? '—',
            $nombreCompleto ?: '—',
            $concepto_adeudo,
            $alumno->diplomado?->grupo ?? '—',
            ucfirst($mesNombre) . ' ' . $this->anio,
        ];
    }
}