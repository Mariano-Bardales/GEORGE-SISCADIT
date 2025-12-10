<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Elimina campos médicos y timestamps de control_rns:
     * - peso, talla, perimetro_cefalico (campos médicos innecesarios)
     * - created_at, updated_at, deleted_at (timestamps innecesarios)
     */
    public function up(): void
    {
        if (Schema::hasTable('control_rns')) {
            Schema::table('control_rns', function (Blueprint $table) {
                // Eliminar campos médicos
                if (Schema::hasColumn('control_rns', 'peso')) {
                    $table->dropColumn('peso');
                }
                if (Schema::hasColumn('control_rns', 'talla')) {
                    $table->dropColumn('talla');
                }
                if (Schema::hasColumn('control_rns', 'perimetro_cefalico')) {
                    $table->dropColumn('perimetro_cefalico');
                }
                
                // Eliminar timestamps
                if (Schema::hasColumn('control_rns', 'created_at')) {
                    $table->dropTimestamps();
                }
                if (Schema::hasColumn('control_rns', 'deleted_at')) {
                    $table->dropSoftDeletes();
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
                // Restaurar campos médicos
                if (!Schema::hasColumn('control_rns', 'peso')) {
                    $table->decimal('peso', 5, 2)->nullable()->after('fecha');
                }
                if (!Schema::hasColumn('control_rns', 'talla')) {
                    $table->decimal('talla', 5, 2)->nullable()->after('peso');
                }
                if (!Schema::hasColumn('control_rns', 'perimetro_cefalico')) {
                    $table->decimal('perimetro_cefalico', 5, 2)->nullable()->after('talla');
                }
                
                // Restaurar timestamps
                if (!Schema::hasColumn('control_rns', 'created_at')) {
                    $table->timestamps();
                }
                if (!Schema::hasColumn('control_rns', 'deleted_at')) {
                    $table->softDeletes();
                }
            });
        }
    }
};
