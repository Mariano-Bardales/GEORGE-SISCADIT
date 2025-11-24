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
        Schema::create('visita_domiciliarias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_niño')->constrained('ninos')->onDelete('cascade');
            $table->string('grupo_visita', 2); // A, B, C, D
            $table->date('fecha_visita')->nullable();
            $table->integer('numero_visitas')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visita_domiciliarias');
    }
};
