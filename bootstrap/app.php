<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\RoleMiddleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        
        $middleware->alias([
            'role' => RoleMiddleware::class,
        ]);
        
        $middleware->redirectGuestsTo(function (Request $request) {
            
            $path = $request->path();

            if (str_contains($path, 'administrador')) {
                return route('admin.login');
            }
            
            if (str_contains($path, 'aspirante')) {
                return route('aspirante.login');
            }
            
            if (str_contains($path, 'alumno')) {
                return route('alumno.login');
            }
            
            if (str_contains($path, 'docente')) {
                return route('docente.login');
            }
            return route('login'); 
        });
        
    })
    ->withExceptions(function (Exceptions $exceptions) {
    })->create();