<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Models\Aspirante;
use Illuminate\Support\Str;
use App\Notifications\CredencialesAspirante;
use App\Models\Alumno;
use App\Models\Diplomado;
use Illuminate\Contracts\Queue\ShouldQueue;

class AspiranteController extends Controller
{
    /*
     * Muestra una lista paginada de todos los Aspirantes. Carga la relación 'usuario' para cada aspirante y 
        los ordena de forma descendente por ID.
    */
    public function index()
    {
        $aspirantes = Aspirante::with('usuario')->orderByDesc('id_aspirante')->paginate(15);
        return view('administrador.CRUDAspirantes.read', compact('aspirantes'));
    }


    /*
     * Muestra la vista del formulario para crear un nuevo Aspirante (manualmente por el Admin).
     *
    */
    public function create()
    {
        return view('administrador.CRUDAspirantes.create');
    }


    /*
     * Muestra la vista del formulario para editar un Aspirante existente. Carga la relación 'usuario' del 
        modelo Aspirante para mostrar todos los datos.
    */
    public function edit(Aspirante $aspirante)
    {
        $aspirante->load('usuario');
        return view('administrador.CRUDAspirantes.update', compact('aspirante'));
    }


    /*
     * Actualiza la información de un Aspirante y, si es aceptado, lo convierte en Alumno.
    */
    public function update(Request $request, Aspirante $aspirante)
    {
        $estatusAnterior = $aspirante->estatus;

        $rules = [
            'nombre' => ['required', 'string', 'max:100'],
            'apellidoP' => ['required', 'string', 'max:100'],
            'apellidoM' => ['required', 'string', 'max:100'],
            'fecha_nac' => ['required', 'date'],
            'usuario' => [
                'required_unless:estatus,aceptado', 'string', 'max:50',
                Rule::unique('usuarios', 'usuario')->ignore($aspirante->usuario->id_usuario, 'id_usuario')
            ],
            'genero' => ['required', Rule::in(['M', 'F', 'Otro'])],
            'correo' => [
                'required','email', 'max:100',
                Rule::unique('usuarios', 'correo')->ignore($aspirante->usuario->id_usuario, 'id_usuario')
            ],
            'telefono'  => ['required', 'string', 'max:20'],
            'direccion' => ['required', 'string', 'max:100'],
            'interes' => ['required', 'string', 'max:50'], 
            'dia' => ['required', 'date'],
            'estatus' => ['required', Rule::in(['activo', 'rechazado', 'aceptado'])],
            'id_diplomado' => ['nullable', 'integer', 'exists:diplomados,id_diplomado'], 
        ];

        $messages = [
            'usuario.required_unless' => 'El campo usuario es obligatorio salvo cuando aceptas al aspirante.',
        ];

        $data = $request->validate($rules, $messages);
        $vaASerAceptado = ($estatusAnterior !== 'aceptado' && $data['estatus'] === 'aceptado');

        $idDiplomado = $data['id_diplomado'] ?? null;
        if ($vaASerAceptado && !$idDiplomado) {
            $idDiplomado = Diplomado::where('nombre', $data['interes'])->value('id_diplomado')
                ?? Diplomado::where('nombre', 'LIKE', $data['interes'] . '%')->value('id_diplomado');
            if (!$idDiplomado) {
                return back()
                    ->withErrors(['interes' => 'No se encontró un diplomado con ese nombre. Selecciona uno válido o envía id_diplomado.'])
                    ->withInput();
            }
        }

        DB::transaction(function () use ($data, $aspirante, $vaASerAceptado, $idDiplomado) {

            $aspirante->usuario->update([
                'nombre'    => $data['nombre'],
                'apellidoP' => $data['apellidoP'],
                'apellidoM' => $data['apellidoM'],
                'fecha_nac' => $data['fecha_nac'],
                'usuario'   => $vaASerAceptado
                    ? $aspirante->usuario->usuario 
                    : $data['usuario'],
                'genero'    => $data['genero'],
                'correo'    => $data['correo'],
                'telefono'  => $data['telefono'],
                'direccion' => $data['direccion'],
            ]);

            $aspirante->update([
                'interes' => $data['interes'],
                'dia'     => $data['dia'],
                'estatus' => $data['estatus'],
            ]);


            /* Si el estatus cambia a 'aceptado' (y no lo estaba), genera una matrícula, crea una contraseña temporal,
            cambia el rol a 'Alumno', lo registra en la tabla `alumnos` y notifica al usuario sus credenciales. */
            if ($vaASerAceptado) {
                $matricula = $this->generarMatriculaUnica((int)$idDiplomado);
                $aspirante->usuario->usuario = $matricula;
                $plain = Str::password(10);
                $aspirante->usuario->pass = Hash::make($plain);
                $aspirante->usuario->save(); 

                $yaEsAlumno = Alumno::where('id_usuario', $aspirante->id_usuario)->exists();
                if (!$yaEsAlumno) {
                    Alumno::create([
                        'id_usuario'   => $aspirante->id_usuario,
                        'matriculaA'   => $matricula,              
                        'id_diplomado' => (int)$idDiplomado,
                        'estatus'      => 'activo',
                    ]);
                }

                $ROL_ALUMNO = 4; 
                if ((int)$aspirante->usuario->id_rol !== $ROL_ALUMNO) {
                    $aspirante->usuario->id_rol = $ROL_ALUMNO;
                    $aspirante->usuario->save();
                }

                $nombre   = trim(($aspirante->usuario->nombre ?? '') . ' ' . ($aspirante->usuario->apellidoP ?? '') . ' ' . ($aspirante->usuario->apellidoM ?? ''));
                $loginUrl = route('inicio');
                $aspirante->usuario->notify(
                    new CredencialesAspirante($nombre, $matricula, $plain, $loginUrl)
                );
            }
        });
        return redirect()->route('aspirantes.index')->with('success', 'Aspirante actualizado exitosamente.');
    }


