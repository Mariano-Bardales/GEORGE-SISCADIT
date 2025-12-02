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
        // Verificar si la tabla se llama 'controles_rn' o 'control_rns'
        $tableName = Schema::hasTable('controles_rn') 
            ? 'controles_rn' 
            : 'control_rns';
        
        if (!Schema::hasTable($tableName)) {
            return; // La tabla no existe, no hacer nada
        }
            
        Schema::table($tableName, function (Blueprint $table) use ($tableName) {
            // Verificar si los campos ya existen antes de agregarlos
            if (!Schema::hasColumn($tableName, 'peso')) {
                $table->decimal('peso', 5, 2)->nullable()->after('estado');
            }
            if (!Schema::hasColumn($tableName, 'talla')) {
                $table->decimal('talla', 5, 2)->nullable()->after('peso');
            }
            if (!Schema::hasColumn($tableName, 'perimetro_cefalico')) {
                $table->decimal('perimetro_cefalico', 5, 2)->nullable()->after('talla');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tableName = Schema::hasTable('controles_rn') 
            ? 'controles_rn' 
            : 'control_rns';
        
        if (!Schema::hasTable($tableName)) {
            return;
        }
            
        Schema::table($tableName, function (Blueprint $table) use ($tableName) {
            if (Schema::hasColumn($tableName, 'perimetro_cefalico')) {
                $table->dropColumn('perimetro_cefalico');
            }
            if (Schema::hasColumn($tableName, 'talla')) {
                $table->dropColumn('talla');
            }
            if (Schema::hasColumn($tableName, 'peso')) {
                $table->dropColumn('peso');
            }
        });
    }
};
