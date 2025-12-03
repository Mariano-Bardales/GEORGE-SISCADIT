<?php

namespace App\Imports;

use Illuminate\Support\Collection;

class ImportMultiHojasCSV
{
    protected $ninosImport;
    protected $extraImport;
    protected $madreImport;
    protected $controlesImport;
    protected $controlesCredImport;

    public function __construct()
    {
        $this->ninosImport = new NinosImport();
        $this->extraImport = new ExtraImport();
        $this->madreImport = new MadreImport();
        $this->controlesImport = new ControlesRnImport();
        $this->controlesCredImport = new ControlesMenor1Import();
    }

    /**
     * Procesar archivo CSV (formato simple, una columna por campo)
     */
    public function import($filePath, $sheetName = null)
    {
        try {
            // Verificar que el archivo exista
            if (!file_exists($filePath)) {
                throw new \Exception("El archivo no existe: {$filePath}");
            }

            // Leer archivo CSV
            $rows = $this->readCSV($filePath);
            
            if (empty($rows)) {
                throw new \Exception("El archivo CSV está vacío o no se pudo leer.");
            }

            // Si se especifica un nombre de hoja, procesar según ese nombre
            if ($sheetName) {
                $this->processSheetByName(strtolower(trim($sheetName)), $rows);
            } else {
                // Intentar detectar el tipo por los encabezados
                $detectedType = $this->detectSheetType($rows);
                if ($detectedType) {
                    $this->processSheetByName($detectedType, $rows);
                } else {
                    throw new \Exception("No se pudo detectar el tipo de datos. Por favor, especifique el nombre de la hoja.");
                }
            }
        } catch (\Exception $e) {
            throw new \Exception("Error al procesar archivo CSV: " . $e->getMessage());
        }
    }

    /**
     * Leer archivo CSV y convertirlo a array
     */
    protected function readCSV($filePath)
    {
        $rows = [];
        
        if (($handle = fopen($filePath, "r")) !== false) {
            // Leer encabezados (primera fila)
            $headers = [];
            if (($data = fgetcsv($handle, 1000, ",")) !== false) {
                foreach ($data as $header) {
                    $headers[] = $this->normalizeHeader(trim($header));
                }
            }

            // Leer datos (desde la segunda fila)
            while (($data = fgetcsv($handle, 1000, ",")) !== false) {
                $rowData = [];
                $isEmpty = true;

                for ($i = 0; $i < count($headers); $i++) {
                    $value = isset($data[$i]) ? trim($data[$i]) : null;
                    if ($value !== null && $value !== '') {
                        $isEmpty = false;
                    }
                    $rowData[$headers[$i]] = $value;
                }

                // Solo agregar filas no vacías
                if (!$isEmpty) {
                    $rows[] = $rowData;
                }
            }
            fclose($handle);
        }

        return $rows;
    }

    /**
     * Detectar el tipo de hoja basándose en los encabezados
     */
    protected function detectSheetType($rows)
    {
        if (empty($rows)) {
            return null;
        }

        $firstRow = $rows[0];
        $headers = array_keys($firstRow);
        $headersLower = array_map('strtolower', $headers);

        // Detectar tipo de hoja por encabezados característicos
        if (in_array('id_nino', $headersLower) || in_array('id_niño', $headersLower)) {
            if (in_array('apellidos_nombres', $headersLower) || in_array('nombre', $headersLower)) {
                if (in_array('fecha_nacimiento', $headersLower)) {
                    return 'ninos';
                }
            }
            if (in_array('red', $headersLower) || in_array('microred', $headersLower)) {
                return 'extra';
            }
            if (in_array('dni', $headersLower) && in_array('celular', $headersLower)) {
                return 'madre';
            }
            if (in_array('numero_control', $headersLower) && in_array('fecha', $headersLower)) {
                if (in_array('estado_cred_once', $headersLower) || in_array('estado_cred_final', $headersLower)) {
                    return 'controles_cred';
                }
                return 'controles';
            }
        }

        return null;
    }

    /**
     * Procesar hoja según su nombre
     */
    protected function processSheetByName($sheetNameLower, $rows)
    {
        switch ($sheetNameLower) {
            case 'niños':
            case 'ninos':
            case 'niño':
            case 'nino':
                $this->processSheet($this->ninosImport, $rows);
                break;

            case 'extra':
            case 'datos_extra':
            case 'datos extra':
                $this->processSheet($this->extraImport, $rows);
                break;

            case 'madre':
            case 'madres':
                $this->processSheet($this->madreImport, $rows);
                break;

            case 'controles':
            case 'control':
            case 'controles_rn':
            case 'controles rn':
                $this->processSheet($this->controlesImport, $rows);
                break;

            case 'controles_cred':
            case 'controles cred':
            case 'controles_menor1':
            case 'controles menor1':
            case 'cred':
                $this->processSheet($this->controlesCredImport, $rows);
                break;
        }
    }

    /**
     * Normalizar encabezados (convertir a formato snake_case y minúsculas)
     */
    protected function normalizeHeader($header)
    {
        if (empty($header)) {
            return '';
        }

        // Convertir a minúsculas y reemplazar espacios/guiones con guiones bajos
        $normalized = strtolower(trim($header));
        $normalized = preg_replace('/[^a-z0-9_]/', '_', $normalized);
        $normalized = preg_replace('/_+/', '_', $normalized);
        $normalized = trim($normalized, '_');

        return $normalized;
    }

