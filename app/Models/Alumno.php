<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;


class Alumno extends Authenticatable implements CanResetPassword
{
    use HasFactory, Notifiable;

    protected $primaryKey = 'id_alumno';
    public $timestamps = false;

    protected $fillable = ['id_usuario', 'matriculaA', 'id_diplomado', 'estatus'];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario', 'id_usuario');
    }

    public function diplomado()
    {
        return $this->belongsTo(Diplomado::class, 'id_diplomado', 'id_diplomado');
    }

    public function calificaciones()
    {
        return $this->hasMany(Calificacion::class, 'id_alumno', 'id_alumno');
    }
    // --- FIN DE LA SOLUCIÓN ---

    public function fichaMedica()
    {
        return $this->hasOne(FichaMedica::class, 'id_alumno', 'id_alumno');
    }

    public function recibos()
    {
        return $this->hasMany(Recibo::class, 'id_alumno', 'id_alumno');
    }

    public function getEmailForPasswordReset()
    {
        return $this->usuario?->correo;
    }

    public function extracurriculares()
    {
        return $this->belongsToMany(
            Taller::class,
            'inscripcion_extracurricular', // Nombre de la tabla pivote
            'id_alumno',                  // Llave foránea de este modelo en la tabla pivote
            'id_extracurricular'          // Llave foránea del otro modelo en la tabla pivote
        );
    }
}