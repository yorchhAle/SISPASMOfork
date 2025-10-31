<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class Taller extends Model{
    protected $table = 'extracurricular';
    protected $primaryKey = 'id_extracurricular';
    public $timestamps = false;

    protected $fillable = ['nombre_act','responsable','fecha','tipo','hora_inicio','hora_fin','lugar','modalidad','estatus','capacidad',
    'descripcion','material','url'];

    protected $casts = [
    'fecha' => 'date', // Formatear como fecha
];

public function alumnos()
    {
        return $this->belongsToMany(
            Alumno::class,
            'inscripcion_extracurricular', // Nombre de la tabla pivote
            'id_extracurricular',         // Llave foránea de este modelo
            'id_alumno'                  // Llave foránea del otro modelo
        );
    }
}

