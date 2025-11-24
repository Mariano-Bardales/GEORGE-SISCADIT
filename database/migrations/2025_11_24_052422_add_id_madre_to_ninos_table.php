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
        Schema::table('ninos', function (Blueprint $table) {
            // Verificar si la columna no existe antes de agregarla
            if (!Schema::hasColumn('ninos', 'id_madre')) {
                $table->foreignId('id_madre')->nullable()->after('id')->constrained('madres')->onDelete('cascade');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ninos', function (Blueprint $table) {
            // Eliminar la foreign key primero
            $table->dropForeign(['id_madre']);
            // Luego eliminar la columna
            $table->dropColumn('id_madre');
        });
    }
};
