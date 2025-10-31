<?php

namespace App\Http\Controllers;

use App\Models\Taller as Extracurricular;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InscripcionController extends Controller
{
    /*
     * Muestra las actividades extracurriculares disponibles y las inscripciones del Alumno autenticado.
    */
    public function index()
    {
        $alumno = Auth::user()->alumno;

        /* Obtiene las actividades a las que el alumno ya está inscrito. */
        $inscripcionesIds = $alumno->extracurriculares()->pluck('extracurricular.id_extracurricular');
        $misInscripciones = $alumno->extracurriculares()->orderBy('fecha', 'asc')->get();

        /* Filtra y muestra solo aquellas actividades con estatus 'Convocatoria' y que no han pasado,
            excluyendo las que el alumno ya tiene inscritas. */
        $actividadesDisponibles = Extracurricular::where('estatus', 'Convocatoria')
            ->where('fecha', '>=', now()->toDateString())
            ->whereNotIn('id_extracurricular', $inscripcionesIds)
            ->orderBy('fecha', 'asc')
            ->get();

        return view('extracurriculares.read', [
            'misInscripciones' => $misInscripciones,
            'extracurricularesDisponibles' => $actividadesDisponibles
        ]);
    }
    

    /*
     * Procesa la inscripción del Alumno autenticado a una actividad extracurricular.
    */
    public function store(Request $request, Extracurricular $extracurricular)
    {
        $user = Auth::user();
        $alumno = $user->alumno;

        if (!$alumno) {
            return back()->with('error', 'Tu usuario no tiene un perfil de alumno asociado.');
        }

        /* Valida que la actividad no haya pasado. */
        if ($extracurricular->fecha < now()->toDateString()) {
            return back()->with('error', 'La fecha de esta actividad ya ha pasado. No puedes inscribirte.');
        }

        /* Validad que haya cupo disponible. */
        $inscritos = $extracurricular->alumnos()->count();
        if ($inscritos >= $extracurricular->capacidad) {
            return back()->with('error', 'Lo sentimos, ya no hay cupos disponibles para esta actividad.');
        }

        /* Valida que la actividad esté en periodo de inscripción o convocatoria. */
        if ($extracurricular->estatus !== 'Convocatoria') {
            return back()->with('error', 'Esta actividad no se encuentra en periodo de inscripción.');
        }

        /* Valida que el alumno no esté ya inscrito en la actividad. */
        $yaInscrito = $alumno->extracurriculares()->where('extracurricular.id_extracurricular', $extracurricular->id_extracurricular)->exists();
        if ($yaInscrito) {
            return back()->with('error', 'Ya te encuentras inscrito en esta actividad.');
        }

        $alumno->extracurriculares()->attach($extracurricular->id_extracurricular);

        return back()->with('success', '¡Felicidades! Te has inscrito correctamente a: ' . $extracurricular->nombre_act);
    }


    /*
     * Procesa la cancelación de la inscripción del Alumno a una actividad extracurricular.
    */
    public function destroy(Extracurricular $extracurricular)
    {
        $alumno = Auth::user()->alumno;

        /* Impide la cancelación si la fecha de la actividad ya ha pasado. */
        if ($extracurricular->fecha < now()->toDateString()) {
            return back()->with('error', 'No puedes cancelar la inscripción a una actividad que ya ha ocurrido.');
        }

        /* Elimina la relación entre el alumno y la actividad de la tabla pivote. */
        $alumno->extracurriculares()->detach($extracurricular->id_extracurricular);

        return back()->with('success', 'Tu inscripción a "' . $extracurricular->nombre_act . '" ha sido cancelada.');
    }
}
