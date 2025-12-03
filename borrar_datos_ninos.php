<?php
/**
 * Script para borrar todos los datos de niños y sus datos relacionados
 * 
 * ⚠️ ADVERTENCIA: Este script borrará TODOS los datos de niños y sus registros relacionados
 * 
 * Uso: php borrar_datos_ninos.php
 */

require __DIR__ . '/vendor/autoload.php';

// Cargar Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Nino;
use App\Models\DatosExtra;
use App\Models\Madre;
use App\Models\ControlRn;
use App\Models\ControlMenor1;
use App\Models\TamizajeNeonatal;
use App\Models\VacunaRn;
use App\Models\RecienNacido;
use App\Models\VisitaDomiciliaria;
use Illuminate\Support\Facades\DB;

echo "⚠️  ADVERTENCIA: Este script borrará TODOS los datos de niños y sus registros relacionados.\n\n";

// Confirmar antes de borrar
echo "¿Estás seguro de que quieres borrar TODOS los datos? (escribe 'SI' para confirmar): ";
$handle = fopen("php://stdin", "r");
$line = trim(fgets($handle));
fclose($handle);

if ($line !== 'SI') {
    echo "❌ Operación cancelada.\n";
    exit(0);
}

echo "\n🔄 Iniciando borrado de datos...\n\n";

try {
    // Iniciar transacción
    DB::beginTransaction();
    
    // Contar registros antes de borrar
    $counts = [
        'ninos' => Nino::count(),
        'datos_extra' => DatosExtra::count(),
        'madres' => Madre::count(),
        'controles_rn' => ControlRn::count(),
        'controles_cred' => ControlMenor1::count(),
        'tamizajes' => TamizajeNeonatal::count(),
        'vacunas' => VacunaRn::count(),
        'recien_nacidos' => RecienNacido::count(),
        'visitas' => VisitaDomiciliaria::count(),
    ];
    
    echo "📊 Registros encontrados:\n";
    foreach ($counts as $tipo => $cantidad) {
        echo "   - {$tipo}: {$cantidad}\n";
    }
    echo "\n";
    
    // Borrar en orden (primero los relacionados, luego los niños)
    echo "🗑️  Borrando registros relacionados...\n";
    
    // 1. Borrar controles CRED
    $deletedCred = ControlMenor1::query()->delete();
    echo "   ✅ Controles CRED borrados: {$deletedCred}\n";
    
    // 2. Borrar controles RN
    $deletedRn = ControlRn::query()->delete();
    echo "   ✅ Controles RN borrados: {$deletedRn}\n";
    
    // 3. Borrar tamizajes
    $deletedTamizaje = TamizajeNeonatal::query()->delete();
    echo "   ✅ Tamizajes borrados: {$deletedTamizaje}\n";
    
    // 4. Borrar vacunas
    $deletedVacunas = VacunaRn::query()->delete();
    echo "   ✅ Vacunas borradas: {$deletedVacunas}\n";
    
    // 5. Borrar recién nacidos (CNV)
    $deletedCNV = RecienNacido::query()->delete();
    echo "   ✅ Recién Nacidos (CNV) borrados: {$deletedCNV}\n";
    
    // 6. Borrar visitas domiciliarias
    $deletedVisitas = VisitaDomiciliaria::query()->delete();
    echo "   ✅ Visitas Domiciliarias borradas: {$deletedVisitas}\n";
    
    // 7. Borrar datos extra
    $deletedExtra = DatosExtra::query()->delete();
    echo "   ✅ Datos Extra borrados: {$deletedExtra}\n";
    
    // 8. Borrar madres
    $deletedMadres = Madre::query()->delete();
    echo "   ✅ Madres borradas: {$deletedMadres}\n";
    
    // 9. Finalmente, borrar niños
    echo "\n🗑️  Borrando niños...\n";
    $deletedNinos = Nino::query()->delete();
    echo "   ✅ Niños borrados: {$deletedNinos}\n";
    
    // Confirmar transacción
    DB::commit();
    
    echo "\n✅ ¡Borrado completado exitosamente!\n\n";
    echo "📊 Resumen:\n";
    echo "   - Niños borrados: {$deletedNinos}\n";
    echo "   - Datos Extra borrados: {$deletedExtra}\n";
    echo "   - Madres borradas: {$deletedMadres}\n";
    echo "   - Controles RN borrados: {$deletedRn}\n";
    echo "   - Controles CRED borrados: {$deletedCred}\n";
    echo "   - Tamizajes borrados: {$deletedTamizaje}\n";
    echo "   - Vacunas borradas: {$deletedVacunas}\n";
    echo "   - Recién Nacidos borrados: {$deletedCNV}\n";
    echo "   - Visitas borradas: {$deletedVisitas}\n";
    echo "\n";
    
} catch (\Exception $e) {
    // Revertir transacción en caso de error
    DB::rollBack();
    echo "\n❌ Error al borrar datos: " . $e->getMessage() . "\n";
    echo "   Todos los cambios han sido revertidos.\n";
    exit(1);
}




