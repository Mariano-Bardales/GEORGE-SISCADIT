<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Modifica visitas_domiciliarias:
     * - Elimina grupo_visita y numero_visitas
     * - Agrega control_de_visita (1, 2, 3, 4) con sus rangos de días:
     *   Control 1: 28 días
     *   Control 2: 60-150 días (2-5 meses)
     *   Control 3: 180-240 días (6-8 meses)
     *   Control 4: 270-330 días (9-11 meses)
     */
    public function up(): void
    {
        // La tabla se llama visita_domiciliarias (singular)
        $tableName = 'visita_domiciliarias';
        
        if (Schema::hasTable($tableName)) {
            Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                // Eliminar campos antiguos
                if (Schema::hasColumn($tableName, 'grupo_visita')) {
                    $table->dropColumn('grupo_visita');
                }
                if (Schema::hasColumn($tableName, 'numero_visitas')) {
                    $table->dropColumn('numero_visitas');
                }
                
                // Agregar nuevo campo control_de_visita
                if (!Schema::hasColumn($tableName, 'control_de_visita')) {
                    $table->integer('control_de_visita')->after('id_niño')->comment('1=28 días, 2=60-150 días, 3=180-240 días, 4=270-330 días');
                }
            });
            
            // Agregar índice único para evitar duplicados
            Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                $connection = Schema::getConnection();
                $database = $connection->getDatabaseName();
                
                $indexExists = $connection->select(
                    "SELECT COUNT(*) as count 
                     FROM information_schema.statistics 
                     WHERE table_schema = ? 
                     AND table_name = ? 
                     AND index_name = ?",
                    [$database, $tableName, 'visita_domiciliarias_id_niño_control_unique']
                );
                
                if ($indexExists[0]->count == 0) {
                    $table->unique(['id_niño', 'control_de_visita'], 'visita_domiciliarias_id_niño_control_unique');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tableName = 'visita_domiciliarias';
        
        if (Schema::hasTable($tableName)) {
            Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                $connection = Schema::getConnection();
                $database = $connection->getDatabaseName();
                
                // Verificar si el índice existe antes de eliminarlo
                $indexExists = $connection->select(
                    "SELECT COUNT(*) as count 
                     FROM information_schema.statistics 
                     WHERE table_schema = ? 
                     AND table_name = ? 
                     AND index_name = ?",
                    [$database, $tableName, 'visita_domiciliarias_id_niño_control_unique']
                );
                
                if ($indexExists[0]->count > 0) {
                    $table->dropUnique('visita_domiciliarias_id_niño_control_unique');
                }
                
                // Eliminar nuevo campo
                if (Schema::hasColumn($tableName, 'control_de_visita')) {
                    $table->dropColumn('control_de_visita');
                }
                
                // Restaurar campos antiguos
                if (!Schema::hasColumn($tableName, 'grupo_visita')) {
                    $table->string('grupo_visita', 2)->after('id_niño');
                }
                if (!Schema::hasColumn($tableName, 'numero_visitas')) {
                    $table->integer('numero_visitas')->default(0)->after('fecha_visita');
                }
            });
        }
    }
};
