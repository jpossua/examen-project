<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;

use App\Http\Controllers\Api\ExamenController;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;

/*
|--------------------------------------------------------------------------
| RUTAS DE LA API
|--------------------------------------------------------------------------
|
| Aquí se registran los endpoints de la API.
| Estas rutas se cargan automáticamente con el prefijo /api.
|
| Estructura:
| 1. Rutas Públicas (Auth, Health Check)
| 2. Rutas Protegidas (Sanctum Middleware)
*/

// ============================================
// SECCIÓN 1: RUTAS PÚBLICAS
// ============================================

// Autenticación de usuarios
Route::post('/register', [AuthController::class, 'register']); // Registro
Route::post('/login', [AuthController::class, 'login']);       // Inicio de sesión

// Health Check (Verificación de estado)
Route::get('/health', function () {
    return response()->json(['status' => 'OK', 'server' => 'running']);
});

// ============================================
// SECCIÓN 2: RUTAS PROTEGIDAS (Requieren Token)
// ============================================

/**
 * Grupo de rutas protegidas por Sanctum.
 * Requieren un token Bearer válido en el header Authorization.
 */
Route::middleware([
    EnsureFrontendRequestsAreStateful::class,
    'throttle:api', // Límite de velocidad
    'auth:sanctum'  // Autenticación obligatoria
])->group(function () {
    
    // CRUD de Exámenes
    Route::apiResource('examenes', ExamenController::class);

    // Gestión de perfil de usuario
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::put('/user', [AuthController::class, 'updateProfile']);
    Route::post('/logout', [AuthController::class, 'logout']);
});
