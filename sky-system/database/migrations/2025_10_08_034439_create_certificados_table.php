<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('certificados', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inscripcion_id')->constrained('inscripciones')->onDelete('cascade');
            $table->string('codigo_certificado', 50)->unique();
            $table->string('codigo_qr', 255)->unique();
            $table->date('fecha_emision');
            $table->text('pdf_url');
            $table->text('firma_digital')->nullable();
            $table->string('firmado_por', 200)->nullable(); // Director, Autoridad
            $table->enum('estado', ['emitido', 'revocado', 'anulado'])->default('emitido');
            $table->text('observaciones')->nullable();
            $table->integer('numero_veces_descargado')->default(0);
            $table->timestamp('ultima_descarga')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('certificados');
    }
};