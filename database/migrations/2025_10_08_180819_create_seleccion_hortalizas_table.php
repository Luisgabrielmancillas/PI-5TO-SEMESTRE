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
        Schema::create('seleccion_hortalizas', function (Blueprint $table) {
            $table->bigIncrements('id_hortaliza');
            $table->string('nombre', 30);
            $table->boolean('seleccion')->nullable()->default(false);
            $table->dateTime('fecha');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seleccion_hortalizas');
    }
};
