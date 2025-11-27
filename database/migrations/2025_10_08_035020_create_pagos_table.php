<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pagos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inscripcion_id')->constrained('inscripciones')->onDelete('cascade');
            $table->foreignId('metodo_pago_id')->constrained('metodos_pago')->onDelete('cascade');
            $table->string('codigo_operacion', 50)->unique()->nullable();
            $table->decimal('monto', 10, 2);
            $table->date('fecha_pago');
            $table->enum('estado', ['pendiente', 'confirmado', 'rechazado', 'reembolsado'])->default('pendiente');
            $table->text('comprobante_url')->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamp('fecha_confirmacion')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pagos');
    }
};