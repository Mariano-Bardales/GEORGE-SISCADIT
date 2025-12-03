<?php

namespace App\Imports;

use App\Models\Nino;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class NinosImport
{
    protected $errors = [];
    protected $success = [];
    protected $stats = ['ninos' => 0, 'actualizados' => 0];
    protected $ninosImportados = []; // IDs de niños importados

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            try {
                $this->importNino($row);
            } catch (\Exception $e) {
                $this->errors[] = "Error en fila: " . $e->getMessage();
            }
        }
    }

    protected function importNino($row)
    {
        try {
            // Normalizar los nombres de columnas (aceptar variaciones)
            $row = $this->normalizeRow($row);
            
            // Buscar el niño por ID o por número de documento
            $nino = null;
            
            if (!empty($row['id_nino']) || !empty($row['id_niño'])) {
                $idNino = $row['id_nino'] ?? $row['id_niño'];
                $nino = Nino::where('id_niño', $idNino)->first();
            } elseif (!empty($row['numero_doc']) && !empty($row['tipo_doc'])) {
                $nino = Nino::where('numero_doc', $row['numero_doc'])
                           ->where('tipo_doc', $row['tipo_doc'])
                           ->first();
            }

            // Mapear tipo de documento (aceptar múltiples formatos)
            $tipoDocMap = [
                'DNI' => 'DNI', 'dni' => 'DNI', '1' => 'DNI',
                'CE' => 'CE', 'ce' => 'CE', '2' => 'CE',
                'PASS' => 'PASS', 'pass' => 'PASS', '3' => 'PASS',
                'DIE' => 'DIE', 'die' => 'DIE', '4' => 'DIE',
                'S/ DOCUMENTO' => 'S/ DOCUMENTO', 'sin documento' => 'S/ DOCUMENTO', '5' => 'S/ DOCUMENTO',
                'CNV' => 'CNV', 'cnv' => 'CNV', '6' => 'CNV',
            ];
            $tipoDocInput = trim($row['tipo_doc'] ?? '');
            $tipoDoc = $tipoDocMap[$tipoDocInput] ?? ($tipoDocInput ?: 'S/ DOCUMENTO');

            // Validar fecha de nacimiento (requerida)
            $fechaNacimientoRaw = $row['fecha_nacimiento'] ?? null;
            \Log::info('Parseando fecha de nacimiento', [
                'valor_raw' => $fechaNacimientoRaw,
                'tipo' => gettype($fechaNacimientoRaw),
                'fila' => $row['apellidos_nombres'] ?? 'N/A'
            ]);
            
            $fechaNacimiento = $this->parseDate($fechaNacimientoRaw);
            if (!$fechaNacimiento) {
                $this->errors[] = "Fecha de nacimiento requerida o inválida. Valor recibido: " . ($fechaNacimientoRaw ?? 'NULL') . ". Fila: " . ($row['apellidos_nombres'] ?? 'N/A');
                \Log::warning('Fecha de nacimiento inválida', [
                    'valor_raw' => $fechaNacimientoRaw,
                    'fila' => $row['apellidos_nombres'] ?? 'N/A'
                ]);
                return;
            }
            
            \Log::info('Fecha de nacimiento parseada correctamente', [
                'valor_raw' => $fechaNacimientoRaw,
                'fecha_parseada' => $fechaNacimiento->format('Y-m-d'),
                'fila' => $row['apellidos_nombres'] ?? 'N/A'
            ]);

            // Validar que tenga al menos nombre o documento
            if (empty($row['apellidos_nombres']) && empty($row['numero_doc'])) {
                $this->errors[] = "Debe tener al menos nombre o número de documento. Fila: " . ($row['apellidos_nombres'] ?? 'N/A');
                return;
            }

            // Preparar datos
            $data = [
                'establecimiento' => trim($row['establecimiento'] ?? '') ?: null,
                'tipo_doc' => $tipoDoc,
                'numero_doc' => trim($row['numero_doc'] ?? '') ?: null,
                'apellidos_nombres' => trim($row['apellidos_nombres'] ?? '') ?: null,
                'fecha_nacimiento' => $fechaNacimiento->format('Y-m-d'),
                'genero' => strtoupper(trim($row['genero'] ?? $row['sexo'] ?? 'M')),
            ];

            // Validar género
            if (!in_array($data['genero'], ['M', 'F', 'MASCULINO', 'FEMENINO'])) {
                $data['genero'] = 'M'; // Por defecto
            }
            if (in_array($data['genero'], ['MASCULINO', 'FEMENINO'])) {
                $data['genero'] = $data['genero'] === 'MASCULINO' ? 'M' : 'F';
            }

            if ($nino) {
                // Actualizar niño existente
                $nino->update($data);
                $this->stats['actualizados']++;
                $this->success[] = "Niño actualizado: " . ($data['apellidos_nombres'] ?? 'N/A') . " (ID: {$nino->id_niño})";
            } else {
                // Crear nuevo niño - intentar conservar ID del Excel si está presente
                $idNinoPersonalizado = $row['id_nino'] ?? $row['id_niño'] ?? null;
                
                if ($idNinoPersonalizado && is_numeric($idNinoPersonalizado)) {
                    // Verificar si el ID ya existe
                    $existeConId = Nino::where('id_niño', $idNinoPersonalizado)->exists();
                    if (!$existeConId) {
                        // Crear con ID personalizado usando inserción directa
                        $idPersonalizado = (int)$idNinoPersonalizado;
                        
                        // Preparar datos completos para inserción directa
                        // NOTA: La tabla 'niños' NO tiene columnas edad_meses ni edad_dias
                        $dataCompleto = array_merge($data, [
                            'id_niño' => $idPersonalizado,
                        ]);
                        
                        // Usar inserción directa para poder especificar el ID
                        \Log::info('Intentando insertar niño con ID personalizado', [
                            'id_personalizado' => $idPersonalizado,
                            'datos' => $dataCompleto
                        ]);
                        
                        try {
                            DB::table('niños')->insert($dataCompleto);
                            \Log::info('Niño insertado exitosamente en BD');
                            
                            // Recargar el registro desde la base de datos
                            $nino = Nino::find($idPersonalizado);
                            if ($nino) {
                                $this->stats['ninos']++;
                                $this->success[] = "Niño creado con ID personalizado: " . ($data['apellidos_nombres'] ?? 'N/A') . " (ID: {$nino->id_niño})";
                                \Log::info('Niño creado exitosamente', ['id' => $nino->id_niño]);
                            } else {
                                \Log::error('Niño insertado pero no se pudo recargar', ['id_personalizado' => $idPersonalizado]);
                            }
                        } catch (\Exception $e) {
                            \Log::error('Error al insertar niño', [
                                'error' => $e->getMessage(),
                                'trace' => $e->getTraceAsString(),
                                'datos' => $dataCompleto
                            ]);
                            throw $e;
                        }
                    } else {
                        // El ID ya existe, crear sin ID (auto-incrementar)
                        $nino = Nino::create($data);
                        $this->stats['ninos']++;
                        $this->success[] = "Niño creado (ID {$idNinoPersonalizado} ya existe, usando ID: {$nino->id_niño}): " . ($data['apellidos_nombres'] ?? 'N/A');
                    }
                } else {
                    // Crear sin ID personalizado (auto-incrementar)
                    $nino = Nino::create($data);
                    $this->stats['ninos']++;
                    $this->success[] = "Niño creado: " . ($data['apellidos_nombres'] ?? 'N/A') . " (ID: {$nino->id_niño})";
                }
            }
            
            // Guardar ID del niño importado
            if ($nino && $nino->id_niño) {
                $this->ninosImportados[] = $nino->id_niño;
            }
        } catch (\Exception $e) {
            $this->errors[] = "Error al importar niño: " . $e->getMessage() . " - Fila: " . ($row['apellidos_nombres'] ?? 'N/A');
        }
    }

    /**
     * Normalizar nombres de columnas para aceptar variaciones
     */
    protected function normalizeRow($row)
    {
        $normalized = [];
        
        // Mapeo de variaciones de nombres de columnas
        $columnMap = [
            'id_nino' => ['id_nino', 'id_niño', 'id', 'id niño', 'id_nino'],
            'establecimiento' => ['establecimiento', 'eess', 'establecimiento_salud', 'establecimiento de salud'],
            'tipo_doc' => ['tipo_doc', 'tipo_documento', 'tipo doc', 'tipo documento', 'tipo'],
            'numero_doc' => ['numero_doc', 'numero_documento', 'numero doc', 'numero documento', 'documento', 'dni'],
            'apellidos_nombres' => ['apellidos_nombres', 'apellidos y nombres', 'nombre', 'nombres', 'nombre_completo', 'nombre completo'],
            'fecha_nacimiento' => ['fecha_nacimiento', 'fecha nacimiento', 'fecha_de_nacimiento', 'nacimiento', 'fecha'],
            'genero' => ['genero', 'género', 'sexo', 'gender'],
        ];

        foreach ($row as $key => $value) {
            $keyLower = strtolower(trim($key));
            
            // Buscar en el mapeo
            foreach ($columnMap as $standardKey => $variations) {
                if (in_array($keyLower, $variations)) {
                    $normalized[$standardKey] = $value;
                    break;
                }
            }
            
            // Si no está en el mapeo, mantener el original
            if (!isset($normalized[$keyLower])) {
                $normalized[$key] = $value;
            }
        }

        return $normalized;
    }

    protected function parseDate($value)
    {
        if (empty($value) && $value !== 0 && $value !== '0') {
            return null;
        }

        if ($value instanceof \DateTime) {
            return Carbon::instance($value);
        }

        // Si es un número (formato serial de Excel)
        if (is_numeric($value)) {
            $numericValue = (float)$value;
            // Excel usa 1900-01-01 como fecha base, pero tiene un bug: considera 1900 como año bisiesto
            // Por eso restamos 2 días en lugar de 1
            // Además, Excel cuenta desde el 1 de enero de 1900 como día 1
            try {
                $baseDate = Carbon::create(1900, 1, 1);
                $daysToAdd = (int)($numericValue - 2); // Restar 2 porque Excel cuenta desde 1900-01-01 como día 1, y tiene un bug con 1900
                return $baseDate->copy()->addDays($daysToAdd);
            } catch (\Exception $e) {
                \Log::warning('Error al parsear fecha numérica de Excel', [
                    'valor' => $value,
                    'error' => $e->getMessage()
                ]);
                return null;
            }
        }

        // Si es una cadena, intentar parsearla
        try {
            // Intentar diferentes formatos comunes
            $formats = ['Y-m-d', 'd/m/Y', 'm/d/Y', 'Y/m/d', 'd-m-Y', 'm-d-Y'];
            foreach ($formats as $format) {
                try {
                    $parsed = Carbon::createFromFormat($format, $value);
                    if ($parsed) {
                        return $parsed;
                    }
                } catch (\Exception $e) {
                    continue;
                }
            }
            
            // Si ningún formato funciona, intentar parse automático
            return Carbon::parse($value);
        } catch (\Exception $e) {
            \Log::warning('Error al parsear fecha como string', [
                'valor' => $value,
                'tipo' => gettype($value),
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function getSuccess()
    {
        return $this->success;
    }

    public function getStats()
    {
        return $this->stats;
    }
    
    public function getNinosImportados()
    {
        return $this->ninosImportados;
    }
}

