<?php

/**
 * ============================================
 * MIGRACIÓN: TABLA DE USUARIOS Y SESIONES (create_users_table)
 * ============================================
 * 
 * Esta migración crea las tablas fundamentales para la autenticación:
 * - users: Almacena credenciales y datos de perfil
 * - password_reset_tokens: Tokens para restablecer contraseñas
 * - sessions: Almacenamiento de sesiones de usuario
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ejecuta las migraciones.
     * Crea las tablas users, password_reset_tokens y sessions.
     */
    public function up(): void
    {
        // ============================================
        // CREACIÓN DE TABLA: USERS
        // ============================================
        Schema::create('users', function (Blueprint $table) {
            $table->id(); // ID único
            $table->string('name')->comment('Nombre del usuario');
            $table->string('email')->unique()->comment('Correo electrónico único');
            $table->timestamp('email_verified_at')->nullable()->comment('Fecha de verificación de email');
            $table->string('password')->comment('Contraseña hasheada');
            $table->rememberToken()->comment('Token para "Recordarme"');
            $table->timestamps();
        });

        // ============================================
        // CREACIÓN DE TABLA: PASSWORD_RESET_TOKENS
        // ============================================
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        // ============================================
        // CREACIÓN DE TABLA: SESSIONS
        // ============================================
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Revierte las migraciones.
     * Elimina las tablas creadas.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
