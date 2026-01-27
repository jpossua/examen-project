<?php

/**
 * ============================================
 * CONTROLADOR DE EXÁMENES (ExamenController)
 * ============================================
 * 
 * Este controlador administra las operaciones CRUD para el modelo Examen.
 * Permite gestionar las evaluaciones de los alumnos.
 * 
 * Operaciones disponibles:
 * - Listar todos los exámenes
 * - Registrar un nuevo examen
 * - Ver detalles de un examen
 * - Actualizar información de un examen
 * - Eliminar un examen
 */

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Examen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class ExamenController extends Controller
{
    // ============================================
    // MÉTODO: INDEX (Listar exámenes)
    // ============================================

    /**
     * Muestra una lista de todos los exámenes registrados.
     * 
     * @return \Illuminate\Http\JsonResponse JSON con array de exámenes
     */
    public function index()
    {
        // ============================================
        // PASO 1: OBTENER TODOS LOS REGISTROS
        // ============================================
        /**
         * Recuperamos todos los registros de la tabla 'examenes'
         * usando el método estático all() de Eloquent.
         */
        $examenes = Examen::all();

        return response()->json([
            'status' => true,
            'message' => 'Listado de exámenes recuperado',
            'data' => $examenes
        ]);
    }

    // ============================================
    // MÉTODO: STORE (Crear examen)
    // ============================================

    /**
     * Registra un nuevo examen en la base de datos.
     * 
     * @param Request $request Datos del nuevo examen
     * @return \Illuminate\Http\JsonResponse Examen creado
     */
    public function store(Request $request)
    {
        // ============================================
        // PASO 1: VALIDAR DATOS DE ENTRADA
        // ============================================
        /**
         * Verificamos que los campos obligatorios vengan en la petición.
         * Validamos tipos de datos (date, integer, numeric) y rangos.
         */
        $validator = Validator::make($request->all(), [
            'dia_examen' => 'required|date',
            'tema' => 'required|string|max:255',
            'aprobado' => 'required|boolean',
            'nombre_alumno' => 'required|string|max:255',
            'asignatura' => 'required|string|max:255',
            'duracion_minutos' => 'required|integer|min:1',
            'nota' => 'nullable|numeric|min:0|max:10'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        // ============================================
        // PASO 2: CREAR REGISTRO EN BD
        // ============================================
        /**
         * Insertamos el nuevo registro usando asignación masiva.
         * Los campos son filtrados por $fillable en el modelo.
         */
        $examen = Examen::create($request->all());

        return response()->json([
            'status' => true,
            'message' => 'Examen creado exitosamente',
            'data' => $examen
        ], 201);
    }

    // ============================================
    // MÉTODO: SHOW (Ver detalle)
    // ============================================

    /**
     * Muestra los detalles de un examen específico.
     * 
     * @param int $id ID del examen buscado
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        // ============================================
        // PASO 1: BUSCAR EXAMEN POR ID
        // ============================================
        $examen = Examen::find($id);

        if (!$examen) {
            return response()->json([
                'status' => false,
                'message' => 'Examen no encontrado'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $examen
        ]);
    }

    // ============================================
    // MÉTODO: UPDATE (Actualizar examen)
    // ============================================

    /**
     * Actualiza la información de un examen existente.
     * 
     * @param Request $request Nuevos datos
     * @param int $id ID del examen a actualizar
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        // ============================================
        // PASO 1: VERIFICAR EXISTENCIA DEL REGISTRO
        // ============================================
        $examen = Examen::find($id);

        if (!$examen) {
            return response()->json([
                'status' => false,
                'message' => 'Examen no encontrado'
            ], 404);
        }

        // ============================================
        // PASO 2: VALIDAR DATOS DE ENTRADA
        // ============================================
        /**
         * Usamos 'sometimes' para validar solo los campos presentes
         * en la petición, permitiendo actualizaciones parciales (PATCH).
         */
        $validator = Validator::make($request->all(), [
            'dia_examen' => 'sometimes|required|date',
            'tema' => 'sometimes|required|string|max:255',
            'aprobado' => 'sometimes|required|boolean',
            'nombre_alumno' => 'sometimes|required|string|max:255',
            'asignatura' => 'sometimes|required|string|max:255',
            'duracion_minutos' => 'sometimes|required|integer|min:1',
            'nota' => 'nullable|numeric|min:0|max:10'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        // ============================================
        // PASO 3: ACTUALIZAR REGISTRO
        // ============================================
        $examen->update($request->all());

        return response()->json([
            'status' => true,
            'message' => 'Examen actualizado exitosamente',
            'data' => $examen
        ]);
    }

    // ============================================
    // MÉTODO: DESTROY (Eliminar examen)
    // ============================================

    /**
     * Elimina un examen de la base de datos de forma permanente.
     * 
     * @param int $id ID del examen a eliminar
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        // ============================================
        // PASO 1: BUSCAR EXAMEN POR ID
        // ============================================
        $examen = Examen::find($id);

        if (!$examen) {
            return response()->json([
                'status' => false,
                'message' => 'Examen no encontrado'
            ], 404);
        }

        // ============================================
        // PASO 2: ELIMINAR REGISTRO DE LA BD
        // ============================================
        $examen->delete();

        return response()->json([
            'status' => true,
            'message' => 'Examen eliminado exitosamente'
        ]);
    }
}
