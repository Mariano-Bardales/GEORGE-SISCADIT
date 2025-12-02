<?php

namespace App\Imports;

use App\Models\Madre;
use App\Models\Nino;
use App\Imports\Traits\BuscaNinoTrait;
use Illuminate\Support\Collection;

class MadreImport
{
    use BuscaNinoTrait;
    
    protected $errors = [];
    protected $success = [];
    protected $stats = ['madres' => 0, 'actualizados' => 0];

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            try {
                $this->importMadre($row);
            } catch (\Exception $e) {
                $this->errors[] = "Error en fila: " . $e->getMessage();
            }
        }
    }

    protected function importMadre($row)
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

        // Buscar madre existente por DNI o por id_niño
        $madre = null;
        if (!empty($row['dni'])) {
            $madre = Madre::where('dni', $row['dni'])->first();
        }
        
        if (!$madre) {
            $madre = Madre::where('id_niño', $ninoId)->first();
        }

        $data = [
            'id_niño' => $ninoId,
            'dni' => $row['dni'] ?? null,
            'apellidos_nombres' => $row['apellidos_nombres'] ?? 'Sin especificar',
            'celular' => $row['celular'] ?? null,
            'domicilio' => $row['domicilio'] ?? null,
            'referencia_direccion' => $row['referencia_direccion'] ?? null,
        ];

        if ($madre) {
            // Actualizar madre existente
            $madre->update($data);
            $this->stats['actualizados']++;
            $this->success[] = "Madre actualizada para niño ID: {$ninoId}";
        } else {
            // Crear nueva madre
            Madre::create($data);
            $this->stats['madres']++;
            $this->success[] = "Madre creada para niño ID: {$ninoId}";
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

