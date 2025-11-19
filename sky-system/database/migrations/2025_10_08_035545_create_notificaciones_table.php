<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notificaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('titulo', 200);
            $table->text('mensaje');
            $table->enum('tipo', ['informacion', 'recordatorio', 'alerta', 'sistema'])->default('informacion');
            $table->enum('canal', ['sistema', 'email', 'sms', 'whatsapp'])->default('sistema');
            $table->boolean('leida')->default(false);
            $table->timestamp('fecha_envio');
            $table->timestamp('fecha_lectura')->nullable();
            $table->text('url_destino')->nullable(); // Enlace relacionado a la notificación
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notificaciones');
    }
};