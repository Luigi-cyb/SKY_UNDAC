<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('validaciones_certificados', function (Blueprint $table) {
            $table->id();
            $table->foreignId('certificado_id')->constrained('certificados')->onDelete('cascade');
            $table->string('ip_validador', 45)->nullable();
            $table->string('user_agent', 255)->nullable();
            $table->timestamp('fecha_validacion');
            $table->enum('resultado', ['valido', 'invalido', 'revocado'])->default('valido');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('validaciones_certificados');
    }
};