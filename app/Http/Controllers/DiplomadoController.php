<?php

namespace App\Http\Controllers;

use App\Models\Diplomado;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class DiplomadoController extends Controller
{
    /*
     * Muestra una lista paginada de todos los Diplomados. Ordena los diplomados de forma descendente por ID.
    */
    public function index()
    {
        $diplomados = Diplomado::orderByDesc('id_diplomado')->paginate(15);
        return view('administrador.CRUDDiplomados.read', compact('diplomados'));
    }


    /*
     * Muestra la vista del formulario para crear un nuevo Diplomado.
    */
    public function create()
    {
        return view('administrador.CRUDDiplomados.create');
    }


    /*
     * Almacena un nuevo Diplomado en la base de datos.
     */
    public function store(Request $request)
    {
        $tiposPermitidos = ['basico', 'intermedio y avanzado'];

        /* Valida todos los campos, asegurando que la fecha de fin sea posterior a la de inicio 
           y que el tipo esté dentro de las opciones permitidas. */
        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:100'],
            'grupo' => ['required', 'string', 'max:50'],
            'tipo' => ['required', 'string', Rule::in($tiposPermitidos)],
            'capacidad' => ['required', 'integer', 'min:1', 'max:1000'],
            'fecha_inicio' => ['required', 'date'],
            'fecha_fin' => ['required', 'date', 'after:fecha_inicio'],
        ]);

        /* Ejecuta la creación dentro de una transacción de base de datos. */
        DB::transaction(function () use ($data) {
            Diplomado::create($data);
        });

        return redirect()->route('admin.diplomados.index')->with('ok', 'Diplomado creado correctamente.');
    }


    /* 
     * Muestra la vista del formulario para editar un Diplomado existente.
    */
    public function edit(Diplomado $diplomado)
    {
        return view('administrador.CRUDDiplomados.update', compact('diplomado'));
    }


    /*
     * Actualiza la información de un Diplomado existente.
    */
    public function update(Request $request, Diplomado $diplomado)
    {
        $tiposPermitidos = ['basico', 'intermedio y avanzado']; 

        /* Valida todos los campos, con las mismas restricciones que en el método `store`. */
        $data = $request->validate([
            'nombre'       => ['required', 'string', 'max:100'],
            'grupo'        => ['required', 'string', 'max:50'],
            'tipo'         => ['required', 'string', Rule::in($tiposPermitidos)],
            'capacidad'    => ['required', 'integer', 'min:1', 'max:1000'],
            'fecha_inicio' => ['required', 'date'],
            'fecha_fin'    => ['required', 'date', 'after:fecha_inicio'],
        ]);

        /* Ejecuta la actualización dentro de una transacción. */
        DB::transaction(function () use ($data, $diplomado) {
            $diplomado->update($data);
        });

        return redirect()->route('admin.diplomados.index')->with('ok', 'Diplomado actualizado correctamente.');
    }


    /*
     * Elimina un Diplomado de la base de datos. Ejecuta la eliminación dentro de una transacción.
    */
    public function destroy(Diplomado $diplomado)
    {
        DB::transaction(function () use ($diplomado) {
            $diplomado->delete();
        });

        return redirect()->route('admin.diplomados.index')->with('ok', 'Diplomado eliminado correctamente.');
    }
}