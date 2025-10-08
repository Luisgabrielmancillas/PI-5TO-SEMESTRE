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
        Schema::create('actuadores', function (Blueprint $table) {
            $table->bigIncrements('id_actuador');
            $table->string('nombre', 40);
            $table->enum('tipo', ['Bomba peristáltica', 'Bomba sumergible', 'Iluminación', 'Ventilación', 'Otro']);
            $table->unsignedTinyInteger('bus')->nullable();
            $table->string('address', 10)->nullable();
            $table->enum('modo_activacion', ['Manual', 'Automático por sensor', 'Temporizador', 'Programado por horario', 'Control por temperatura', 'Otro']);
            $table->boolean('estado_inicial')->default(false);
            $table->boolean('estado_actual')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('actuadores');
    }
};
