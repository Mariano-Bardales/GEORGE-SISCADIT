<?php

namespace App\Imports;

use App\Models\VisitaDomiciliaria;
use App\Models\Nino;
use App\Imports\Traits\BuscaNinoTrait;
use Illuminate\Support\Collection;
use Carbon\Carbon;

class VisitasImport
{
    use BuscaNinoTrait;
    
    protected $errors = [];
    protected $success = [];
    protected $stats = ['visitas' => 0, 'actualizados' => 0];

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            try {
                $this->importVisita($row);
            } catch (\Exception $e) {
                $this->errors[] = "Error en fila: " . $e->getMessage();
            }
        }
    }

    protected function importVisita($row)
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

        // Aceptar 'control_de_visita', 'numero_control', 'nro_control', o valores como 'A', 'B', 'C', 'D'
        $controlDeVisita = null;
        
        // Mapear valores de texto a números
        $controlMap = [
            'A' => 1, '28D' => 1, '28 DÍAS' => 1, '28 DIAS' => 1, '28 días' => 1,
            'B' => 2, '2-5M' => 2, '2-5 MESES' => 2, '2-5 MES' => 2, '2-5 meses' => 2,
            'C' => 3, '6-8M' => 3, '6-8 MESES' => 3, '6-8 MES' => 3, '6-8 meses' => 3,
            'D' => 4, '9-11M' => 4, '9-11 MESES' => 4, '9-11 MES' => 4, '9-11 meses' => 4,
        ];
        
        $controlInput = $row['control_de_visita'] ?? $row['numero_control'] ?? $row['nro_control'] ?? null;
        
        if ($controlInput) {
            if (is_numeric($controlInput)) {
                $controlDeVisita = (int)$controlInput;
            } else {
                $controlDeVisita = $controlMap[strtoupper(trim($controlInput))] ?? null;
            }
        }
        
        if (!$controlDeVisita || $controlDeVisita < 1 || $controlDeVisita > 4) {
            $this->errors[] = "Control de visita inválido: {$controlInput} (debe ser 1-4 o A-D) para niño ID: {$ninoId}";
            return;
        }
        
        // Aceptar 'fecha_visita' o 'fecha'
        $fechaVisita = $this->parseDate($row['fecha_visita'] ?? $row['fecha'] ?? null);
        if (!$fechaVisita) {
            $this->errors[] = "Fecha de visita requerida para niño ID: {$ninoId}";
            return;
        }

        // Preparar datos - la tabla tiene control_de_visita (1-4)
        $data = [
            'id_niño' => $ninoId,
            'control_de_visita' => $controlDeVisita,
            'fecha_visita' => $fechaVisita->format('Y-m-d'),
        ];

        // Verificar si hay ID personalizado del Excel
        $idVisitaPersonalizado = $row['id_visita'] ?? null;
        
        $existe = null;
        
        // Buscar visita existente por ID personalizado o por id_niño + numero_visitas
        if ($idVisitaPersonalizado && is_numeric($idVisitaPersonalizado)) {
            $existe = VisitaDomiciliaria::find($idVisitaPersonalizado);
        }
        
        if (!$existe && $controlDeVisita) {
            $existe = VisitaDomiciliaria::where('id_niño', $ninoId)
                                       ->where('control_de_visita', $controlDeVisita)
                                       ->first();
        }
        
        if ($existe) {
            $existe->update($data);
            $this->stats['actualizados']++;
            $this->success[] = "Visita actualizada para niño ID: {$ninoId}";
        } else {
            // Si hay ID personalizado y no existe, crear con ese ID
            if ($idVisitaPersonalizado && is_numeric($idVisitaPersonalizado)) {
                $existeConId = VisitaDomiciliaria::where('id_visita', $idVisitaPersonalizado)->exists();
                if (!$existeConId) {
                    $data['id_visita'] = (int)$idVisitaPersonalizado;
                    \Illuminate\Support\Facades\DB::table('visita_domiciliarias')->insert($data);
                    $this->stats['visitas']++;
                    $this->success[] = "Visita creada con ID personalizado (ID: {$idVisitaPersonalizado}) para niño ID: {$ninoId}";
                } else {
                    VisitaDomiciliaria::create($data);
                    $this->stats['visitas']++;
                    $this->success[] = "Visita creada para niño ID: {$ninoId}";
                }
            } else {
                VisitaDomiciliaria::create($data);
                $this->stats['visitas']++;
                $this->success[] = "Visita creada para niño ID: {$ninoId}";
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

