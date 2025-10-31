<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Maneja la solicitud entrante verificando autenticación y rol.
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()
                ->route('inicio')
                ->with('message', 'Tu sesión ha expirado por inactividad. Por favor, inicia sesión nuevamente.');
        }

        if (!$user->rol) {
            abort(403, 'No tienes un rol asignado.');
        }

        $nombreRol = strtolower($user->rol->nombre_rol ?? '');
        $roles = array_map('strtolower', $roles);

        if (!in_array($nombreRol, $roles, true)) {
            abort(403, 'No tienes permiso para acceder a esta sección.');
        }

        return $next($request);
    }
}
