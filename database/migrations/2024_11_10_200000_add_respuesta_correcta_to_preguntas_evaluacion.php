<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('preguntas_evaluacion', function (Blueprint $table) {
            $table->string('respuesta_correcta', 500)->nullable()->after('imagen_url');
        });
    }

    public function down()
    {
        Schema::table('preguntas_evaluacion', function (Blueprint $table) {
            $table->dropColumn('respuesta_correcta');
        });
    }
};