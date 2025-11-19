<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inscripciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('estudiante_id')->constrained('estudiantes')->onDelete('cascade');
            $table->foreignId('curso_id')->constrained('cursos')->onDelete('cascade');
            $table->string('codigo_inscripcion', 30)->unique();
            $table->date('fecha_inscripcion');
            $table->enum('estado', ['provisional', 'confirmada', 'cancelada', 'rechazada'])->default('provisional');
            $table->text('documentos_url')->nullable();
            $table->boolean('pago_confirmado')->default(false);
            $table->decimal('nota_final', 4, 2)->nullable();
            $table->integer('porcentaje_asistencia')->nullable();
            $table->boolean('aprobado')->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamps();
            
            // Índice único para evitar inscripciones duplicadas
            $table->unique(['estudiante_id', 'curso_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inscripciones');
    }
};