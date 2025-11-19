<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('docentes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('dni', 8)->unique();
            $table->string('codigo_docente', 20)->unique()->nullable();
            $table->string('nombres', 100);
            $table->string('apellidos', 100);
            $table->date('fecha_nacimiento')->nullable();
            $table->enum('sexo', ['M', 'F'])->nullable();
            $table->string('telefono', 15)->nullable();
            $table->text('direccion')->nullable();
            $table->string('correo_personal', 100)->nullable();
            $table->string('correo_institucional', 100)->unique();
            $table->text('formacion_academica')->nullable();
            $table->text('experiencia_profesional')->nullable();
            $table->text('especialidades')->nullable();
            $table->text('cv_url')->nullable();
            $table->text('foto_url')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('docentes');
    }
};