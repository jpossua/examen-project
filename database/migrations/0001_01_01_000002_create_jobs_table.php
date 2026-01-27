<?php

/**
 * ============================================
 * MIGRACIÓN: COLAS DE TRABAJO (create_jobs_table)
 * ============================================
 * 
 * Crea las tablas necesarias para el sistema de Queues (Colas).
 * Permite procesamiento asíncrono y tareas en segundo plano.
 * 
 * Tablas:
 * - jobs: Trabajos pendientes
 * - job_batches: Lotes de trabajos
 * - failed_jobs: Trabajos que fallaron
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
        // TABLA: JOBS (Tareas pendientes)
        // ============================================
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->string('queue')->index()->comment('Nombre de la cola');
            $table->longText('payload')->comment('Datos del trabajo');
            $table->unsignedTinyInteger('attempts')->comment('Intentos realizados');
            $table->unsignedInteger('reserved_at')->nullable();
            $table->unsignedInteger('available_at');
            $table->unsignedInteger('created_at');
        });

        // ============================================
        // TABLA: JOB BATCHES (Lotes)
        // ============================================
        Schema::create('job_batches', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('name');
            $table->integer('total_jobs');
            $table->integer('pending_jobs');
            $table->integer('failed_jobs');
            $table->longText('failed_job_ids');
            $table->mediumText('options')->nullable();
            $table->integer('cancelled_at')->nullable();
            $table->integer('created_at');
            $table->integer('finished_at')->nullable();
        });

        // ============================================
        // TABLA: FAILED JOBS (Errores)
        // ============================================
        Schema::create('failed_jobs', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->text('connection');
            $table->text('queue');
            $table->longText('payload');
            $table->longText('exception')->comment('Traza del error');
            $table->timestamp('failed_at')->useCurrent();
        });
    }

    /**
     * Revierte las migraciones.
     */
    public function down(): void
    {
        Schema::dropIfExists('jobs');
        Schema::dropIfExists('job_batches');
        Schema::dropIfExists('failed_jobs');
    }
};
