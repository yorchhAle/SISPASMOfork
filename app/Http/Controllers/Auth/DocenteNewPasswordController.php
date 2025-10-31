<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;

class DocenteNewPasswordController extends Controller
{
    /*
     * Muestra la vista para que el Docente pueda establecer una nueva contraseña. Recibe el token de restablecimiento 
     * de contraseña y el correo electrónico del usuario a través de los parámetros de la URL para rellenar el 
     * formulario.
    */
    public function create(Request $request, $token)
    {
        $correo = $request->query('email');
        return view('docente.auth.reset-password', [
            'token' => $token,
            'correo' => $correo,
        ]);
    }


    /*
     * Procesa la solicitud para restablecer la contraseña del Docente.
    */
    public function store(Request $request)
    {
        /*Valida el token, el correo electrónico y la nueva contraseña (incluyendo confirmación).*/
        $request->validate([
            'token' => 'required',
            'correo' => 'required|email',
            'password' => 'required|confirmed|min:8',
        ]);

        /* Utiliza el broker de contraseñas de Laravel para validar el token y actualizar la contraseña del usuario. */
        $status = Password::broker('users')->reset(
            [
                'correo' => $request->correo,
                'password' => $request->password,
                'password_confirmation' => $request->password_confirmation,
                'token' => $request->token,
            ],
            function ($user) use ($request) {
                $user->forceFill([
                    /* La contraseña se actualiza en el campo 'pass' (en lugar del campo 'password' por defecto). */
                    'pass' => Hash::make($request->password),
                ])->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('docente.login')->with('status', __($status))
            : back()->withErrors(['correo' => __($status)]);
    }
}
