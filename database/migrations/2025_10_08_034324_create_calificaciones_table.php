<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('calificaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inscripcion_id')->constrained('inscripciones')->onDelete('cascade');
            $table->foreignId('evaluacion_id')->constrained('evaluaciones')->onDelete('cascade');
            $table->decimal('nota', 4, 2);
            $table->text('observaciones')->nullable();
            $table->text('evidencia_url')->nullable(); // URL del trabajo o proyecto
            $table->timestamp('fecha_registro')->nullable();
            $table->timestamps();
            
            // Índice único para evitar duplicados
            $table->unique(['inscripcion_id', 'evaluacion_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('calificaciones');
    }
};