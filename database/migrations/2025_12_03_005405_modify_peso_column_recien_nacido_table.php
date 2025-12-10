<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Cambia el tipo de dato de la columna 'peso' de DECIMAL(5,2) a SMALLINT
     * para poder almacenar pesos en gramos (valores de 500 a 5000+ gramos).
     * SMALLINT puede almacenar valores de 0 a 32,767 (suficiente para pesos en gramos).
     */
    public function up(): void
    {
        Schema::table('recien_nacidos', function (Blueprint $table) {
            // Cambiar de DECIMAL(5,2) a SMALLINT para almacenar pesos en gramos
            // SMALLINT puede almacenar valores de 0 a 32,767 gramos (32.7 kg)
            $table->smallInteger('peso')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     * 
     * Revertir el cambio, volviendo a DECIMAL(5,2)
     */
    public function down(): void
    {
        Schema::table('recien_nacidos', function (Blueprint $table) {
            // Revertir a DECIMAL(5,2)
            $table->decimal('peso', 5, 2)->nullable()->change();
        });
    }
};
