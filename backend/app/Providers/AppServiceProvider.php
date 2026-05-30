<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\RateLimiter;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Configuración de Rate Limiting para la API: 60 peticiones por minuto por IP
        // Nota: Laravel 11 aplica automáticamente el limitador llamado api a todas las rutas dentro de routes/api.php, asi que con esto protegemos todo.
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->ip());
        });
    }
}
