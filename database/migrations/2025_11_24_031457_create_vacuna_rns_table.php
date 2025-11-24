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
        Schema::create('vacuna_rns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_niño')->constrained('ninos')->onDelete('cascade');
            $table->date('fecha_bcg')->nullable();
            $table->integer('edad_bcg')->nullable();
            $table->string('estado_bcg', 20)->nullable();
            $table->date('fecha_hvb')->nullable();
            $table->integer('edad_hvb')->nullable();
            $table->string('estado_hvb', 20)->nullable();
            $table->string('cumple_BCG_HVB', 10)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vacuna_rns');
    }
};
