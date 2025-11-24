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
        Schema::create('madres', function (Blueprint $table) {
            $table->id();
            $table->string('dni', 15)->nullable();
            $table->string('apellidos_nombres', 150);
            $table->string('celular', 20)->nullable();
            $table->text('domicilio')->nullable();
            $table->text('referencia_direccion')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('madres');
    }
};
