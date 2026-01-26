<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alumno extends Model
{
    /** @use HasFactory<\Database\Factories\AlumnoFactory> */
    use HasFactory;
    protected $fillable = [
        'nombre',
        'email',
        'fecha_nacimiento',
        'activo',
    ];
    public function examens()
    {
        return $this->hasMany(Examen::class);
    }
}
