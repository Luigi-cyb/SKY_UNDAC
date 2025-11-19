<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('asistencias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inscripcion_id')->constrained('inscripciones')->onDelete('cascade');
            $table->foreignId('curso_id')->constrained('cursos')->onDelete('cascade');
            $table->integer('numero_sesion');
            $table->date('fecha_sesion');
            $table->time('hora_registro')->nullable();
            $table->enum('estado', ['presente', 'ausente', 'tardanza', 'justificado'])->default('ausente');
            $table->text('observaciones')->nullable();
            $table->timestamps();
            
            // Índice único para evitar registros duplicados
            $table->unique(['inscripcion_id', 'numero_sesion']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asistencias');
    }
};