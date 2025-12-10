<?php

namespace App\Imports;

use App\Models\TamizajeNeonatal;
use App\Models\Nino;
use App\Imports\Traits\BuscaNinoTrait;
use Illuminate\Support\Collection;
use Carbon\Carbon;

class TamizajeImport
{
    use BuscaNinoTrait;
    
    protected $errors = [];
    protected $success = [];
    protected $stats = ['tamizajes' => 0, 'actualizados' => 0];

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            try {
                $this->importTamizaje($row);
            } catch (\Exception $e) {
                $this->errors[] = "Error en fila: " . $e->getMessage();
            }
        }
    }

    protected function importTamizaje($row)
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

        // Aceptar 'fecha_tam_neo' o 'fecha_tamizaje'
        $fechaTamNeo = $this->parseDate($row['fecha_tam_neo'] ?? $row['fecha_tamizaje'] ?? null);
        if (!$fechaTamNeo) {
            $this->errors[] = "Fecha de tamizaje neonatal requerida para niño ID: {$ninoId}";
            return;
        }

        // Validar que el niño existe (ya se validó en buscarNino)

        // Aceptar 'galen_fecha_tam_feo' o 'galen_fecha'
        $galenFecha = $this->parseDate($row['galen_fecha_tam_feo'] ?? $row['galen_fecha'] ?? null);
        
        // Obtener numero_control si está presente
        $numeroControl = $row['numero_control'] ?? null;

        // Solo guardar los campos que existen en la tabla
        $data = [
            'id_niño' => $ninoId,
            'numero_control' => $numeroControl,
            'fecha_tam_neo' => $fechaTamNeo->format('Y-m-d'),
            'galen_fecha_tam_feo' => $galenFecha ? $galenFecha->format('Y-m-d') : null,
        ];

        // Verificar si hay ID personalizado del Excel
        $idTamizajePersonalizado = $row['id_tamizaje'] ?? null;
        
        $existe = TamizajeNeonatal::where('id_niño', $ninoId)->first();
        
        if ($existe) {
            TamizajeNeonatal::where('id_niño', $ninoId)->update($data);
            $this->stats['actualizados']++;
            $this->success[] = "Tamizaje actualizado para niño ID: {$ninoId}";
        } else {
            // Si hay ID personalizado y no existe, crear con ese ID
            if ($idTamizajePersonalizado && is_numeric($idTamizajePersonalizado)) {
                $existeConId = TamizajeNeonatal::where('id_tamizaje', $idTamizajePersonalizado)->exists();
                if (!$existeConId) {
                    $data['id_tamizaje'] = (int)$idTamizajePersonalizado;
                    \Illuminate\Support\Facades\DB::table('tamizaje_neonatals')->insert($data);
                    $this->stats['tamizajes']++;
                    $this->success[] = "Tamizaje creado con ID personalizado (ID: {$idTamizajePersonalizado}) para niño ID: {$ninoId}";
                } else {
                    TamizajeNeonatal::create($data);
                    $this->stats['tamizajes']++;
                    $this->success[] = "Tamizaje creado para niño ID: {$ninoId}";
                }
            } else {
                TamizajeNeonatal::create($data);
                $this->stats['tamizajes']++;
                $this->success[] = "Tamizaje creado para niño ID: {$ninoId}";
            }
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
}

