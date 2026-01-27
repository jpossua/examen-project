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
        $examenes = Examen::all();
        return response()->json([
            'status' => true,
            'data' => $examenes
        ]);
    }

    public function store(Request $request)
    {
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
                'errors' => $validator->errors()
            ], 422);
        }

        $examen = Examen::create($request->all());

        return response()->json([
            'status' => true,
            'message' => 'Examen creado exitosamente',
            'data' => $examen
        ], 201);
    }

    public function show($id)
    {
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
                'errors' => $validator->errors()
            ], 422);
        }

        $examen->update($request->all());

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
}
