<?php

/**
 * ============================================
 * SEEDER DE EXÁMENES (ExamenSeeder)
 * ============================================
 * 
 * Este seeder puebla la tabla 'examenes' con datos de prueba.
 * Crea varios registros con diferentes estados (aprobado/reprobado).
 */

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Examen;
use Carbon\Carbon;

class ExamenSeeder extends Seeder
{
    /**
     * Ejecuta el seeder de exámenes.
     */
    public function run(): void
    {
        // ============================================
        // CASO 1: EXAMEN APROBADO
        // ============================================
        Examen::create([
            'dia_examen' => Carbon::parse('2026-06-23 10:00:00'), // Tipo: DateTime
            'tema' => 'Programación Orientada a Objetos',         // Tipo: String
            'aprobado' => true,                                   // Tipo: Boolean
            'nota' => 8.50,                                       // Tipo: Decimal
            'nombre_alumno' => 'Juan Pérez',                      // Tipo: String
            'asignatura' => 'Programación Backend',               // Tipo: String
            'duracion_minutos' => 90,                             // Tipo: Integer
        ]);

        // ============================================
        // CASO 2: EXAMEN REPROBADO
        // ============================================
        Examen::create([
            'dia_examen' => Carbon::parse('2026-06-25 09:30:00'),
            'tema' => 'Cálculo Diferencial',
            'aprobado' => false,
            'nota' => 4.25,
            'nombre_alumno' => 'Ana García',
            'asignatura' => 'Matemáticas',
            'duracion_minutos' => 120,
        ]);

        // ============================================
        // CASO 3: EXAMEN SOBRESALIENTE
        // ============================================
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
