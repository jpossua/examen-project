<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Profesor;

class ProfesorSeeder extends Seeder
{
    public function run(): void
    {
        Profesor::create([
            'nombre' => 'Profesor Test',
            'especialidad' => 'Informática',
            'email' => 'profe@test.com',
            'experiencia_anos' => 5,
        ]);
    }
}
