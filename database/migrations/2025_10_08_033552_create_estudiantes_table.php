<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('estudiantes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('dni', 8)->unique();
            $table->string('codigo_estudiante', 20)->unique()->nullable();
            $table->string('nombres', 100);
            $table->string('apellidos', 100);
            $table->date('fecha_nacimiento')->nullable();
            $table->enum('sexo', ['M', 'F'])->nullable();
            $table->string('telefono', 15)->nullable();
            $table->string('telefono_emergencia', 15)->nullable();
            $table->text('direccion')->nullable();
            $table->string('correo_personal', 100)->nullable();
            $table->string('correo_institucional', 100)->unique();
            $table->boolean('pertenece_eisc')->default(true);
            $table->string('ciclo_academico', 10)->nullable();
            $table->text('foto_url')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('estudiantes');
    }
};