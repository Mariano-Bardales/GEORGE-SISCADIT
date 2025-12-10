<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Elimina los campos estado_cred_once y estado_cred_final de control_menor1s
     * ya que son innecesarios y no se usan realmente.
     */
    public function up(): void
    {
        if (Schema::hasTable('control_menor1s')) {
            Schema::table('control_menor1s', function (Blueprint $table) {
                // Eliminar campos si existen
                if (Schema::hasColumn('control_menor1s', 'estado_cred_once')) {
                    $table->dropColumn('estado_cred_once');
                }
                if (Schema::hasColumn('control_menor1s', 'estado_cred_final')) {
                    $table->dropColumn('estado_cred_final');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('control_menor1s')) {
            Schema::table('control_menor1s', function (Blueprint $table) {
                // Restaurar campos si se necesita rollback
                if (!Schema::hasColumn('control_menor1s', 'estado_cred_once')) {
                    $table->string('estado_cred_once', 20)->nullable()->after('fecha');
                }
                if (!Schema::hasColumn('control_menor1s', 'estado_cred_final')) {
                    $table->string('estado_cred_final', 20)->nullable()->after('estado_cred_once');
                }
            });
        }
    }
};
