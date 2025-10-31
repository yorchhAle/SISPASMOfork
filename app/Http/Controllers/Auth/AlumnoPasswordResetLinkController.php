<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class AlumnoPasswordResetLinkController extends Controller
{
    /*
     * Muestra la vista del formulario para solicitar el restablecimiento de contraseña.
    */
    public function create()
    {
        return view('alumno.auth.forgot-password');
    }


    /*
     * Envía el enlace de restablecimiento de contraseña al correo electrónico del Alumno.
    */
    public function store(Request $request)
    {
        /* Valida que el campo 'correo' sea obligatorio y tenga formato de email. */
        $request->validate(['correo' => 'required|email']);

        /* Envía el enlace de restablecimiento de contraseña al correo electrónico proporcionado. */
        $status = \Password::broker('users')->sendResetLink([
            'correo' => $request->input('correo'),
        ]);

        return $status === \Password::RESET_LINK_SENT
            ? back()->with('status', __($status))
            : back()->withErrors(['correo' => __($status)]);
    }
}
