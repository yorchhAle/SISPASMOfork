<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Recibo extends Model
{
    protected $table = 'reciboss';             
    protected $primaryKey = 'id_recibo';
    public $timestamps = false;              

    protected $fillable = [
        'id_alumno',
        'fecha_pago',
        'concepto',
        'monto',
        'comprobante_path',  
        'pdf_path',
        'estatus',           
        'fecha_validacion',
        'validado_por',
        'comentarios'
    ];

    protected $casts = [
        'fecha_pago'       => 'date',
        'fecha_validacion' => 'datetime',
        'monto'            => 'decimal:2',
    ];

    public function alumno()
    {
        return $this->belongsTo(Alumno::class, 'id_alumno', 'id_alumno');
    }

    public function validador()
    {
        return $this->belongsTo(User::class, 'validado_por', 'id_usuario');
    }

    protected function comprobanteUrl(): Attribute
    {
        return Attribute::get(function () {
            return $this->comprobante_path; 
        });
    }
}
