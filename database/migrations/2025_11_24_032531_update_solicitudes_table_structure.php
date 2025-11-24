<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Renombrar tabla si existe con el nombre antiguo
        if (Schema::hasTable('solicituds') && !Schema::hasTable('solicitudes')) {
            Schema::rename('solicituds', 'solicitudes');
        }

        // Si la tabla solicitudes existe, verificar y actualizar estructura
        if (Schema::hasTable('solicitudes')) {
            // Usar SQL directo para renombrar columnas si es necesario
            $columns = Schema::getColumnListing('solicitudes');
            
            // Renombrar columnas usando SQL directo
            if (in_array('Id_Tipo_Documento', $columns) && !in_array('id_tipo_documento', $columns)) {
                DB::statement('ALTER TABLE solicitudes CHANGE Id_Tipo_Documento id_tipo_documento INT(11) NOT NULL');
            }
            if (in_array('Numero_Documento', $columns) && !in_array('numero_documento', $columns)) {
                DB::statement('ALTER TABLE solicitudes CHANGE Numero_Documento numero_documento VARCHAR(20) NOT NULL');
            }
            if (in_array('Codigo_Red', $columns) && !in_array('codigo_red', $columns)) {
                DB::statement('ALTER TABLE solicitudes CHANGE Codigo_Red codigo_red INT(11) NOT NULL');
            }
            if (in_array('Codigo_Microred', $columns) && !in_array('codigo_microred', $columns)) {
                DB::statement('ALTER TABLE solicitudes CHANGE Codigo_Microred codigo_microred VARCHAR(255) NOT NULL');
            }
            if (in_array('Id_Establecimiento', $columns) && !in_array('id_establecimiento', $columns)) {
                DB::statement('ALTER TABLE solicitudes CHANGE Id_Establecimiento id_establecimiento VARCHAR(255) NOT NULL');
            }
            if (in_array('acceptTerms', $columns) && !in_array('accept_terms', $columns)) {
                DB::statement('ALTER TABLE solicitudes CHANGE acceptTerms accept_terms TINYINT(1) NOT NULL DEFAULT 0');
            }
            
            // Eliminar columna observaciones si existe
            if (in_array('observaciones', $columns)) {
                Schema::table('solicitudes', function (Blueprint $table) {
                    $table->dropColumn('observaciones');
                });
            }
            
            // Agregar índices si no existen
            Schema::table('solicitudes', function (Blueprint $table) {
                if (!in_array('solicitudes_numero_documento_index', $this->getIndexes('solicitudes'))) {
                    $table->index('numero_documento', 'solicitudes_numero_documento_index');
                }
                if (!in_array('solicitudes_estado_index', $this->getIndexes('solicitudes'))) {
                    $table->index('estado', 'solicitudes_estado_index');
                }
            });
        }
    }
    
    private function getIndexes($table)
    {
        $indexes = [];
        $results = DB::select("SHOW INDEXES FROM {$table}");
        foreach ($results as $result) {
            $indexes[] = $result->Key_name;
        }
        return $indexes;
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revertir cambios si es necesario
        if (Schema::hasTable('solicitudes')) {
            Schema::rename('solicitudes', 'solicituds');
        }
    }
};
