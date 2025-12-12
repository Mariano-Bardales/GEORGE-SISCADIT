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
        // Obtener el nombre exacto de la columna desde INFORMATION_SCHEMA
        $columnName = DB::selectOne("
            SELECT COLUMN_NAME 
            FROM INFORMATION_SCHEMA.COLUMNS 
            WHERE TABLE_SCHEMA = DATABASE() 
            AND TABLE_NAME = 'control_menor1s' 
            AND COLUMN_NAME LIKE 'id_ni%'
        ");
        
        if ($columnName && $columnName->COLUMN_NAME !== 'id_niño') {
            // Renombrar la columna con codificación correcta
            $tables = ['control_menor1s', 'control_rns', 'tamizaje_neonatals', 'vacuna_rns', 'visita_domiciliarias', 'datos_extras', 'recien_nacidos', 'madres'];
            
            foreach ($tables as $table) {
                $column = DB::selectOne("
                    SELECT COLUMN_NAME 
                    FROM INFORMATION_SCHEMA.COLUMNS 
                    WHERE TABLE_SCHEMA = DATABASE() 
                    AND TABLE_NAME = ? 
                    AND COLUMN_NAME LIKE 'id_ni%'
                ", [$table]);
                
                if ($column && $column->COLUMN_NAME !== 'id_niño') {
                    DB::statement("ALTER TABLE `{$table}` CHANGE COLUMN `{$column->COLUMN_NAME}` `id_niño` BIGINT UNSIGNED NOT NULL");
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
