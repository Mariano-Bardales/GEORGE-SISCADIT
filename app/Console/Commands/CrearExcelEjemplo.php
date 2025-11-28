<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Nino;
use App\Exports\TemplateControlesExport;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class CrearExcelEjemplo extends Command
{
    protected $signature = 'controles:crear-excel-ejemplo {--output=ejemplo_controles.xlsx}';
    protected $description = 'Crear un archivo Excel de ejemplo con datos reales del sistema';

    public function handle()
    {
        $outputFile = $this->option('output');
        $outputPath = storage_path('app/' . $outputFile);

        $this->info("🔄 Creando archivo Excel de ejemplo...");

        // Obtener niños reales de la base de datos
        $ninos = Nino::take(4)->get();

        if ($ninos->isEmpty()) {
            $this->error("❌ No hay niños en la base de datos. Crea algunos niños primero.");
            return 1;
        }

        $this->info("📋 Encontrados " . $ninos->count() . " niños en la base de datos");

        // Crear datos de ejemplo basados en niños reales
        $datos = [];
        
        foreach ($ninos as $nino) {
            $ninoId = $nino->id_niño;
            $fechaNacimiento = Carbon::parse($nino->fecha_nacimiento);
            $hoy = Carbon::now();
            $edadDias = $fechaNacimiento->diffInDays($hoy);

            $this->info("  👶 Procesando: {$nino->apellidos_nombres} (ID: {$ninoId}, Edad: {$edadDias} días)");

            // Si es recién nacido (0-28 días)
            if ($edadDias <= 28) {
                // Controles RN
                for ($i = 1; $i <= min(4, (int)($edadDias / 7) + 1); $i++) {
                    $fechaControl = $fechaNacimiento->copy()->addDays(rand(2 + ($i-1)*7, min(6 + ($i-1)*7, $edadDias)));
                    $datos[] = [
                        $ninoId,
                        'CRN',
                        $i,
                        $fechaControl->format('Y-m-d'),
                        'Completo',
                        '',
                        '',
                        '',
                        '',
                        '',
                        '',
                        '',
                        '',
                        '',
                        '',
                        '',
                        '',
                        ''
                    ];
                }

                // Vacunas RN
                $fechaBCG = $fechaNacimiento->copy()->addDays(rand(0, min(7, $edadDias)));
                $fechaHVB = $fechaNacimiento->copy()->addDays(rand(0, min(7, $edadDias)));
                $datos[] = [
                    $ninoId,
                    'VACUNA',
                    '',
                    '',
                    '',
                    '',
                    '',
                    $fechaBCG->format('Y-m-d'),
                    'SI',
                    $fechaHVB->format('Y-m-d'),
                    'SI',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    ''
                ];

                // Tamizaje
                $fechaTamizaje = $fechaNacimiento->copy()->addDays(rand(1, min(29, $edadDias)));
                $datos[] = [
                    $ninoId,
                    'TAMIZAJE',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    $fechaTamizaje->format('Y-m-d'),
                    '',
                    '',
                    '',
                    '',
                    '',
                    ''
                ];
            }

            // Si es menor de 1 año (29-359 días)
            if ($edadDias >= 29 && $edadDias <= 359) {
                // Controles CRED mensual
                $rangos = [
                    1 => ['min' => 29, 'max' => 59],
                    2 => ['min' => 60, 'max' => 89],
                    3 => ['min' => 90, 'max' => 119],
                    4 => ['min' => 120, 'max' => 149],
                    5 => ['min' => 150, 'max' => 179],
                ];

                foreach ($rangos as $numControl => $rango) {
                    if ($edadDias >= $rango['min']) {
                        $fechaControl = $fechaNacimiento->copy()->addDays(rand($rango['min'], min($rango['max'], $edadDias)));
                        $datos[] = [
                            $ninoId,
                            'CRED',
                            $numControl,
                            $fechaControl->format('Y-m-d'),
                            'Completo',
                            'Adecuado',
                            'Normal',
                            '',
                            '',
                            '',
                            '',
                            '',
                            '',
                            '',
                            '',
                            '',
                            '',
                            ''
                        ];
                    }
                }

                // Visitas domiciliarias
                $numVisitas = min(3, (int)($edadDias / 60) + 1);
                for ($i = 1; $i <= $numVisitas; $i++) {
                    $fechaVisita = $fechaNacimiento->copy()->addDays(rand(30, min($edadDias, 180)));
                    $datos[] = [
                        $ninoId,
                        'VISITA',
                        '',
                        '',
                        '',
                        '',
                        '',
                        '',
                        '',
                        '',
                        '',
                        '',
                        '',
                        $fechaVisita->format('Y-m-d'),
                        'Grupo A',
                        '',
                        '',
                        '',
                        ''
                    ];
                }
            }

            // Datos extra (para todos)
            $datos[] = [
                $ninoId,
                'DATOS_EXTRA',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                'Red de Salud Lima Norte',
                'Microred 01',
                'San Juan de Lurigancho',
                ''
            ];
        }

        // Crear clase export personalizada
        $export = new class($datos) extends TemplateControlesExport {
            protected $customData;

            public function __construct($data)
            {
                $this->customData = $data;
            }

            public function array(): array
            {
                return $this->customData;
            }
        };

        // Guardar el archivo
        Excel::store($export, $outputFile, 'local');

        $this->info("✅ Archivo creado exitosamente: {$outputPath}");
        $this->info("📊 Total de registros: " . count($datos));
        $this->info("\n💡 Puedes usar este archivo para probar la importación:");
        $this->info("   - Sube el archivo desde: /importar-controles");
        $this->info("   - O ejecuta: php artisan controles:import-excel storage/app/{$outputFile}");

        return 0;
    }
}
