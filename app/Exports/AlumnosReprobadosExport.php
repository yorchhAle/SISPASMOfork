<?php

namespace App\Exports;

use App\Models\Alumno;
use App\Models\Diplomado;
use App\Models\Modulo;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

/*
 * Clase de exportación para generar un listado detallado de Alumnos que han reprobado en módulos de un Diplomado 
    específico (calificación menor a 80).
    La colección se aplana para que cada fila represente una Calificación reprobatoria, no un alumno, permitiendo 
    listar todas las materias reprobadas por persona.
    Implementa FromCollection, WithHeadings y WithMapping.
*/
class AlumnosReprobadosExport implements FromCollection, WithHeadings, WithMapping
{
    protected $idDiplomado;

    public function __construct(int $idDiplomado, bool $byDiplomado = false)
    {
        $this->idDiplomado = $idDiplomado;
        /* La bandera $byDiplomado se usa para indicar que se debe exportar por Diplomado, no por Módulo. */
        if (!$byDiplomado) {
            throw new \Exception('Este exportador debe ser llamado con el ID de Diplomado.');
        }
    }


    /*
     * Define la colección de datos que se exportará.
    */
    public function collection()
    {
        $diplomado = Diplomado::find($this->idDiplomado);

        if (!$diplomado) {
            /* Retorna una colección vacía si no encuentra el diplomado */
            return collect(); 
        }

        /* Obtener los IDs de todos los módulos asociados a este diplomado. */
        $modulosIds = $diplomado->horarios->pluck('id_modulo')->unique();

        /* Buscar a los alumnos que reprobaron (< 80) en CUALQUIER módulo de este diplomado y cargamos la relación de 
            Calificaciones filtrada por esos módulos */
        return Alumno::whereHas('calificaciones', function ($query) use ($modulosIds) {
            $query->whereIn('id_modulo', $modulosIds)->where('calificacion', '<', 80);
        })
        ->with(['usuario', 'diplomado', 'calificaciones' => function ($query) use ($modulosIds) {
            /* Cargar todas las calificaciones reprobadas de ESTOS módulos para el mapeo. */
            $query->whereIn('id_modulo', $modulosIds)->where('calificacion', '<', 80);
        }, 'calificaciones.modulo']) // Cargamos también el Módulo asociado a cada Calificación
        ->get()
        /* Usamos flatMap para aplanar el resultado, ya que un alumno puede tener varias reprobadas */
        ->flatMap(function ($alumno) {
            return $alumno->calificaciones->map(function ($calif) use ($alumno) {
                /* Devolvemos una nueva estructura por cada calificación reprobada */
                return (object)[
                    'alumno' => $alumno,
                    'calificacion' => $calif
                ];
            });
        });
    }


    /*
     * Define los encabezados de las columnas del archivo Excel.
    */
    public function headings(): array
    {
        return [
            'Nombre Completo',
            'Matrícula',
            'Diplomado',
            'Módulo Reprobado',
            'Calificación Obtenida'
        ];
    }


    /*
     * Mapea el objeto aplanado ($item) a una fila del archivo Excel. 
        Extrae los datos del alumno, el nombre del diplomado, el nombre del módulo reprobado y la calificación.
    */
    public function map($item): array
    {
        /* $item es la nueva estructura (alumno, calificacion) creada en collection() */
        $alumno = $item->alumno;
        $calificacion = $item->calificacion;
        
        $moduloNombre = $calificacion->modulo ? $calificacion->modulo->nombre_modulo : 'N/A';
        
        return [
            $alumno->usuario->nombre . ' ' . $alumno->usuario->apellidoP . ' ' . $alumno->usuario->apellidoM,
            $alumno->matriculaA,
            $alumno->diplomado->nombre,
            $moduloNombre,
            $calificacion->calificacion,
        ];
    }
}