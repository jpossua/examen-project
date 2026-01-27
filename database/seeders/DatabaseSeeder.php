<?php

/**
 * ============================================
 * SEEDER PRINCIPAL (DatabaseSeeder)
 * ============================================
 * 
 * Este clase orquesta la ejecución de todos los seeders.
 * Define el orden en que se deben poblar las tablas.
 */

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Ejecuta los seeders de la base de datos.
     */
    public function run(): void
    {
        // ============================================
        // EJECUCIÓN DE SEEDERS
        // ============================================
        $this->call([
            UserSeeder::class,   // 1. Crear usuarios
            ExamenSeeder::class, // 2. Crear exámenes de prueba
        ]);
    }
}
