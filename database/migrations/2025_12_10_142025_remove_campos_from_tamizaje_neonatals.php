<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Elimina campos innecesarios de tamizaje_neonatals:
     * - fecha_29_dias (se puede calcular dinámicamente)
     * - cumple_tam_neo (se puede calcular dinámicamente)
     */
    public function up(): void
    {
        if (Schema::hasTable('tamizaje_neonatals')) {
            Schema::table('tamizaje_neonatals', function (Blueprint $table) {
                if (Schema::hasColumn('tamizaje_neonatals', 'fecha_29_dias')) {
                    $table->dropColumn('fecha_29_dias');
                }
                if (Schema::hasColumn('tamizaje_neonatals', 'cumple_tam_neo')) {
                    $table->dropColumn('cumple_tam_neo');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('tamizaje_neonatals')) {
            Schema::table('tamizaje_neonatals', function (Blueprint $table) {
                if (!Schema::hasColumn('tamizaje_neonatals', 'fecha_29_dias')) {
                    $table->date('fecha_29_dias')->nullable()->after('id_niño');
                }
                if (!Schema::hasColumn('tamizaje_neonatals', 'cumple_tam_neo')) {
                    $table->string('cumple_tam_neo', 10)->nullable()->after('galen_fecha_tam_feo');
                }
            });
        }
    }
};
