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
        Schema::create('solicitudes', function (Blueprint $table) {
            $table->id();
            $table->integer('id_tipo_documento');
            $table->string('numero_documento', 20);
            $table->integer('codigo_red');
            $table->string('codigo_microred', 255);
            $table->string('id_establecimiento', 255);
            $table->string('motivo', 255);
            $table->string('cargo', 255);
            $table->string('celular', 20);
            $table->string('correo', 255);
            $table->boolean('accept_terms')->default(false);
            $table->enum('estado', ['pendiente', 'aprobada', 'rechazada'])->default('pendiente');
            $table->timestamps();
            
            // Índices
            $table->index('numero_documento');
            $table->index('estado');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('solicitudes');
    }
};
