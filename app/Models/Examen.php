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
        'alumno_id',
        'profesor_id',
        'asignatura_id',
        'nota',
    ];

    protected $casts = [
        'dia_examen' => 'datetime',
        'aprobado' => 'boolean',
    ];

    public function alumno()
    {
        return $this->belongsTo(Alumno::class);
    }

    public function profesor()
    {
        return $this->belongsTo(Profesor::class);
    }

    public function asignatura()
    {
        return $this->belongsTo(Asignatura::class);
    }
}
