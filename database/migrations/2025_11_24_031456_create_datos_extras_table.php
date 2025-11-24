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
        Schema::create('datos_extras', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_niño')->constrained('ninos')->onDelete('cascade');
            $table->string('red', 100)->nullable();
            $table->string('microred', 100)->nullable();
            $table->string('eess_nacimiento', 150)->nullable();
            $table->string('distrito', 100)->nullable();
            $table->string('provincia', 100)->nullable();
            $table->string('departamento', 100)->nullable();
            $table->string('seguro', 100)->nullable();
            $table->string('programa', 100)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('datos_extras');
    }
};
