<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('certificados', function (Blueprint $table) {
            $table->boolean('firmado')->default(false)->after('pdf_url');
            $table->text('pdf_firmado_url')->nullable()->after('firmado');
            $table->timestamp('fecha_firmado')->nullable()->after('pdf_firmado_url');
            $table->bigInteger('firmado_por_user_id')->unsigned()->nullable()->after('fecha_firmado');
            
            $table->foreign('firmado_por_user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('certificados', function (Blueprint $table) {
            $table->dropForeign(['firmado_por_user_id']);
            $table->dropColumn(['firmado', 'pdf_firmado_url', 'fecha_firmado', 'firmado_por_user_id']);
        });
    }
};