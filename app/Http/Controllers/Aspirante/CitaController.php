<?php

namespace App\Http\Controllers\Aspirante;

use App\Http\Controllers\Controller;
use App\Models\Cita;
use App\Models\Aspirante;
use App\Models\Coordinador;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class CitaController extends Controller
{
    /*
     * Muestra la lista de citas agendadas por el aspirante autenticado.
     * Obtiene el ID del aspirante a partir del usuario logueado y recupera las citas paginadas, ordenadas por fecha y hora descendente.
    */
    public function index()
    {
        $aspiranteId = Aspirante::where('id_usuario', auth()->id())->value('id_aspirante');

        $citas = Cita::where('id_aspirante', $aspiranteId)
            ->orderByDesc('fecha_cita')
            ->orderByDesc('hora_cita')
            ->paginate(10);

        return view('aspirante.citas.index', compact('citas'));
    }


    /*
     * Muestra el formulario para agendar una nueva cita.
    */
    public function create()
    {
        return view('aspirante.citas.create');
    }


    /**
     * Almacena una nueva cita en la base de datos.
    */
    public function store(Request $request)
    {
        $aspiranteId = Aspirante::where('id_usuario', auth()->id())->value('id_aspirante');
        if (!$aspiranteId) {
            return back()->withErrors(['general' => 'No se encontró tu perfil de aspirante.'])->withInput();
        }   
        
        /* Valida la fecha (futura) y la hora.*/
        $data = $request->validate([
            'fecha_cita' => ['required', 'date', 'after_or_equal:today'],
            'hora_cita'  => ['required', 'date_format:H:i'],
        ]);

        /*Verifica que el aspirante no tenga citas activas (Pendiente o Aprobada).*/
        $tieneActiva = Cita::where('id_aspirante', $aspiranteId)
            ->whereIn('estatus', ['Pendiente','Aprobada'])
            ->exists();

        if ($tieneActiva) {
            return back()->withErrors(['general' => 'Ya tienes una cita activa. Cancélala o espera a que concluya para agendar otra.'])->withInput();
        }

        /*Verifica que la fecha/hora seleccionada no esté ya ocupada.*/
        $ocupada = Cita::whereDate('fecha_cita', $data['fecha_cita'])
            ->where('hora_cita', $data['hora_cita'])
            ->whereIn('estatus', ['Pendiente','Aprobada'])
            ->exists();

        if ($ocupada) {
            return back()->withErrors(['general' => 'Ese horario ya está ocupado. Elige otro día/hora.'])->withInput();
        }

        /*Asigna la cita al coordinador con el ID más bajo (primer coordinador disponible).*/
        $coordinadorId = Coordinador::min('id_coordinador');
        if (!$coordinadorId) {
            return back()->withErrors(['general' => 'No hay coordinadores registrados para asignar a la cita.'])->withInput();
        }

        Cita::create([
            'fecha_cita'   => $data['fecha_cita'],
            'hora_cita'    => $data['hora_cita'] . ':00',
            'estatus'      => 'Pendiente',
            'lugar'        => 'Facultad de Medicina de la UAEM',
            'id_aspirante' => $aspiranteId,
            'id_coordinador' => $coordinadorId,
        ]);

        return redirect()->route('aspirante.citas.index')->with('ok', 'Cita creada con éxito.');
    }


    /**
     * Cancela una cita específica del aspirante autenticado.
    */
    public function cancel(Cita $cita)
    {
        $aspiranteId = Aspirante::where('id_usuario', auth()->id())->value('id_aspirante');

        /*Verifica que la cita pertenezca al aspirante.*/
        if ($cita->id_aspirante !== $aspiranteId) {
            abort(403);
        }

        /*Solo permite la cancelación si el estatus es 'Pendiente' o 'Aprobada'.*/
        if (in_array($cita->estatus, ['Pendiente','Aprobada'])) {
            /*Actualiza el estatus a 'Cancelada'.*/
            $cita->estatus = 'Cancelada';
            $cita->save();
        }
        return back()->with('ok', 'Cita cancelada.');
    }
}
