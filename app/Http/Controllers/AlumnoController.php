<?php

namespace App\Http\Controllers;

use App\Models\Alumno;
use App\Models\User;
use App\Models\Diplomado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AlumnoController extends Controller
{
    /*
     * Muestra una lista paginada de todos los Alumnos. Carga las relaciones de 'usuario' y 'diplomado' 
     * para cada alumno y ordena la lista de forma descendente por ID.
     *
    */
    public function index()
    {
        $alumnos = Alumno::with(['usuario', 'diplomado'])->orderByDesc('id_alumno')->paginate(15);
        return view('administrador.CRUDAlumnos.read', compact('alumnos'));
    }


    /*
     * Muestra la vista del formulario para crear un nuevo Alumno. Obtiene todos los diplomados disponibles 
     * para la selección en el formulario.
    */
    public function create()
    {
        $diplomados = Diplomado::all();
        return view('administrador.CRUDalumnos.create', compact('diplomados'));
    }


    /*
     * Almacena un nuevo Alumno y su Usuario asociado en la base de datos.
    */
    public function store(Request $request)
    {
        /* Valida todos los datos, incluyendo la unicidad de matrícula, usuario y correo. */
        $data = $request->validate([
            'nombre'       => ['required','string','max:100'],
            'apellidoP'    => ['required','string','max:100'],
            'apellidoM'    => ['required','string','max:100'],
            'fecha_nac'    => ['required','date'],
            'usuario'      => ['required','string','max:50','unique:usuarios,usuario'],
            'pass'         => ['required','string','min:8','confirmed'],
            'genero'       => ['required', Rule::in(['M','F','Otro'])],
            'correo'       => ['required','email','max:100','unique:usuarios,correo'],
            'telefono'     => ['required','string','max:20'],
            'direccion'    => ['required','string','max:100'],
            'id_rol'       => ['required','integer'], 

            'matriculaA'   => ['required','string','max:20','unique:alumnos,matriculaA'],
            'id_diplomado' => ['required','integer','exists:diplomados,id_diplomado'],
            'estatus'      => ['required', Rule::in(['activo','baja','egresado'])],
        ]);

        /* Ejecuta una transacción para garantizar la creación simultánea del registro en 'usuarios' y 'alumnos'.*/
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
                'id_rol'      => $data['id_rol'],
            ]);

            Alumno::create([
                'id_usuario'    => $usuario->id_usuario,
                'matriculaA'    => $data['matriculaA'],
                'id_diplomado'  => $data['id_diplomado'],
                'estatus'       => $data['estatus'],
            ]);
        });

        return redirect()->route('alumnos.index')->with('ok', 'Alumno creado correctamente.');
    }


    /*
     * Muestra la vista del formulario para editar un Alumno existente. Carga las relaciones 'usuario' y 'diplomado' 
     * del Alumno y pasa todos los diplomados al formulario para permitir la edición.
    */
    public function edit(Alumno $alumno)
    {
        $alumno->load('usuario', 'diplomado');
        $diplomados = Diplomado::all();
        return view('administrador.CRUDalumnos.update', compact('alumno', 'diplomados'));
    }


    /*
     * Actualiza la información de un Alumno y su Usuario asociado.
    */
    public function update(Request $request, Alumno $alumno)
    {
        $alumno->load('usuario');
        $u = $alumno->usuario;

        /* Valida los campos, asegurando la unicidad de datos sensibles (matrícula, usuario, correo) ignorando el registro actual. */
        $data = $request->validate([
            'nombre'  => ['required','string','max:100'],
            'apellidoP' => ['required','string','max:100'],
            'apellidoM' => ['required','string','max:100'],
            'fecha_nac'    => ['required','date'],
            'usuario'      => [
                'required','string','max:50',
                Rule::unique('usuarios','usuario')->ignore($u->id_usuario, 'id_usuario'),
            ],
            'pass'         => ['nullable','string','min:8','confirmed'],
            'genero'       => ['required', Rule::in(['M','F','Otro'])],
            'correo'       => [
                'required','email','max:100',
                Rule::unique('usuarios','correo')->ignore($u->id_usuario, 'id_usuario'),
            ],
            'telefono'     => ['required','string','max:20'],
            'direccion'    => ['required','string','max:100'],
            'id_rol'       => ['required','integer'],

            'matriculaA'   => [
                'required','string','max:20',
                Rule::unique('alumnos','matriculaA')->ignore($alumno->id_alumno, 'id_alumno'),
            ],
            'id_diplomado' => ['required','integer','exists:diplomados,id_diplomado'],
            'estatus'      => ['required', Rule::in(['activo','baja','egresado'])],
        ]);

        /* Ejecuta una transacción para actualizar los datos en 'usuarios' y 'alumnos'. */
        DB::transaction(function () use ($data, $u, $alumno) {
            $u->fill([
                'nombre'    => $data['nombre'],
                'apellidoP' => $data['apellidoP'],
                'apellidoM' => $data['apellidoM'],
                'fecha_nac' => $data['fecha_nac'],
                'usuario'   => $data['usuario'],
                'genero'    => $data['genero'],
                'correo'    => $data['correo'],
                'telefono'  => $data['telefono'],
                'direccion' => $data['direccion'],
                'id_rol'    => $data['id_rol'],
            ]);

            /* Actualiza la contraseña solo si se proporciona un nuevo valor. */
            if (!empty($data['pass'])) {
                $u->pass = Hash::make($data['pass']);
            }
            $u->save();

            $alumno->update([
                'matriculaA'    => $data['matriculaA'],
                'id_diplomado'  => $data['id_diplomado'],
                'estatus'       => $data['estatus'],
            ]);
        });

        return redirect()->route('alumnos.index')->with('ok', 'Alumno actualizado.');
    }


    /*
     * Elimina un registro de Alumno y su Usuario asociado. Ejecuta una transacción para garantizar que 
     * ambos registros (Alumno y Usuario) sean eliminados de forma conjunta.
    */
    public function destroy(Alumno $alumno)
    {
        DB::transaction(function () use ($alumno) {
            $usuario = $alumno->usuario;
            $alumno->delete();
            if ($usuario) {
                $usuario->delete();
            }
        });
        return redirect()->route('alumnos.index')->with('ok', 'Alumno eliminado.');
    }
}