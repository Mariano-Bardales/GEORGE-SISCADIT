<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Elimina campos de estado de vacuna_rns:
     * - estado_bcg (se puede determinar por fecha_bcg)
     * - estado_hvb (se puede determinar por fecha_hvb)
     * - cumple_BCG_HVB (se puede calcular dinámicamente)
     */
    public function up(): void
    {
        if (Schema::hasTable('vacuna_rns')) {
            Schema::table('vacuna_rns', function (Blueprint $table) {
                if (Schema::hasColumn('vacuna_rns', 'estado_bcg')) {
                    $table->dropColumn('estado_bcg');
                }
                if (Schema::hasColumn('vacuna_rns', 'estado_hvb')) {
                    $table->dropColumn('estado_hvb');
                }
                if (Schema::hasColumn('vacuna_rns', 'cumple_BCG_HVB')) {
                    $table->dropColumn('cumple_BCG_HVB');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('vacuna_rns')) {
            Schema::table('vacuna_rns', function (Blueprint $table) {
                if (!Schema::hasColumn('vacuna_rns', 'estado_bcg')) {
                    $table->string('estado_bcg', 20)->nullable()->after('fecha_bcg');
                }
                if (!Schema::hasColumn('vacuna_rns', 'estado_hvb')) {
                    $table->string('estado_hvb', 20)->nullable()->after('fecha_hvb');
                }
                if (!Schema::hasColumn('vacuna_rns', 'cumple_BCG_HVB')) {
                    $table->string('cumple_BCG_HVB', 10)->nullable()->after('estado_hvb');
                }
            });
        }
    }
};
