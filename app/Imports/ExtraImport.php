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
        // Log para depuración
        \Log::info('Importando Datos Extra', [
            'columnas_disponibles' => array_keys($row),
            'id_niño_en_fila' => $row['id_niño'] ?? $row['id_nino'] ?? $row['id_ni_o'] ?? 'NO ENCONTRADO',
            'datos_fila' => array_slice($row, 0, 10, true)
        ]);
        
        // Buscar el niño por id_niño (llave primaria) o por documento
        $nino = $this->buscarNino($row);
        
        if (!$nino) {
            $idNino = $row['id_nino'] ?? $row['id_niño'] ?? $row['id_ni_o'] ?? 'N/A';
            $numeroDoc = $row['numero_doc'] ?? 'N/A';
            $this->errors[] = "No se encontró niño con ID: {$idNino} o documento: {$numeroDoc}. Asegúrate de que el niño exista en la hoja 'Niños'.";
            \Log::warning('No se encontró niño para Datos Extra', [
                'id_niño_buscado' => $idNino,
                'numero_doc' => $numeroDoc,
                'columnas_disponibles' => array_keys($row)
            ]);
            return;
        }
        
        $ninoId = $nino->id;

        $existe = DatosExtra::where('id_niño', $ninoId)->exists();

        // Aceptar variaciones de nombres de columnas
        $data = [
            'id_niño' => $ninoId,
            'red' => $row['red'] ?? null,
            'microred' => $row['microred'] ?? null,
            'eess_nacimiento' => $row['eess_nacimiento'] ?? $row['eess_nacimiento'] ?? null,
            'distrito' => $row['distrito'] ?? null,
            'provincia' => $row['provincia'] ?? null,
            'departamento' => $row['departamento'] ?? null,
            'seguro' => $row['seguro'] ?? null,
            'programa' => $row['programa'] ?? null,
        ];
        
        \Log::info('Datos Extra preparados', [
            'nino_id' => $ninoId,
            'data' => $data,
            'existe' => $existe
        ]);

        // Verificar si hay ID personalizado del Excel
        $idExtraPersonalizado = $row['id_extra'] ?? null;
        
        if ($existe) {
            DatosExtra::where('id_niño', $ninoId)->update($data);
            $this->stats['actualizados']++;
            $this->success[] = "Datos extra actualizados para niño ID: {$ninoId}";
        } else {
            // Si hay ID personalizado y no existe, crear con ese ID
            if ($idExtraPersonalizado && is_numeric($idExtraPersonalizado)) {
                $existeConId = DatosExtra::where('id_extra', $idExtraPersonalizado)->exists();
                if (!$existeConId) {
                    // Insertar con ID personalizado usando DB directo
                    $data['id_extra'] = (int)$idExtraPersonalizado;
                    \Illuminate\Support\Facades\DB::table('datos_extra')->insert($data);
                    $this->stats['datos_extra']++;
                    $this->success[] = "Datos extra creados con ID personalizado (ID: {$idExtraPersonalizado}) para niño ID: {$ninoId}";
                } else {
                    // ID ya existe, crear sin ID
                    DatosExtra::create($data);
                    $this->stats['datos_extra']++;
                    $this->success[] = "Datos extra creados para niño ID: {$ninoId}";
                }
            } else {
                // Crear sin ID personalizado
                DatosExtra::create($data);
                $this->stats['datos_extra']++;
                $this->success[] = "Datos extra creados para niño ID: {$ninoId}";
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

