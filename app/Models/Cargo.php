<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cargo extends Model
{
    protected $table = 'cargos';
    protected $primaryKey = 'id_cargo';
    public $timestamps = true;

    protected $fillable = [
        'id_alumno','concepto','monto','fecha_limite','estatus','id_recibo',
        'notificaciones_enviadas','notas'
    ];

    protected $casts = [
        'fecha_limite' => 'date',
        'monto' => 'decimal:2',
    ];

    public function alumno()
    {
        return $this->belongsTo(Alumno::class, 'id_alumno', 'id_alumno');
    }

    public function recibo()
    {
        return $this->belongsTo(Recibo::class, 'id_recibo', 'id_recibo');
    }
}