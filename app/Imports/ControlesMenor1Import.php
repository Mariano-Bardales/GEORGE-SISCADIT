<?php

namespace App\Imports;

use App\Models\ControlMenor1;
use App\Models\Nino;
use App\Imports\Traits\BuscaNinoTrait;
use Illuminate\Support\Collection;
use Carbon\Carbon;

class ControlesMenor1Import
{
    use BuscaNinoTrait;
    
    protected $errors = [];
    protected $success = [];
    protected $stats = ['controles_cred' => 0, 'actualizados' => 0];

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            try {
                $this->importControlCred($row);
            } catch (\Exception $e) {
                $this->errors[] = "Error en fila: " . $e->getMessage();
            }
        }
    }

    protected function importControlCred($row)
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
        if ($numeroControl < 1 || $numeroControl > 11) {
            $this->errors[] = "Número de control CRED inválido: {$numeroControl} (debe ser 1-11)";
            return;
        }

        // Verificar si ya existe
        $existe = ControlMenor1::where('id_niño', $ninoId)
                               ->where('numero_control', $numeroControl)
                               ->exists();

        $fecha = $this->parseDate($row['fecha'] ?? null);
        if (!$fecha) {
            $this->errors[] = "Fecha requerida para control CRED {$numeroControl} del niño ID: {$ninoId}";
            return;
        }
        
        // Calcular edad
        $edad = $this->calculateAge($ninoId, $fecha);
        
        // Determinar estado automáticamente basándose en rangos CRED
        $rangosCRED = [
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
        
        $rango = $rangosCRED[$numeroControl] ?? ['min' => 0, 'max' => 365];
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
            'estado_cred_once' => $row['estado_cred_once'] ?? null,
            'estado_cred_final' => $row['estado_cred_final'] ?? null,
            'peso' => !empty($row['peso']) ? (float)$row['peso'] : null,
            'talla' => !empty($row['talla']) ? (float)$row['talla'] : null,
            'perimetro_cefalico' => !empty($row['perimetro_cefalico']) || !empty($row['pc']) ? (float)($row['perimetro_cefalico'] ?? $row['pc']) : null,
        ];

        try {
            if ($existe) {
                $actualizado = ControlMenor1::where('id_niño', $ninoId)
                             ->where('numero_control', $numeroControl)
                             ->update($data);
                
                if ($actualizado) {
                    $this->stats['actualizados']++;
                    $this->success[] = "Control CRED {$numeroControl} actualizado para niño ID: {$ninoId}";
                } else {
                    $this->errors[] = "No se pudo actualizar el control CRED {$numeroControl} para niño ID: {$ninoId}";
                }
            } else {
                $control = ControlMenor1::create($data);
                
                if ($control && $control->id_cred) {
                    $this->stats['controles_cred']++;
                    $this->success[] = "Control CRED {$numeroControl} creado para niño ID: {$ninoId} (ID en BD: {$control->id_cred})";
                } else {
                    $this->errors[] = "No se pudo crear el control CRED {$numeroControl} para niño ID: {$ninoId}";
                }
            }
        } catch (\Exception $e) {
            $this->errors[] = "Error al guardar control CRED {$numeroControl} para niño ID: {$ninoId}: " . $e->getMessage();
            throw $e; // Re-lanzar para que la transacción haga rollback
        }
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

