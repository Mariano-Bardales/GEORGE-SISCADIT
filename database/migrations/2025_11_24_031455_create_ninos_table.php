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
        if (Schema::hasTable('ninos')) {
            return;
        }
        
        Schema::create('ninos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_madre')->constrained('madres')->onDelete('cascade');
            $table->string('establecimiento', 150)->nullable();
            $table->string('tipo_doc', 10)->nullable();
            $table->string('numero_doc', 20)->nullable();
            $table->string('apellidos_nombres', 150);
            $table->date('fecha_nacimiento')->nullable();
            $table->string('genero', 10)->nullable();
            $table->integer('edad_meses')->nullable();
            $table->integer('edad_dias')->nullable();
            $table->text('datos_extras')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ninos');
    }
};
