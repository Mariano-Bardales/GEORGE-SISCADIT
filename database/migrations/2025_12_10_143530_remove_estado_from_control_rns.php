<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Elimina el campo 'estado' de control_rns
     * ya que se calcula dinámicamente con RangosCredService.
     */
    public function up(): void
    {
        if (Schema::hasTable('control_rns')) {
            Schema::table('control_rns', function (Blueprint $table) {
                if (Schema::hasColumn('control_rns', 'estado')) {
                    $table->dropColumn('estado');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('control_rns')) {
            Schema::table('control_rns', function (Blueprint $table) {
                if (!Schema::hasColumn('control_rns', 'estado')) {
                    $table->string('estado', 20)->nullable()->after('fecha');
                }
            });
        }
    }
};
