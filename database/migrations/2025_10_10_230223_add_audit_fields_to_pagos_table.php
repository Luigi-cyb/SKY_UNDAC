<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('pagos', function (Blueprint $table) {
            // ==================== CAMPOS DE AUDITORÍA ====================
            
            // Agregar después de 'observaciones' (que existe en tu tabla)
            $table->unsignedBigInteger('registrado_por')->nullable()->after('observaciones');
            $table->unsignedBigInteger('modificado_por')->nullable()->after('registrado_por');
            $table->unsignedBigInteger('confirmado_por')->nullable()->after('fecha_confirmacion');
            $table->unsignedBigInteger('rechazado_por')->nullable()->after('confirmado_por');
            
            // ==================== FECHAS ADICIONALES ====================
            
            $table->timestamp('fecha_rechazo')->nullable()->after('rechazado_por');
            
            // ==================== MOTIVO DE RECHAZO ====================
            
            $table->text('motivo_rechazo')->nullable()->after('fecha_rechazo');
            
            // ==================== CAMPOS ADICIONALES NECESARIOS ====================
            
            // Agregar campos que faltan según el PagoController
            $table->string('codigo_pago', 50)->nullable()->after('metodo_pago_id')
                ->comment('Código único de pago PAY-YYYY-XXXXXXXX');
            
            $table->string('numero_operacion', 50)->nullable()->after('codigo_pago')
                ->comment('Número de operación del banco/pasarela');
            
            // Renombrar 'observaciones' a 'descripcion' para consistencia
            // (Opcional - puedes mantener 'observaciones' si prefieres)
            
            // ==================== FOREIGN KEYS ====================
            
            $table->foreign('registrado_por')
                ->references('id')
                ->on('users')
                ->onDelete('set null')
                ->onUpdate('cascade');
            
            $table->foreign('modificado_por')
                ->references('id')
                ->on('users')
                ->onDelete('set null')
                ->onUpdate('cascade');
            
            $table->foreign('confirmado_por')
                ->references('id')
                ->on('users')
                ->onDelete('set null')
                ->onUpdate('cascade');
            
            $table->foreign('rechazado_por')
                ->references('id')
                ->on('users')
                ->onDelete('set null')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pagos', function (Blueprint $table) {
            // Eliminar foreign keys primero
            $table->dropForeign(['registrado_por']);
            $table->dropForeign(['modificado_por']);
            $table->dropForeign(['confirmado_por']);
            $table->dropForeign(['rechazado_por']);
            
            // Eliminar columnas
            $table->dropColumn([
                'registrado_por',
                'modificado_por',
                'confirmado_por',
                'rechazado_por',
                'fecha_rechazo',
                'motivo_rechazo',
                'codigo_pago',
                'numero_operacion',
            ]);
        });
    }
};