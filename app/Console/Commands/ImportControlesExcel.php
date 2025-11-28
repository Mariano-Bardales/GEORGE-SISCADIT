<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Imports\ControlesImport;
use Maatwebsite\Excel\Facades\Excel;

class ImportControlesExcel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'controles:import-excel {file : Ruta al archivo Excel}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Importar controles desde un archivo Excel';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $filePath = $this->argument('file');

        if (!file_exists($filePath)) {
            $this->error("❌ El archivo no existe: {$filePath}");
            return 1;
        }

        $this->info("🔄 Importando controles desde: {$filePath}");

        try {
            $import = new ControlesImport();
            Excel::import($import, $filePath);

            $stats = $import->getStats();
            $success = $import->getSuccess();
            $errors = $import->getErrors();

            $this->info("\n✅ Importación completada!");
            $this->info("\n📊 Estadísticas:");
            $this->table(
                ['Tipo', 'Cantidad'],
                [
                    ['Controles RN', $stats['controles_rn']],
                    ['Controles CRED', $stats['controles_cred']],
                    ['Tamizajes', $stats['tamizajes']],
                    ['Vacunas', $stats['vacunas']],
                    ['Visitas', $stats['visitas']],
                    ['Datos Extra', $stats['datos_extra']],
                    ['Recién Nacido', $stats['recien_nacido']],
                ]
            );

            if (!empty($success)) {
                $this->info("\n✅ Registros importados exitosamente: " . count($success));
            }

            if (!empty($errors)) {
                $this->warn("\n⚠️  Errores encontrados: " . count($errors));
                foreach ($errors as $error) {
                    $this->error("  - {$error}");
                }
            }

            return 0;
        } catch (\Exception $e) {
            $this->error("❌ Error al importar: " . $e->getMessage());
            return 1;
        }
    }
}
