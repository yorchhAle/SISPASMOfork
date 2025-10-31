<?php

namespace App\Http\Controllers;

use App\Models\Docente;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Symfony\Contracts\Service\Attribute\Required;

class DocenteController extends Controller
{
    /*
     * Muestra una lista paginada de todos los Docentes. Carga la relación 'usuario' para cada docente y 
        los ordena de forma descendente por ID.
    */
    public function index()
    {
        $docentes = Docente::with('usuario')->orderByDesc('id_docente')->paginate(15);

        return view('administrador.CRUDDocentes.read', compact('docentes'));
    }


    /*
     * Muestra la vista del formulario para crear un nuevo Docente.
    */
    public function create()
    {
        return view('administrador.CRUDDocentes.create');
    }


    /*
     * Almacena un nuevo Docente y su Usuario asociado en la base de datos.
    */
    public function store(Request $request)
    {
        /* Valida todos los campos, incluyendo la unicidad de matrícula, usuario y correo. */
        $data = $request->validate([
            'nombre'       => ['required', 'string', 'max:100'],
            'apellidoP'    => ['required', 'string', 'max:100'],
            'apellidoM'    => ['required', 'string', 'max:100'],
            'fecha_nac'    => ['required', 'date'],
            'usuario'      => ['required', 'string', 'max:50', 'unique:usuarios,usuario'],
            'pass'         => ['required', 'string', 'min:8', 'confirmed'],
            'genero'       => ['required', Rule::in(['M', 'F', 'Otro'])],
            'correo'       => ['required', 'email', 'max:100', 'unique:usuarios,correo'],
            'telefono'     => ['required', 'string', 'max:20'],
            'direccion'    => ['required', 'string', 'max:100'],
            'matriculaD'   => ['required', 'string', 'max:20', 'unique:docentes,matriculaD'],
            'especialidad' => ['required', 'string', 'max:100'],
            'cedula'        => ['required', 'string', 'max:100'],
            'salario' => ['required', 'decimal:0,2']
        ]);

        /* Ejecuta una transacción para garantizar la creación simultánea del registro en 'usuarios' y 'docentes', 
            asignando el rol de Docente (ID 3). */
        $id_rol_docente = 3;

        DB::transaction(function () use ($data, $id_rol_docente) {
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
                'id_rol' => $id_rol_docente,
            ]);
            Docente::create([
                'matriculaD'   => $data['matriculaD'],
                'especialidad' => $data['especialidad'],
                'cedula'       => $data['cedula'],
                'salario'      => $data['salario'],
                'id_usuario'   => $usuario->id_usuario,
            ]);
        });
        return redirect()->route('docentes.index')->with('success', 'Docente creado exitosamente.');
    }


    /*
     * Muestra la vista del formulario para editar un Docente existente. Carga la relación 'usuario' del modelo 
        Docente para mostrar todos los datos.
    */
    public function edit(Docente $docente)
    {
        $docente->load('usuario');

        return view('administrador.CRUDDocentes.update', compact('docente'));
    }


    /*
     * Actualiza la información de un Docente y su Usuario asociado.
    */
    public function update(Request $request, Docente $docente)
    {
        /* Valida los campos, asegurando la unicidad de datos sensibles (matrícula, usuario, correo)
            ignorando el registro actual. */
        $data = $request->validate([
            'nombre'       => ['required', 'string', 'max:100'],
            'apellidoP'    => ['required', 'string', 'max:100'],
            'apellidoM'    => ['required', 'string', 'max:100'],
            'fecha_nac'    => ['required', 'date'],
            'usuario'      => ['required', 'string', 'max:50', 'unique:usuarios,usuario,' . $docente->usuario->id_usuario . ',id_usuario'],
            'pass'         => ['nullable', 'string', 'min:8', 'confirmed'],
            'genero'       => ['required', Rule::in(['M', 'F', 'Otro'])],
            'correo'       => ['required', 'email', 'max:100', 'unique:usuarios,correo,' . $docente->usuario->id_usuario . ',id_usuario'],
            'telefono'     => ['required', 'string', 'max:20'],
            'direccion'    => ['required', 'string', 'max:100'],
            'matriculaD'   => ['required', 'string', 'max:20', 'unique:docentes,matriculaD,' . $docente->id_docente . ',id_docente'],
            'especialidad' => ['required', 'string', 'max:100'],
            'cedula'        => ['required', 'string', 'max:100'],
            'salario' => ['required', 'decimal:0,2']
        ]);

        $id_rol_docente = 3;

        /* Ejecuta una transacción para actualizar los datos en 'usuarios' y 'docentes'. */
        DB::transaction(function () use ($data, $docente, $id_rol_docente) {
            $usuarioData = [
                'nombre'      => $data['nombre'],
                'apellidoP'   => $data['apellidoP'],
                'apellidoM'   => $data['apellidoM'],
                'fecha_nac'   => $data['fecha_nac'],
                'usuario'     => $data['usuario'],
                'genero'      => $data['genero'],
                'correo'      => $data['correo'],
                'telefono'    => $data['telefono'],
                'direccion'   => $data['direccion'],
                'id_rol' => $id_rol_docente, // Uso de la variable capturada
            ];

            /* Actualiza la contraseña solo si se proporciona un nuevo valor. */
            if (!empty($data['pass'])) {
                $usuarioData['pass'] = Hash::make($data['pass']);
            }
            $docente->usuario->update($usuarioData);
            $docente->update([
                'matriculaD'   => $data['matriculaD'],
                'especialidad' => $data['especialidad'],
                'cedula'       => $data['cedula'],
                'salario'      => $data['salario'],
            ]);
        });

        return redirect()->route('docentes.index')->with('ok', 'Docente actualizado correctamente.');
    }


    /*
     * Elimina un registro de Docente y su Usuario asociado. Ejecuta una transacción para garantizar que ambos registros (Docente y Usuario)
        sean eliminados de forma conjunta.
    */
    public function destroy(Docente $docente)
    {
        DB::transaction(function () use ($docente) {
            $usuario = $docente->usuario;
            $docente->delete();

            if ($usuario) {
                $usuario->delete();
            }
        });

        return redirect()->route('docentes.index')->with('ok', 'docente eliminado.');
    }


    /*
     * Muestra el dashboard o panel principal del Docente. Busca la información del docente autenticado 
        para cargar la vista.
    */
    public function dashboard()
    {
        $userId = auth()->user()->id_usuario;
        $docente = Docente::where('id_usuario', $userId)->with('usuario')->firstOrFail();

        return view('docente.dashboarddocente', compact('docente'));
    }
}
