<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Examen;
use Carbon\Carbon;

class ExamenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ejemplo 1: Examen aprobado de Programación
        Examen::create([
            'dia_examen' => Carbon::parse('2026-06-23 10:00:00'), // Tipo: DateTime
            'tema' => 'Programación Orientada a Objetos',         // Tipo: String
            'aprobado' => true,                                   // Tipo: Boolean
            'nota' => 8.50,                                       // Tipo: Decimal
            'nombre_alumno' => 'Juan Pérez',                      // Tipo: String
            'asignatura' => 'Programación Backend',               // Tipo: String
            'duracion_minutos' => 90,                             // Tipo: Integer
        ]);

        // Ejemplo 2: Examen suspenso de Matemáticas
        Examen::create([
            'dia_examen' => Carbon::parse('2026-06-25 09:30:00'),
            'tema' => 'Cálculo Diferencial',
            'aprobado' => false,
            'nota' => 4.25,
            'nombre_alumno' => 'Ana García',
            'asignatura' => 'Matemáticas',
            'duracion_minutos' => 120,
        ]);

        // Ejemplo 3: Examen perfecto de Base de Datos
        Examen::create([
            'dia_examen' => Carbon::parse('2026-07-01 11:00:00'),
            'tema' => 'Diseño de Bases de Datos Relacionales',
            'aprobado' => true,
            'nota' => 10.00,
            'nombre_alumno' => 'Carlos López',
            'asignatura' => 'Bases de Datos',
            'duracion_minutos' => 60,
        ]);
    }
}
