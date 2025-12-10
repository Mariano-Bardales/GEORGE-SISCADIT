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
    protected $tamizajeImport;
    protected $vacunasImport;
    protected $visitasImport;
    protected $recienNacidoImport;

    public function __construct()
    {
        $this->ninosImport = new NinosImport();
        $this->extraImport = new ExtraImport();
        $this->madreImport = new MadreImport();
        $this->controlesImport = new ControlesRnImport();
        $this->controlesCredImport = new ControlesMenor1Import();
        $this->tamizajeImport = new TamizajeImport();
        $this->vacunasImport = new VacunasImport();
        $this->visitasImport = new VisitasImport();
        $this->recienNacidoImport = new RecienNacidoImport();
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
            
            // Usar PhpSpreadsheet (compatible con PHP 8) - REQUERIDO
            if (!class_exists('\PhpOffice\PhpSpreadsheet\IOFactory')) {
                throw new \Exception("PhpSpreadsheet no está disponible. Por favor, ejecute: composer require phpoffice/phpspreadsheet");
            }
            
            // Verificar que ZipArchive esté disponible (requerido por PhpSpreadsheet)
            if (!class_exists('ZipArchive')) {
                throw new \Exception("La extensión ZipArchive de PHP no está habilitada. Por favor, habilite extension=zip en php.ini y reinicie Apache.");
            }
            
            // Intentar importar con PhpSpreadsheet
            try {
                return $this->importWithPhpSpreadsheet($filePath);
            } catch (\Exception $e) {
                // Si falla, mostrar error claro
                $errorMessage = "Error al importar archivo Excel con PhpSpreadsheet: " . $e->getMessage();
                
                // Si el error menciona ZipArchive, dar instrucciones específicas
                if (stripos($e->getMessage(), 'ZipArchive') !== false || stripos($e->getMessage(), 'zip') !== false) {
                    $errorMessage .= "\n\nSOLUCIÓN: La extensión ZipArchive no está habilitada. ";
                    $errorMessage .= "Habilite extension=zip en C:\\xampp82\\php\\php.ini y reinicie Apache.";
                }
                
                throw new \Exception($errorMessage);
            }
            
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
            // Verificar que ZipArchive esté disponible antes de cargar
            if (!class_exists('ZipArchive')) {
                throw new \Exception("La extensión ZipArchive de PHP no está habilitada. Habilite extension=zip en php.ini y reinicie Apache.");
            }
            
            // Cargar archivo Excel con PhpSpreadsheet
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($filePath);
            
            // Obtener todas las hojas
            $sheetNames = $spreadsheet->getSheetNames();
            
            // Lista de hojas reconocidas
            $hojasReconocidas = [];
            $hojasNoReconocidas = [];
            
            // Separar hojas: primero "Niños" (OBLIGATORIA), luego las demás
            $hojaNinos = null;
            $otrasHojas = [];
            
            foreach ($sheetNames as $sheetName) {
                $sheetNameLower = strtolower(trim($sheetName));
                if (in_array($sheetNameLower, ['niños', 'ninos', 'niño', 'nino'])) {
                    $hojaNinos = $sheetName;
                } else {
                    $otrasHojas[] = $sheetName;
                }
            }
            
            // PRIMERO: Procesar hoja "Niños" (OBLIGATORIA)
            if ($hojaNinos) {
                $sheet = $spreadsheet->getSheetByName($hojaNinos);
                $sheetNameLower = strtolower(trim($hojaNinos));
                
                \Log::info('Procesando hoja Niños PRIMERO', [
                    'nombre_original' => $hojaNinos,
                    'nombre_normalizado' => $sheetNameLower
                ]);
                
                // Convertir hoja a array con encabezados
                $rows = $this->sheetToArrayPhpSpreadsheet($sheet);
                
                \Log::info('Datos de hoja Niños', [
                    'hoja' => $hojaNinos,
                    'filas' => count($rows),
                    'primeras_columnas' => !empty($rows) ? array_keys($rows[0] ?? []) : [],
                    'primera_fila_ejemplo' => !empty($rows) ? array_slice($rows[0] ?? [], 0, 5, true) : []
                ]);
                
                $hojasReconocidas[] = $hojaNinos;
                try {
                    $this->processSheetByName($sheetNameLower, $rows);
                } catch (\Exception $e) {
                    \Log::error('Error al procesar hoja Niños', [
                        'hoja' => $hojaNinos,
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);
                    throw new \Exception("Error crítico al procesar hoja 'Niños' (OBLIGATORIA): " . $e->getMessage());
                }
            } else {
                throw new \Exception("La hoja 'Niños' es OBLIGATORIA y no se encontró en el archivo Excel.");
            }
            
            // SEGUNDO: Procesar las demás hojas
            foreach ($otrasHojas as $sheetName) {
                $sheet = $spreadsheet->getSheetByName($sheetName);
                $sheetNameLower = strtolower(trim($sheetName));
                
                \Log::info('Procesando hoja', [
                    'nombre_original' => $sheetName,
                    'nombre_normalizado' => $sheetNameLower,
                    'reconocida' => $this->isSheetRecognized($sheetNameLower)
                ]);
                
                // Convertir hoja a array con encabezados
                $rows = $this->sheetToArrayPhpSpreadsheet($sheet);
                
                \Log::info('Datos de hoja', [
                    'hoja' => $sheetName,
                    'filas' => count($rows),
                    'primeras_columnas' => !empty($rows) ? array_keys($rows[0] ?? []) : [],
                    'primera_fila_ejemplo' => !empty($rows) ? array_slice($rows[0] ?? [], 0, 5, true) : []
                ]);
                
                // Verificar si la hoja será procesada
                if ($this->isSheetRecognized($sheetNameLower)) {
                    $hojasReconocidas[] = $sheetName;
                    // Procesar según el nombre de la hoja
                    try {
                        $this->processSheetByName($sheetNameLower, $rows);
                    } catch (\Exception $e) {
                        \Log::error('Error al procesar hoja reconocida', [
                            'hoja' => $sheetName,
                            'error' => $e->getMessage(),
                            'trace' => $e->getTraceAsString()
                        ]);
                        $this->addWarning("Error al procesar hoja '{$sheetName}': " . $e->getMessage());
                    }
                } else {
                    $hojasNoReconocidas[] = $sheetName;
                    \Log::warning('Hoja no reconocida', ['hoja' => $sheetName]);
                }
            }
            
            // Guardar información de las hojas procesadas para reporte
            $this->processedSheets = [
                'reconocidas' => $hojasReconocidas,
                'no_reconocidas' => $hojasNoReconocidas,
                'total' => count($sheetNames)
            ];
            
            // Si hay hojas no reconocidas, agregar advertencia
            if (!empty($hojasNoReconocidas)) {
                $this->addWarning("Las siguientes hojas no fueron reconocidas y fueron omitidas: " . implode(', ', $hojasNoReconocidas) . 
                    ". Hojas válidas: 'Niños', 'Datos Extra' (o 'Extra'), 'Madre', 'Controles RN', 'Controles CRED', 'Tamizaje', 'Vacunas', 'Visitas', 'Recién Nacidos' (o 'Recien Nacidos', 'CNV')");
            }
            
        } catch (\Exception $e) {
            throw new \Exception("Error al procesar archivo Excel con PhpSpreadsheet: " . $e->getMessage());
        }
    }
    
    /**
     * Verificar si una hoja es reconocida
     */
    protected function isSheetRecognized($sheetNameLower)
    {
        $hojasValidas = [
            'niños', 'ninos', 'niño', 'nino',
            'extra', 'datos_extra', 'datos extra',
            'madre', 'madres',
            'controles', 'control', 'controles_rn', 'controles rn',
            'controles_cred', 'controles cred', 'controles_menor1', 'controles menor1', 'cred',
            'tamizaje', 'tamisaje', 'tamizaje neonatal',
            'vacunas', 'vacuna', 'vacuna_rn', 'vacuna rn',
            'visitas', 'visita', 'visita domiciliaria',
            'recien nacido', 'recien_nacido', 'recién nacido', 'recién_nacido', 'recién nacidos', 'recien nacidos', 'recién_nacidos', 'recien_nacidos', 'cnv'
        ];
        
        return in_array($sheetNameLower, $hojasValidas);
    }
    
    protected $processedSheets = [];
    protected $warnings = [];
    
    protected function addWarning($message)
    {
        if (!isset($this->warnings)) {
            $this->warnings = [];
        }
        $this->warnings[] = $message;
    }
    
    public function getWarnings()
    {
        return $this->warnings ?? [];
    }
    
    public function getProcessedSheets()
    {
        return $this->processedSheets ?? [];
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

            case 'tamizaje':
            case 'tamisaje':
            case 'tamizaje neonatal':
                $this->processSheet($this->tamizajeImport, $rows);
                break;

            case 'vacunas':
            case 'vacuna':
            case 'vacuna_rn':
            case 'vacuna rn':
                $this->processSheet($this->vacunasImport, $rows);
                break;

            case 'visitas':
            case 'visita':
            case 'visita domiciliaria':
                $this->processSheet($this->visitasImport, $rows);
                break;

            case 'recien nacido':
            case 'recien_nacido':
            case 'recién nacido':
            case 'recién_nacido':
            case 'recién nacidos':
            case 'recien nacidos':
            case 'recién_nacidos':
            case 'recien_nacidos':
            case 'cnv':
                $this->processSheet($this->recienNacidoImport, $rows);
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
     * Preserva caracteres especiales como ñ, á, é, í, ó, ú
     */
    protected function normalizeHeader($header)
    {
        if (empty($header)) {
            return '';
        }

        // Convertir a minúsculas y preservar caracteres especiales
        $normalized = mb_strtolower(trim($header), 'UTF-8');
        
        // Reemplazar espacios y guiones con guiones bajos, pero preservar letras con acentos y ñ
        $normalized = preg_replace('/[\s\-]+/', '_', $normalized);
        
        // Eliminar caracteres especiales excepto letras, números, guiones bajos y caracteres acentuados
        $normalized = preg_replace('/[^a-z0-9_áéíóúñü]/u', '_', $normalized);
        
        // Reemplazar múltiples guiones bajos con uno solo
        $normalized = preg_replace('/_+/', '_', $normalized);
        
        // Eliminar guiones bajos al inicio y final
        $normalized = trim($normalized, '_');

        return $normalized;
    }

    /**
     * Procesar una hoja usando un importador específico
     */
    protected function processSheet($importer, $rows)
    {
        // Verificar que haya filas para procesar
        if (empty($rows) || count($rows) === 0) {
            \Log::warning('Hoja vacía o sin datos para procesar');
            return;
        }

        // Crear una Collection con los datos
        $collection = new Collection($rows);

        // Llamar al método collection del importador
        if (method_exists($importer, 'collection')) {
            try {
                $importer->collection($collection);
                \Log::info('Hoja procesada exitosamente', [
                    'filas' => count($rows),
                    'importer' => get_class($importer)
                ]);
            } catch (\Exception $e) {
                \Log::error('Error al procesar hoja', [
                    'error' => $e->getMessage(),
                    'importer' => get_class($importer),
                    'trace' => $e->getTraceAsString()
                ]);
                throw $e;
            }
        } else {
            \Log::warning('El importador no tiene método collection', [
                'importer' => get_class($importer)
            ]);
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
        
        if (method_exists($this->tamizajeImport, 'getErrors')) {
            $errors = $this->tamizajeImport->getErrors();
            foreach ($errors as $error) {
                $allErrors[] = "[tamizaje] {$error}";
            }
        }
        
        if (method_exists($this->vacunasImport, 'getErrors')) {
            $errors = $this->vacunasImport->getErrors();
            foreach ($errors as $error) {
                $allErrors[] = "[vacunas] {$error}";
            }
        }
        
        if (method_exists($this->visitasImport, 'getErrors')) {
            $errors = $this->visitasImport->getErrors();
            foreach ($errors as $error) {
                $allErrors[] = "[visitas] {$error}";
            }
        }
        
        if (method_exists($this->recienNacidoImport, 'getErrors')) {
            $errors = $this->recienNacidoImport->getErrors();
            foreach ($errors as $error) {
                $allErrors[] = "[recien_nacido] {$error}";
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
        
        if (method_exists($this->tamizajeImport, 'getSuccess')) {
            $success = $this->tamizajeImport->getSuccess();
            foreach ($success as $s) {
                $allSuccess[] = "[tamizaje] {$s}";
            }
        }
        
        if (method_exists($this->vacunasImport, 'getSuccess')) {
            $success = $this->vacunasImport->getSuccess();
            foreach ($success as $s) {
                $allSuccess[] = "[vacunas] {$s}";
            }
        }
        
        if (method_exists($this->visitasImport, 'getSuccess')) {
            $success = $this->visitasImport->getSuccess();
            foreach ($success as $s) {
                $allSuccess[] = "[visitas] {$s}";
            }
        }
        
        if (method_exists($this->recienNacidoImport, 'getSuccess')) {
            $success = $this->recienNacidoImport->getSuccess();
            foreach ($success as $s) {
                $allSuccess[] = "[recien_nacido] {$s}";
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
            'tamizajes' => 0,
            'actualizados_tamizajes' => 0,
            'vacunas' => 0,
            'actualizados_vacunas' => 0,
            'visitas' => 0,
            'actualizados_visitas' => 0,
            'recien_nacido' => 0,
            'actualizados_recien_nacido' => 0,
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
        
        if (method_exists($this->tamizajeImport, 'getStats')) {
            $stats = $this->tamizajeImport->getStats();
            $allStats['tamizajes'] += $stats['tamizajes'] ?? 0;
            $allStats['actualizados_tamizajes'] += $stats['actualizados'] ?? 0;
        }
        
        if (method_exists($this->vacunasImport, 'getStats')) {
            $stats = $this->vacunasImport->getStats();
            $allStats['vacunas'] += $stats['vacunas'] ?? 0;
            $allStats['actualizados_vacunas'] += $stats['actualizados'] ?? 0;
        }
        
        if (method_exists($this->visitasImport, 'getStats')) {
            $stats = $this->visitasImport->getStats();
            $allStats['visitas'] += $stats['visitas'] ?? 0;
            $allStats['actualizados_visitas'] += $stats['actualizados'] ?? 0;
        }
        
        if (method_exists($this->recienNacidoImport, 'getStats')) {
            $stats = $this->recienNacidoImport->getStats();
            $allStats['recien_nacido'] += $stats['recien_nacido'] ?? 0;
            $allStats['actualizados_recien_nacido'] += $stats['actualizados'] ?? 0;
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

