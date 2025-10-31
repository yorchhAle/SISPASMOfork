<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Administrador;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{

    /*
     * Constructor del controlador. Aplica un middleware que verifica que el usuario autenticado
     * tenga el rol de 'Administrador' para permitir el acceso a todas sus acciones.
    */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (auth()->user()->rol->nombre_rol !== 'Administrador') {
                abort(403, 'Acción no autorizada. No tienes permisos de Administrador.');
            }
            return $next($request);
        });
    }


    /*
     * Muestra una lista paginada de todos los Administradores. Incluye la información del usuario asociado y los 
     * ordena de forma descendente por ID.
    */
    public function index(){
        $admin=Administrador::with('usuario')->orderByDesc('id_admin')->paginate(15);
        return view('CRUDAdmin.read', compact('admin'));
    }
    

    /*
     * Muestra la vista del formulario para crear un nuevo Administrador.
    */
    public function create(){
        return view('CRUDAdmin.create');
    }


    /*
     * Muestra la vista del formulario para editar un Administrador existente. Carga la relación 'usuario' del 
     * modelo Administrador para mostrar todos los datos. Muestra la vista del formulario para editar un Administrador 
     * existente. Carga la relación 'usuario' del modelo Administrador para mostrar todos los datos.
    */
    public function edit(Administrador $admin){
        $admin->load('usuario');
        return view('CRUDAdmin.update', compact('admin'));
    }


    /*
     * Almacena un nuevo Administrador y su Usuario asociado en la base de datos.
    */
    public function store(Request $request){
        /* Valida todos los campos, incluyendo la unicidad del usuario y correo. */
        $data=$request->validate([
            'nombre'       => ['required', 'string', 'max:100'],
            'apellidoP'    => ['required', 'string', 'max:100'],
            'apellidoM'    => ['required', 'string', 'max:100'],
            'fecha_nac'    => ['required', 'date'],
            'usuario'      => ['required', 'string', 'max:50', 'unique:usuarios,usuario'],
            'pass'         => ['required', 'string', 'min:8', 'max:255'],
            'genero'       => ['required', Rule::in(['M','F','Otro'])],
            'correo'       => ['required', 'email', 'max:100', 'unique:usuarios,correo'],
            'telefono'     => ['required', 'string', 'max:20'],
            'direccion'    => ['required', 'string', 'max:100'],
            'fecha_ingreso'=> ['required', 'date'],
            'rol'=>['required', 'string', 'max:50'],
            'estatus'      => ['required', Rule::in(['activo','inactivo'])],
        ]);

        /* Ejecuta una transacción para garantizar la creación simultánea del registro en 'usuarios' y 'administradores'. */
        DB::transaction(function() use ($data){
            $user=User::create([
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
                'id_rol'      => 1,
                'tipo_usuario'=> 'admin',
            ]);
            Administrador::create([
                'id_usuario'   => $user->id_usuario,
                'fecha_ingreso'=> $data['fecha_ingreso'],
                'estatus'      => $data['estatus'],
            ]);
        });
        return redirect()->route('admin.index')->with('success','Administrador creado exitosamente.');
    }


    /*
     * Actualiza la información de un Administrador y su Usuario asociado.
    */
    public function update(){
        /* Valida los campos, incluyendo la regla de unicidad para 'usuario' y 'correo', ignorando el registro actual. */
        $data=request()->validate([
            'nombre'       => ['required', 'string', 'max:100'],
            'apellidoP'    => ['required', 'string', 'max:100'],
            'apellidoM'    => ['required', 'string', 'max:100'],
            'fecha_nac'    => ['required', 'date'],
            'usuario'      => ['required', 'string', 'max:50', Rule::unique('usuarios','usuario')->ignore(request('id_usuario'),'id_usuario')],
            'pass'         => ['nullable', 'string', 'min:8', 'max:255'],
            'genero'       => ['required', Rule::in(['M','F','Otro'])],
            'correo'       => ['required', 'email', 'max:100', Rule::unique('usuarios','correo')->ignore(request('id_usuario'),'id_usuario')],
            'telefono'     => ['required', 'string', 'max:20'],
            'direccion'    => ['required', 'string', 'max:100'],
            'fecha_ingreso'=> ['required', 'date'],
            'rol'=>['required', 'string', 'max:50'],
            'estatus'      => ['required', Rule::in(['activo','inactivo'])],
        ]);

        /* Ejecuta una transacción para actualizar los datos tanto en la tabla 'usuarios' como en 'administradores'. */
        DB::transaction(function() use ($data){
            $user=User::where('id_usuario', request('id_usuario'))->first();
            $user->nombre      = $data['nombre'];
            $user->apellidoP   = $data['apellidoP'];
            $user->apellidoM   = $data['apellidoM'];
            $user->fecha_nac   = $data['fecha_nac'];
            $user->usuario     = $data['usuario'];
            /* Actualiza la contraseña solo si se proporciona un nuevo valor. */
            if(!empty($data['pass'])){
                $user->pass    = Hash::make($data['pass']);
            }
            $user->genero      = $data['genero'];
            $user->correo      = $data['correo'];
            $user->telefono    = $data['telefono'];
            $user->direccion   = $data['direccion'];
            $user->save();
            $admin=Administrador::where('id_usuario', request('id_usuario'))->first();
            $admin->fecha_ingreso= $data['fecha_ingreso'];
            $admin->estatus      = $data['estatus'];
            $admin->save();
        });
        return redirect()->route('admin.index')->with('ok','Administrador actualizado exitosamente.');
    }

    /*
     * Elimina un registro de Administrador y su Usuario asociado. Ejecuta una transacción para asegurar que ambos 
     * registros (Administrador y Usuario) sean eliminados de forma conjunta, manteniendo la integridad de los datos.
    */
    public function destroy(Administrador $admin)
    {
        DB::transaction(function () use ($admin) {
            $usuario = $admin->usuario; 
            $admin->delete();           

            if ($usuario) {
                $usuario->delete();
            }
        });
        return redirect()->route('admin.index')->with('ok', 'Coordinador eliminado.');
    }
}
