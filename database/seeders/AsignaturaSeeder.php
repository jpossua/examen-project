<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Asignatura;

class AsignaturaSeeder extends Seeder
{
    public function run(): void
    {
        Asignatura::create([
            'nombre' => 'Matemáticas',
            'codigo' => 'MAT101',
            'descripcion' => 'Matemáticas básicas',
            'curso' => 1,
            'creditos' => 6,
        ]);
    }
}
