<?php

namespace App\Imports;

use App\Models\Nino;
use Illuminate\Support\Collection;
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
            $fechaNacimiento = $this->parseDate($row['fecha_nacimiento'] ?? null);
            if (!$fechaNacimiento) {
                $this->errors[] = "Fecha de nacimiento requerida. Fila: " . ($row['apellidos_nombres'] ?? 'N/A');
                return;
            }

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
                // Crear nuevo niño
                $nino = Nino::create($data);
                $this->stats['ninos']++;
                $this->success[] = "Niño creado: " . ($data['apellidos_nombres'] ?? 'N/A') . " (ID: {$nino->id_niño})";
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
        if (empty($value)) {
            return null;
        }

        if ($value instanceof \DateTime) {
            return Carbon::instance($value);
        }

        if (is_numeric($value)) {
            // Excel date serial number
            return Carbon::createFromFormat('Y-m-d', '1900-01-01')->addDays($value - 2);
        }

        try {
            return Carbon::parse($value);
        } catch (\Exception $e) {
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

