<?php

namespace App\Services;

use App\Models\Nino;
use App\Models\ControlRn;
use App\Models\ControlMenor1;
use App\Services\EdadService;
use App\Services\RangosCredService;
use Carbon\Carbon;

/**
 * Servicio para detección y generación de alertas
 */
class AlertasService
{
    protected $edadService;

    public function __construct(EdadService $edadService)
    {
        $this->edadService = $edadService;
    }

    /**
     * Obtener todas las alertas del sistema
     */
    public function obtenerTodasLasAlertas(): array
    {
        $alertas = [];
        $ninos = Nino::all();

        foreach ($ninos as $nino) {
            if (!$nino->fecha_nacimiento) {
                continue;
            }

            $edadDias = $this->edadService->calcularEdadEnDias($nino);
            
            if ($edadDias === null) {
                continue;
            }

            // Alertas de controles RN (0-28 días)
            if ($edadDias <= 28) {
                $alertas = array_merge($alertas, $this->obtenerAlertasRecienNacido($nino, $edadDias));
            }

            // Alertas de controles CRED (29-359 días o más si tiene controles registrados)
            if ($edadDias >= 29) {
                $alertas = array_merge($alertas, $this->obtenerAlertasCred($nino, $edadDias));
            }
        }

        return $alertas;
    }

