<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        if (! $request->expectsJson()) {
            
            if (empty($this->guards)) {
                return route('login');
            }
            
            $guard = $this->guards[0];

            return match ($guard) {
                'admin' => route('admin.login'),
                'aspirante' => route('aspirante.login'),
                'alumno' => route('alumno.login'),
                'docente' => route('docente.login'),
                default => route('login'),
            };
        }

        return null;
    }
}