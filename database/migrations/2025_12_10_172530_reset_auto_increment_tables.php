<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Reorganiza los IDs de todas las tablas para que empiecen desde 1
     * y sean consecutivos, y resetea el AUTO_INCREMENT.
     */
    public function up(): void
    {
        // Desactivar verificación de claves foráneas temporalmente
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        try {
            // 1. Reorganizar IDs de usuarios
            $this->reorganizarIds('users', 'id');
            
            // 2. Reorganizar IDs de solicitudes
            $this->reorganizarIds('solicitudes', 'id');
            
            // 3. Reorganizar IDs de niños (importante: antes de controles)
            $this->reorganizarIds('ninos', 'id');
            
            // 4. Reorganizar IDs de madres (actualizar id_niño también)
            $this->reorganizarIdsConRelacion('madres', 'id', 'id_niño', 'ninos', 'id');
            
            // 5. Reorganizar IDs de datos_extras (actualizar id_niño también)
            $this->reorganizarIdsConRelacion('datos_extras', 'id', 'id_niño', 'ninos', 'id');
            
            // 6. Reorganizar IDs de recien_nacidos (actualizar id_niño también)
            $this->reorganizarIdsConRelacion('recien_nacidos', 'id', 'id_niño', 'ninos', 'id');
            
            // 7. Reorganizar IDs de tamizaje_neonatals (actualizar id_niño también)
            $this->reorganizarIdsConRelacion('tamizaje_neonatals', 'id', 'id_niño', 'ninos', 'id');
            
            // 8. Reorganizar IDs de vacuna_rns (actualizar id_niño también)
            $this->reorganizarIdsConRelacion('vacuna_rns', 'id', 'id_niño', 'ninos', 'id');
            
            // 9. Reorganizar IDs de visita_domiciliarias (actualizar id_niño también)
            $this->reorganizarIdsConRelacion('visita_domiciliarias', 'id', 'id_niño', 'ninos', 'id');
            
            // 10. Reorganizar IDs de control_rns (actualizar id_niño también)
            $this->reorganizarIdsConRelacion('control_rns', 'id', 'id_niño', 'ninos', 'id');
            
            // 11. Reorganizar IDs de control_menor1s (actualizar id_niño también)
            $this->reorganizarIdsConRelacion('control_menor1s', 'id', 'id_niño', 'ninos', 'id');
            
        } finally {
            // Reactivar verificación de claves foráneas
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }
    }

    /**
     * Reorganizar IDs de una tabla simple (sin relaciones)
     */
    private function reorganizarIds($tableName, $idColumn)
    {
        if (!Schema::hasTable($tableName)) {
            return;
        }
        
        // Obtener todos los registros ordenados por ID actual
        $registros = DB::table($tableName)->orderBy($idColumn)->get();
        
        if ($registros->isEmpty()) {
            // Si no hay registros, resetear AUTO_INCREMENT a 1
            DB::statement("ALTER TABLE `{$tableName}` AUTO_INCREMENT = 1");
            return;
        }
        
        // Crear tabla temporal para almacenar el mapeo de IDs
        $mapeoIds = [];
        $nuevoId = 1;
        
        foreach ($registros as $registro) {
            $idViejo = $registro->{$idColumn};
            if ($idViejo != $nuevoId) {
                $mapeoIds[$idViejo] = $nuevoId;
            }
            $nuevoId++;
        }
        
        // Si no hay cambios necesarios, solo resetear AUTO_INCREMENT
        if (empty($mapeoIds)) {
            $maxId = DB::table($tableName)->max($idColumn) ?? 0;
            DB::statement("ALTER TABLE `{$tableName}` AUTO_INCREMENT = " . ($maxId + 1));
            return;
        }
        
        // Aplicar cambios de IDs (empezar desde el más alto para evitar conflictos)
        krsort($mapeoIds);
        
        foreach ($mapeoIds as $idViejo => $idNuevo) {
            // Usar un ID temporal muy alto para evitar conflictos
            $idTemporal = 999999 + $idViejo;
            DB::table($tableName)->where($idColumn, $idViejo)->update([$idColumn => $idTemporal]);
        }
        
        // Ahora asignar los IDs finales
        foreach ($mapeoIds as $idViejo => $idNuevo) {
            $idTemporal = 999999 + $idViejo;
            DB::table($tableName)->where($idColumn, $idTemporal)->update([$idColumn => $idNuevo]);
        }
        
        // Resetear AUTO_INCREMENT
        $maxId = DB::table($tableName)->max($idColumn) ?? 0;
        DB::statement("ALTER TABLE `{$tableName}` AUTO_INCREMENT = " . ($maxId + 1));
    }

    /**
     * Reorganizar IDs de una tabla con relación a otra tabla
     */
    private function reorganizarIdsConRelacion($tableName, $idColumn, $foreignKeyColumn, $relatedTable, $relatedIdColumn)
    {
        if (!Schema::hasTable($tableName) || !Schema::hasTable($relatedTable)) {
            return;
        }
        
        // Obtener mapeo de IDs de la tabla relacionada
        $mapeoRelacionados = [];
        $relacionados = DB::table($relatedTable)->orderBy($relatedIdColumn)->get();
        $nuevoIdRelacionado = 1;
        
        foreach ($relacionados as $relacionado) {
            $idViejo = $relacionado->{$relatedIdColumn};
            if ($idViejo != $nuevoIdRelacionado) {
                $mapeoRelacionados[$idViejo] = $nuevoIdRelacionado;
            }
            $nuevoIdRelacionado++;
        }
        
        // Actualizar foreign keys primero si hay cambios
        if (!empty($mapeoRelacionados)) {
            foreach ($mapeoRelacionados as $idViejo => $idNuevo) {
                DB::table($tableName)->where($foreignKeyColumn, $idViejo)->update([$foreignKeyColumn => $idNuevo]);
            }
        }
        
        // Ahora reorganizar los IDs de esta tabla
        $this->reorganizarIds($tableName, $idColumn);
    }

    /**
     * Reverse the migrations.
     * 
     * No se puede revertir esta operación de forma segura,
     * ya que los IDs originales se pierden.
     */
    public function down(): void
    {
        // No hacer nada - no se puede revertir de forma segura
    }
};
