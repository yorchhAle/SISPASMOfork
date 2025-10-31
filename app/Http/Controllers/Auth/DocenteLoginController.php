<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Docente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class DocenteLoginController extends Controller
{
    /*
     * Muestra el formulario de inicio de sesión para el módulo de Docente.
    */
    public function showLoginForm()
    {
        return view('docente.docentelogin');
    }


    /*
     * Procesa la autenticación del Docente.
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
        $docente = Docente::with('usuario.rol')
                        ->where('matriculaD', $request->matricula)
                        ->first();

        /* Busca al Docente por matrícula y verifica que tenga un usuario asociado. */
        if (!$docente || !$docente->usuario) {
            return back()->withErrors(['matricula' => 'Matrícula no encontrada.'])->withInput();
        }

        $user = $docente->usuario;

        /* Verifica la contraseña utilizando Hash::check(). */
        if (!Hash::check($request->password, $user->pass)) {
            return back()->withErrors(['password' => 'Contraseña incorrecta.'])->withInput();
        }

        /* Confirma que el rol del usuario sea 'Docente'. */
        if (!$user->rol || $user->rol->nombre_rol !== 'Docente') {
            return back()->withErrors(['matricula' => 'No tienes permisos para acceder aquí.'])->withInput();
        }

        /* Inicia sesión y redirige al dashboard del Docente. */    
        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('docente.dashboard');
    }


    /*
     * Cierra la sesión activa del usuario Docente. Invalida la sesión, regenera el token CSRF y redirige 
     * al formulario de inicio de sesión de docentes.
    */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('docente.login');
    }
}
