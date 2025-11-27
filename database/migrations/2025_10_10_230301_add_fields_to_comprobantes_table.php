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
        Schema::table('comprobantes', function (Blueprint $table) {
            
            // ==================== AUDITORÍA ====================
            
            // ✅ Solo agregar 'emitido_por' si NO existe
            if (!Schema::hasColumn('comprobantes', 'emitido_por')) {
                $table->foreignId('emitido_por')->nullable()->after('observaciones')
                    ->constrained('users')
                    ->nullOnDelete()
                    ->cascadeOnUpdate();
            }
            
            // ==================== CAMPOS FALTANTES ====================
            
            // Agregar 'numero_comprobante' si no existe
            if (!Schema::hasColumn('comprobantes', 'numero_comprobante')) {
                $table->string('numero_comprobante', 50)->nullable()->after('numero')
                    ->comment('Número completo del comprobante (ej: COMP-2025-000001)');
            }
            
            // Agregar 'monto_total' si no existe
            if (!Schema::hasColumn('comprobantes', 'monto_total')) {
                $table->decimal('monto_total', 10, 2)->nullable()->after('total')
                    ->comment('Alias del campo total para compatibilidad');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('comprobantes', function (Blueprint $table) {
            
            // Eliminar foreign key y columnas si existen
            if (Schema::hasColumn('comprobantes', 'emitido_por')) {
                $table->dropForeign(['emitido_por']);
                $table->dropColumn('emitido_por');
            }
            
            if (Schema::hasColumn('comprobantes', 'numero_comprobante')) {
                $table->dropColumn('numero_comprobante');
            }
            
            if (Schema::hasColumn('comprobantes', 'monto_total')) {
                $table->dropColumn('monto_total');
            }
        });
    }
};