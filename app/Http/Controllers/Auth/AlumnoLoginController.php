<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Alumno;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AlumnoLoginController extends Controller
{
    /*
     * Muestra el formulario de inicio de sesión para el módulo de Alumno.
    */
    public function showLoginForm()
    {
        return view('alumno.loginalumno');
    }


    /*
     * Procesa la autenticación del Alumno.
    */
    public function login(Request $request)
    {
        $request->validate([
            'matricula' => ['required', 'string'],
            'password'  => ['required', 'string'],
        ], [
            'matricula.required' => 'La matrícula es obligatoria.',
            'password.required'  => 'La contraseña es obligatoria.',
        ]);

        /* Valida la matrícula y la contraseña. */
        $alumno = Alumno::with('usuario.rol')
                        ->where('matriculaA', $request->matricula)
                        ->first();

        /* Busca al Alumno por matrícula y verifica que tenga un usuario asociado. */
        if (!$alumno || !$alumno->usuario) {
            return back()
                ->withErrors(['matricula' => 'Matrícula no encontrada.'])
                ->withInput();
        }

        $user = $alumno->usuario;

        /* Verifica la contraseña utilizando Hash::check(). */
        if (!Hash::check($request->password, $user->pass)) {
            return back()
                ->withErrors(['password' => 'Contraseña incorrecta.'])
                ->withInput();
        }

        /* Confirma que el rol del usuario sea 'Alumno'. */
        if (!$user->rol || $user->rol->nombre_rol !== 'Alumno') {
            return back()
                ->withErrors(['matricula' => 'El usuario no tiene rol de Alumno.'])
                ->withInput();
        }

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('alumno.dashboard');
    }

    
    /*
     * Cierra la sesión activa del usuario Alumno. Invalida la sesión y el token CSRF, y redirige a la vista de login.
    */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('alumno.login');
    }
}
