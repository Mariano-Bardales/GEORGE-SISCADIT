<?php

namespace App\Imports;

use App\Models\RecienNacido;
use App\Models\Nino;
use App\Imports\Traits\BuscaNinoTrait;
use Illuminate\Support\Collection;

class RecienNacidoImport
{
    use BuscaNinoTrait;
    
    protected $errors = [];
    protected $success = [];
    protected $stats = ['recien_nacido' => 0, 'actualizados' => 0];

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            try {
                $this->importRecienNacido($row);
            } catch (\Exception $e) {
                $this->errors[] = "Error en fila: " . $e->getMessage();
            }
        }
    }

    protected function importRecienNacido($row)
    {
        // Buscar el niño por id_niño (llave primaria) o por documento
        $nino = $this->buscarNino($row);
        
        if (!$nino) {
            $idNino = $row['id_nino'] ?? $row['id_niño'] ?? 'N/A';
            $numeroDoc = $row['numero_doc'] ?? 'N/A';
            $this->errors[] = "No se encontró niño con ID: {$idNino} o documento: {$numeroDoc}. Asegúrate de que el niño exista en la hoja 'Niños'.";
            return;
        }
        
        $ninoId = $nino->id;

        // Preparar datos - aceptar 'peso', 'peso_nacer', 'peso_rn', 'edad_gestacional', 'clasificacion'
        // Convertir peso de gramos a kg si viene en gramos (valores > 10 probablemente son gramos)
        $peso = $row['peso_nacer'] ?? $row['peso_rn'] ?? $row['peso'] ?? null;
        if ($peso && is_numeric($peso)) {
            $pesoFloat = (float)$peso;
            // Si el valor es > 10, probablemente está en gramos, convertir a kg
            if ($pesoFloat > 10) {
                $peso = $pesoFloat / 1000; // Convertir gramos a kg
            } else {
                $peso = $pesoFloat; // Ya está en kg
            }
        } else {
            $peso = null;
        }
        
        $data = [
            'id_niño' => $ninoId,
            'peso' => $peso, // Decimal (kg)
            'edad_gestacional' => !empty($row['edad_gestacional']) ? (int)$row['edad_gestacional'] : null,
            'clasificacion' => $row['clasificacion'] ?? null,
        ];
        
        // Validar que todos los campos requeridos estén presentes
        $camposFaltantes = [];
        if (empty($peso)) $camposFaltantes[] = 'Peso al Nacer';
        if (empty($data['edad_gestacional'])) $camposFaltantes[] = 'Edad Gestacional';
        if (empty($data['clasificacion'])) $camposFaltantes[] = 'Clasificación';
        
        if (!empty($camposFaltantes)) {
            $this->errors[] = "CNV incompleto para niño ID: {$ninoId}. Faltan: " . implode(', ', $camposFaltantes);
            return;
        }
        
        // Validar clasificación
        $clasificacionesValidas = ['Normal', 'Bajo Peso al Nacer y/o Prematuro'];
        if (!in_array($data['clasificacion'], $clasificacionesValidas)) {
            $this->errors[] = "Clasificación inválida para niño ID: {$ninoId}. Debe ser 'Normal' o 'Bajo Peso al Nacer y/o Prematuro'. Valor recibido: " . ($data['clasificacion'] ?? 'NULL');
            return;
        }

        $existe = RecienNacido::where('id_niño', $ninoId)->first();
        
        if ($existe) {
            // Actualizar registro existente
            $existe->update($data);
            $this->stats['actualizados']++;
            $this->success[] = "Recién nacido actualizado para niño ID: {$ninoId}";
        } else {
            // Crear nuevo registro
            try {
                RecienNacido::create($data);
                $this->stats['recien_nacido']++;
                $this->success[] = "Recién nacido creado para niño ID: {$ninoId}";
            } catch (\Exception $e) {
                $this->errors[] = "Error al crear recién nacido para niño ID: {$ninoId}. Error: " . $e->getMessage();
                \Log::error('Error al crear RecienNacido', [
                    'nino_id' => $ninoId,
                    'data' => $data,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
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
}

