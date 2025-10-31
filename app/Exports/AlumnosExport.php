<?php

namespace App\Exports;

use App\Models\Alumno;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

/*
 * Clase de exportación para generar una lista simple de Alumnos en formato Excel. Esta clase toma una colección 
    existente de modelos Alumno y la mapea a una hoja de cálculo con encabezados básicos como Nombre y Matrícula.
    Implementa FromCollection y WithHeadings para definir los datos y sus títulos.
*/
class AlumnosExport implements FromCollection, WithHeadings
{
    protected $alumnos;

    /*
     * Constructor de la clase. Inicializa la clase con la colección de modelos Alumno que se desea exportar.
    */
    public function __construct(Collection $alumnos)
    {
        $this->alumnos = $alumnos;
    }


    /*
     * Define la colección de datos que se exportará. Mapea cada objeto Alumno para incluir solo los campos 
        necesarios (Nombre, Apellidos, Matrícula).
    */
    public function collection()
    {
        return $this->alumnos->map(function ($alumno) {
            return [
                'Nombre' => $alumno->nombre,
                'Apellido Paterno' => $alumno->apellidoP,
                'Apellido Materno' => $alumno->apellidoM,
                'Matricula' => $alumno->matriculaA,
            ];
        });
    }

    /*
     * Define los encabezados de las columnas del archivo Excel.
    */
    public function headings(): array
    {
        return [
            'Nombre',
            'Apellido Paterno',
            'Apellido Materno',
            'Matrícula',
        ];
    }
}