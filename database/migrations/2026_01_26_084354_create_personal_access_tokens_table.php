<?php

/**
 * ============================================
 * MIGRACIÓN: TOKENS DE ACCESO PERSONAL (Sanctum)
 * ============================================
 * 
 * Esta migración crea la tabla necesaria para Laravel Sanctum.
 * Almacena los tokens de API emitidos a los usuarios.
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ejecuta las migraciones.
     * Crea la tabla personal_access_tokens.
     */
    public function up(): void
    {
        // ============================================
        // CREACIÓN DE TABLA: PERSONAL_ACCESS_TOKENS
        // ============================================
        Schema::create('personal_access_tokens', function (Blueprint $table) {
            $table->id();
            $table->morphs('tokenable'); // Polimorfismo (User, Admin, etc.)
            $table->text('name')->comment('Nombre descriptivo del token');
            $table->string('token', 64)->unique()->comment('Token hasheado');
            $table->text('abilities')->nullable()->comment('Permisos del token');
            $table->timestamp('last_used_at')->nullable()->comment('Último uso');
            $table->timestamp('expires_at')->nullable()->index()->comment('Fecha de expiración');
            $table->timestamps();
        });
    }

    /**
     * Revierte las migraciones.
     */
    public function down(): void
    {
        Schema::dropIfExists('personal_access_tokens');
    }
};
