<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Asignatura;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AsignaturaController extends Controller
{
    public function index()
    {
        $asignaturas = Asignatura::all();
        return response()->json([
            'status' => true,
            'data' => $asignaturas
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255',
            'codigo' => 'required|string|max:20|unique:asignaturas',
            'descripcion' => 'nullable|string',
            'curso' => 'required|integer|min:1|max:6',
            'creditos' => 'required|numeric|min:0|max:10',
            'horas_semanales' => 'required|integer|min:1|max:20'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $asignatura = Asignatura::create($request->all());

        return response()->json([
            'status' => true,
            'message' => 'Asignatura creada exitosamente',
            'data' => $asignatura
        ], 201);
    }

    public function show($id)
    {
        $asignatura = Asignatura::find($id);

        if (!$asignatura) {
            return response()->json([
                'status' => false,
                'message' => 'Asignatura no encontrada'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $asignatura
        ]);
    }

    public function update(Request $request, $id)
    {
        $asignatura = Asignatura::find($id);

        if (!$asignatura) {
            return response()->json([
                'status' => false,
                'message' => 'Asignatura no encontrada'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'nombre' => 'sometimes|required|string|max:255',
            'codigo' => 'sometimes|required|string|max:20|unique:asignaturas,codigo,' . $id,
            'descripcion' => 'nullable|string',
            'curso' => 'sometimes|required|integer|min:1|max:6',
            'creditos' => 'sometimes|required|numeric|min:0|max:10',
            'horas_semanales' => 'sometimes|required|integer|min:1|max:20'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $asignatura->update($request->all());

        return response()->json([
            'status' => true,
            'message' => 'Asignatura actualizada exitosamente',
            'data' => $asignatura
        ]);
    }

    public function destroy($id)
    {
        $asignatura = Asignatura::find($id);

        if (!$asignatura) {
            return response()->json([
                'status' => false,
                'message' => 'Asignatura no encontrada'
            ], 404);
        }

        $asignatura->delete();

        return response()->json([
            'status' => true,
            'message' => 'Asignatura eliminada exitosamente'
        ]);
    }
}
