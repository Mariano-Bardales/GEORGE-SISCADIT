<?php

namespace App\Imports;

use App\Models\DatosExtra;
use App\Models\Nino;
use App\Imports\Traits\BuscaNinoTrait;
use Illuminate\Support\Collection;

class ExtraImport
{
    use BuscaNinoTrait;
    
    protected $errors = [];
    protected $success = [];
    protected $stats = ['datos_extra' => 0, 'actualizados' => 0];

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            try {
                $this->importDatosExtra($row);
            } catch (\Exception $e) {
                $this->errors[] = "Error en fila: " . $e->getMessage();
            }
        }
    }

    protected function importDatosExtra($row)
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

        $existe = DatosExtra::where('id_niño', $ninoId)->exists();

        $data = [
            'id_niño' => $ninoId,
            'red' => $row['red'] ?? null,
            'microred' => $row['microred'] ?? null,
            'eess_nacimiento' => $row['eess_nacimiento'] ?? null,
            'distrito' => $row['distrito'] ?? null,
            'provincia' => $row['provincia'] ?? null,
            'departamento' => $row['departamento'] ?? null,
            'seguro' => $row['seguro'] ?? null,
            'programa' => $row['programa'] ?? null,
        ];

        if ($existe) {
            DatosExtra::where('id_niño', $ninoId)->update($data);
            $this->stats['actualizados']++;
            $this->success[] = "Datos extra actualizados para niño ID: {$ninoId}";
        } else {
            DatosExtra::create($data);
            $this->stats['datos_extra']++;
            $this->success[] = "Datos extra creados para niño ID: {$ninoId}";
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

