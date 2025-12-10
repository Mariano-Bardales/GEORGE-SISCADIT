<?php

namespace App\Imports\Traits;

use App\Models\Nino;

trait BuscaNinoTrait
{
    /**
     * Buscar niño por id_niño (llave primaria) o por documento
     * 
     * @param array $row Fila de datos del Excel
     * @return Nino|null El niño encontrado o null
     */
    protected function buscarNino($row)
    {
        // Prioridad 1: Buscar por id_niño (llave primaria)
        // Buscar en múltiples variaciones del nombre de columna (normalizado y original)
        $idNino = null;
        if (!empty($row['id_nino'])) {
            $idNino = $row['id_nino'];
        } elseif (!empty($row['id_niño'])) {
            $idNino = $row['id_niño'];
        } elseif (!empty($row['id_ni_o'])) {
            // Variación normalizada (sin la ñ)
            $idNino = $row['id_ni_o'];
        }
        
        if ($idNino) {
            $nino = Nino::where('id', $idNino)->first();
            if ($nino) {
                return $nino;
            }
        }
        
        // Prioridad 2: Buscar por número de documento + tipo de documento
        if (!empty($row['numero_doc']) && !empty($row['tipo_doc'])) {
            // Normalizar tipo de documento
            $tipoDocMap = [
                'DNI' => 'DNI', 'dni' => 'DNI', '1' => 'DNI',
                'CE' => 'CE', 'ce' => 'CE', '2' => 'CE',
                'PASS' => 'PASS', 'pass' => 'PASS', '3' => 'PASS',
                'DIE' => 'DIE', 'die' => 'DIE', '4' => 'DIE',
                'S/ DOCUMENTO' => 'S/ DOCUMENTO', 'sin documento' => 'S/ DOCUMENTO', '5' => 'S/ DOCUMENTO',
                'CNV' => 'CNV', 'cnv' => 'CNV', '6' => 'CNV',
            ];
            $tipoDocInput = trim($row['tipo_doc'] ?? '');
            $tipoDoc = $tipoDocMap[$tipoDocInput] ?? ($tipoDocInput ?: 'S/ DOCUMENTO');
            
            $nino = Nino::where('numero_doc', trim($row['numero_doc']))
                       ->where('tipo_doc', $tipoDoc)
                       ->first();
            if ($nino) {
                return $nino;
            }
        }
        
        // Prioridad 3: Buscar solo por número de documento (si no hay tipo)
        if (!empty($row['numero_doc'])) {
            $nino = Nino::where('numero_doc', trim($row['numero_doc']))->first();
            if ($nino) {
                return $nino;
            }
        }
        
        return null;
    }
    
    /**
     * Obtener el id_niño de un niño, buscándolo si es necesario
     * 
     * @param array $row Fila de datos del Excel
     * @return int|null El id_niño o null si no se encuentra
     */
    protected function obtenerIdNino($row)
    {
        $nino = $this->buscarNino($row);
        return $nino ? $nino->id : null;
    }
}




