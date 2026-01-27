<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;

use App\Http\Controllers\Api\ExamenController;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;

/*
|--------------------------------------------------------------------------
| Rutas de la API
|--------------------------------------------------------------------------
|
| Aquí es donde puedes registrar las rutas de la API para tu aplicación.
| Estas rutas son cargadas por el RouteServiceProvider dentro de un grupo
| al que se le asigna el grupo de middleware "api". ¡Disfruta construyendo tu API!
|
*/

// Rutas públicas (no necesitan autenticación)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Ruta de prueba para verificar que el servidor funciona (GET)
Route::get('/health', function () {
    return response()->json(['status' => 'OK', 'server' => 'running']);
});

// Rutas protegidas por Sanctum
Route::middleware([
    EnsureFrontendRequestsAreStateful::class,
    'throttle:api',
])->group(function () {
    // CRUD Exámenes
    Route::apiResource('examenes', ExamenController::class);
});

// Rutas privadas (necesitan autenticación)
Route::get('/user', function (
    Request $request
) {
    return $request->user();
})->middleware('auth:sanctum');

Route::put('/user', [AuthController::class, 'updateProfile'])->middleware('auth:sanctum');
