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
        
        $ninoId = $nino->id_niño;

        // Preparar datos - aceptar 'peso', 'edad_gestacional', 'clasificacion'
        // Nota: 'peso' ahora es SMALLINT (entero) para almacenar gramos (valores de 500 a 5000+)
        $data = [
            'id_niño' => $ninoId,
            'peso' => !empty($row['peso']) ? (int)$row['peso'] : null, // Convertir a entero (gramos)
            'edad_gestacional' => !empty($row['edad_gestacional']) ? (int)$row['edad_gestacional'] : null,
            'clasificacion' => $row['clasificacion'] ?? null,
        ];

        // Verificar si hay ID personalizado del Excel
        $idRnPersonalizado = $row['id_rn'] ?? null;
        
        $existe = RecienNacido::where('id_niño', $ninoId)->first();
        
        if ($existe) {
            RecienNacido::where('id_niño', $ninoId)->update($data);
            $this->stats['actualizados']++;
            $this->success[] = "Recién nacido actualizado para niño ID: {$ninoId}";
        } else {
            // Si hay ID personalizado y no existe, crear con ese ID
            if ($idRnPersonalizado && is_numeric($idRnPersonalizado)) {
                $existeConId = RecienNacido::where('id_rn', $idRnPersonalizado)->exists();
                if (!$existeConId) {
                    $data['id_rn'] = (int)$idRnPersonalizado;
                    \Illuminate\Support\Facades\DB::table('recien_nacido')->insert($data);
                    $this->stats['recien_nacido']++;
                    $this->success[] = "Recién nacido creado con ID personalizado (ID: {$idRnPersonalizado}) para niño ID: {$ninoId}";
                } else {
                    RecienNacido::create($data);
                    $this->stats['recien_nacido']++;
                    $this->success[] = "Recién nacido creado para niño ID: {$ninoId}";
                }
            } else {
                RecienNacido::create($data);
                $this->stats['recien_nacido']++;
                $this->success[] = "Recién nacido creado para niño ID: {$ninoId}";
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

