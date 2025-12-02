<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Exports\EjemploMultiHojasExport;

class CrearExcelSimple extends Command
{
    protected $signature = 'excel:crear-simple {--output=ejemplo_importacion_siscadit.xlsx}';
    protected $description = 'Crear un archivo Excel simple para importar en SISCADIT';

    public function handle()
    {
        $outputFile = $this->option('output');
        $outputPath = base_path($outputFile);

        $this->info("🔄 Creando archivo Excel de ejemplo...");

        try {
            // Verificar versión de PHP
            if (version_compare(PHP_VERSION, '8.0.0', '>=')) {
                $this->warn("⚠️  PHPExcel no es compatible con PHP 8.x");
                $this->info("📝 Por favor, crea el Excel manualmente siguiendo las instrucciones en:");
                $this->info("   INSTRUCCIONES_CREAR_EXCEL_MANUAL.md");
                $this->info("\n💡 También puedes usar los archivos CSV de ejemplo y convertirlos a Excel:");
                $this->info("   - ejemplo_importacion_ninos.csv");
                $this->info("   - ejemplo_importacion_extra.csv");
                $this->info("   - ejemplo_importacion_madre.csv");
                $this->info("   - ejemplo_importacion_controles_cred.csv");
                return 1;
            }

            $export = new EjemploMultiHojasExport();
            $tempFile = $export->download();
            
            // Copiar el archivo temporal a la ubicación deseada
            copy($tempFile, $outputPath);
            @unlink($tempFile);

            $this->info("✅ Archivo Excel creado exitosamente!");
            $this->info("📁 Ubicación: " . $outputPath);
            $this->info("\n📊 Contenido:");
            $this->info("   - 1 niño");
            $this->info("   - 1 registro de datos extra");
            $this->info("   - 1 registro de datos de madre");
            $this->info("   - 3 controles CRED");
            $this->info("\n🚀 Ahora puedes importar este archivo en SISCADIT usando el botón 'Importar Datos'");

            return 0;
        } catch (\Exception $e) {
            $this->error("❌ Error al crear el archivo: " . $e->getMessage());
            $this->info("\n💡 Solución alternativa:");
            $this->info("   Crea el Excel manualmente siguiendo: INSTRUCCIONES_CREAR_EXCEL_MANUAL.md");
            return 1;
        }
    }
}

