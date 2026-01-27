<?php

/**
 * ============================================
 * MODELO DE EXAMEN (Examen)
 * ============================================
 * 
 * Este modelo representa la tabla de exámenes en la base de datos.
 * Almacena la información de las evaluaciones realizadas.
 * 
 * Atributos principales:
 * - dia_examen: Fecha y hora de realización
 * - tema: Tema evaluado
 * - aprobado: Estado de aprobación (1: Aprobado, 0: Reprobado)
 * - nota: Calificación numérica obtenida
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Examen extends Model
{
    /** @use HasFactory<\Database\Factories\ExamenFactory> */
    use HasFactory;

    /**
     * Atributos asignables masivamente
     * @var array
     */
    protected $fillable = [
        'dia_examen',
        'tema',
        'aprobado',
        'nota',
        'nombre_alumno',
        'asignatura',
        'duracion_minutos',
    ];

    /**
     * Conversión de tipos de atributos
     * @var array
     */
    protected $casts = [
        'dia_examen' => 'datetime',
        'aprobado' => 'boolean',
        'nota' => 'decimal:2',
    ];
}
