<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Imports\ImportMultiHojas;
use App\Imports\ImportMultiHojasCSV;
use App\Models\Nino;
use App\Models\DatosExtra;
use App\Models\Madre;
use App\Models\ControlRn;
use App\Models\ControlMenor1;
use App\Models\TamizajeNeonatal;
use App\Models\VacunaRn;
use App\Models\RecienNacido;
use App\Models\VisitaDomiciliaria;

class ImportControlesController extends Controller
{
    /**
     * Procesar archivo Excel subido (formato múltiples hojas)
     */
    public function import(Request $request)
    {
        $request->validate([
            'archivo_excel' => 'required|mimes:xlsx,xls,csv|max:10240', // Max 10MB
            'tipo_archivo' => 'nullable|in:excel,csv', // Tipo de archivo
        ], [
            'archivo_excel.required' => 'Debe seleccionar un archivo Excel o CSV',
            'archivo_excel.mimes' => 'El archivo debe ser de tipo Excel (.xlsx, .xls) o CSV (.csv)',
            'archivo_excel.max' => 'El archivo no debe exceder 10MB',
        ]);

        try {
            $file = $request->file('archivo_excel');
            
            // Validar que el archivo existe y es válido
            if (!$file || !$file->isValid()) {
                return redirect()->route('controles-cred')
                    ->with('import_error', 'El archivo no es válido o no se pudo cargar correctamente.');
            }

            // Validar tamaño del archivo
            if ($file->getSize() > 10485760) { // 10MB en bytes
                return redirect()->route('controles-cred')
                    ->with('import_error', 'El archivo excede el tamaño máximo permitido de 10MB.');
            }

            // Detectar tipo de archivo
            $extension = strtolower($file->getClientOriginalExtension());
            $tipoArchivo = $request->input('tipo_archivo', $extension === 'csv' ? 'csv' : 'excel');
            
            // Guardar archivo temporalmente
            $tempPath = $file->getRealPath();
            if (!$tempPath) {
                $tempPath = $file->store('temp', 'local');
                $tempPath = storage_path('app/' . $tempPath);
            }
            
            // Usar transacción de base de datos para asegurar que todo se guarde o nada
            DB::beginTransaction();
            
            try {
                // Si es CSV, usar el importador CSV (funciona con PHP 8)
                if ($tipoArchivo === 'csv' || $extension === 'csv') {
                    $import = new ImportMultiHojasCSV();
                    $import->import($tempPath);
                } else {
                    // Intentar con Excel (puede fallar en PHP 8)
                    $import = new ImportMultiHojas();
                    $import->import($tempPath);
                }
                
                // Obtener estadísticas y resultados
                $stats = $import->getStats();
                $success = $import->getSuccess();
                $errors = $import->getErrors();
                
                // Verificar que se haya guardado algo
                $totalGuardados = ($stats['ninos'] ?? 0) + 
                                 ($stats['controles_cred'] ?? 0) + 
                                 ($stats['controles_rn'] ?? 0) + 
                                 ($stats['madres'] ?? 0) + 
                                 ($stats['datos_extra'] ?? 0);
                
                if ($totalGuardados === 0 && empty($errors)) {
                    DB::rollBack();
                    return redirect()->route('controles-cred')
                        ->with('import_error', 'No se importaron datos. Verifique que el archivo tenga el formato correcto y datos válidos.');
                }
                
                // Si hay errores críticos, hacer rollback
                $erroresCriticos = array_filter($errors, function($error) {
                    return stripos($error, 'error fatal') !== false || 
                           stripos($error, 'no se puede conectar') !== false ||
                           stripos($error, 'tabla no existe') !== false;
                });
                
                if (!empty($erroresCriticos)) {
                    DB::rollBack();
                    return redirect()->route('controles-cred')
                        ->with('import_error', 'Error crítico en la importación: ' . implode(', ', $erroresCriticos));
                }
                
                // Confirmar la transacción - TODOS los datos se guardan ahora
                DB::commit();
                
                Log::info('Importación completada exitosamente', [
                    'stats' => $stats,
                    'total_guardados' => $totalGuardados,
                    'errores' => count($errors)
                ]);
                
            } catch (\Exception $e) {
                // Revertir todos los cambios si hay algún error
                DB::rollBack();
                
                // Limpiar archivo temporal
                if ($file->getRealPath() !== $tempPath && file_exists($tempPath)) {
                    @unlink($tempPath);
                }
                
                $errorMessage = $e->getMessage();
                
                // Si es un error de PHPExcel con PHP 8, sugerir usar CSV
                if (strpos($errorMessage, 'syntax error') !== false || strpos($errorMessage, 'unexpected token') !== false) {
                    $errorMessage = "Error de compatibilidad: PHPExcel no es compatible con PHP 8. " .
                                   "SOLUCIÓN: Por favor, convierta su archivo Excel a CSV y vuelva a intentar. " .
                                   "O use PHP 7.4. Error técnico: " . $e->getMessage();
                }
                
                Log::error('Error en importación', [
                    'error' => $errorMessage,
                    'trace' => $e->getTraceAsString()
                ]);
                
                return redirect()->route('controles-cred')
                    ->with('import_error', 'Error al importar el archivo: ' . $errorMessage);
            }
            
            // Limpiar archivo temporal si fue creado
            if ($file->getRealPath() !== $tempPath && file_exists($tempPath)) {
                @unlink($tempPath);
            }

            $mensaje = "✅ Importación completada exitosamente!\n\n";
            $mensaje .= "📊 Estadísticas:\n";
            if (isset($stats['ninos']) && $stats['ninos'] > 0) {
                $mensaje .= "- Niños creados: {$stats['ninos']}\n";
            }
            if (isset($stats['actualizados_ninos']) && $stats['actualizados_ninos'] > 0) {
                $mensaje .= "- Niños actualizados: {$stats['actualizados_ninos']}\n";
            }
            if (isset($stats['madres']) && $stats['madres'] > 0) {
                $mensaje .= "- Madres creadas: {$stats['madres']}\n";
            }
            if (isset($stats['actualizados_madres']) && $stats['actualizados_madres'] > 0) {
                $mensaje .= "- Madres actualizadas: {$stats['actualizados_madres']}\n";
            }
            if (isset($stats['datos_extra']) && $stats['datos_extra'] > 0) {
                $mensaje .= "- Datos extra creados: {$stats['datos_extra']}\n";
            }
            if (isset($stats['actualizados_extra']) && $stats['actualizados_extra'] > 0) {
                $mensaje .= "- Datos extra actualizados: {$stats['actualizados_extra']}\n";
            }
            if (isset($stats['controles_rn']) && $stats['controles_rn'] > 0) {
                $mensaje .= "- Controles RN creados: {$stats['controles_rn']}\n";
            }
            if (isset($stats['actualizados_controles']) && $stats['actualizados_controles'] > 0) {
                $mensaje .= "- Controles RN actualizados: {$stats['actualizados_controles']}\n";
            }
            if (isset($stats['controles_cred']) && $stats['controles_cred'] > 0) {
                $mensaje .= "- Controles CRED: {$stats['controles_cred']}\n";
            }
            if (isset($stats['tamizajes']) && $stats['tamizajes'] > 0) {
                $mensaje .= "- Tamizajes: {$stats['tamizajes']}\n";
            }
            if (isset($stats['vacunas']) && $stats['vacunas'] > 0) {
                $mensaje .= "- Vacunas: {$stats['vacunas']}\n";
            }
            if (isset($stats['visitas']) && $stats['visitas'] > 0) {
                $mensaje .= "- Visitas: {$stats['visitas']}\n";
            }
            if (isset($stats['recien_nacido']) && $stats['recien_nacido'] > 0) {
                $mensaje .= "- Recién Nacido: {$stats['recien_nacido']}\n";
            }

            if (!empty($errors)) {
                $mensaje .= "\n⚠️ Errores: " . count($errors) . "\n";
                foreach (array_slice($errors, 0, 10) as $error) {
                    $mensaje .= "- {$error}\n";
                }
                if (count($errors) > 10) {
                    $mensaje .= "... y " . (count($errors) - 10) . " errores más\n";
                }
            }

            // Obtener datos detallados de los niños importados
            $ninosImportadosIds = method_exists($import, 'getNinosImportados') ? $import->getNinosImportados() : [];
            $ninosDetallados = $this->obtenerDatosDetalladosNinos($ninosImportadosIds);
            
            // Verificar que los datos se guardaron correctamente en la BD
            $verificacionBD = $this->verificarDatosEnBaseDatos($ninosImportadosIds, $stats);
            
            Log::info('Importación completada - Verificación BD', [
                'ninos_importados' => count($ninosImportadosIds),
                'verificacion_bd' => $verificacionBD
            ]);

            return redirect()->route('controles-cred')
                ->with('import_success', $mensaje)
                ->with('stats', $stats)
                ->with('errors', $errors)
                ->with('ninos_detallados', $ninosDetallados)
                ->with('verificacion_bd', $verificacionBD);

        } catch (\Exception $e) {
            return redirect()->route('controles-cred')
                ->with('import_error', 'Error al importar el archivo: ' . $e->getMessage());
        }
    }
    
