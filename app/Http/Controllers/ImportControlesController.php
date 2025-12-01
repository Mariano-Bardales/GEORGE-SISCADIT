<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\ControlesImport;
use App\Exports\TemplateControlesExport;
use App\Models\Nino;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class ImportControlesController extends Controller
{
    /**
     * Procesar archivo Excel subido
     */
    public function import(Request $request)
    {
        $request->validate([
            'archivo_excel' => 'required|mimes:xlsx,xls,csv|max:10240', // Max 10MB
        ], [
            'archivo_excel.required' => 'Debe seleccionar un archivo Excel o CSV',
            'archivo_excel.mimes' => 'El archivo debe ser de tipo Excel (.xlsx, .xls) o CSV (.csv)',
            'archivo_excel.max' => 'El archivo no debe exceder 10MB',
        ]);

        try {
            $file = $request->file('archivo_excel');
            $import = new ControlesImport();
            
            Excel::import($import, $file);

            $stats = $import->getStats();
            $success = $import->getSuccess();
            $errors = $import->getErrors();

            $mensaje = "✅ Importación completada exitosamente!\n\n";
            $mensaje .= "📊 Estadísticas:\n";
            if (isset($stats['ninos']) && $stats['ninos'] > 0) {
                $mensaje .= "- Niños creados/actualizados: {$stats['ninos']}\n";
            }
            if (isset($stats['madres']) && $stats['madres'] > 0) {
                $mensaje .= "- Madres creadas/actualizadas: {$stats['madres']}\n";
            }
            $mensaje .= "- Controles RN: {$stats['controles_rn']}\n";
            $mensaje .= "- Controles CRED: {$stats['controles_cred']}\n";
            $mensaje .= "- Tamizajes: {$stats['tamizajes']}\n";
            $mensaje .= "- Vacunas: {$stats['vacunas']}\n";
            $mensaje .= "- Visitas: {$stats['visitas']}\n";
            $mensaje .= "- Datos Extra: {$stats['datos_extra']}\n";
            $mensaje .= "- Recién Nacido: {$stats['recien_nacido']}\n";

            if (!empty($errors)) {
                $mensaje .= "\n⚠️ Errores: " . count($errors) . "\n";
                foreach (array_slice($errors, 0, 10) as $error) {
                    $mensaje .= "- {$error}\n";
                }
                if (count($errors) > 10) {
                    $mensaje .= "... y " . (count($errors) - 10) . " errores más\n";
                }
            }

            return redirect()->route('controles-cred')
                ->with('import_success', $mensaje)
                ->with('stats', $stats)
                ->with('errors', $errors);

        } catch (\Exception $e) {
            return redirect()->route('controles-cred')
                ->with('import_error', 'Error al importar el archivo: ' . $e->getMessage());
        }
    }

    /**
     * Descargar template Excel
     */
    public function downloadTemplate()
    {
        return Excel::download(new TemplateControlesExport(), 'template_controles.xlsx');
    }

    /**
     * Descargar archivo de ejemplo con datos reales
     */
    public function downloadEjemplo()
    {
        $ejemploPath = storage_path('app/ejemplo_controles.csv');
        
        // Siempre regenerar el archivo con datos actuales
        $this->crearArchivoEjemploCompleto($ejemploPath);

        if (!file_exists($ejemploPath)) {
            return redirect()->route('controles-cred')
                ->with('import_error', 'No se pudo generar el archivo de ejemplo.');
        }

        return response()->download($ejemploPath, 'ejemplo_controles.csv', [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="ejemplo_controles.csv"',
        ]);
    }

    /**
     * Crear archivo de ejemplo completo con datos reales
     */
    protected function crearArchivoEjemploCompleto($path)
    {
        $dir = dirname($path);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $ninos = Nino::take(4)->get();

        if ($ninos->isEmpty()) {
            // Si no hay niños, crear ejemplo básico
            $this->crearArchivoEjemploBasico($path);
            return;
        }

        $fp = fopen($path, 'w');
        
        // Encabezados
        fputcsv($fp, [
            'ID_NINO', 'TIPO_CONTROL', 'NUMERO_CONTROL', 'FECHA', 'ESTADO',
            'ESTADO_CRED_ONCE', 'ESTADO_CRED_FINAL', 'PESO', 'TALLA', 'PERIMETRO_CEFALICO',
            'FECHA_BCG', 'ESTADO_BCG', 'FECHA_HVB', 'ESTADO_HVB', 'FECHA_TAMIZAJE', 
            'FECHA_VISITA', 'PERIODO', 'GRUPO_VISITA', 'RED', 'MICRORED', 'DISTRITO', 'PROVINCIA', 'DEPARTAMENTO', 'SEGURO', 'PROGRAMA',
            'PESO_RN', 'EDAD_GESTACIONAL', 'CLASIFICACION',
            'NUMERO_DOCUMENTO', 'TIPO_DOCUMENTO', 'APELLIDOS_NOMBRES', 'FECHA_NACIMIENTO', 'GENERO', 'ESTABLECIMIENTO',
            'DNI_MADRE', 'APELLIDOS_NOMBRES_MADRE', 'CELULAR_MADRE', 'DOMICILIO_MADRE', 'REFERENCIA_DIRECCION',
            'SOBRESCRIBIR'
        ]);

        foreach ($ninos as $nino) {
            $ninoId = $nino->id_niño;
            $fechaNacimiento = Carbon::parse($nino->fecha_nacimiento);
            $hoy = Carbon::now();
            $edadDias = $fechaNacimiento->diffInDays($hoy);

            // Recién nacido (0-28 días)
            if ($edadDias <= 28) {
                // CRN 1 (2-6 días) con datos antropométricos
                if ($edadDias >= 2) {
                    $fecha = $fechaNacimiento->copy()->addDays(rand(2, min(6, $edadDias)));
                    $peso = 3200 + rand(-200, 200);
                    $talla = 50.0 + rand(-5, 5) / 10;
                    $pc = 35.0 + rand(-3, 3) / 10;
                    fputcsv($fp, [$ninoId, 'CRN', 1, $fecha->format('Y-m-d'), 'Completo', '', '', $peso, $talla, $pc, '', '', '', '', '', '', '', '', '', '', '', '', '']);
                }
                // CRN 2 (7-13 días)
                if ($edadDias >= 7) {
                    $fecha = $fechaNacimiento->copy()->addDays(rand(7, min(13, $edadDias)));
                    $peso = 3300 + rand(-200, 200);
                    $talla = 51.0 + rand(-5, 5) / 10;
                    $pc = 35.5 + rand(-3, 3) / 10;
                    fputcsv($fp, [$ninoId, 'CRN', 2, $fecha->format('Y-m-d'), 'Completo', '', '', $peso, $talla, $pc, '', '', '', '', '', '', '', '', '', '', '', '', '']);
                }
                // CRN 3 (14-20 días)
                if ($edadDias >= 14) {
                    $fecha = $fechaNacimiento->copy()->addDays(rand(14, min(20, $edadDias)));
                    $peso = 3400 + rand(-200, 200);
                    $talla = 52.0 + rand(-5, 5) / 10;
                    $pc = 36.0 + rand(-3, 3) / 10;
                    fputcsv($fp, [$ninoId, 'CRN', 3, $fecha->format('Y-m-d'), 'Completo', '', '', $peso, $talla, $pc, '', '', '', '', '', '', '', '', '', '', '', '', '']);
                }
                // CRN 4 (21-28 días)
                if ($edadDias >= 21) {
                    $fecha = $fechaNacimiento->copy()->addDays(rand(21, min(28, $edadDias)));
                    $peso = 3500 + rand(-200, 200);
                    $talla = 53.0 + rand(-5, 5) / 10;
                    $pc = 36.5 + rand(-3, 3) / 10;
                    fputcsv($fp, [$ninoId, 'CRN', 4, $fecha->format('Y-m-d'), 'Completo', '', '', $peso, $talla, $pc, '', '', '', '', '', '', '', '', '', '', '', '', '']);
                }

                // Vacunas (1-2 días)
                $fechaBCG = $fechaNacimiento->copy()->addDays(rand(1, min(2, $edadDias)));
                $fechaHVB = $fechaNacimiento->copy()->addDays(rand(1, min(2, $edadDias)));
                fputcsv($fp, [$ninoId, 'VACUNA', '', '', '', '', '', '', '', '', $fechaBCG->format('Y-m-d'), 'SI', $fechaHVB->format('Y-m-d'), 'SI', '', '', '', '', '', '', '', '', '']);

                // Tamizaje (1-29 días)
                $fechaTamizaje = $fechaNacimiento->copy()->addDays(rand(1, min(29, $edadDias)));
                fputcsv($fp, [$ninoId, 'TAMIZAJE', '', '', '', '', '', '', '', '', '', '', '', $fechaTamizaje->format('Y-m-d'), '', '', '', '', '', '', '', '', '']);

                // Recién Nacido (CNV)
                $pesoRN = 3500 + rand(-500, 500);
                $edadGestacional = 38 + rand(-2, 2);
                $clasificacion = rand(0, 1) === 0 ? 'Normal' : 'Bajo Peso al Nacer y/o Prematuro';
                fputcsv($fp, [$ninoId, 'RECIEN_NACIDO', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', $pesoRN, $edadGestacional, $clasificacion, '']);
            }

            // Menor de 1 año (29-359 días) - Usando los mismos rangos que el ApiController
            if ($edadDias >= 29 && $edadDias <= 359) {
                $rangos = [
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
                    11 => ['min' => 330, 'max' => 359],
                ];

                foreach ($rangos as $numControl => $rango) {
                    if ($edadDias >= $rango['min']) {
                        // Calcular fecha dentro del rango válido
                        $diasDesdeNacimiento = rand($rango['min'], min($rango['max'], $edadDias));
                        $fechaControl = $fechaNacimiento->copy()->addDays($diasDesdeNacimiento);
                        $edadControl = $fechaNacimiento->diffInDays($fechaControl);
                        
                        // Determinar estado según si cumple con el rango
                        $estado = ($edadControl >= $rango['min'] && $edadControl <= $rango['max']) ? 'cumple' : 'no_cumple';
                        
                        // Generar datos antropométricos realistas según la edad en meses
                        $meses = floor($edadControl / 30);
                        $pesoBase = 3200 + ($meses * 600); // Incremento de ~600g por mes
                        $tallaBase = 50 + ($meses * 2.2); // Incremento de ~2.2cm por mes
                        $pcBase = 35 + ($meses * 0.6); // Incremento de ~0.6cm por mes
                        
                        // Asegurar valores mínimos y máximos realistas
                        $peso = max(2500, min(12000, $pesoBase + rand(-300, 300)));
                        $talla = max(45, min(85, round($tallaBase + rand(-1.5, 1.5), 1)));
                        $pc = max(32, min(48, round($pcBase + rand(-0.8, 0.8), 1)));
                        
                        // Estados CRED según la edad
                        $estadoCredOnce = $meses >= 11 ? 'Adecuado' : 'Normal';
                        $estadoCredFinal = 'Normal';
                        
                        fputcsv($fp, [
                            $ninoId, 'CRED', $numControl, $fechaControl->format('Y-m-d'), $estado, 
                            $estadoCredOnce, $estadoCredFinal, 
                            $peso, $talla, $pc,
                            '', '', '', '', '', '', '', '', '', '', '', '', ''
                        ]);
                    }
                }
            }

            // Visitas domiciliarias (si el niño tiene más de 28 días)
            if ($edadDias >= 28) {
                $fechaVisita28 = $fechaNacimiento->copy()->addDays(28);
                fputcsv($fp, [$ninoId, 'VISITA', '', '', '', '', '', '', '', '', '', '', '', '', $fechaVisita28->format('Y-m-d'), 'A', '', '', '', '', '', '', '']);
            }
            if ($edadDias >= 60) {
                $fechaVisita2_5 = $fechaNacimiento->copy()->addDays(rand(60, min(150, $edadDias)));
                fputcsv($fp, [$ninoId, 'VISITA', '', '', '', '', '', '', '', '', '', '', '', '', $fechaVisita2_5->format('Y-m-d'), 'B', '', '', '', '', '', '', '']);
            }

            // Datos extra (para todos)
            fputcsv($fp, [$ninoId, 'DATOS_EXTRA', '', '', '', '', '', '', '', '', '', '', '', '', '', 'Red de Salud Lima Norte', 'Microred 01', 'San Juan de Lurigancho', '', '', '', '']);
        }

        fclose($fp);
    }

    /**
     * Crear archivo de ejemplo básico si no hay niños
     */
    protected function crearArchivoEjemploBasico($path)
    {
        $fp = fopen($path, 'w');
        
        // Encabezados
        fputcsv($fp, [
            'ID_NINO', 'TIPO_CONTROL', 'NUMERO_CONTROL', 'FECHA', 'ESTADO',
            'ESTADO_CRED_ONCE', 'ESTADO_CRED_FINAL', 'PESO', 'TALLA', 'PERIMETRO_CEFALICO',
            'FECHA_BCG', 'ESTADO_BCG', 'FECHA_HVB', 'ESTADO_HVB', 'FECHA_TAMIZAJE', 
            'FECHA_VISITA', 'PERIODO', 'GRUPO_VISITA', 'RED', 'MICRORED', 'DISTRITO', 'PROVINCIA', 'DEPARTAMENTO', 'SEGURO', 'PROGRAMA',
            'PESO_RN', 'EDAD_GESTACIONAL', 'CLASIFICACION',
            'NUMERO_DOCUMENTO', 'TIPO_DOCUMENTO', 'APELLIDOS_NOMBRES', 'FECHA_NACIMIENTO', 'GENERO', 'ESTABLECIMIENTO',
            'DNI_MADRE', 'APELLIDOS_NOMBRES_MADRE', 'CELULAR_MADRE', 'DOMICILIO_MADRE', 'REFERENCIA_DIRECCION',
            'SOBRESCRIBIR'
        ]);

        // Datos de ejemplo básicos con valores realistas
        $hoy = Carbon::now();
        $fechaNacimientoEjemplo = $hoy->copy()->subDays(45);
        
        // CRN 1 (2-6 días después del nacimiento) con datos antropométricos
        $fechaCRN1 = $fechaNacimientoEjemplo->copy()->addDays(4);
        fputcsv($fp, ['1', 'CRN', '1', $fechaCRN1->format('Y-m-d'), 'Completo', '', '', '3200', '50.5', '35.2', '', '', '', '', '', '', '', '', '', '', '', '', '']);
        
        // CRED 1 (29-59 días) - dentro del rango válido
        $fechaCRED1 = $fechaNacimientoEjemplo->copy()->addDays(45);
        fputcsv($fp, ['1', 'CRED', '1', $fechaCRED1->format('Y-m-d'), 'Completo', 'Normal', 'Normal', '3800', '53.2', '36.8', '', '', '', '', '', '', '', '', '', '', '', '', '']);
        
        // Vacunas (1-2 días después del nacimiento)
        $fechaBCG = $fechaNacimientoEjemplo->copy()->addDays(1);
        $fechaHVB = $fechaNacimientoEjemplo->copy()->addDays(1);
        fputcsv($fp, ['1', 'VACUNA', '', '', '', '', '', '', '', '', $fechaBCG->format('Y-m-d'), 'SI', $fechaHVB->format('Y-m-d'), 'SI', '', '', '', '', '', '', '', '', '']);
        
        // Tamizaje (1-29 días después del nacimiento)
        $fechaTamizaje = $fechaNacimientoEjemplo->copy()->addDays(5);
        fputcsv($fp, ['1', 'TAMIZAJE', '', '', '', '', '', '', '', '', '', '', '', $fechaTamizaje->format('Y-m-d'), '', '', '', '', '', '', '', '', '']);
        
        // Visita domiciliaria
        $fechaVisita = $fechaNacimientoEjemplo->copy()->addDays(28);
        fputcsv($fp, ['1', 'VISITA', '', '', '', '', '', '', '', '', '', '', '', '', $fechaVisita->format('Y-m-d'), 'A', '', '', '', '', '', '', '']);
        
        // Recién Nacido (CNV)
        fputcsv($fp, ['1', 'RECIEN_NACIDO', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '3500', '38', 'Normal', '']);
        
        // Datos extra
        fputcsv($fp, ['1', 'DATOS_EXTRA', '', '', '', '', '', '', '', '', '', '', '', '', '', 'Red de Salud Lima Norte', 'Microred 01', 'San Juan de Lurigancho', '', '', '', '']);

        fclose($fp);
    }
}