    /**
     * Obtener alertas de controles recién nacido
     */
    protected function obtenerAlertasRecienNacido(Nino $nino, int $edadDias): array
    {
        $alertas = [];
        $ninoId = $nino->id_niño ?? $nino->id;
        
        $controlesRn = ControlRn::where('id_niño', $ninoId)->get();
        $controlesRegistrados = $controlesRn->pluck('numero_control')->toArray();
        $controlesRegistradosMap = [];
        foreach ($controlesRn as $control) {
            $controlesRegistradosMap[$control->numero_control] = $control;
        }
        
        $rangosRN = RangosCredService::getRangosRecienNacido();
        $fechaNacimiento = Carbon::parse($nino->fecha_nacimiento);
        
        // Encontrar el control más alto registrado con fecha
        $controlMasAltoConFecha = null;
        $numeroControlMasAlto = 0;
        foreach ($controlesRegistradosMap as $num => $control) {
            if ($control && $control->fecha && $num > $numeroControlMasAlto) {
                $numeroControlMasAlto = $num;
                $controlMasAltoConFecha = $control;
            }
        }
        
        // Si hay un control registrado con fecha, verificar controles anteriores faltantes
        if ($controlMasAltoConFecha) {
            for ($numAnterior = 1; $numAnterior < $numeroControlMasAlto; $numAnterior++) {
                if (!isset($controlesRegistradosMap[$numAnterior]) || 
                    !$controlesRegistradosMap[$numAnterior] || 
                    !$controlesRegistradosMap[$numAnterior]->fecha) {
                    // Control anterior faltante
                    $rangoAnterior = $rangosRN[$numAnterior];
                    $fechaControlAlto = Carbon::parse($controlMasAltoConFecha->fecha);
                    $edadDiasControlAlto = $fechaNacimiento->diffInDays($fechaControlAlto);
                    
                    // Calcular días fuera del rango
                    $diasFuera = $edadDiasControlAlto > $rangoAnterior['max'] 
                        ? ($edadDiasControlAlto - $rangoAnterior['max']) 
                        : 0;
                    
                    $mensaje = "El niño tiene el control CRN{$numeroControlMasAlto} registrado, pero falta el control CRN{$numAnterior} ({$rangoAnterior['descripcion']}) que debió realizarse entre los {$rangoAnterior['min']} y {$rangoAnterior['max']} días.";
                    
                    $alertas[] = [
                        'tipo' => 'control_recien_nacido',
                        'nino_id' => $ninoId,
                        'nino_nombre' => $nino->apellidos_nombres,
                        'nino_dni' => $nino->numero_doc,
                        'establecimiento' => $nino->establecimiento,
                        'control' => "CRN{$numAnterior}",
                        'edad_dias' => $edadDias,
                        'rango_min' => $rangoAnterior['min'],
                        'rango_max' => $rangoAnterior['max'],
                        'rango_dias' => $rangoAnterior['min'] . '-' . $rangoAnterior['max'],
                        'prioridad' => 'alta',
                        'fecha_nacimiento' => $nino->fecha_nacimiento->format('Y-m-d'),
                        'mensaje' => $mensaje,
                        'dias_fuera' => $diasFuera,
                        'control_referencia' => "CRN{$numeroControlMasAlto}",
                    ];
                }
            }
        }
        
        foreach ($rangosRN as $num => $rango) {
            $debeTener = false;
            if ($edadDias >= $rango['min'] && $edadDias <= $rango['max']) {
                $debeTener = true;
            } elseif ($edadDias > $rango['max']) {
                $debeTener = true;
            }
            
            if ($debeTener && !in_array($num, $controlesRegistrados)) {
                // Verificar si ya fue detectado como control anterior faltante
                $yaDetectado = false;
                foreach ($alertas as $alertaExistente) {
                    if (isset($alertaExistente['control']) && 
                        $alertaExistente['control'] == "CRN{$num}" && 
                        isset($alertaExistente['control_referencia'])) {
                        $yaDetectado = true;
                        break;
                    }
                }
                
                if (!$yaDetectado) {
                    $diasFuera = $edadDias > $rango['max'] ? ($edadDias - $rango['max']) : 0;
                    $mensaje = $edadDias > $rango['max'] 
                        ? "El niño tiene {$edadDias} días y el control {$rango['descripcion']} debió realizarse entre los {$rango['min']} y {$rango['max']} días. Ya pasaron {$diasFuera} día(s) del límite máximo."
                        : "El niño tiene {$edadDias} días y debe realizarse el control {$rango['descripcion']} entre los {$rango['min']} y {$rango['max']} días.";
                    
                    $alertas[] = [
                        'tipo' => 'control_recien_nacido',
                        'nino_id' => $ninoId,
                        'nino_nombre' => $nino->apellidos_nombres,
                        'nino_dni' => $nino->numero_doc,
                        'establecimiento' => $nino->establecimiento,
                        'control' => "CRN{$num}",
                        'edad_dias' => $edadDias,
                        'rango_min' => $rango['min'],
                        'rango_max' => $rango['max'],
                        'rango_dias' => $rango['min'] . '-' . $rango['max'],
                        'prioridad' => $edadDias > $rango['max'] ? 'alta' : 'media',
                        'fecha_nacimiento' => $nino->fecha_nacimiento->format('Y-m-d'),
                        'mensaje' => $mensaje,
                        'dias_fuera' => $diasFuera,
                    ];
                }
            }

            // Verificar controles fuera de rango
            $control = $controlesRn->where('numero_control', $num)->first();
            if ($control && $control->fecha) {
                $fechaNacimiento = Carbon::parse($nino->fecha_nacimiento);
                $fechaControl = Carbon::parse($control->fecha);
                $edadDiasControl = $fechaNacimiento->diffInDays($fechaControl);
                
                if ($edadDiasControl < $rango['min'] || $edadDiasControl > $rango['max']) {
                    $diasFuera = $edadDiasControl > $rango['max'] 
                        ? ($edadDiasControl - $rango['max']) 
                        : ($rango['min'] - $edadDiasControl);
                    
                    $mensaje = $edadDiasControl > $rango['max']
                        ? "El control CRN{$num} fue realizado a los {$edadDiasControl} días, fuera del rango permitido ({$rango['min']}-{$rango['max']} días). Está {$diasFuera} día(s) fuera del límite máximo."
                        : "El control CRN{$num} fue realizado a los {$edadDiasControl} días, fuera del rango permitido ({$rango['min']}-{$rango['max']} días). Está {$diasFuera} día(s) antes del límite mínimo.";
                    
                    $alertas[] = [
                        'tipo' => 'control_recien_nacido',
                        'nino_id' => $ninoId,
                        'nino_nombre' => $nino->apellidos_nombres,
                        'nino_dni' => $nino->numero_doc,
                        'establecimiento' => $nino->establecimiento,
                        'control' => "CRN{$num}",
                        'edad_dias' => $edadDias,
                        'edad_dias_control' => $edadDiasControl,
                        'rango_min' => $rango['min'],
                        'rango_max' => $rango['max'],
                        'rango_dias' => $rango['min'] . '-' . $rango['max'],
                        'prioridad' => 'alta',
                        'fecha_nacimiento' => $nino->fecha_nacimiento->format('Y-m-d'),
                        'fecha_control' => $control->fecha->format('Y-m-d'),
                        'mensaje' => $mensaje,
                        'dias_fuera' => $diasFuera,
                    ];
                }
            }
        }

        return $alertas;
    }

