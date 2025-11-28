<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Nino;
use Carbon\Carbon;

echo "🔄 Creando archivo Excel de ejemplo con datos reales...\n\n";

// Obtener niños reales
$ninos = Nino::take(4)->get();

if ($ninos->isEmpty()) {
    echo "❌ No hay niños en la base de datos.\n";
    exit(1);
}

echo "📋 Encontrados " . $ninos->count() . " niños\n\n";

// Preparar datos
$datos = [];
$encabezados = [
    'ID_NINO', 'TIPO_CONTROL', 'NUMERO_CONTROL', 'FECHA', 'ESTADO',
    'ESTADO_CRED_ONCE', 'ESTADO_CRED_FINAL', 'FECHA_BCG', 'ESTADO_BCG',
    'FECHA_HVB', 'ESTADO_HVB', 'FECHA_TAMIZAJE', 'FECHA_VISITA',
    'GRUPO_VISITA', 'RED', 'MICRORED', 'DISTRITO', 'SOBRESCRIBIR'
];

foreach ($ninos as $nino) {
    $ninoId = $nino->id_niño;
    $fechaNacimiento = Carbon::parse($nino->fecha_nacimiento);
    $hoy = Carbon::now();
    $edadDias = $fechaNacimiento->diffInDays($hoy);

    echo "  👶 {$nino->apellidos_nombres} (ID: {$ninoId}, Edad: {$edadDias} días)\n";

    // Recién nacido (0-28 días)
    if ($edadDias <= 28) {
        // CRN 1 (2-6 días)
        if ($edadDias >= 2) {
            $fecha = $fechaNacimiento->copy()->addDays(rand(2, min(6, $edadDias)));
            $datos[] = [$ninoId, 'CRN', 1, $fecha->format('Y-m-d'), 'Completo', '', '', '', '', '', '', '', '', '', '', '', '', ''];
        }
        // CRN 2 (7-13 días)
        if ($edadDias >= 7) {
            $fecha = $fechaNacimiento->copy()->addDays(rand(7, min(13, $edadDias)));
            $datos[] = [$ninoId, 'CRN', 2, $fecha->format('Y-m-d'), 'Completo', '', '', '', '', '', '', '', '', '', '', '', '', ''];
        }
        // CRN 3 (14-20 días)
        if ($edadDias >= 14) {
            $fecha = $fechaNacimiento->copy()->addDays(rand(14, min(20, $edadDias)));
            $datos[] = [$ninoId, 'CRN', 3, $fecha->format('Y-m-d'), 'Completo', '', '', '', '', '', '', '', '', '', '', '', '', ''];
        }
        // CRN 4 (21-28 días)
        if ($edadDias >= 21) {
            $fecha = $fechaNacimiento->copy()->addDays(rand(21, min(28, $edadDias)));
            $datos[] = [$ninoId, 'CRN', 4, $fecha->format('Y-m-d'), 'Completo', '', '', '', '', '', '', '', '', '', '', '', '', ''];
        }

        // Vacunas
        $fechaBCG = $fechaNacimiento->copy()->addDays(rand(0, min(7, $edadDias)));
        $fechaHVB = $fechaNacimiento->copy()->addDays(rand(0, min(7, $edadDias)));
        $datos[] = [$ninoId, 'VACUNA', '', '', '', '', '', $fechaBCG->format('Y-m-d'), 'SI', $fechaHVB->format('Y-m-d'), 'SI', '', '', '', '', '', '', ''];

        // Tamizaje
        $fechaTamizaje = $fechaNacimiento->copy()->addDays(rand(1, min(29, $edadDias)));
        $datos[] = [$ninoId, 'TAMIZAJE', '', '', '', '', '', '', '', '', '', $fechaTamizaje->format('Y-m-d'), '', '', '', '', '', ''];
    }

    // Menor de 1 año (29-359 días) - Solo para el niño de 24 días, no aplica aún
    // Pero lo dejamos para cuando crezcan

    // Datos extra (para todos)
    $datos[] = [$ninoId, 'DATOS_EXTRA', '', '', '', '', '', '', '', '', '', '', '', '', 'Red de Salud Lima Norte', 'Microred 01', 'San Juan de Lurigancho', ''];
}

// Crear archivo CSV (compatible con Excel)
$outputFile = storage_path('app/ejemplo_controles.csv');
$fp = fopen($outputFile, 'w');

// Escribir encabezados
fputcsv($fp, $encabezados);

// Escribir datos
foreach ($datos as $fila) {
    fputcsv($fp, $fila);
}

fclose($fp);

echo "\n✅ Archivo CSV creado: {$outputFile}\n";
echo "📊 Total de registros: " . count($datos) . "\n";
echo "\n💡 INSTRUCCIONES:\n";
echo "   1. Abre el archivo CSV en Excel\n";
echo "   2. Guárdalo como .xlsx (Archivo > Guardar como > Excel)\n";
echo "   3. O súbelo directamente como CSV desde /importar-controles\n";
echo "\n📋 Datos incluidos:\n";
echo "   - Controles RN (CRN 1-4) para recién nacidos\n";
echo "   - Vacunas (BCG y HVB)\n";
echo "   - Tamizaje neonatal\n";
echo "   - Datos extra (red, microred, distrito)\n";

