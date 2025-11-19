<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plantillas_notificaciones', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100)->unique();
            $table->string('asunto', 200);
            $table->text('cuerpo'); // Plantilla con variables {nombre}, {curso}, etc.
            $table->enum('tipo', ['inscripcion', 'pago', 'certificado', 'recordatorio', 'general'])->default('general');
            $table->enum('canal', ['email', 'sms', 'whatsapp', 'sistema'])->default('email');
            $table->boolean('activa')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plantillas_notificaciones');
    }
};