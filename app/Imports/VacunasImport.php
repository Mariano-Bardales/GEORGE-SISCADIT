<?php

namespace App\Imports;

use App\Models\VacunaRn;
use App\Models\Nino;
use App\Imports\Traits\BuscaNinoTrait;
use Illuminate\Support\Collection;
use Carbon\Carbon;

class VacunasImport
{
    use BuscaNinoTrait;
    
    protected $errors = [];
    protected $success = [];
    protected $stats = ['vacunas' => 0, 'actualizados' => 0];

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            try {
                $this->importVacuna($row);
            } catch (\Exception $e) {
                $this->errors[] = "Error en fila: " . $e->getMessage();
            }
        }
    }

    protected function importVacuna($row)
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
        
        $fechaBCG = $this->parseDate($row['fecha_bcg'] ?? null);
        $fechaHVB = $this->parseDate($row['fecha_hvb'] ?? null);
        
        if (!$fechaBCG || !$fechaHVB) {
            $this->errors[] = "Fechas de vacunas BCG y HVB requeridas para niño ID: {$ninoId}";
            return;
        }
        
        // Obtener numero_control si está presente
        $numeroControl = $row['numero_control'] ?? null;

        // Solo guardar los campos que existen en la tabla
        $data = [
            'id_niño' => $ninoId,
            'numero_control' => $numeroControl,
            'fecha_bcg' => $fechaBCG->format('Y-m-d'),
            'fecha_hvb' => $fechaHVB->format('Y-m-d'),
        ];

        // Verificar si hay ID personalizado del Excel
        $idVacunaPersonalizado = $row['id_vacuna'] ?? null;
        
        $existe = VacunaRn::where('id_niño', $ninoId)->first();
        
        if ($existe) {
            VacunaRn::where('id_niño', $ninoId)->update($data);
            $this->stats['actualizados']++;
            $this->success[] = "Vacunas actualizadas para niño ID: {$ninoId}";
        } else {
            // Si hay ID personalizado y no existe, crear con ese ID
            if ($idVacunaPersonalizado && is_numeric($idVacunaPersonalizado)) {
                $existeConId = VacunaRn::where('id_vacuna', $idVacunaPersonalizado)->exists();
                if (!$existeConId) {
                    $data['id_vacuna'] = (int)$idVacunaPersonalizado;
                    \Illuminate\Support\Facades\DB::table('vacuna_rn')->insert($data);
                    $this->stats['vacunas']++;
                    $this->success[] = "Vacunas creadas con ID personalizado (ID: {$idVacunaPersonalizado}) para niño ID: {$ninoId}";
                } else {
                    VacunaRn::create($data);
                    $this->stats['vacunas']++;
                    $this->success[] = "Vacunas creadas para niño ID: {$ninoId}";
                }
            } else {
                VacunaRn::create($data);
                $this->stats['vacunas']++;
                $this->success[] = "Vacunas creadas para niño ID: {$ninoId}";
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

