<?php

/**
 * ============================================
 * PROVEEDOR DE SERVICIOS DE LA APLICACIÓN
 * ============================================
 * 
 * Este ServiceProvider es el punto central para arrancar servicios
 * de la aplicación. Aquí se configuran:
 * - Límites de velocidad (Rate Limiting)
 * - Bindings de contenedores
 * - Configuración global
 */

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Http\Request;
use Illuminate\Cache\RateLimiting\Limit;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Registra los servicios de la aplicación.
     * Use este método para bindear servicios al contenedor.
     */
    public function register(): void
    {
        //
    }

    /**
     * Inicializa los servicios de la aplicación.
     * Este método se llama después de que todos los servicios
     * han sido registrados.
     */
    public function boot(): void
    {
        // ============================================
        // CONFIGURACIÓN: RATE LIMITING (API)
        // ============================================
        /**
         * Define un límite de 60 peticiones por minuto por usuario/IP.
         * Protege la API contra abusos y ataques de fuerza bruta.
         */
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
    }
}
