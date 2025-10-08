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
        Schema::create('registro_mediciones', function (Blueprint $table) {
            $table->bigIncrements('id_regis_med');
            $table->decimal('ph_value', 5);
            $table->decimal('ce_value', 5);
            $table->decimal('tagua_value', 5);
            $table->decimal('us_value', 5);
            $table->decimal('tam_value', 5);
            $table->decimal('hum_value', 5);
            $table->dateTime('fecha');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registro_mediciones');
    }
};
