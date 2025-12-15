<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Models\Solicitud;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * Obtener el código de red del usuario autenticado desde su Solicitud
     */
    protected function getUserRed()
    {
        $user = auth()->user();
        if (!$user) {
            return null;
        }

        $solicitud = Solicitud::where('user_id', $user->id)->first();
        return $solicitud ? $solicitud->codigo_red : null;
    }

    /**
     * Obtener el código de microred del usuario autenticado desde su Solicitud
     */
    protected function getUserMicrored()
    {
        $user = auth()->user();
        if (!$user) {
            return null;
        }

        $solicitud = Solicitud::where('user_id', $user->id)->first();
        return $solicitud ? $solicitud->codigo_microred : null;
    }

    /**
     * Obtener el id_establecimiento del usuario autenticado desde su Solicitud
     */
    protected function getUserEessNacimiento()
    {
        $user = auth()->user();
        if (!$user) {
            return null;
        }

        $solicitud = Solicitud::where('user_id', $user->id)->first();
        return $solicitud ? $solicitud->id_establecimiento : null;
    }

    /**
     * Aplicar filtros de red/microred según el rol del usuario autenticado
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $relationName Nombre de la relación (por defecto 'datosExtra')
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function applyRedMicroredFilter($query, $relationName = 'datosExtra')
    {
        $user = auth()->user();
        if (!$user) {
            return $query;
        }

        $role = strtolower($user->role ?? '');

        // Admin: sin filtros, puede ver todo
        if ($role === 'admin' || $role === 'administrator') {
            return $query;
        }

        // Jefe de Red: filtrar por red
        if ($role === 'jefe_red' || $role === 'jefedered' || $role === 'jefe_microred') {
            $codigoRed = $this->getUserRed();
            if ($codigoRed) {
                // Mapear código de red a nombre de red
                $redes = [
                    1 => 'AGUAYTIA',
                    2 => 'ATALAYA',
                    3 => 'BAP-CURARAY',
                    4 => 'CORONEL PORTILLO',
                    5 => 'ESSALUD',
                    6 => 'FEDERICO BASADRE - YARINACOCHA',
                    7 => 'HOSPITAL AMAZONICO - YARINACOCHA',
                    8 => 'HOSPITAL REGIONAL DE PUCALLPA',
                    9 => 'NO PERTENECE A NINGUNA RED'
                ];
                $nombreRed = $redes[$codigoRed] ?? null;
                if ($nombreRed) {
                    $query->whereHas($relationName, function($q) use ($nombreRed) {
                        $q->where('red', $nombreRed);
                    });
                }
            }
            return $query;
        }

        // Coordinador de Micro Red: filtrar por microred
        if ($role === 'coordinador_microred' || $role === 'coordinadordemicrored' || $role === 'coordinador_red') {
            $codigoMicrored = $this->getUserMicrored();
            if ($codigoMicrored) {
                $query->whereHas($relationName, function($q) use ($codigoMicrored) {
                    $q->where('microred', $codigoMicrored);
                });
            }
            return $query;
        }

        // Otros roles: sin filtros (por seguridad, podrías querer restringir más)
        return $query;
    }
}
