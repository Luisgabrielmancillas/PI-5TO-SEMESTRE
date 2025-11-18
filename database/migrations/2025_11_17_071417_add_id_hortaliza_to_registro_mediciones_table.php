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
        Schema::table('registro_mediciones', function (Blueprint $table) {
            // Agregamos la columna FK después del id
            $table->foreignId('id_hortaliza')
                  ->nullable() // para no romper los registros ya existentes
                  ->after('id_regis_med')
                  ->constrained('seleccion_hortalizas', 'id_hortaliza')
                  ->nullOnDelete(); // si borras la hortaliza, se pone NULL en lugar de borrar el historial
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('registro_mediciones', function (Blueprint $table) {
            // Primero quitamos la foreign key
            $table->dropForeign(['id_hortaliza']);
            // Luego la columna
            $table->dropColumn('id_hortaliza');
        });
    }
};
