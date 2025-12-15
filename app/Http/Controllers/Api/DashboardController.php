<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Nino;
use App\Models\ControlRn;
use App\Models\ControlMenor1;
use App\Models\User;
use App\Models\TamizajeNeonatal;
use App\Models\VacunaRn;
use Carbon\Carbon;
use Illuminate\Http\Request;

/**
 * Controlador API para Dashboard y Estadísticas
 */
class DashboardController extends Controller
{
    /**
     * Helper para obtener el ID correcto del niño
     */
    private function getNinoId($nino)
    {
        return $nino->id_niño ?? $nino->id ?? null;
    }

    /**
     * Obtener estadísticas del dashboard
     */
    public function stats()
    {
        // Aplicar filtros según el rol del usuario
        $queryNinos = Nino::query();
        $queryNinos = $this->applyRedMicroredFilter($queryNinos, 'datosExtra');
        $totalNinos = $queryNinos->count();
        
        // Para controles, necesitamos filtrar por los niños filtrados
        $ninosIds = $queryNinos->pluck('id')->toArray();
        $totalControles = ControlRn::whereIn('id_niño', $ninosIds)->count() + 
                         ControlMenor1::whereIn('id_niño', $ninosIds)->count();
        
        // Para usuarios, aplicar filtros según rol
        $queryUsuarios = User::query();
        $user = auth()->user();
        if ($user) {
            $role = strtolower($user->role);
            if ($role === 'jefe_red' || $role === 'jefedered') {
                $codigoRed = $this->getUserRed();
                if ($codigoRed) {
                    $queryUsuarios->whereHas('solicitud', function($q) use ($codigoRed) {
                        $q->where('codigo_red', $codigoRed);
                    });
                }
            } elseif ($role === 'coordinador_microred' || $role === 'coordinadordemicrored') {
                $codigoMicrored = $this->getUserMicrored();
                if ($codigoMicrored) {
                    $queryUsuarios->whereHas('solicitud', function($q) use ($codigoMicrored) {
                        $q->where('codigo_microred', $codigoMicrored);
                    });
                }
            }
        }
        $totalUsuarios = $queryUsuarios->count();
        
        // Calcular alertas reales
        $totalAlertas = 0;
        $hoy = Carbon::now();
        
        $ninos = $queryNinos->get();
        
        foreach ($ninos as $nino) {
            // Validar que el niño tenga fecha de nacimiento
            if (!$nino->fecha_nacimiento) {
                continue;
            }
            
            try {
                $fechaNacimiento = Carbon::parse($nino->fecha_nacimiento);
                $edadDias = $fechaNacimiento->diffInDays($hoy);
            } catch (\Exception $e) {
                continue;
            }
            
            // Alertas de controles recién nacido
            if ($edadDias <= 28) {
                $ninoId = $this->getNinoId($nino);
                $controlesRn = ControlRn::where('id_niño', $ninoId)->count();
                $controlesEsperados = 0;
                
                $rangosRN = [
                    1 => ['min' => 2, 'max' => 6],
                    2 => ['min' => 7, 'max' => 13],
                    3 => ['min' => 14, 'max' => 20],
                    4 => ['min' => 21, 'max' => 28]
                ];
                
                foreach ($rangosRN as $num => $rango) {
                    if ($edadDias >= $rango['min'] && $edadDias <= $rango['max']) {
                        $controlesEsperados++;
                    } else if ($edadDias > $rango['max']) {
                        $controlesEsperados++;
                    }
                }
                
                if ($controlesRn < $controlesEsperados) {
                    $totalAlertas += ($controlesEsperados - $controlesRn);
                }
            }
            
            // Alertas de CRED mensual
            if ($edadDias >= 29 && $edadDias <= 359) {
                $ninoId = $this->getNinoId($nino);
                $controlesCred = ControlMenor1::where('id_niño', $ninoId)->count();
                $controlesEsperados = 0;
                
                $rangosCred = [
                    1 => ['min' => 29, 'max' => 59],
                    2 => ['min' => 60, 'max' => 89],
                    3 => ['min' => 90, 'max' => 119],
                    4 => ['min' => 120, 'max' => 149],
                    5 => ['min' => 150, 'max' => 179],
                    6 => ['min' => 180, 'max' => 209],
                    7 => ['min' => 210, 'max' => 239],
                    8 => ['min' => 240, 'max' => 269],
                    9 => ['min' => 270, 'max' => 299],
                    10 => ['min' => 300, 'max' => 329],
                    11 => ['min' => 330, 'max' => 359]
                ];
                
                foreach ($rangosCred as $num => $rango) {
                    if ($edadDias > $rango['max']) {
                        $controlesEsperados++;
                    } else if ($edadDias >= $rango['min'] && $edadDias <= $rango['max']) {
                        $controlesEsperados++;
                    }
                }
                
                if ($controlesCred < $controlesEsperados) {
                    $totalAlertas += ($controlesEsperados - $controlesCred);
                }
            }
            
            // Alertas de tamizaje
            if ($edadDias >= 1 && $edadDias <= 29) {
                $ninoId = $this->getNinoId($nino);
                $tamizaje = TamizajeNeonatal::where('id_niño', $ninoId)->first();
                if (!$tamizaje) {
                    $totalAlertas++;
                }
            }
            
            // Alertas de vacunas
            if ($edadDias <= 30) {
                $ninoId = $this->getNinoId($nino);
                $vacunas = VacunaRn::where('id_niño', $ninoId)->first();
                if (!$vacunas || !$vacunas->fecha_bcg || !$vacunas->fecha_hvb) {
                    $totalAlertas++;
                }
            }
        }

        return response()->json([
            'success' => true,
            'data' => [
                'total_ninos' => $totalNinos,
                'total_controles' => $totalControles,
                'total_usuarios' => $totalUsuarios,
                'total_alertas' => $totalAlertas,
            ]
        ]);
    }

    /**
     * Obtener reportes y estadísticas
     */
    public function reportes()
    {
        // Por ahora, devolver datos básicos
        // Se puede expandir más adelante
        return response()->json([
            'success' => true,
            'data' => [
                'message' => 'Reportes y estadísticas'
            ]
        ]);
    }
}

