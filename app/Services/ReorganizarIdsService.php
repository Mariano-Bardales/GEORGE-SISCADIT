<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Nino;
use App\Models\ControlRn;
use App\Models\ControlMenor1;
use App\Models\Madre;
use App\Models\DatosExtra;

/**
 * Servicio para reorganizar IDs después de la importación
 * Asegura que los IDs sean consecutivos y estén bien ordenados
 */
class ReorganizarIdsService
{
    /**
     * Reorganizar SOLO los IDs de niños primero (antes de importar controles)
     * Esto asegura que los IDs empiecen desde 1 y sean consecutivos
     * También resetea el AUTO_INCREMENT para que los nuevos IDs sigan desde el siguiente consecutivo
     */
    public static function reorganizarIdsNinosPrimero()
    {
        try {
            DB::beginTransaction();
            
            $resultado = self::reorganizarIdsNinos();
            
            // Resetear AUTO_INCREMENT para que los nuevos IDs sigan desde el siguiente consecutivo
            $maxId = Nino::max('id_niño') ?? 0;
            $siguienteId = $maxId + 1;
            
            try {
                DB::statement("ALTER TABLE `niños` AUTO_INCREMENT = {$siguienteId}");
                Log::info("AUTO_INCREMENT de niños reseteado a: {$siguienteId}");
            } catch (\Exception $e) {
                // Si no se puede resetear, no es crítico
                Log::warning("No se pudo resetear AUTO_INCREMENT de niños: " . $e->getMessage());
            }
            
            DB::commit();
            
            Log::info('IDs de niños reorganizados exitosamente', array_merge($resultado, ['siguiente_id' => $siguienteId]));
            
            return array_merge($resultado, ['siguiente_id' => $siguienteId, 'mensaje_adicional' => "El siguiente ID será: {$siguienteId}"]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al reorganizar IDs de niños: ' . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Reorganizar todos los IDs para que sean consecutivos
     */
    public static function reorganizarTodosLosIds()
    {
        try {
            DB::beginTransaction();
            
            $resultados = [
                'ninos' => self::reorganizarIdsNinos(),
                'controles_rn' => self::reorganizarIdsControlesRn(),
                'controles_cred' => self::reorganizarIdsControlesCred(),
                'madres' => self::reorganizarIdsMadres(),
                'datos_extra' => self::reorganizarIdsDatosExtra(),
            ];
            
            DB::commit();
            
            Log::info('IDs reorganizados exitosamente', $resultados);
            
            return $resultados;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al reorganizar IDs: ' . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Reorganizar IDs de niños
     */
    protected static function reorganizarIdsNinos()
    {
        // Obtener todos los niños ordenados por ID actual
        $ninos = Nino::orderBy('id_niño', 'asc')->get();
        
        $nuevoId = 1;
        $mapeoIds = []; // Mapa: id_antiguo => id_nuevo
        
        foreach ($ninos as $nino) {
            $idAntiguo = $nino->id_niño;
            
            if ($idAntiguo != $nuevoId) {
                $mapeoIds[$idAntiguo] = $nuevoId;
            }
            
            $nuevoId++;
        }
        
        // Si no hay cambios, retornar
        if (empty($mapeoIds)) {
            return ['actualizados' => 0, 'mensaje' => 'Los IDs de niños ya estaban organizados'];
        }
        
        // Actualizar IDs de niños y sus relaciones
        $totalActualizados = 0;
        
        foreach ($mapeoIds as $idAntiguo => $idNuevo) {
            // Verificar que el ID nuevo no esté en uso
            $existeNuevoId = Nino::where('id_niño', $idNuevo)->exists();
            
            if (!$existeNuevoId) {
                // Usar un ID temporal para evitar conflictos
                $idTemporal = 999999999 - $idAntiguo;
                
                // Paso 1: Cambiar a ID temporal
                DB::table('niños')->where('id_niño', $idAntiguo)->update(['id_niño' => $idTemporal]);
                
                // Paso 2: Actualizar todas las tablas relacionadas
                self::actualizarRelaciones($idAntiguo, $idTemporal);
                
                // Paso 3: Cambiar a ID definitivo
                DB::table('niños')->where('id_niño', $idTemporal)->update(['id_niño' => $idNuevo]);
                
                // Paso 4: Actualizar relaciones al ID definitivo
                self::actualizarRelaciones($idTemporal, $idNuevo);
                
                $totalActualizados++;
            }
        }
        
        return ['actualizados' => $totalActualizados, 'mensaje' => "Se reorganizaron {$totalActualizados} IDs de niños"];
    }
    
    /**
     * Reorganizar IDs de controles RN
     */
    protected static function reorganizarIdsControlesRn()
    {
        // Obtener todos los controles ordenados por id_niño y numero_control
        $controles = ControlRn::orderBy('id_niño', 'asc')
                             ->orderBy('numero_control', 'asc')
                             ->orderBy('id_crn', 'asc')
                             ->get();
        
        $nuevoId = 1;
        $mapeoIds = [];
        
        foreach ($controles as $control) {
            $idAntiguo = $control->id_crn;
            
            if ($idAntiguo != $nuevoId) {
                $mapeoIds[$idAntiguo] = $nuevoId;
            }
            
            $nuevoId++;
        }
        
        if (empty($mapeoIds)) {
            return ['actualizados' => 0, 'mensaje' => 'Los IDs de controles RN ya estaban organizados'];
        }
        
        // Actualizar IDs
        $totalActualizados = 0;
        
        foreach ($mapeoIds as $idAntiguo => $idNuevo) {
            $existeNuevoId = ControlRn::where('id_crn', $idNuevo)->exists();
            
            if (!$existeNuevoId) {
                $idTemporal = 999999999 - $idAntiguo;
                
                DB::table('controles_rn')->where('id_crn', $idAntiguo)->update(['id_crn' => $idTemporal]);
                DB::table('controles_rn')->where('id_crn', $idTemporal)->update(['id_crn' => $idNuevo]);
                
                $totalActualizados++;
            }
        }
        
        // Resetear AUTO_INCREMENT
        if ($totalActualizados > 0) {
            try {
                $maxId = ControlRn::max('id_crn') ?? 0;
                $siguienteId = $maxId + 1;
                DB::statement("ALTER TABLE `controles_rn` AUTO_INCREMENT = {$siguienteId}");
            } catch (\Exception $e) {
                Log::warning("No se pudo resetear AUTO_INCREMENT de controles_rn: " . $e->getMessage());
            }
        }
        
        return ['actualizados' => $totalActualizados, 'mensaje' => "Se reorganizaron {$totalActualizados} IDs de controles RN"];
    }
    
    /**
     * Reorganizar IDs de controles CRED
     */
    protected static function reorganizarIdsControlesCred()
    {
        // Obtener todos los controles ordenados por id_niño y numero_control
        $controles = ControlMenor1::orderBy('id_niño', 'asc')
                                  ->orderBy('numero_control', 'asc')
                                  ->orderBy('id_cred', 'asc')
                                  ->get();
        
        $nuevoId = 1;
        $mapeoIds = [];
        
        foreach ($controles as $control) {
            $idAntiguo = $control->id_cred;
            
            if ($idAntiguo != $nuevoId) {
                $mapeoIds[$idAntiguo] = $nuevoId;
            }
            
            $nuevoId++;
        }
        
        if (empty($mapeoIds)) {
            return ['actualizados' => 0, 'mensaje' => 'Los IDs de controles CRED ya estaban organizados'];
        }
        
        // Actualizar IDs
        $totalActualizados = 0;
        
        foreach ($mapeoIds as $idAntiguo => $idNuevo) {
            $existeNuevoId = ControlMenor1::where('id_cred', $idNuevo)->exists();
            
            if (!$existeNuevoId) {
                $idTemporal = 999999999 - $idAntiguo;
                
                DB::table('controles_menor1')->where('id_cred', $idAntiguo)->update(['id_cred' => $idTemporal]);
                DB::table('controles_menor1')->where('id_cred', $idTemporal)->update(['id_cred' => $idNuevo]);
                
                $totalActualizados++;
            }
        }
        
        // Resetear AUTO_INCREMENT
        if ($totalActualizados > 0) {
            try {
                $maxId = ControlMenor1::max('id_cred') ?? 0;
                $siguienteId = $maxId + 1;
                DB::statement("ALTER TABLE `controles_menor1` AUTO_INCREMENT = {$siguienteId}");
            } catch (\Exception $e) {
                Log::warning("No se pudo resetear AUTO_INCREMENT de controles_menor1: " . $e->getMessage());
            }
        }
        
        return ['actualizados' => $totalActualizados, 'mensaje' => "Se reorganizaron {$totalActualizados} IDs de controles CRED"];
    }
    
    /**
     * Reorganizar IDs de madres
     */
    protected static function reorganizarIdsMadres()
    {
        $madres = Madre::orderBy('id_madre', 'asc')->get();
        
        $nuevoId = 1;
        $mapeoIds = [];
        
        foreach ($madres as $madre) {
            $idAntiguo = $madre->id_madre;
            
            if ($idAntiguo != $nuevoId) {
                $mapeoIds[$idAntiguo] = $nuevoId;
            }
            
            $nuevoId++;
        }
        
        if (empty($mapeoIds)) {
            return ['actualizados' => 0, 'mensaje' => 'Los IDs de madres ya estaban organizados'];
        }
        
        $totalActualizados = 0;
        
        foreach ($mapeoIds as $idAntiguo => $idNuevo) {
            $existeNuevoId = Madre::where('id_madre', $idNuevo)->exists();
            
            if (!$existeNuevoId) {
                $idTemporal = 999999999 - $idAntiguo;
                
                DB::table('madres')->where('id_madre', $idAntiguo)->update(['id_madre' => $idTemporal]);
                DB::table('madres')->where('id_madre', $idTemporal)->update(['id_madre' => $idNuevo]);
                
                $totalActualizados++;
            }
        }
        
        return ['actualizados' => $totalActualizados, 'mensaje' => "Se reorganizaron {$totalActualizados} IDs de madres"];
    }
    
    /**
     * Reorganizar IDs de datos extra
     */
    protected static function reorganizarIdsDatosExtra()
    {
        $datosExtra = DatosExtra::orderBy('id_extra', 'asc')->get();
        
        $nuevoId = 1;
        $mapeoIds = [];
        
        foreach ($datosExtra as $extra) {
            $idAntiguo = $extra->id_extra;
            
            if ($idAntiguo != $nuevoId) {
                $mapeoIds[$idAntiguo] = $nuevoId;
            }
            
            $nuevoId++;
        }
        
        if (empty($mapeoIds)) {
            return ['actualizados' => 0, 'mensaje' => 'Los IDs de datos extra ya estaban organizados'];
        }
        
        $totalActualizados = 0;
        
        foreach ($mapeoIds as $idAntiguo => $idNuevo) {
            $existeNuevoId = DatosExtra::where('id_extra', $idNuevo)->exists();
            
            if (!$existeNuevoId) {
                $idTemporal = 999999999 - $idAntiguo;
                
                DB::table('datos_extras')->where('id_extra', $idAntiguo)->update(['id_extra' => $idTemporal]);
                DB::table('datos_extras')->where('id_extra', $idTemporal)->update(['id_extra' => $idNuevo]);
                
                $totalActualizados++;
            }
        }
        
        return ['actualizados' => $totalActualizados, 'mensaje' => "Se reorganizaron {$totalActualizados} IDs de datos extra"];
    }
    
    /**
     * Actualizar relaciones cuando cambia el ID de un niño
     */
    protected static function actualizarRelaciones($idAntiguo, $idNuevo)
    {
        // Actualizar en todas las tablas que referencian id_niño
        $tablas = [
            'madres' => 'id_niño',
            'datos_extras' => 'id_niño',
            'controles_rn' => 'id_niño',
            'controles_menor1' => 'id_niño',
            'tamizaje_neonatal' => 'id_niño',
            'vacuna_rns' => 'id_niño',
            'visitas_domiciliarias' => 'id_niño',
            'recien_nacido' => 'id_niño',
        ];
        
        foreach ($tablas as $tabla => $columna) {
            try {
                DB::table($tabla)->where($columna, $idAntiguo)->update([$columna => $idNuevo]);
            } catch (\Exception $e) {
                // Si la tabla no existe o hay error, continuar
                Log::warning("No se pudo actualizar tabla {$tabla}: " . $e->getMessage());
            }
        }
    }
    
    /**
     * Reorganizar solo los IDs de controles (manteniendo los IDs de niños)
     */
    public static function reorganizarSoloControles()
    {
        try {
            DB::beginTransaction();
            
            $resultados = [
                'controles_rn' => self::reorganizarIdsControlesRn(),
                'controles_cred' => self::reorganizarIdsControlesCred(),
            ];
            
            DB::commit();
            
            return $resultados;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}

