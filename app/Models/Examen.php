<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Examen extends Model
{
    /** @use HasFactory<\Database\Factories\ExamenFactory> */
    use HasFactory;
    protected $fillable = [
        'dia_examen',
        'tema',
        'aprobado',
        'nota',
        'nombre_alumno',
        'asignatura',
        'duracion_minutos',
    ];

    protected $casts = [
        'dia_examen' => 'datetime',
        'aprobado' => 'boolean',
        'nota' => 'decimal:2',
    ];
}
