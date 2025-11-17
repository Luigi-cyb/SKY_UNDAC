<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('comprobantes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pago_id')->constrained('pagos')->onDelete('cascade');
            $table->enum('tipo_comprobante', ['boleta', 'factura', 'recibo'])->default('boleta');
            $table->string('serie', 10);
            $table->string('numero', 20);
            $table->string('ruc_dni', 11);
            $table->string('razon_social', 200);
            $table->decimal('subtotal', 10, 2);
            $table->decimal('igv', 10, 2)->default(0);
            $table->decimal('total', 10, 2);
            $table->date('fecha_emision');
            $table->text('xml_url')->nullable(); // Archivo XML para SUNAT
            $table->text('pdf_url')->nullable(); // PDF del comprobante
            $table->string('codigo_sunat', 100)->nullable(); // Respuesta de SUNAT
            $table->enum('estado_sunat', ['pendiente', 'aceptado', 'rechazado', 'anulado'])->default('pendiente');
            $table->text('observaciones')->nullable();
            $table->timestamps();
            
            // Índice único para serie y número
            $table->unique(['serie', 'numero']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('comprobantes');
    }
};