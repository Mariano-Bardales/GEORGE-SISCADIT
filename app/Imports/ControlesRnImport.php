<?php

namespace App\Imports;

use App\Models\ControlRn;
use App\Models\Nino;
use App\Imports\Traits\BuscaNinoTrait;
use Illuminate\Support\Collection;
use Carbon\Carbon;

class ControlesRnImport
{
    use BuscaNinoTrait;
    
    protected $errors = [];
    protected $success = [];
    protected $stats = ['controles_rn' => 0, 'actualizados' => 0];

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            try {
                $this->importControlRN($row);
            } catch (\Exception $e) {
                $this->errors[] = "Error en fila: " . $e->getMessage();
            }
        }
    }

    protected function importControlRN($row)
    {
        // Buscar el niño por id_niño (llave primaria) o por documento
        $nino = $this->buscarNino($row);
        
        if (!$nino) {
            $idNino = $row['id_nino'] ?? $row['id_niño'] ?? 'N/A';
            $numeroDoc = $row['numero_doc'] ?? 'N/A';
            $this->errors[] = "No se encontró niño con ID: {$idNino} o documento: {$numeroDoc}. Asegúrate de que el niño exista en la hoja 'Niños'.";
            return;
        }
        
        $ninoId = $nino->id_niño;

        $numeroControl = (int)($row['numero_control'] ?? 0);
        if ($numeroControl < 1 || $numeroControl > 4) {
            $this->errors[] = "Número de control RN inválido: {$numeroControl} (debe ser 1-4)";
            return;
        }

        // Verificar si ya existe
        $existe = ControlRn::where('id_niño', $ninoId)
                          ->where('numero_control', $numeroControl)
                          ->exists();

        $fecha = $this->parseDate($row['fecha'] ?? null);
        if (!$fecha) {
            $this->errors[] = "Fecha requerida para control RN {$numeroControl} del niño ID: {$ninoId}";
            return;
        }
        
        // Calcular edad
        $edad = $this->calculateAge($ninoId, $fecha);
        
        // Determinar estado automáticamente basándose en rangos
        $rangosRN = [
            1 => ['min' => 2, 'max' => 6],
            2 => ['min' => 7, 'max' => 13],
            3 => ['min' => 14, 'max' => 20],
            4 => ['min' => 21, 'max' => 28],
        ];
        
        $rango = $rangosRN[$numeroControl] ?? ['min' => 0, 'max' => 28];
        $estado = 'SEGUIMIENTO'; // Por defecto
        
        if ($edad !== null) {
            if ($edad >= $rango['min'] && $edad <= $rango['max']) {
                $estado = 'CUMPLE';
            } elseif ($edad > $rango['max']) {
                $estado = 'NO CUMPLE';
            }
        }

        $data = [
            'id_niño' => $ninoId,
            'numero_control' => $numeroControl,
            'fecha' => $fecha->format('Y-m-d'),
            'edad' => $edad,
            'estado' => $estado,
            'peso' => !empty($row['peso']) ? (float)$row['peso'] : null,
            'talla' => !empty($row['talla']) ? (float)$row['talla'] : null,
            'perimetro_cefalico' => !empty($row['perimetro_cefalico']) || !empty($row['pc']) ? (float)($row['perimetro_cefalico'] ?? $row['pc']) : null,
        ];

        if ($existe) {
            ControlRn::where('id_niño', $ninoId)
                     ->where('numero_control', $numeroControl)
                     ->update($data);
            $this->stats['actualizados']++;
        } else {
            ControlRn::create($data);
            $this->stats['controles_rn']++;
        }

        $this->success[] = "Control RN {$numeroControl} importado para niño ID: {$ninoId}";
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

    protected function calculateAge($ninoId, $fechaControl)
    {
        if (!$fechaControl) {
            return null;
        }

        $nino = Nino::where('id_niño', $ninoId)->first();
        if (!$nino || !$nino->fecha_nacimiento) {
            return null;
        }

        $fechaNacimiento = Carbon::parse($nino->fecha_nacimiento);
        return $fechaNacimiento->diffInDays($fechaControl);
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

