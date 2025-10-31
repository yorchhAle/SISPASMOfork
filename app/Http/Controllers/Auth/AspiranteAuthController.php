<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Aspirante;
use App\Models\User;
use App\Models\Diplomado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AspiranteAuthController extends Controller
{
    /*
     * Muestra la vista de selección para el registro o login de Aspirantes.
    */
    public function select()
    {
        return view('aspirante.aspiranteselect');
    }


    /*
     * Muestra el formulario de registro para un nuevo Aspirante. Carga el listado de diplomados 
     * disponibles para que el usuario pueda seleccionar su interés.
    */
    public function showRegisterForm()
    {
        $diplomados = Diplomado::orderBy('nombre')->get(['id_diplomado','nombre']);
        return view('aspirante.aspiranteregistro', compact('diplomados'));
    }


    /*
     * Procesa el registro de un nuevo Aspirante.
    */
    public function register(Request $request)
    {
        $messages = [
            'nombre.required' => 'El nombre es obligatorio.',
            'apellidoP.required' => 'El apellido paterno es obligatorio.',
            'apellidoM.required' => 'El apellido materno es obligatorio.',
            'fecha_nac.required' => 'Debes ingresar tu fecha de nacimiento.',
            'genero.required' => 'Debes seleccionar tu género.',
            'correo.required' => 'El correo electrónico es obligatorio.',
            'correo.email' => 'El correo debe tener un formato válido (ejemplo: correo@gmail.com).',
            'correo.unique' => 'Ya existe una cuenta registrada con este correo.',
            'telefono.required' => 'El número de teléfono es obligatorio.',
            'direccion.required' => 'La dirección es obligatoria.',
            'password.required' => 'La contraseña no puede estar vacía.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.regex' => 'La contraseña debe incluir al menos una letra mayúscula, una minúscula, un número y un símbolo (#$%&/).',
            'password.confirmed' => 'Las contraseñas no coinciden.',
            'id_diplomado.required' => 'Debes seleccionar un diplomado.',
            'id_diplomado.exists' => 'El diplomado seleccionado no existe.',
            'acepto.accepted' => 'Debes aceptar el aviso de privacidad.',
        ];
    
        /* Realiza una validación estricta de todos los campos, incluyendo una contraseña segura. */
        $data = $request->validate([
            'nombre'      => ['required','string','max:100'],
            'apellidoP'   => ['required','string','max:100'],
            'apellidoM'   => ['required','string','max:100'],
            'fecha_nac'   => ['required','date'],
            'genero'      => ['required','in:M,F,Otro'],
            'correo'      => ['required','email','max:100','unique:usuarios,correo'],
            'telefono'    => ['required','string','max:20'],
            'direccion'   => ['required','string','max:100'],
            'password'    => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/[A-Z]/',     
                'regex:/[a-z]/',    
                'regex:/[0-9]/',     
                'regex:/[#\$%&\/]/', 
            ],
            'id_diplomado'=> ['required','integer','exists:diplomados,id_diplomado'],
            'dia'         => ['nullable','date'],
            'acepto'      => ['accepted'],
        ], $messages);

        /* Ejecuta una transacción de base de datos para asegurar que el usuario y el aspirante se creen correctamente. */
        return DB::transaction(function () use ($data, $request) {
            $idRol = DB::table('roles')->where('nombre_rol','Aspirante')->value('id_rol')
                    ?? DB::table('roles')->insertGetId(['nombre_rol'=>'Aspirante']);

            $idUsuario = DB::table('usuarios')->insertGetId([
                'nombre'    => $data['nombre'],
                'apellidoP' => $data['apellidoP'],
                'apellidoM' => $data['apellidoM'],
                'fecha_nac' => $data['fecha_nac'],
                'usuario'   => strtolower($data['correo']),
                'pass'      => Hash::make($data['password']), 
                'genero'    => $data['genero'],
                'correo'    => strtolower($data['correo']),
                'telefono'  => $data['telefono'],
                'direccion' => $data['direccion'],
                'id_rol'    => $idRol,
            ]);

            $dip = Diplomado::findOrFail($data['id_diplomado']);

            /* Crea el registro en la tabla 'usuarios' y 'aspirantes'.*/
            DB::table('aspirantes')->insert([
                'id_usuario' => $idUsuario,
                'interes'    => $dip->nombre,                     
                'dia'        => $data['dia'] ?? now()->toDateString(),
                'estatus'    => 'activo',
            ]);

            /* Inicia sesión automáticamente con el nuevo usuario Aspirante. */
            $user = User::find($idUsuario);
            Auth::login($user);
            $request->session()->regenerate();

            return redirect()->route('aspirante.dashboard')->with('ok','¡Registro completado!');
        });
    }


    /*
     * Muestra la vista del formulario de inicio de sesión para Aspirantes.
    */
    public function showLoginForm() 
    { 
        return view('aspirante.aspirantelogin'); 
    }

    /*
     * Procesa la autenticación del Aspirante.
    */
    public function login(Request $request)
    {
        /* Valida el correo y la contraseña. */
        $cred = $request->validate([
            'correo'   => ['required','email'],
            'password' => ['required','string'],
        ]);

        /* Busca al usuario por correo y verifica que la contraseña sea correcta. */
        $user = User::with('rol')->where('correo', $cred['correo'])->first();

        if (!$user || !Hash::check($cred['password'], $user->pass)) {
            return back()->withErrors(['correo' => 'Credenciales inválidas.'])->withInput();
        }

        /* Confirma que el rol del usuario sea 'Aspirante'. */
        if (!$user->rol || $user->rol->nombre_rol !== 'Aspirante') {
            return back()->withErrors(['correo' => 'Esta cuenta no es de aspirante.'])->withInput();
        }

        /* Inicia sesión y redirige al dashboard del aspirante. */
        Auth::login($user);
        $request->session()->regenerate();
        return redirect()->route('aspirante.dashboard');
    }

    /*
     * Cierra la sesión activa del usuario Aspirante. Invalida la sesión, regenera el token CSRF y redirige 
     * al formulario de inicio de sesión de aspirantes.
    */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('aspirante.login');
    }
}

