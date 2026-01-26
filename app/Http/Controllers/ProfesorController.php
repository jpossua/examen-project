<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Profesor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProfesorController extends Controller
{
    public function index()
    {
        $profesores = Profesor::all();
        return response()->json([
            'status' => true,
            'data' => $profesores
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255',
            'especialidad' => 'required|string|max:100',
            'email' => 'required|email|unique:profesores',
            'experiencia_anos' => 'required|integer|min:0',
            'telefono' => 'nullable|string|max:20'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $profesor = Profesor::create($request->all());

        return response()->json([
            'status' => true,
            'message' => 'Profesor creado exitosamente',
            'data' => $profesor
        ], 201);
    }

    public function show($id)
    {
        $profesor = Profesor::find($id);

        if (!$profesor) {
            return response()->json([
                'status' => false,
                'message' => 'Profesor no encontrado'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $profesor
        ]);
    }

    public function update(Request $request, $id)
    {
        $profesor = Profesor::find($id);

        if (!$profesor) {
            return response()->json([
                'status' => false,
                'message' => 'Profesor no encontrado'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'nombre' => 'sometimes|required|string|max:255',
            'especialidad' => 'sometimes|required|string|max:100',
            'email' => 'sometimes|required|email|unique:profesores,email,' . $id,
            'experiencia_anos' => 'sometimes|required|integer|min:0',
            'telefono' => 'nullable|string|max:20'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $profesor->update($request->all());

        return response()->json([
            'status' => true,
            'message' => 'Profesor actualizado exitosamente',
            'data' => $profesor
        ]);
    }

    public function destroy($id)
    {
        $profesor = Profesor::find($id);

        if (!$profesor) {
            return response()->json([
                'status' => false,
                'message' => 'Profesor no encontrado'
            ], 404);
        }

        $profesor->delete();

        return response()->json([
            'status' => true,
            'message' => 'Profesor eliminado exitosamente'
        ]);
    }
}
