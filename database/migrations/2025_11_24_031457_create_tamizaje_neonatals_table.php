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
        Schema::create('tamizaje_neonatals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_niño')->constrained('ninos')->onDelete('cascade');
            $table->date('fecha_29_dias')->nullable();
            $table->date('fecha_tam_neo')->nullable();
            $table->integer('edad_tam_neo')->nullable();
            $table->date('galen_fecha_tam_feo')->nullable();
            $table->integer('galen_dias_tam_feo')->nullable();
            $table->string('cumple_tam_neo', 10)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tamizaje_neonatals');
    }
};
