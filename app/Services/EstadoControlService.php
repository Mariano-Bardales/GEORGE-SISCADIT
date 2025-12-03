<?php

namespace App\Services;

use App\Services\RangosCredService;

/**
 * Servicio para determinar estados de controles
 */
class EstadoControlService
{
    /**
     * Determinar estado de un control según su edad y rango
     * 
     * @param int $numeroControl
     * @param int $edadEnDias
     * @param string $tipoControl 'cred' o 'recien_nacido'
     * @return string 'CUMPLE' | 'NO CUMPLE' | 'SEGUIMIENTO'
     */
    public function determinarEstado(int $numeroControl, ?int $edadEnDias, string $tipoControl = 'cred'): string
    {
        if ($edadEnDias === null) {
            return 'SEGUIMIENTO';
        }

        $validacion = RangosCredService::validarControl($numeroControl, $edadEnDias, $tipoControl);
        
        return $validacion['estado'];
    }

    /**
     * Determinar si un control debe tener estado CUMPLE
     */
    public function cumpleRango(int $numeroControl, ?int $edadEnDias, string $tipoControl = 'cred'): bool
    {
        if ($edadEnDias === null) {
            return false;
        }

        $validacion = RangosCredService::validarControl($numeroControl, $edadEnDias, $tipoControl);
        
        return $validacion['cumple'];
    }

    /**
     * Obtener información completa del estado de un control
     */
    public function obtenerInfoEstado(int $numeroControl, ?int $edadEnDias, string $tipoControl = 'cred'): array
    {
        return RangosCredService::validarControl($numeroControl, $edadEnDias, $tipoControl);
    }
}

