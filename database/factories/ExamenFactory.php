<?php

/**
 * ============================================
 * FACTORY DE EXAMEN (ExamenFactory)
 * ============================================
 * 
 * Generador de datos de prueba para el modelo Examen.
 * Utiliza Faker para crear registros realistas.
 */

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Examen>
 */
class ExamenFactory extends Factory
{
    /**
     * Define el estado por defecto del modelo.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'dia_examen' => fake()->dateTimeBetween('-1 year', 'now'),
            'tema' => fake()->sentence(3),
            'aprobado' => fake()->boolean(70), // 70% de probabilidad de aprobar
            'nota' => fake()->randomFloat(2, 0, 10),
            'nombre_alumno' => fake()->name(),
            'asignatura' => fake()->randomElement(['Matemáticas', 'Física', 'Química', 'Literatura', 'Historia']),
            'duracion_minutos' => fake()->numberBetween(30, 120),
        ];
    }
}
