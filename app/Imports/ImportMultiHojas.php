<?php

namespace App\Imports;

use Illuminate\Support\Collection;

class ImportMultiHojas
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
     * Procesar archivo Excel con múltiples hojas usando PhpSpreadsheet (compatible con PHP 8)
     */
    public function import($filePath)
    {
        try {
            // Verificar que el archivo exista
            if (!file_exists($filePath)) {
                throw new \Exception("El archivo no existe: {$filePath}");
            }
            
            // Intentar usar PhpSpreadsheet primero (compatible con PHP 8)
            if (class_exists('\PhpOffice\PhpSpreadsheet\IOFactory')) {
                return $this->importWithPhpSpreadsheet($filePath);
            }
            
            // Fallback a PHPExcel si PhpSpreadsheet no está disponible
            if (class_exists('\PHPExcel_IOFactory')) {
                return $this->importWithPHPExcel($filePath);
            }
            
            throw new \Exception("No se encontró ninguna biblioteca de Excel disponible. Por favor, instale PhpSpreadsheet: composer require phpoffice/phpspreadsheet");
            
        } catch (\Exception $e) {
            throw new \Exception("Error al procesar archivo Excel: " . $e->getMessage());
        }
    }
    
    /**
     * Importar usando PhpSpreadsheet (compatible con PHP 8)
     */
    protected function importWithPhpSpreadsheet($filePath)
    {
        try {
            // Cargar archivo Excel con PhpSpreadsheet
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($filePath);
            
            // Obtener todas las hojas
            $sheetNames = $spreadsheet->getSheetNames();
            
            // Procesar cada hoja
            foreach ($sheetNames as $sheetName) {
                $sheet = $spreadsheet->getSheetByName($sheetName);
                $sheetNameLower = strtolower(trim($sheetName));
                
                // Convertir hoja a array con encabezados
                $rows = $this->sheetToArrayPhpSpreadsheet($sheet);
                
                // Procesar según el nombre de la hoja
                $this->processSheetByName($sheetNameLower, $rows);
            }
        } catch (\Exception $e) {
            throw new \Exception("Error al procesar archivo Excel con PhpSpreadsheet: " . $e->getMessage());
        }
    }
    
    /**
     * Importar usando PHPExcel (fallback para compatibilidad)
     */
    protected function importWithPHPExcel($filePath)
    {
        try {
            $objPHPExcel = \PHPExcel_IOFactory::load($filePath);
            $sheetNames = $objPHPExcel->getSheetNames();
            
            foreach ($sheetNames as $sheetName) {
                $sheet = $objPHPExcel->getSheetByName($sheetName);
                $sheetNameLower = strtolower(trim($sheetName));
                $rows = $this->sheetToArrayPHPExcel($sheet);
                $this->processSheetByName($sheetNameLower, $rows);
            }
        } catch (\Exception $e) {
            throw new \Exception("Error al procesar archivo Excel con PHPExcel: " . $e->getMessage());
        }
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
     * Convertir hoja de Excel a array con encabezados (PhpSpreadsheet - compatible con PHP 8)
     */
    protected function sheetToArrayPhpSpreadsheet($sheet)
    {
        $rows = [];
        
        // Obtener el rango de datos
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();
        $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn);
        
        // Leer encabezados (primera fila)
        $headers = [];
        for ($col = 1; $col <= $highestColumnIndex; $col++) {
            // Convertir índice de columna a letra (A, B, C, etc.)
            $columnLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col);
            $cell = $sheet->getCell($columnLetter . '1');
            $cellValue = $cell->getCalculatedValue();
            if ($cellValue === null) {
                $cellValue = $cell->getValue();
            }
            $headers[] = $this->normalizeHeader($cellValue);
        }
        
        // Leer datos (desde la fila 2)
        for ($row = 2; $row <= $highestRow; $row++) {
            $rowData = [];
            $isEmpty = true;
            
            for ($col = 1; $col <= $highestColumnIndex; $col++) {
                // Convertir índice de columna a letra (A, B, C, etc.)
                $columnLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col);
                $cell = $sheet->getCell($columnLetter . $row);
                $cellValue = $cell->getCalculatedValue();
                if ($cellValue === null) {
                    $cellValue = $cell->getValue();
                }
                // Limpiar el valor
                if (is_string($cellValue)) {
                    $cellValue = trim($cellValue);
                }
                if ($cellValue !== null && $cellValue !== '') {
                    $isEmpty = false;
                }
                $rowData[$headers[$col - 1]] = $cellValue;
            }
            
            // Solo agregar filas no vacías
            if (!$isEmpty) {
                $rows[] = $rowData;
            }
        }
        
        return $rows;
    }
    
    /**
     * Convertir hoja de Excel a array con encabezados (PHPExcel - fallback)
     */
    protected function sheetToArrayPHPExcel($sheet)
    {
        $rows = [];
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();

        // Leer encabezados (primera fila)
        $headers = [];
        
        // Usar PHPExcel_Cell directamente - se carga automáticamente
        try {
            $highestColumnIndex = \PHPExcel_Cell::columnIndexFromString($highestColumn);
        } catch (\Error $e) {
            throw new \Exception("Error al procesar columnas Excel: " . $e->getMessage());
        } catch (\Exception $e) {
            throw new \Exception("Error al procesar columnas Excel: " . $e->getMessage());
        }
        
        for ($col = 0; $col < $highestColumnIndex; $col++) {
            $cell = $sheet->getCellByColumnAndRow($col, 1);
            $cellValue = $cell->getCalculatedValue();
            if ($cellValue === null) {
                $cellValue = $cell->getValue();
            }
            $headers[] = $this->normalizeHeader($cellValue);
        }

        // Leer datos (desde la fila 2)
        for ($row = 2; $row <= $highestRow; $row++) {
            $rowData = [];
            $isEmpty = true;

            for ($col = 0; $col < $highestColumnIndex; $col++) {
                $cell = $sheet->getCellByColumnAndRow($col, $row);
                $cellValue = $cell->getCalculatedValue();
                if ($cellValue === null) {
                    $cellValue = $cell->getValue();
                }
                if ($cellValue !== null && $cellValue !== '') {
                    $isEmpty = false;
                }
                $rowData[$headers[$col]] = $cellValue;
            }

            // Solo agregar filas no vacías
            if (!$isEmpty) {
                $rows[] = $rowData;
            }
        }

        return $rows;
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

    public function getNinosImportados()
    {
        $ninosIds = [];
        
        if (method_exists($this->ninosImport, 'getNinosImportados')) {
            $ninosIds = array_merge($ninosIds, $this->ninosImport->getNinosImportados());
        }
        
        return array_unique($ninosIds);
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
}

