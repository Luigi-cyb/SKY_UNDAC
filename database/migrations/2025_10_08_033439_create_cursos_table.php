<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cursos', function (Blueprint $table) {
            $table->id();
            $table->string('codigo', 20)->unique();
            $table->string('nombre', 200);
            $table->text('descripcion')->nullable();
            $table->text('objetivos')->nullable();
            $table->text('competencias')->nullable();
            $table->text('perfil_ingreso')->nullable();
            $table->text('perfil_egreso')->nullable();
            $table->foreignId('categoria_id')->constrained('categorias_cursos')->onDelete('cascade');
            $table->foreignId('modalidad_id')->constrained('modalidades')->onDelete('cascade');
            $table->string('nivel', 50)->nullable(); // Básico, Intermedio, Avanzado
            $table->integer('horas_academicas');
            $table->integer('cupo_minimo')->default(10);
            $table->integer('cupo_maximo')->default(30);
            $table->date('fecha_inicio');
            $table->date('fecha_fin');
            $table->decimal('costo_inscripcion', 10, 2)->default(0);
            $table->decimal('nota_minima_aprobacion', 4, 2)->default(13.00);
            $table->integer('asistencia_minima_porcentaje')->default(70);
            $table->enum('estado', ['borrador', 'convocatoria', 'en_curso', 'finalizado', 'archivado'])->default('borrador');
            $table->text('silabo_url')->nullable();
            $table->text('temario')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cursos');
    }
};  