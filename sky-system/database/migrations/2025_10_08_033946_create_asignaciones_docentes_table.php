<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('asignaciones_docentes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('docente_id')->constrained('docentes')->onDelete('cascade');
            $table->foreignId('curso_id')->constrained('cursos')->onDelete('cascade');
            $table->enum('tipo_asignacion', ['titular', 'asistente', 'invitado'])->default('titular');
            $table->integer('carga_horaria')->nullable();
            $table->date('fecha_asignacion');
            $table->boolean('activo')->default(true);
            $table->timestamps();
            
            // Índice único para evitar asignaciones duplicadas
            $table->unique(['docente_id', 'curso_id', 'tipo_asignacion']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asignaciones_docentes');
    }
};