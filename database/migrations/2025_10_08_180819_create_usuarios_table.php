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
        Schema::create('usuarios', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('perfil_image')->default('empresa.png');
            $table->string('nombre', 40);
            $table->string('apellido_paterno', 30);
            $table->string('apellido_materno', 30);
            $table->string('email', 100)->unique('users_email_unique');
            $table->string('password', 80)->nullable();
            $table->string('telefono', 10);
            $table->timestamp('fecha_registro')->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuarios');
    }
};
