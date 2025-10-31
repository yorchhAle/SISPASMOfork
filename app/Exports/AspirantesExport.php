<?php

namespace App\Exports;

use App\Models\Aspirante;
use Illuminate\Contracts\Support\Responsable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;


/*
 * Clase de exportación para generar una lista detallada de Aspirantes y su interés en diplomados. 
    Permite filtrar la colección basándose en el modo de reporte ('total' o 'comparacion') y el tipo de diplomado 
    ('basico', 'intermedio y avanzado', o 'todos').
    Implementa FromCollection, WithHeadings, WithMapping y Responsable.
*/
class AspirantesExport implements FromCollection, WithHeadings, WithMapping, Responsable
{
    /**
     * $modo: 'total' | 'comparacion'
     * $tipo: 'basico' | 'intermedio y avanzado' | 'todos'  (solo para 'total')
     */
    public function __construct(
        private string $modo = 'total',
        private string $tipo = 'todos'
    ) {}

    public string $fileName = 'Aspirantes.xlsx';

    /*
     * Define la colección de datos que se exportará.
        Aplica la lógica de filtrado basada en el `$modo` y `$tipo` definidos en el constructor.
        Carga solo los campos necesarios (nombre, correo, interes).
    */
    public function collection()
    {
        if ($this->modo === 'comparacion') {
            /* Ambos tipos */
            return Aspirante::query()
                ->whereIn('interes', ['basico', 'intermedio y avanzado'])
                ->orderBy('interes')
                ->orderBy('nombre')
                ->get(['nombre', 'correo', 'interes']);
        }

        /* Modo total con filtro */
        if ($this->tipo === 'todos' || $this->tipo === '') {
            return Aspirante::query()
                ->orderBy('interes')
                ->orderBy('nombre')
                ->get(['nombre', 'correo', 'interes']);
        }

        // Filtro específico
        return Aspirante::query()
            ->where('interes', $this->tipo)
            ->orderBy('nombre')
            ->get(['nombre', 'correo', 'interes']);
    }


    /*
     * Define los encabezados de las columnas del archivo Excel.
    */
    public function headings(): array
    {
        return ['Nombre', 'Correo', 'Diplomado de interés'];
    }


    /*
     * Mapea cada objeto Aspirante de la colección a una fila del archivo Excel.
        Se encarga de traducir el valor del campo `interes` (ej. 'basico') a una etiqueta más legible para el reporte.
    */
    public function map($row): array
    {
        $labels = [
            'basico' => 'Diplomado nivel básico',
            'intermedio y avanzado' => 'Diplomado intermedio y avanzado',
        ];
        $interes = $labels[$row->interes] ?? $row->interes;

        return [
            $row->nombre,
            $row->correo,
            $interes,
        ];
    }
}
