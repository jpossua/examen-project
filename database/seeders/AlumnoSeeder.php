<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Alumno;

class AlumnoSeeder extends Seeder
{
    public function run(): void
    {
        Alumno::create([
            'nombre' => 'Alumno Test',
            'email' => 'alumno@test.com',
            'fecha_nacimiento' => '2000-01-01',
            'activo' => true,
        ]);
    }
}