    /**
     * Verificar que los datos se guardaron correctamente en la base de datos
     */
    private function verificarDatosEnBaseDatos(array $ninosIds, array $stats)
    {
        $verificacion = [
            'ninos_en_bd' => 0,
            'controles_cred_en_bd' => 0,
            'controles_rn_en_bd' => 0,
            'madres_en_bd' => 0,
            'datos_extra_en_bd' => 0,
            'total_verificado' => true
        ];
        
        if (empty($ninosIds)) {
            return $verificacion;
        }
        
        // Verificar que los niños existen en la BD
        $ninosEnBD = Nino::whereIn('id_niño', $ninosIds)->count();
        $verificacion['ninos_en_bd'] = $ninosEnBD;
        
        // Verificar controles CRED
        $controlesCredEnBD = ControlMenor1::whereIn('id_niño', $ninosIds)->count();
        $verificacion['controles_cred_en_bd'] = $controlesCredEnBD;
        
        // Verificar controles RN
        $controlesRnEnBD = ControlRn::whereIn('id_niño', $ninosIds)->count();
        $verificacion['controles_rn_en_bd'] = $controlesRnEnBD;
        
        // Verificar madres
        $madresEnBD = Madre::whereIn('id_niño', $ninosIds)->count();
        $verificacion['madres_en_bd'] = $madresEnBD;
        
        // Verificar datos extra
        $datosExtraEnBD = DatosExtra::whereIn('id_niño', $ninosIds)->count();
        $verificacion['datos_extra_en_bd'] = $datosExtraEnBD;
        
        // Verificar que los datos coinciden con las estadísticas
        $esperadoControlesCred = ($stats['controles_cred'] ?? 0) + ($stats['actualizados_controles_cred'] ?? 0);
        if ($esperadoControlesCred > 0 && $controlesCredEnBD < $esperadoControlesCred) {
            $verificacion['total_verificado'] = false;
        }
        
        return $verificacion;
    }
    
