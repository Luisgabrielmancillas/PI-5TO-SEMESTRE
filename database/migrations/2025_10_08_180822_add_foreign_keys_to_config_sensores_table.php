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
        Schema::table('config_sensores', function (Blueprint $table) {
            $table->foreign(['id_hortaliza'])->references(['id_hortaliza'])->on('seleccion_hortalizas')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['id_sensor'])->references(['id_sensor'])->on('sensores')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('config_sensores', function (Blueprint $table) {
            $table->dropForeign('config_sensores_id_hortaliza_foreign');
            $table->dropForeign('config_sensores_id_sensor_foreign');
        });
    }
};
