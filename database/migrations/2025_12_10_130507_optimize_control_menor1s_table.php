<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Optimiza la tabla control_menor1s:
     * 1. Elimina campo 'estado' (redundante - se calcula dinámicamente)
     * 2. Agrega índice único compuesto (id_niño, numero_control) para evitar duplicados
     */
    public function up(): void
    {
        if (Schema::hasTable('control_menor1s')) {
            Schema::table('control_menor1s', function (Blueprint $table) {
                // Eliminar campo 'estado' si existe (se calcula dinámicamente)
                if (Schema::hasColumn('control_menor1s', 'estado')) {
                    $table->dropColumn('estado');
                }
            });

            // Agregar índice único compuesto para evitar duplicados
            // Un niño no puede tener dos controles con el mismo número
            Schema::table('control_menor1s', function (Blueprint $table) {
                // Verificar si el índice único ya existe
                $connection = Schema::getConnection();
                $database = $connection->getDatabaseName();
                
                $indexExists = $connection->select(
                    "SELECT COUNT(*) as count 
                     FROM information_schema.statistics 
                     WHERE table_schema = ? 
                     AND table_name = ? 
                     AND index_name = ?",
                    [$database, 'control_menor1s', 'control_menor1s_id_niño_numero_control_unique']
                );
                
                if ($indexExists[0]->count == 0) {
                    $table->unique(['id_niño', 'numero_control'], 'control_menor1s_id_niño_numero_control_unique');
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
                // Eliminar índice único
                $table->dropUnique('control_menor1s_id_niño_numero_control_unique');
                
                // Restaurar campo 'estado' si se necesita rollback
                if (!Schema::hasColumn('control_menor1s', 'estado')) {
                    $table->string('estado', 20)->nullable()->after('fecha');
                }
            });
        }
    }
};
