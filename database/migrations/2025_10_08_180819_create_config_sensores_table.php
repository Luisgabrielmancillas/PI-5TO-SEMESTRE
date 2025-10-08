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
        Schema::create('config_sensores', function (Blueprint $table) {
            $table->bigIncrements('id_config');
            $table->unsignedBigInteger('id_hortaliza')->index('config_sensores_id_hortaliza_foreign');
            $table->unsignedBigInteger('id_sensor')->index('config_sensores_id_sensor_foreign');
            $table->decimal('valor_min_acept', 10)->default(0);
            $table->decimal('valor_max_acept', 10)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('config_sensores');
    }
};
