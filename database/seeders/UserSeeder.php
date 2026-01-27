<?php

/**
 * ============================================
 * SEEDER DE USUARIOS (UserSeeder)
 * ============================================
 * 
 * Este seeder crea un usuario administrador por defecto.
 * Útil para pruebas y acceso inicial al sistema.
 */

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Ejecuta el seeder de usuarios.
     */
    public function run(): void
    {
        // ============================================
        // CREAR USUARIO ADMIN
        // ============================================
        User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'),
        ]);
    }
}
