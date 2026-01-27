<?php

/**
 * ============================================
 * MIGRACIÓN: CACHÉ DE APLICACIÓN (create_cache_table)
 * ============================================
 * 
 * Crea la tabla necesaria para el driver de caché de base de datos.
 * Mejora el rendimiento almacenando datos temporales.
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ejecuta las migraciones.
     */
    public function up(): void
    {
        // ============================================
        // TABLA: CACHE
        // ============================================
        Schema::create('cache', function (Blueprint $table) {
            $table->string('key')->primary()->comment('Clave única del item');
            $table->mediumText('value')->comment('Valor serializado');
            $table->integer('expiration')->index()->comment('Timestamp de expiración');
        });

        // ============================================
        // TABLA: CACHE LOCKS (Bloqueos atómicos)
        // ============================================
        Schema::create('cache_locks', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->string('owner');
            $table->integer('expiration')->index();
        });
    }

    /**
     * Revierte las migraciones.
     */
    public function down(): void
    {
        Schema::dropIfExists('cache');
        Schema::dropIfExists('cache_locks');
    }
};
