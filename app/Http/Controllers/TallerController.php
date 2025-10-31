<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Taller;
use Illuminate\Support\Facades\Notification;
use App\Notifications\InicioActividadSimple;
use App\Models\Alumno;


class TallerController extends Controller
{
    /*
     * Muestra una lista paginada de todas las actividades Extracurriculares (Talleres/Prácticas). Ordena las 
        actividades de forma descendente por ID.
    */
    public function index()
    {
        $talleres = Taller::orderByDesc('id_extracurricular')->paginate(15);
        return view('CRUDTaller.read', compact('talleres'));
    }


    /*
     * Muestra la vista del formulario para crear una nueva actividad Extracurricular.
    */
    public function create()
    {
        return view('CRUDTaller.create');
    }


    /*
     * Almacena una nueva actividad Extracurricular en la base de datos y notifica a los alumnos.
    */
    public function store(Request $request)
    {
        /*  Valida todos los campos, incluyendo la hora de fin debe ser posterior a la de inicio. */
        $request->validate([
            'nombre_act'   => 'required|string|max:255',
            'responsable'  => 'required|string|max:255',
            'fecha'        => 'required|date',
            'tipo'         => ['required', Rule::in(['Taller', 'Practica'])],
            'hora_inicio'  => 'required|date_format:H:i',
            'hora_fin'     => 'required|date_format:H:i|after:hora_inicio',
            'lugar'        => 'required|string|max:255',
            'modalidad'    => ['required', Rule::in(['Presencial', 'Virtual'])],
            'estatus'      => ['required', Rule::in(['Finalizada', 'Convocatoria', 'En proceso'])],
            'capacidad'    => 'required|integer|min:1',
            'descripcion'  => 'nullable|string',
            'material'     => 'nullable|string',
            'url'          => 'nullable|url',
        ]);

        /* Crea el registro del Taller. */
        $taller = Taller::create($request->all());

        /* Llama al método privado para enviar una notificación masiva a todos los alumnos activos. */
        $this->notificarATodosLosAlumnos($taller);

        return redirect()->route('extracurricular.index')->with('success', 'Taller creado y notificado exitosamente.');
    }

    /*
     * Muestra la vista del formulario para editar una actividad Extracurricular existente.
    */
    public function edit($id)
    {
        $taller = Taller::findOrFail($id);
        return view('CRUDTaller.update', compact('taller'));
    }

    /*
     * Actualiza la información de una actividad Extracurricular existente.
    */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre_act' => 'required|string|max:255',
            'responsable' => 'required|string|max:255',
            'fecha' => 'required|date',
            'tipo' => ['required', Rule::in(['Taller', 'Practica'])],
            'hora_inicio' => 'required|date_format:H:i',
            'hora_fin' => 'required|date_format:H:i|after:hora_inicio',
            'lugar' => 'required|string|max:255',
            'modalidad' => ['required', Rule::in(['Presencial', 'Virtual'])],
            'estatus' => ['required', Rule::in(['Finalizada', 'Convocatoria', 'En proceso'])],
            'capacidad' => 'required|integer|min:1',
            'descripcion' => 'nullable|string',
            'material' => 'nullable|string',
            'url' => 'nullable|url',
        ]);

        /* Utiliza `findOrFail` para obtener la actividad y luego aplica la actualización. */
        $taller = Taller::findOrFail($id);
        $taller->update($request->all());
        return redirect()->route('extracurricular.index')->with('success', 'Taller actualizado exitosamente.');
    }

    /*
     * Elimina una actividad Extracurricular de la base de datos.
    */
    public function destroy($id)
    {
        $taller = Taller::findOrFail($id);
        $taller->delete();
        return redirect()->route('extracurricular.index')->with('success', 'Taller eliminado exitosamente.');
    }


    /*
     * Envía una notificación a todos los alumnos con estatus 'activo' sobre la nueva actividad.
    */
    private function notificarATodosLosAlumnos(Taller $taller): void
    {
        $lugar = $taller->modalidad === 'Virtual'
            ? ($taller->url ?: 'Enlace por confirmar')
            : ($taller->lugar ?: 'Por confirmar');

        $fecha = $taller->fecha instanceof \Carbon\Carbon
            ? $taller->fecha->format('Y-m-d')
            : (string) $taller->fecha;

        $noti = new InicioActividadSimple(
            nombreActividad: $taller->nombre_act,
            fecha: $fecha,
            hora: $taller->hora_inicio,
            lugar: $lugar,
            docente: $taller->responsable,
            instrucciones: $taller->material ?: $taller->descripcion,
            urlDetalle: $taller->url ?: null
        );

        \App\Models\Alumno::where('estatus', 'activo')
            ->with('usuario:id_usuario,nombre,correo') 
            ->select('id_alumno','id_usuario')    
            ->chunkById(500, function ($chunk) use ($noti) {
                $usuarios = $chunk->pluck('usuario')->filter()->unique('id_usuario');
                if ($usuarios->isNotEmpty()) {
                    \Illuminate\Support\Facades\Notification::send($usuarios, $noti);
                }
            }, 'id_alumno');
    }
}
