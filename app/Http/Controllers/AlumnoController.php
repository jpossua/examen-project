<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Alumno;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AlumnoController extends Controller
{
    public function index()
    {
        $alumnos = Alumno::all();
        return response()->json([
            'status' => true,
            'data' => $alumnos
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255',
            'email' => 'required|email|unique:alumnos',
            'fecha_nacimiento' => 'required|date',
            'activo' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $alumno = Alumno::create($request->all());

        return response()->json([
            'status' => true,
            'message' => 'Alumno creado exitosamente',
            'data' => $alumno
        ], 201);
    }

    public function show($id)
    {
        $alumno = Alumno::find($id);

        if (!$alumno) {
            return response()->json([
                'status' => false,
                'message' => 'Alumno no encontrado'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $alumno
        ]);
    }

    public function update(Request $request, $id)
    {
        $alumno = Alumno::find($id);

        if (!$alumno) {
            return response()->json([
                'status' => false,
                'message' => 'Alumno no encontrado'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'nombre' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:alumnos,email,' . $id,
            'fecha_nacimiento' => 'sometimes|required|date',
            'activo' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $alumno->update($request->all());

        return response()->json([
            'status' => true,
            'message' => 'Alumno actualizado exitosamente',
            'data' => $alumno
        ]);
    }

    public function destroy($id)
    {
        $alumno = Alumno::find($id);

        if (!$alumno) {
            return response()->json([
                'status' => false,
                'message' => 'Alumno no encontrado'
            ], 404);
        }

        $alumno->delete();

        return response()->json([
            'status' => true,
            'message' => 'Alumno eliminado exitosamente'
        ]);
    }
}
