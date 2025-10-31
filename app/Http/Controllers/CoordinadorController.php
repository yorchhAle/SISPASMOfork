<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Coordinador;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class CoordinadorController extends Controller
{
    /*
     * Constructor del controlador. Aplica un middleware que verifica que el usuario autenticado
        tenga el rol de 'Administrador' para permitir la gestión de coordinadores.
    */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (auth()->user()->rol->nombre_rol !== 'Administrador') {
                abort(403, 'Acción no autorizada. No tienes permisos para gestionar coordinadores.');
            }
            return $next($request);
        });
    }


    /*
     * Muestra una lista paginada de todos los Coordinadores. Incluye la información del usuario asociado y 
        los ordena de forma descendente por ID.
    */
    public function index()
    {
        $coordinadores = Coordinador::with('usuario')->orderByDesc('id_coordinador')->paginate(15);
        return view('administrador.CRUDCoordinadores.read', compact('coordinadores'));
    }


    /*
     * Muestra la vista del formulario para crear un nuevo Coordinador.
    */
    public function create()
    {
        return view('administrador.CRUDCoordinadores.create');
    }


    /*
     * Muestra la vista del formulario para editar un Coordinador existente. Carga la relación 'usuario' del 
        modelo Coordinador para mostrar todos los datos.
    */
    public function edit(Coordinador $coordinador)
    {
        $coordinador->load('usuario');
        return view('administrador.CRUDCoordinadores.update', compact('coordinador'));
    }


    /*
     * Actualiza la información de un Coordinador y su Usuario asociado.
    */
    public function update(Request $request, Coordinador $coordinador)
    {
        /* Valida los campos, asegurando la unicidad del usuario y correo, ignorando el registro actual. */
        $data = $request->validate([
            'nombre'       => ['required', 'string', 'max:100'],
            'apellidoP'    => ['required', 'string', 'max:100'],
            'apellidoM'    => ['required', 'string', 'max:100'],
            'fecha_nac'    => ['required', 'date'],
            'usuario'      => ['required', 'string', 'max:50', Rule::unique('usuarios', 'usuario')->ignore($coordinador->usuario->id_usuario, 'id_usuario')],
            'genero'       => ['required', Rule::in(['M', 'F', 'Otro'])],
            'correo'       => ['required', 'email', 'max:100', Rule::unique('usuarios', 'correo')->ignore($coordinador->usuario->id_usuario, 'id_usuario')],
            'telefono'     => ['required', 'string', 'max:20'],
            'direccion'    => ['required', 'string', 'max:100'],
            'fecha_ingreso' => ['required', 'date'],
            'estatus'       => ['required', Rule::in(['activo', 'inactivo'])],
        ]);

        /* Ejecuta una transacción para actualizar los datos tanto en la tabla 'usuarios' como en 'coordinadores'. */
        DB::transaction(function () use ($data, $coordinador) {
            $coordinador->usuario->update([
                'nombre'      => $data['nombre'],
                'apellidoP'   => $data['apellidoP'],
                'apellidoM'   => $data['apellidoM'],
                'fecha_nac'   => $data['fecha_nac'],
                'usuario'     => $data['usuario'],
                'genero'      => $data['genero'],
                'correo'      => $data['correo'],
                'telefono'    => $data['telefono'],
                'direccion'   => $data['direccion'],
            ]);
            $coordinador->update([
                'fecha_ingreso' => $data['fecha_ingreso'],
                'estatus'       => $data['estatus'],
            ]);
        });
        return redirect()->route('coordinadores.index')->with('success', 'Coordinador actualizado exitosamente.');
    }


    /*
     * Almacena un nuevo Coordinador y su Usuario asociado en la base de datos.
    */
    public function store(Request $request)
    {
        /* Valida todos los campos, incluyendo la unicidad del usuario y correo. */
        $data = $request->validate([
            'nombre'       => ['required', 'string', 'max:100'],
            'apellidoP'    => ['required', 'string', 'max:100'],
            'apellidoM'    => ['required', 'string', 'max:100'],
            'fecha_nac'    => ['required', 'date'],
            'usuario'      => ['required', 'string', 'max:50', 'unique:usuarios,usuario'],
            'pass'         => ['required', 'string', 'min:8', 'max:255'],
            'genero'       => ['required', Rule::in(['M', 'F', 'Otro'])],
            'correo'       => ['required', 'email', 'max:100', 'unique:usuarios,correo'],
            'telefono'     => ['required', 'string', 'max:20'],
            'direccion'    => ['required', 'string', 'max:100'],
            'fecha_ingreso' => ['required', 'date'],
            'estatus'       => ['required', Rule::in(['activo', 'inactivo'])],
        ]);

        /* Ejecuta una transacción para garantizar la creación simultánea del registro en 'usuarios' y 'coordinadores'. */
        DB::transaction(function () use ($data) {
            $usuario = User::create([
                'nombre'      => $data['nombre'],
                'apellidoP'   => $data['apellidoP'],
                'apellidoM'   => $data['apellidoM'],
                'fecha_nac'   => $data['fecha_nac'],
                'usuario'     => $data['usuario'],
                'pass'        => Hash::make($data['pass']),
                'genero'      => $data['genero'],
                'correo'      => $data['correo'],
                'telefono'    => $data['telefono'],
                'direccion'   => $data['direccion'],
                /* Asigna el rol de Coordinador (id_rol = 2) al nuevo usuario. */
                'id_rol'      => 2, 
            ]);
            Coordinador::create([
                'id_usuario' => $usuario->id_usuario,
                'fecha_ingreso' => $data['fecha_ingreso'],
                'estatus' => $data['estatus'],
            ]);
        });
        return redirect()->route('coordinadores.index')->with('success', 'Coordinador creado exitosamente.');
    }

    /*
     * Elimina un registro de Coordinador y su Usuario asociado.
    */
    public function destroy(Coordinador $coordinador)
    {
        /* Ejecuta una transacción para asegurar que ambos registros (Coordinador y Usuario) sean eliminados
            de forma conjunta. */
        DB::transaction(function () use ($coordinador) {
            $usuario = $coordinador->usuario; 
            $coordinador->delete();          
            if ($usuario) {
                $usuario->delete();
            }
        });
        return redirect()->route('coordinadores.index')->with('ok', 'Coordinador eliminado.');
    }
}