    /**
     * Obtener alertas de controles CRED
     */
    protected function obtenerAlertasCred(Nino $nino, int $edadDias): array
    {
        $alertas = [];
        $ninoId = $nino->id_niño ?? $nino->id;
        
        $controlesCred = ControlMenor1::where('id_niño', $ninoId)->get();
        $controlesRegistradosMap = [];
        foreach ($controlesCred as $control) {
            $controlesRegistradosMap[$control->numero_control] = $control;
        }
        
        $rangosCred = RangosCredService::getRangosCredMensual();
        $fechaNacimiento = Carbon::parse($nino->fecha_nacimiento);
        
        // Encontrar el control más alto registrado con fecha
        $controlMasAltoConFecha = null;
        $numeroControlMasAlto = 0;
        foreach ($controlesRegistradosMap as $num => $control) {
            if ($control && $control->fecha && $num > $numeroControlMasAlto) {
                $numeroControlMasAlto = $num;
                $controlMasAltoConFecha = $control;
            }
        }
        
        // Si hay un control registrado con fecha, verificar controles anteriores faltantes
        if ($controlMasAltoConFecha) {
            for ($mesAnterior = 1; $mesAnterior < $numeroControlMasAlto; $mesAnterior++) {
                if (!isset($controlesRegistradosMap[$mesAnterior]) || 
                    !$controlesRegistradosMap[$mesAnterior] || 
                    !$controlesRegistradosMap[$mesAnterior]->fecha) {
                    // Control anterior faltante
                    $rangoAnterior = $rangosCred[$mesAnterior];
                    $fechaControlAlto = Carbon::parse($controlMasAltoConFecha->fecha);
                    $edadDiasControlAlto = $fechaNacimiento->diffInDays($fechaControlAlto);
                    
                    // Calcular días fuera del rango (usando la fecha del control más alto como referencia)
                    $diasFuera = $edadDiasControlAlto > $rangoAnterior['max'] 
                        ? ($edadDiasControlAlto - $rangoAnterior['max']) 
                        : 0;
                    
                    $mensaje = "El niño tiene el control Mes {$numeroControlMasAlto} registrado, pero falta el control Mes {$mesAnterior} que debió realizarse entre los {$rangoAnterior['min']} y {$rangoAnterior['max']} días.";
                    
                    $alertas[] = [
                        'tipo' => 'control_cred_mensual',
                        'nino_id' => $ninoId,
                        'nino_nombre' => $nino->apellidos_nombres,
                        'nino_dni' => $nino->numero_doc,
                        'establecimiento' => $nino->establecimiento,
                        'control' => "Mes {$mesAnterior}",
                        'mes' => $mesAnterior,
                        'edad_dias' => $edadDias,
                        'rango_min' => $rangoAnterior['min'],
                        'rango_max' => $rangoAnterior['max'],
                        'rango_dias' => $rangoAnterior['min'] . '-' . $rangoAnterior['max'],
                        'prioridad' => 'alta',
                        'fecha_nacimiento' => $nino->fecha_nacimiento->format('Y-m-d'),
                        'mensaje' => $mensaje,
                        'dias_fuera' => $diasFuera,
                        'control_referencia' => "Mes {$numeroControlMasAlto}",
                    ];
                }
            }
        }
        
        foreach ($rangosCred as $mes => $rango) {
            $debeTener = false;
            if ($edadDias > $rango['max']) {
                $debeTener = true;
            } elseif ($edadDias >= $rango['min'] && $edadDias <= $rango['max']) {
                $debeTener = true;
            }
            
            $control = $controlesRegistradosMap[$mes] ?? null;
            
            if ($control && $control->fecha) {
                // Verificar si el control está fuera de rango
                $fechaControl = Carbon::parse($control->fecha);
                $edadDiasControl = $fechaNacimiento->diffInDays($fechaControl);
                
                if ($edadDiasControl < $rango['min'] || $edadDiasControl > $rango['max']) {
                    $diasFuera = $edadDiasControl > $rango['max'] 
                        ? ($edadDiasControl - $rango['max']) 
                        : ($rango['min'] - $edadDiasControl);
                    
                    $mensaje = $edadDiasControl > $rango['max']
                        ? "El control Mes {$mes} fue realizado a los {$edadDiasControl} días, fuera del rango permitido ({$rango['min']}-{$rango['max']} días). Está {$diasFuera} día(s) fuera del límite máximo."
                        : "El control Mes {$mes} fue realizado a los {$edadDiasControl} días, fuera del rango permitido ({$rango['min']}-{$rango['max']} días). Está {$diasFuera} día(s) antes del límite mínimo.";
                    
                    $alertas[] = [
                        'tipo' => 'control_cred_mensual',
                        'nino_id' => $ninoId,
                        'nino_nombre' => $nino->apellidos_nombres,
                        'nino_dni' => $nino->numero_doc,
                        'establecimiento' => $nino->establecimiento,
                        'control' => "Mes {$mes}",
                        'mes' => $mes,
                        'edad_dias' => $edadDias,
                        'edad_dias_control' => $edadDiasControl,
                        'rango_min' => $rango['min'],
                        'rango_max' => $rango['max'],
                        'rango_dias' => $rango['min'] . '-' . $rango['max'],
                        'prioridad' => 'alta',
                        'fecha_nacimiento' => $nino->fecha_nacimiento->format('Y-m-d'),
                        'fecha_control' => $control->fecha->format('Y-m-d'),
                        'mensaje' => $mensaje,
                        'dias_fuera' => $diasFuera,
                    ];
                }
            } elseif ($debeTener && !$control) {
                // Control faltante (solo si no fue detectado por el control más alto)
                // Verificar si ya fue detectado como control anterior faltante
                $yaDetectado = false;
                foreach ($alertas as $alertaExistente) {
                    if (isset($alertaExistente['mes']) && 
                        $alertaExistente['mes'] == $mes && 
                        isset($alertaExistente['control_referencia'])) {
                        $yaDetectado = true;
                        break;
                    }
                }
                
                if (!$yaDetectado) {
                    $diasFuera = $edadDias > $rango['max'] ? ($edadDias - $rango['max']) : 0;
                    $mensaje = $edadDias > $rango['max'] 
                        ? "El niño tiene {$edadDias} días y el control Mes {$mes} debió realizarse entre los {$rango['min']} y {$rango['max']} días. Ya pasaron {$diasFuera} día(s) del límite máximo."
                        : "El niño tiene {$edadDias} días y debe realizarse el control Mes {$mes} entre los {$rango['min']} y {$rango['max']} días.";
                    
                    $alertas[] = [
                        'tipo' => 'control_cred_mensual',
                        'nino_id' => $ninoId,
                        'nino_nombre' => $nino->apellidos_nombres,
                        'nino_dni' => $nino->numero_doc,
                        'establecimiento' => $nino->establecimiento,
                        'control' => "Mes {$mes}",
                        'mes' => $mes,
                        'edad_dias' => $edadDias,
                        'rango_min' => $rango['min'],
                        'rango_max' => $rango['max'],
                        'rango_dias' => $rango['min'] . '-' . $rango['max'],
                        'prioridad' => $edadDias > $rango['max'] ? 'alta' : 'media',
                        'fecha_nacimiento' => $nino->fecha_nacimiento->format('Y-m-d'),
                        'mensaje' => $mensaje,
                        'dias_fuera' => $diasFuera,
                    ];
                }
            }
        }

        return $alertas;
    }

    /**
     * Contar total de alertas
     */
    public function contarTotalAlertas(): int
    {
        return count($this->obtenerTodasLasAlertas());
    }
}