    /**
     * Procesar una hoja usando un importador específico
     */
    protected function processSheet($importer, $rows)
    {
        // Crear una Collection con los datos
        $collection = new Collection($rows);

        // Llamar al método collection del importador
        if (method_exists($importer, 'collection')) {
            $importer->collection($collection);
        }
    }

    public function getNinosImportados()
    {
        $ninosIds = [];
        
        if (method_exists($this->ninosImport, 'getNinosImportados')) {
            $ninosIds = array_merge($ninosIds, $this->ninosImport->getNinosImportados());
        }
        
        return array_unique($ninosIds);
    }

    public function getErrors()
    {
        $allErrors = [];
        
        // Recopilar errores de todas las hojas
        if (method_exists($this->ninosImport, 'getErrors')) {
            $errors = $this->ninosImport->getErrors();
            foreach ($errors as $error) {
                $allErrors[] = "[niños] {$error}";
            }
        }
        
        if (method_exists($this->extraImport, 'getErrors')) {
            $errors = $this->extraImport->getErrors();
            foreach ($errors as $error) {
                $allErrors[] = "[extra] {$error}";
            }
        }
        
        if (method_exists($this->madreImport, 'getErrors')) {
            $errors = $this->madreImport->getErrors();
            foreach ($errors as $error) {
                $allErrors[] = "[madre] {$error}";
            }
        }
        
        if (method_exists($this->controlesImport, 'getErrors')) {
            $errors = $this->controlesImport->getErrors();
            foreach ($errors as $error) {
                $allErrors[] = "[controles] {$error}";
            }
        }
        
        if (method_exists($this->controlesCredImport, 'getErrors')) {
            $errors = $this->controlesCredImport->getErrors();
            foreach ($errors as $error) {
                $allErrors[] = "[controles_cred] {$error}";
            }
        }
        
        return $allErrors;
    }

    public function getSuccess()
    {
        $allSuccess = [];
        
        // Recopilar éxitos de todas las hojas
        if (method_exists($this->ninosImport, 'getSuccess')) {
            $success = $this->ninosImport->getSuccess();
            foreach ($success as $s) {
                $allSuccess[] = "[niños] {$s}";
            }
        }
        
        if (method_exists($this->extraImport, 'getSuccess')) {
            $success = $this->extraImport->getSuccess();
            foreach ($success as $s) {
                $allSuccess[] = "[extra] {$s}";
            }
        }
        
        if (method_exists($this->madreImport, 'getSuccess')) {
            $success = $this->madreImport->getSuccess();
            foreach ($success as $s) {
                $allSuccess[] = "[madre] {$s}";
            }
        }
        
        if (method_exists($this->controlesImport, 'getSuccess')) {
            $success = $this->controlesImport->getSuccess();
            foreach ($success as $s) {
                $allSuccess[] = "[controles] {$s}";
            }
        }
        
        if (method_exists($this->controlesCredImport, 'getSuccess')) {
            $success = $this->controlesCredImport->getSuccess();
            foreach ($success as $s) {
                $allSuccess[] = "[controles_cred] {$s}";
            }
        }
        
        return $allSuccess;
    }

    public function getStats()
    {
        $allStats = [
            'ninos' => 0,
            'actualizados_ninos' => 0,
            'datos_extra' => 0,
            'actualizados_extra' => 0,
            'madres' => 0,
            'actualizados_madres' => 0,
            'controles_rn' => 0,
            'actualizados_controles' => 0,
            'controles_cred' => 0,
            'actualizados_controles_cred' => 0,
        ];
        
        // Recopilar estadísticas de todas las hojas
        if (method_exists($this->ninosImport, 'getStats')) {
            $stats = $this->ninosImport->getStats();
            $allStats['ninos'] += $stats['ninos'] ?? 0;
            $allStats['actualizados_ninos'] += $stats['actualizados'] ?? 0;
        }
        
        if (method_exists($this->extraImport, 'getStats')) {
            $stats = $this->extraImport->getStats();
            $allStats['datos_extra'] += $stats['datos_extra'] ?? 0;
            $allStats['actualizados_extra'] += $stats['actualizados'] ?? 0;
        }
        
        if (method_exists($this->madreImport, 'getStats')) {
            $stats = $this->madreImport->getStats();
            $allStats['madres'] += $stats['madres'] ?? 0;
            $allStats['actualizados_madres'] += $stats['actualizados'] ?? 0;
        }
        
        if (method_exists($this->controlesImport, 'getStats')) {
            $stats = $this->controlesImport->getStats();
            $allStats['controles_rn'] += $stats['controles_rn'] ?? 0;
            $allStats['actualizados_controles'] += $stats['actualizados'] ?? 0;
        }
        
        if (method_exists($this->controlesCredImport, 'getStats')) {
            $stats = $this->controlesCredImport->getStats();
            $allStats['controles_cred'] += $stats['controles_cred'] ?? 0;
            $allStats['actualizados_controles_cred'] += $stats['actualizados'] ?? 0;
        }
        
        return $allStats;
    }

    /**
     * Recopilar todas las alertas de controles fuera de rango
     */
    public function getAlertas()
    {
        $allAlertas = [];
        
        // Recopilar alertas de controles RN
        if (method_exists($this->controlesImport, 'getAlertas')) {
            $alertas = $this->controlesImport->getAlertas();
            $allAlertas = array_merge($allAlertas, $alertas);
        }
        
        // Recopilar alertas de controles CRED
        if (method_exists($this->controlesCredImport, 'getAlertas')) {
            $alertas = $this->controlesCredImport->getAlertas();
            $allAlertas = array_merge($allAlertas, $alertas);
        }
        
        return $allAlertas;
    }
}



