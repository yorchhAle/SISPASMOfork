<?php

namespace App\Exports;

use App\Models\Diplomado;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ReporteDipConcluidoExport implements FromCollection, WithHeadings
{
    protected $year;
    protected $reportType;

    public function __construct(int $year, string $reportType)
    {
        $this->year = $year;
        $this->reportType = $reportType;
    }

    /*
     * Define los encabezados de las columnas del archivo Excel.
    */
    public function headings(): array
    {
        if ($this->reportType === 'egresados') {
            return [
                'ID Diplomado',
                'Nombre',
                'Grupo',
                'Número de Egresados',
            ];
        }

        if ($this->reportType === 'estatus') {
            return [
                'ID Diplomado',
                'Nombre',
                'Grupo',
                'Activos',
                'Egresados',
            ];
        }

        return [];
    }

    public function collection()
    {
        $query = Diplomado::whereYear('fecha_fin', $this->year);

        if ($this->reportType === 'egresados') {
            return $query->withCount(['alumnos as egresados' => function ($query) {
                $query->where('estatus', 'egresado');
            }])
            ->get()
            ->map(function ($diplomado) {
                return [
                    'id_diplomado' => $diplomado->id_diplomado,
                    'nombre' => $diplomado->nombre,
                    'grupo' => $diplomado->grupo,
                    'egresados' => $diplomado->egresados,
                ];
            });
        }

        if ($this->reportType === 'estatus') {
            return $query->withCount(['alumnos as activos' => function ($query) {
                $query->where('estatus', 'activo');
            }])
            ->withCount(['alumnos as egresados' => function ($query) {
                $query->where('estatus', 'egresado');
            }])
            ->get()
            ->map(function ($diplomado) {
                return [
                    'id_diplomado' => $diplomado->id_diplomado,
                    'nombre' => $diplomado->nombre,
                    'grupo' => $diplomado->grupo,
                    'activos' => $diplomado->activos,
                    'egresados' => $diplomado->egresados
                ];
            });
        }

        return collect();
    }
}