    /*
     * Genera una matrícula única para un nuevo Alumno. La matrícula se basa en el año actual, el ID del diplomado 
        y un consecutivo. Asegura la unicidad de la matrícula consultando la base de datos y aumentando el consecutivo 
        si es necesario.
    */
    private function generarMatriculaUnica(int $idDiplomado): string
    {
        $anio = now()->format('Y');
        $prefijoDip = 'D' . $idDiplomado;

        $consecutivo = Alumno::where('id_diplomado', $idDiplomado)->count() + 1;
        $num = str_pad((string)$consecutivo, 4, '0', STR_PAD_LEFT);
        $mat = "A-{$anio}-{$prefijoDip}-{$num}";

        while (Alumno::where('matriculaA', $mat)->exists()) {
            $consecutivo++;
            $num = str_pad((string)$consecutivo, 4, '0', STR_PAD_LEFT);
            $mat = "A-{$anio}-{$prefijoDip}-{$num}";
        }
        return $mat;
    }


    /*
     * Almacena un nuevo Aspirante y su Usuario asociado en la base de datos (registro manual por Admin).
    */
    public function store(Request $request)
    {
        /* Valida todos los campos, incluyendo la unicidad del usuario y correo. */
        $data = $request->validate([
            'nombre'  => ['required', 'string', 'max:100'],
            'apellidoP' => ['required', 'string', 'max:100'],
            'apellidoM' => ['required', 'string', 'max:100'],
            'fecha_nac' => ['required', 'date'],
            'usuario' => ['required', 'string', 'max:50', 'unique:usuarios,usuario'],
            'pass' => ['required', 'string', 'min:8', 'confirmed'],
            'genero' => ['required', Rule::in(['M', 'F', 'Otro'])],
            'correo' => ['required', 'email', 'max:100', 'unique:usuarios,correo'],
            'telefono' => ['required', 'string', 'max:20'],
            'direccion' => ['required', 'string', 'max:100'],
            'id_rol' => ['required', 'integer'],
            'interes' => ['required', 'string', 'max:50'],
            'dia' => ['required', 'date'],
            'estatus' => ['required', Rule::in(['activo', 'rechazado'])],
        ]);

        /* Ejecuta una transacción para garantizar la creación simultánea del registro en 'usuarios' y 'aspirantes'. */
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
            Aspirante::create([
                'id_usuario' => $usuario->id_usuario,
                'interes'    => $data['interes'],
                'dia'        => $data['dia'],
                'estatus'    => $data['estatus'],
            ]);
        });
        return redirect()->route('aspirantes.index')->with('success', 'Aspirante creado exitosamente.');
    }


    /*
     * Elimina un registro de Aspirante y su Usuario asociado.
    */
    public function destroy(Aspirante $aspirante)
    {
        /* Ejecuta una transacción para asegurar que ambos registros (Aspirante y Usuario) sean eliminados de
        forma conjunta, manteniendo la integridad de los datos. */
        DB::transaction(function () use ($aspirante) {
            $usuario = $aspirante->usuario;
            $aspirante->delete();

            if ($usuario) {
                $usuario->delete();
            }
        });

        return redirect()->route('aspirantes.index')->with('ok', 'Aspirante eliminado.');
    }


    /*
     * Muestra el dashboard o panel principal del Aspirante. Busca la información del aspirante autenticado 
     * para cargar la vista.
    */
    public function dashboard()
    {
        $userId = auth()->user()->id_usuario;
        $aspirante = Aspirante::where('id_usuario', $userId)->with('usuario')->firstOrFail();

        return view('aspirante.dashboardaspirante', compact('aspirante'));
    }
}