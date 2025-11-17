<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lista_espera', function (Blueprint $table) {
            $table->id();
            $table->foreignId('estudiante_id')->constrained('estudiantes')->onDelete('cascade');
            $table->foreignId('curso_id')->constrained('cursos')->onDelete('cascade');
            $table->integer('posicion');
            $table->date('fecha_registro');
            $table->enum('estado', ['en_espera', 'asignado', 'cancelado'])->default('en_espera');
            $table->timestamp('fecha_asignacion')->nullable();
            $table->timestamps();
            
            // Índice único para evitar duplicados en lista de espera
            $table->unique(['estudiante_id', 'curso_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lista_espera');
    }
};