    /**
     * Obtener datos detallados de los niños importados
     */
    private function obtenerDatosDetalladosNinos(array $ninosIds)
    {
        if (empty($ninosIds)) {
            return [];
        }
        
        $ninosDetallados = [];
        
        foreach ($ninosIds as $ninoId) {
            $nino = Nino::where('id_niño', $ninoId)->first();
            if (!$nino) {
                continue;
            }
            
            // Obtener datos extra
            $datosExtra = DatosExtra::where('id_niño', $ninoId)->first();
            
            // Obtener datos de la madre
            $madre = Madre::where('id_niño', $ninoId)->first();
            
            // Obtener controles RN
            $controlesRn = ControlRn::where('id_niño', $ninoId)->get();
            
            // Obtener controles CRED
            $controlesCred = ControlMenor1::where('id_niño', $ninoId)->get();
            
            // Obtener tamizaje neonatal
            $tamizaje = TamizajeNeonatal::where('id_niño', $ninoId)->first();
            
            // Obtener vacunas RN
            $vacunas = VacunaRn::where('id_niño', $ninoId)->first();
            
            // Obtener CNV (Recién Nacido)
            $cnv = RecienNacido::where('id_niño', $ninoId)->first();
            
            // Obtener visitas domiciliarias
            $visitas = VisitaDomiciliaria::where('id_niño', $ninoId)->get();
            
            $ninosDetallados[] = [
                'nino' => [
                    'id_niño' => $nino->id_niño,
                    'apellidos_nombres' => $nino->apellidos_nombres,
                    'numero_doc' => $nino->numero_doc,
                    'tipo_doc' => $nino->tipo_doc,
                    'fecha_nacimiento' => $nino->fecha_nacimiento,
                    'genero' => $nino->genero,
                    'establecimiento' => $nino->establecimiento,
                ],
                'datos_extra' => $datosExtra ? [
                    'red' => $datosExtra->red,
                    'microred' => $datosExtra->microred,
                    'eess_nacimiento' => $datosExtra->eess_nacimiento,
                    'distrito' => $datosExtra->distrito,
                    'provincia' => $datosExtra->provincia,
                    'departamento' => $datosExtra->departamento,
                    'seguro' => $datosExtra->seguro,
                    'programa' => $datosExtra->programa,
                ] : null,
                'madre' => $madre ? [
                    'dni' => $madre->dni,
                    'apellidos_nombres' => $madre->apellidos_nombres,
                    'celular' => $madre->celular,
                    'domicilio' => $madre->domicilio,
                    'referencia_direccion' => $madre->referencia_direccion,
                ] : null,
                'controles_rn' => $controlesRn->map(function($control) {
                    return [
                        'numero_control' => $control->numero_control,
                        'fecha' => $control->fecha,
                        'edad' => $control->edad,
                        'estado' => $control->estado,
                        'peso' => $control->peso,
                        'talla' => $control->talla,
                        'perimetro_cefalico' => $control->perimetro_cefalico,
                    ];
                }),
                'controles_cred' => $controlesCred->map(function($control) {
                    return [
                        'numero_control' => $control->numero_control,
                        'fecha' => $control->fecha,
                        'edad' => $control->edad,
                        'estado' => $control->estado,
                        'peso' => $control->peso,
                        'talla' => $control->talla,
                        'perimetro_cefalico' => $control->perimetro_cefalico,
                    ];
                }),
                'tamizaje' => $tamizaje ? [
                    'fecha_tam_neo' => $tamizaje->fecha_tam_neo,
                    'edad_tam_neo' => $tamizaje->edad_tam_neo,
                    'cumple_tam_neo' => $tamizaje->cumple_tam_neo,
                ] : null,
                'vacunas' => $vacunas ? [
                    'fecha_bcg' => $vacunas->fecha_bcg,
                    'edad_bcg' => $vacunas->edad_bcg,
                    'estado_bcg' => $vacunas->estado_bcg,
                    'fecha_hvb' => $vacunas->fecha_hvb,
                    'edad_hvb' => $vacunas->edad_hvb,
                    'estado_hvb' => $vacunas->estado_hvb,
                    'cumple_BCG_HVB' => $vacunas->cumple_BCG_HVB,
                ] : null,
                'cnv' => $cnv ? [
                    'peso' => $cnv->peso,
                    'edad_gestacional' => $cnv->edad_gestacional,
                    'clasificacion' => $cnv->clasificacion,
                ] : null,
                'visitas' => $visitas->map(function($visita) {
                    return [
                        'grupo_visita' => $visita->grupo_visita,
                        'fecha_visita' => $visita->fecha_visita,
                        'numero_visitas' => $visita->numero_visitas,
                    ];
                }),
            ];
        }
        
        return $ninosDetallados;
    }
}

