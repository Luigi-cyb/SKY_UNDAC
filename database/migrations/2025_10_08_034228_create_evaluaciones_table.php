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
        Schema::table('evaluaciones', function (Blueprint $table) {
            // Fechas y duración
            $table->dateTime('fecha_disponible')->nullable()->after('fecha_evaluacion');
            $table->dateTime('fecha_limite')->nullable()->after('fecha_disponible');
            $table->integer('duracion_minutos')->default(60)->after('fecha_limite');
            
            // Intentos y configuración
            $table->integer('numero_intentos_permitidos')->default(1)->after('duracion_minutos');
            $table->boolean('mostrar_respuestas_correctas')->default(false)->after('numero_intentos_permitidos');
            $table->boolean('aleatorizar_preguntas')->default(false)->after('mostrar_respuestas_correctas');
            
            // Nota mínima de aprobación
            $table->decimal('nota_minima_aprobacion', 4, 2)->default(10.50)->after('nota_maxima');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('evaluaciones', function (Blueprint $table) {
            $table->dropColumn([
                'fecha_disponible',
                'fecha_limite',
                'duracion_minutos',
                'numero_intentos_permitidos',
                'mostrar_respuestas_correctas',
                'aleatorizar_preguntas',
                'nota_minima_aprobacion'
            ]);
        });
    }
};