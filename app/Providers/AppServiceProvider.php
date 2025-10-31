<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Route; // Es bueno importar Route

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {        
        Route::middleware('web')
            ->group(function () {
                Route::fallback(function () {
                    if (!auth()->check()) {
                        return redirect()->route('inicio')
                            ->with('message', 'Por favor, inicia sesión para continuar.');
                    }
                    abort(404);
                });
            });
    }

}