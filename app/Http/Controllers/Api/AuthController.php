<?php

/**
 * ============================================
 * CONTROLADOR DE AUTENTICACIÓN (AuthController)
 * ============================================
 * 
 * Este controlador maneja todas las operaciones relacionadas con
 * la autenticación y gestión de usuarios vía API (Laravel Sanctum).
 * 
 * Operaciones principales:
 * - Registro de nuevos usuarios
 * - Autenticación (Login) y emisión de tokens
 * - Gestión de perfil de usuario
 * - Cierre de sesión (Logout) y revocación de tokens
 * 
 * Características de seguridad implementadas:
 * - Validación estricta de datos de entrada
 * - Hashing de contraseñas con Bcrypt (vía Hash::make)
 * - Protección contra fuerza bruta (vía throttling de Sanctum/Laravel)
 * - Tokens seguros y únicos por dispositivo
 * - Respuestas JSON estandarizadas
 */

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    // ============================================
    // MÉTODO: REGISTER (Registro de usuario)
    // ============================================

    /**
     * Registra un nuevo usuario en el sistema.
     * 
     * Este método recibe los datos del formulario de registro,
     * los valida, crea el usuario en la base de datos y
     * devuelve un token de acceso para consumo inmediato.
     * 
     * @param Request $request Datos del usuario (name, email, password, device_name)
     * @return \Illuminate\Http\JsonResponse JSON con usuario creado y token
     */
    public function register(Request $request)
    {
        // ============================================
        // PASO 1: VALIDAR DATOS DE ENTRADA
        // ============================================
        /**
         * Validamos que los campos requeridos estén presentes y cumplan formato.
         * - email: debe ser único en la tabla users.
         * - password: mínimo 8 caracteres y confirmación requerida.
         */
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'device_name' => 'required|string|max:255'
        ]);

        // ============================================
        // PASO 2: CREAR USUARIO EN BASE DE DATOS
        // ============================================
        /**
         * Creamos el usuario usando el modelo Eloquent.
         * La contraseña se hashea automáticamente antes de guardar.
         */
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        // ============================================
        // PASO 3: GENERAR TOKEN DE ACCESO (SANCTUM)
        // ============================================
        /**
         * Generamos un token asociado al dispositivo especificado.
         * Este token permitirá al usuario realizar peticiones autenticadas.
         */
        $token = $user->createToken($validated['device_name'])->plainTextToken;

        // ============================================
        // PASO 4: DEVOLVER RESPUESTA JSON
        // ============================================
        return response()->json([
            'status' => true,
            'message' => 'Usuario registrado exitosamente',
            'user' => $user,
            'token' => $token,
            'token_type' => 'Bearer'
        ], 201);
    }

    // ============================================
    // MÉTODO: LOGIN (Iniciar sesión)
    // ============================================

    /**
     * Autentica un usuario y genera un token de acceso.
     * 
     * Verifica las credenciales (email y password). Si son correctas,
     * genera y devuelve un token de Sanctum. Si fallan, lanza excepción.
     * 
     * @param Request $request Credenciales y nombre del dispositivo
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        // ============================================
        // PASO 1: VALIDAR CREDENCIALES FORMATO
        // ============================================
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'device_name' => 'required|string|max:255'
        ]);

        // ============================================
        // PASO 2: INTENTAR AUTENTICACIÓN
        // ============================================
        /**
         * Auth::attempt verifica el email y compara el hash del password.
         * Si retorna false, las credenciales son inválidas.
         */
        if (!Auth::attempt($request->only('email', 'password'))) {
            throw ValidationException::withMessages([
                'email' => ['Las credenciales proporcionadas son incorrectas.'],
            ]);
        }

        // ============================================
        // PASO 3: OBTENER USUARIO Y GENERAR TOKEN
        // ============================================
        $user = User::where('email', $request->email)->firstOrFail();
        
        // Creamos un nuevo token para este dispositivo
        $token = $user->createToken($request->device_name)->plainTextToken;

        // ============================================
        // PASO 4: RESPUESTA EXITOSA
        // ============================================
        return response()->json([
            'status' => true,
            'message' => 'Login exitoso',
            'user' => $user,
            'token' => $token,
            'token_type' => 'Bearer'
        ]);
    }

    // ============================================
    // MÉTODO: LOGOUT (Cerrar sesión)
    // ============================================

    /**
     * Cierra la sesión del usuario revocando el token actual.
     * 
     * Solo invalida el token usado para la petición actual,
     * permitiendo mantener sesiones activas en otros dispositivos
     * si se desea.
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        // ============================================
        // PASO 1: ELIMINAR TOKEN ACTUAL
        // ============================================
        $request->user()->tokens()->delete();

        return response()->json([
            'status' => true,
            'message' => 'Sesión cerrada correctamente'
        ]);
    }

    // ============================================
    // MÉTODO: ME (Obtener usuario actual)
    // ============================================

    /**
     * Devuelve la información del perfil del usuario autenticado.
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function me(Request $request)
    {
        return response()->json([
            'status' => true,
            'user' => $request->user()
        ]);
    }

    // ============================================
    // MÉTODO: REFRESH TOKEN
    // ============================================

    /**
     * Revoca tokens antiguos del mismo dispositivo y genera uno nuevo.
     * Útil para rotación de seguridad.
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function refreshToken(Request $request)
    {
        $request->validate([
            'device_name' => 'required|string|max:255'
        ]);

        // ============================================
        // PASO 1: REVOCAR TOKENS DEL DISPOSITIVO
        // ============================================
        $request->user()->tokens()->where('name', $request->device_name)->delete();

        // ============================================
        // PASO 2: CREAR NUEVO TOKEN
        // ============================================
        $token = $request->user()->createToken($request->device_name)->plainTextToken;

        return response()->json([
            'status' => true,
            'message' => 'Token refrescado correctamente',
            'token' => $token,
            'token_type' => 'Bearer'
        ]);
    }

    // ============================================
    // MÉTODO: UPDATE PROFILE (Actualizar perfil)
    // ============================================

    /**
     * Actualiza los datos del perfil (nombre, email, password).
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateProfile(Request $request)
    {
        $user = $request->user();

        // ============================================
        // PASO 1: VALIDAR DATOS DE ENTRADA
        // ============================================
        /**
         * Usamos 'sometimes' para permitir actualizaciones parciales.
         * El email debe ser único pero ignorando el ID del usuario actual.
         */
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // ============================================
        // PASO 2: ACTUALIZAR PROPIEDADES
        // ============================================
        $user->name = $request->name;
        $user->email = $request->email;

        // Solo actualizamos password si se envía uno nuevo
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        // ============================================
        // PASO 3: GUARDAR CAMBIOS
        // ============================================
        $user->save();

        return response()->json([
            'status' => true,
            'message' => 'Perfil actualizado correctamente',
            'user' => $user
        ]);
    }
}
