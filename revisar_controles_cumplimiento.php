<?php

/**
 * Script para revisar todos los controles y verificar si alguno muestra "NO CUMPLE" incorrectamente
 * 
 * Uso: php revisar_controles_cumplimiento.php
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Nino;
use App\Models\ControlRn;
use App\Models\ControlMenor1;
use App\Models\VisitaDomiciliaria;
use App\Models\VacunaRn;
use App\Models\TamizajeNeonatal;
use App\Services\RangosCredService;
use Carbon\Carbon;

echo "🔍 Revisando todos los controles del sistema...\n\n";

$problemas = [];
$totalRevisados = 0;

// Obtener todos los niños
$ninos = Nino::all();

foreach ($ninos as $nino) {
    if (!$nino->fecha_nacimiento) {
        continue;
    }
    
    $fechaNacimiento = Carbon::parse($nino->fecha_nacimiento)->startOfDay();
    $ninoId = $nino->id;
    
    echo "📋 Revisando niño: {$nino->apellidos_nombres} (ID: {$ninoId})\n";
    
    // ========== CONTROLES RN ==========
    $controlesRN = ControlRn::where('id_niño', $ninoId)->get();
    foreach ($controlesRN as $control) {
        $totalRevisados++;
        $fechaControl = Carbon::parse($control->fecha)->startOfDay();
        $edadDias = $fechaNacimiento->diffInDays($fechaControl);
        
        $rangosRN = RangosCredService::getRangosRecienNacido();
        $rango = $rangosRN[$control->numero_control] ?? null;
        
        if ($rango) {
            $debeCumplir = ($edadDias >= $rango['min'] && $edadDias <= $rango['max']);
            if (!$debeCumplir) {
                $problemas[] = [
                    'tipo' => 'Control RN',
                    'nino' => $nino->apellidos_nombres,
                    'nino_id' => $ninoId,
                    'control_id' => $control->id,
                    'numero_control' => $control->numero_control,
                    'fecha_control' => $control->fecha,
                    'edad_dias' => $edadDias,
                    'rango_min' => $rango['min'],
                    'rango_max' => $rango['max'],
                    'problema' => "Edad: {$edadDias} días, Rango: {$rango['min']}-{$rango['max']} días"
                ];
            }
        }
    }
    
    // ========== CONTROLES CRED ==========
    $controlesCRED = ControlMenor1::where('id_niño', $ninoId)->get();
    foreach ($controlesCRED as $control) {
        $totalRevisados++;
        $fechaControl = Carbon::parse($control->fecha)->startOfDay();
        $edadDias = $fechaNacimiento->diffInDays($fechaControl);
        
        $rangosCRED = RangosCredService::getRangosCredMensual();
        $rango = $rangosCRED[$control->numero_control] ?? null;
        
        if ($rango) {
            $debeCumplir = ($edadDias >= $rango['min'] && $edadDias <= $rango['max']);
            if (!$debeCumplir) {
                $problemas[] = [
                    'tipo' => 'Control CRED',
                    'nino' => $nino->apellidos_nombres,
                    'nino_id' => $ninoId,
                    'control_id' => $control->id,
                    'numero_control' => $control->numero_control,
                    'fecha_control' => $control->fecha,
                    'edad_dias' => $edadDias,
                    'rango_min' => $rango['min'],
                    'rango_max' => $rango['max'],
                    'problema' => "Edad: {$edadDias} días, Rango: {$rango['min']}-{$rango['max']} días"
                ];
            }
        }
    }
    
    // ========== VISITAS DOMICILIARIAS ==========
    $visitas = VisitaDomiciliaria::where('id_niño', $ninoId)->get();
    $rangosVisitas = [
        1 => ['min' => 28, 'max' => 30],
        2 => ['min' => 60, 'max' => 150],
        3 => ['min' => 180, 'max' => 240],
        4 => ['min' => 270, 'max' => 330],
    ];
    
    foreach ($visitas as $visita) {
        $totalRevisados++;
        $fechaVisita = Carbon::parse($visita->fecha_visita)->startOfDay();
        $edadDias = $fechaNacimiento->diffInDays($fechaVisita);
        
        $rango = $rangosVisitas[$visita->control_de_visita] ?? null;
        
        if ($rango) {
            $debeCumplir = ($edadDias >= $rango['min'] && $edadDias <= $rango['max']);
            if (!$debeCumplir) {
                $problemas[] = [
                    'tipo' => 'Visita Domiciliaria',
                    'nino' => $nino->apellidos_nombres,
                    'nino_id' => $ninoId,
                    'visita_id' => $visita->id,
                    'control_de_visita' => $visita->control_de_visita,
                    'fecha_visita' => $visita->fecha_visita,
                    'edad_dias' => $edadDias,
                    'rango_min' => $rango['min'],
                    'rango_max' => $rango['max'],
                    'problema' => "Edad: {$edadDias} días, Rango: {$rango['min']}-{$rango['max']} días"
                ];
            }
        }
    }
    
    // ========== VACUNAS ==========
    $vacunas = VacunaRn::where('id_niño', $ninoId)->get();
    foreach ($vacunas as $vacuna) {
        if ($vacuna->fecha_bcg) {
            $totalRevisados++;
            $fechaBCG = Carbon::parse($vacuna->fecha_bcg)->startOfDay();
            $edadDias = $fechaNacimiento->diffInDays($fechaBCG);
            
            $debeCumplir = ($edadDias >= 0 && $edadDias <= 2);
            if (!$debeCumplir) {
                $problemas[] = [
                    'tipo' => 'Vacuna BCG',
                    'nino' => $nino->apellidos_nombres,
                    'nino_id' => $ninoId,
                    'vacuna_id' => $vacuna->id,
                    'fecha' => $vacuna->fecha_bcg,
                    'edad_dias' => $edadDias,
                    'rango_min' => 0,
                    'rango_max' => 2,
                    'problema' => "Edad: {$edadDias} días, Rango: 0-2 días"
                ];
            }
        }
        
        if ($vacuna->fecha_hvb) {
            $totalRevisados++;
            $fechaHVB = Carbon::parse($vacuna->fecha_hvb)->startOfDay();
            $edadDias = $fechaNacimiento->diffInDays($fechaHVB);
            
            $debeCumplir = ($edadDias >= 0 && $edadDias <= 2);
            if (!$debeCumplir) {
                $problemas[] = [
                    'tipo' => 'Vacuna HVB',
                    'nino' => $nino->apellidos_nombres,
                    'nino_id' => $ninoId,
                    'vacuna_id' => $vacuna->id,
                    'fecha' => $vacuna->fecha_hvb,
                    'edad_dias' => $edadDias,
                    'rango_min' => 0,
                    'rango_max' => 2,
                    'problema' => "Edad: {$edadDias} días, Rango: 0-2 días"
                ];
            }
        }
    }
    
    // ========== TAMIZAJE ==========
    $tamizajes = TamizajeNeonatal::where('id_niño', $ninoId)->get();
    foreach ($tamizajes as $tamizaje) {
        if ($tamizaje->fecha_tam_neo) {
            $totalRevisados++;
            $fechaTamizaje = Carbon::parse($tamizaje->fecha_tam_neo)->startOfDay();
            $edadDias = $fechaNacimiento->diffInDays($fechaTamizaje);
            
            $debeCumplir = ($edadDias >= 1 && $edadDias <= 29);
            if (!$debeCumplir) {
                $problemas[] = [
                    'tipo' => 'Tamizaje Neonatal',
                    'nino' => $nino->apellidos_nombres,
                    'nino_id' => $ninoId,
                    'tamizaje_id' => $tamizaje->id,
                    'fecha' => $tamizaje->fecha_tam_neo,
                    'edad_dias' => $edadDias,
                    'rango_min' => 1,
                    'rango_max' => 29,
                    'problema' => "Edad: {$edadDias} días, Rango: 1-29 días"
                ];
            }
        }
        
        if ($tamizaje->galen_fecha_tam_feo) {
            $totalRevisados++;
            $fechaGalen = Carbon::parse($tamizaje->galen_fecha_tam_feo)->startOfDay();
            $edadDias = $fechaNacimiento->diffInDays($fechaGalen);
            
            $debeCumplir = ($edadDias >= 1 && $edadDias <= 29);
            if (!$debeCumplir) {
                $problemas[] = [
                    'tipo' => 'Tamizaje Galen',
                    'nino' => $nino->apellidos_nombres,
                    'nino_id' => $ninoId,
                    'tamizaje_id' => $tamizaje->id,
                    'fecha' => $tamizaje->galen_fecha_tam_feo,
                    'edad_dias' => $edadDias,
                    'rango_min' => 1,
                    'rango_max' => 29,
                    'problema' => "Edad: {$edadDias} días, Rango: 1-29 días"
                ];
            }
        }
    }
}

echo "\n";
echo "═══════════════════════════════════════════════════════════\n";
echo "📊 RESUMEN DE REVISIÓN\n";
echo "═══════════════════════════════════════════════════════════\n";
echo "Total de controles revisados: {$totalRevisados}\n";
echo "Controles con problemas encontrados: " . count($problemas) . "\n";
echo "\n";

if (count($problemas) > 0) {
    echo "⚠️  PROBLEMAS ENCONTRADOS:\n";
    echo "═══════════════════════════════════════════════════════════\n\n";
    
    foreach ($problemas as $index => $problema) {
        echo ($index + 1) . ". {$problema['tipo']}\n";
        echo "   Niño: {$problema['nino']} (ID: {$problema['nino_id']})\n";
        
        if (isset($problema['numero_control'])) {
            echo "   Control #{$problema['numero_control']}\n";
        }
        if (isset($problema['control_de_visita'])) {
            echo "   Visita Control #{$problema['control_de_visita']}\n";
        }
        
        $fechaProblema = isset($problema['fecha_control']) ? $problema['fecha_control'] : (isset($problema['fecha_visita']) ? $problema['fecha_visita'] : $problema['fecha']);
        echo "   Fecha: {$fechaProblema}\n";
        echo "   {$problema['problema']}\n";
        echo "\n";
    }
    
    echo "\n💡 NOTA: Estos controles están fuera del rango esperado.\n";
    echo "   Si alguno debería cumplir pero muestra 'NO CUMPLE', puede ser un problema de cálculo.\n";
} else {
    echo "✅ No se encontraron problemas. Todos los controles están dentro de sus rangos esperados.\n";
}

echo "\n";

