<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('respuestas_encuestas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('encuesta_id')->constrained('encuestas')->onDelete('cascade');
            $table->foreignId('estudiante_id')->nullable()->constrained('estudiantes')->onDelete('cascade');
            $table->json('respuestas'); // Array de respuestas en formato JSON
            $table->timestamp('fecha_respuesta');
            $table->timestamps();
            
            // Índice único si la encuesta no es anónima
            $table->unique(['encuesta_id', 'estudiante_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('respuestas_encuestas');
    }
};