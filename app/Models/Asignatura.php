<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asignatura extends Model
{
    /** @use HasFactory<\Database\Factories\AsignaturaFactory> */
    use HasFactory;
    protected $fillable = [
        'nombre',
        'codigo',
        'descripcion',
        'curso',
        'creditos',
    ];
    public function examens()
    {
        return $this->hasMany(Examen::class);
    }
}
