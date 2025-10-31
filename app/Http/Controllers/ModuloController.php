<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Modulo;


class ModuloController extends Controller
{
    /*
     * Muestra una lista paginada de todos los Módulos. Los módulos se ordenan por su número consecutivo 
        (`numero_modulo`) de forma ascendente.
    */
    public function index()
    {
        $modulos = Modulo::orderBy('numero_modulo')  
            ->paginate(10);                         
        return view('CRUDModulo.read', compact('modulos'));
    }


    /*
     * Muestra la vista del formulario para crear un nuevo Módulo.
    */
    public function create()
    {
        return view('CRUDModulo.create');
    }


    /*
     * Almacena un nuevo Módulo en la base de datos.
    */
    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre_modulo' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'duracion' => 'required|string',
            'estatus' => ['required', Rule::in(['activa', 'concluida'])],
            'url' => 'nullable|url|max:200',
        ]);

        /* Calcula automáticamente el `numero_modulo` como el siguiente consecutivo al más alto existente. */
        $ultimoNumero = Modulo::max('numero_modulo') ?? 0;
        $data['numero_modulo'] = $ultimoNumero + 1;

        Modulo::create($data);

        return redirect()->route('modulos.index')->with('success', 'Módulo creado exitosamente.');
    }


    /*
     * Muestra la vista del formulario para editar un Módulo existente. Utiliza `findOrFail` para obtener la 
        instancia del módulo.
    */
    public function edit($id)
    {
        $modulo = Modulo::findOrFail($id);
        return view('CRUDModulo.update', compact('modulo'));
    }


    /*
     * Actualiza la información de un Módulo existente.
    */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre_modulo' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'duracion' => 'required|string',
            'estatus' => ['required', Rule::in(['activa', 'concluida'])],
            'url' => 'required|String',
        ]);

        /* Utiliza `findOrFail` para obtener el módulo y luego aplica la actualización. */
        $modulo = Modulo::findOrFail($id);
        $modulo->update($request->all());

        return redirect()->route('modulos.index')->with('success', 'Módulo actualizado exitosamente.');
    }


    /*
     * Elimina un Módulo de la base de datos.
    */
    public function destroy($id)
    {
        /* Utiliza `findOrFail` para obtener el módulo y luego lo elimina. */
        $modulo = Modulo::findOrFail($id);
        $modulo->delete();

        return redirect()->route('modulos.index')->with('success', 'Módulo eliminado exitosamente.');
    }
}
