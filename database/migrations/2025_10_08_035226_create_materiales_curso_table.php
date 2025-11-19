<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('materiales_curso', function (Blueprint $table) {
            $table->id();
            $table->foreignId('curso_id')->constrained('cursos')->onDelete('cascade');
            $table->foreignId('docente_id')->constrained('docentes')->onDelete('cascade');
            $table->string('titulo', 200);
            $table->text('descripcion')->nullable();
            $table->enum('tipo', ['presentacion', 'documento', 'video', 'enlace', 'otro'])->default('documento');
            $table->text('archivo_url')->nullable();
            $table->string('enlace_externo', 500)->nullable();
            $table->integer('numero_sesion')->nullable(); // Sesión a la que pertenece
            $table->date('fecha_publicacion');
            $table->boolean('visible')->default(true);
            $table->integer('numero_descargas')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('materiales_curso');
    }
};