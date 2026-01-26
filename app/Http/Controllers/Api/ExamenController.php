<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Examen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class ExamenController extends Controller
{
    public function index()
    {
        $examenes = Examen::with(['alumno', 'profesor', 'asignatura'])->get();
        return response()->json([
            'status' => true,
            'data' => $examenes
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'dia_examen' => 'required|date_format:Y-m-d H:i:s',
            'tema' => 'required|string|max:255',
            'aprobado' => 'required|boolean',
            'alumno_id' => 'required|exists:alumnos,id',
            'profesor_id' => 'required|exists:profesores,id',
            'asignatura_id' => 'required|exists:asignaturas,id',
            'nota' => 'nullable|numeric|min:0|max:10'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $examen = Examen::create($request->all());

        // Cargar relaciones para la respuesta
        $examen->load(['alumno', 'profesor', 'asignatura']);

        return response()->json([
            'status' => true,
            'message' => 'Examen creado exitosamente',
            'data' => $examen
        ], 201);
    }

    public function show($id)
    {
        $examen = Examen::with(['alumno', 'profesor', 'asignatura'])->find($id);

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

    public function update(Request $request, $id)
    {
        $examen = Examen::find($id);

        if (!$examen) {
            return response()->json([
                'status' => false,
                'message' => 'Examen no encontrado'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'dia_examen' => 'sometimes|required|date_format:Y-m-d H:i:s',
            'tema' => 'sometimes|required|string|max:255',
            'aprobado' => 'sometimes|required|boolean',
            'alumno_id' => 'sometimes|required|exists:alumnos,id',
            'profesor_id' => 'sometimes|required|exists:profesores,id',
            'asignatura_id' => 'sometimes|required|exists:asignaturas,id',
            'nota' => 'nullable|numeric|min:0|max:10'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $examen->update($request->all());

        // Cargar relaciones para la respuesta
        $examen->load(['alumno', 'profesor', 'asignatura']);

        return response()->json([
            'status' => true,
            'message' => 'Examen actualizado exitosamente',
            'data' => $examen
        ]);
    }

    public function destroy($id)
    {
        $examen = Examen::find($id);

        if (!$examen) {
            return response()->json([
                'status' => false,
                'message' => 'Examen no encontrado'
            ], 404);
        }

        $examen->delete();

        return response()->json([
            'status' => true,
            'message' => 'Examen eliminado exitosamente'
        ]);
    }

    // Método adicional: Obtener exámenes por alumno
    public function getExamenesPorAlumno($alumnoId)
    {
        $examenes = Examen::with(['profesor', 'asignatura'])
            ->where('alumno_id', $alumnoId)
            ->get();

        if ($examenes->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'No se encontraron exámenes para este alumno'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $examenes
        ]);
    }

    // Método adicional: Obtener exámenes por asignatura
    public function getExamenesPorAsignatura($asignaturaId)
    {
        $examenes = Examen::with(['alumno', 'profesor'])
            ->where('asignatura_id', $asignaturaId)
            ->get();

        if ($examenes->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'No se encontraron exámenes para esta asignatura'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $examenes
        ]);
    }
}
