<?php

namespace App\Exports;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use Carbon\Carbon;

class EjemploImportacionExport
{
    protected $spreadsheet;
    
    public function __construct()
    {
        $this->spreadsheet = new Spreadsheet();
    }
    
    /**
     * Crear el archivo Excel completo con todas las hojas
     */
    public function export($filePath)
    {
        // Crear hojas
        $this->crearHojaNinos();
        $this->crearHojaControlesRN();
        $this->crearHojaControlesCRED();
        $this->crearHojaDatosExtra();
        $this->crearHojaMadre();
        
        // Eliminar hoja por defecto
        $sheetIndex = $this->spreadsheet->getIndex(
            $this->spreadsheet->getSheetByName('Worksheet')
        );
        if ($sheetIndex !== false) {
            $this->spreadsheet->removeSheetByIndex($sheetIndex);
        }
        
        // Guardar archivo
        $writer = new Xlsx($this->spreadsheet);
        $writer->save($filePath);
        
        return $filePath;
    }
    
    /**
     * Crear hoja de Niños
     */
    protected function crearHojaNinos()
    {
        $sheet = $this->spreadsheet->createSheet();
        $sheet->setTitle('Niños');
        
        $hoy = Carbon::now();
        
        // Encabezados
        $headers = ['id_niño', 'numero_doc', 'tipo_doc', 'apellidos_nombres', 'fecha_nacimiento', 'genero', 'establecimiento'];
        $col = 1;
        foreach ($headers as $header) {
            $cell = Coordinate::stringFromColumnIndex($col) . '1';
            $sheet->setCellValue($cell, $header);
            $col++;
        }
        
        // Aplicar estilo a encabezados
        $lastCol = Coordinate::stringFromColumnIndex(count($headers));
        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4472C4']
            ],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ];
        $sheet->getStyle('A1:' . $lastCol . '1')->applyFromArray($headerStyle);
        
        // Datos de ejemplo - 3 niños
        $fechaNac1 = $hoy->copy()->subDays(50); // 50 días
        $fechaNac2 = $hoy->copy()->subDays(120); // 120 días
        $fechaNac3 = $hoy->copy()->subDays(15); // 15 días (recién nacido)
        
        $datos = [
            [1, '73811019', 'DNI', 'Juan Pérez García', $fechaNac1->format('Y-m-d'), 'M', 'Hospital Nacional Dos de Mayo'],
            [2, '87654321', 'DNI', 'María González López', $fechaNac2->format('Y-m-d'), 'F', 'Centro de Salud San Juan'],
            [3, '12345678', 'DNI', 'Carlos Rodríguez Silva', $fechaNac3->format('Y-m-d'), 'M', 'Hospital Nacional Arzobispo Loayza'],
        ];
        
        $row = 2;
        foreach ($datos as $data) {
            $col = 1;
            foreach ($data as $value) {
                $cell = Coordinate::stringFromColumnIndex($col) . $row;
                $sheet->setCellValue($cell, $value);
                $col++;
            }
            $row++;
        }
        
        // Ajustar ancho de columnas
        foreach (range(1, count($headers)) as $colNum) {
            $colLetter = Coordinate::stringFromColumnIndex($colNum);
            $sheet->getColumnDimension($colLetter)->setAutoSize(true);
        }
    }
    
    /**
     * Crear hoja de Controles RN
     */
    protected function crearHojaControlesRN()
    {
        $sheet = $this->spreadsheet->createSheet();
        $sheet->setTitle('Controles RN');
        
        $hoy = Carbon::now();
        
        // Encabezados
        $headers = ['id_crn', 'id_niño', 'numero_control', 'fecha', 'peso', 'talla', 'perimetro_cefalico'];
        $col = 1;
        foreach ($headers as $header) {
            $cell = Coordinate::stringFromColumnIndex($col) . '1';
            $sheet->setCellValue($cell, $header);
            $col++;
        }
        
        // Estilo de encabezados
        $lastCol = Coordinate::stringFromColumnIndex(count($headers));
        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4472C4']
            ],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ];
        $sheet->getStyle('A1:' . $lastCol . '1')->applyFromArray($headerStyle);
        
        // Datos de ejemplo
        $fechaNac3 = $hoy->copy()->subDays(15);
        
        $datos = [
            [100, 3, 1, $fechaNac3->copy()->addDays(5)->format('Y-m-d'), '3200', '50.5', '35.2'],
            [101, 3, 2, $fechaNac3->copy()->addDays(12)->format('Y-m-d'), '3400', '51.8', '35.8'],
        ];
        
        $row = 2;
        foreach ($datos as $data) {
            $col = 1;
            foreach ($data as $value) {
                $cell = Coordinate::stringFromColumnIndex($col) . $row;
                $sheet->setCellValue($cell, $value);
                $col++;
            }
            $row++;
        }
        
        // Ajustar ancho de columnas
        foreach (range(1, count($headers)) as $colNum) {
            $colLetter = Coordinate::stringFromColumnIndex($colNum);
            $sheet->getColumnDimension($colLetter)->setAutoSize(true);
        }
    }
    
    /**
     * Crear hoja de Controles CRED
     */
    protected function crearHojaControlesCRED()
    {
        $sheet = $this->spreadsheet->createSheet();
        $sheet->setTitle('Controles CRED');
        
        $hoy = Carbon::now();
        
        // Encabezados
        $headers = ['id_cred', 'id_niño', 'numero_control', 'fecha', 'peso', 'talla', 'perimetro_cefalico', 'estado_cred_once', 'estado_cred_final'];
        $col = 1;
        foreach ($headers as $header) {
            $cell = Coordinate::stringFromColumnIndex($col) . '1';
            $sheet->setCellValue($cell, $header);
            $col++;
        }
        
        // Estilo de encabezados
        $lastCol = Coordinate::stringFromColumnIndex(count($headers));
        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4472C4']
            ],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ];
        $sheet->getStyle('A1:' . $lastCol . '1')->applyFromArray($headerStyle);
        
        // Datos de ejemplo
        $fechaNac1 = $hoy->copy()->subDays(50);
        $fechaNac2 = $hoy->copy()->subDays(120);
        
        $datos = [
            // Niño 1 (50 días) - Control 1 (29-59 días)
            [200, 1, 1, $fechaNac1->copy()->addDays(35)->format('Y-m-d'), '3800', '53.2', '36.8', 'Normal', 'Normal'],
            // Niño 2 (120 días) - Control 3 (90-119 días) y Control 4 (120-149 días)
            [201, 2, 3, $fechaNac2->copy()->addDays(95)->format('Y-m-d'), '4500', '58.5', '38.5', 'Normal', 'Normal'],
            [202, 2, 4, $fechaNac2->copy()->addDays(125)->format('Y-m-d'), '4800', '60.2', '39.0', 'Normal', 'Normal'],
        ];
        
        $row = 2;
        foreach ($datos as $data) {
            $col = 1;
            foreach ($data as $value) {
                $cell = Coordinate::stringFromColumnIndex($col) . $row;
                $sheet->setCellValue($cell, $value);
                $col++;
            }
            $row++;
        }
        
        // Ajustar ancho de columnas
        foreach (range(1, count($headers)) as $colNum) {
            $colLetter = Coordinate::stringFromColumnIndex($colNum);
            $sheet->getColumnDimension($colLetter)->setAutoSize(true);
        }
    }
    
    /**
     * Crear hoja de Datos Extra
     */
    protected function crearHojaDatosExtra()
    {
        $sheet = $this->spreadsheet->createSheet();
        $sheet->setTitle('Datos Extra');
        
        // Encabezados
        $headers = ['id_extra', 'id_niño', 'red', 'microred', 'eess_nacimiento', 'distrito', 'provincia', 'departamento', 'seguro', 'programa'];
        $col = 1;
        foreach ($headers as $header) {
            $cell = Coordinate::stringFromColumnIndex($col) . '1';
            $sheet->setCellValue($cell, $header);
            $col++;
        }
        
        // Estilo de encabezados
        $lastCol = Coordinate::stringFromColumnIndex(count($headers));
        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4472C4']
            ],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ];
        $sheet->getStyle('A1:' . $lastCol . '1')->applyFromArray($headerStyle);
        
        // Datos de ejemplo
        $datos = [
            [10, 1, 'CORONEL PORTILLO', 'Microred 01', 'Hospital Nacional Dos de Mayo', 'Callao', 'Callao', 'Lima', 'SIS', 'Programa CRED'],
            [11, 2, 'CORONEL PORTILLO', 'Microred 02', 'Centro de Salud San Juan', 'San Juan de Lurigancho', 'Lima', 'Lima', 'SIS', 'Programa CRED'],
            [12, 3, 'CORONEL PORTILLO', 'Microred 01', 'Hospital Nacional Arzobispo Loayza', 'Lima', 'Lima', 'Lima', 'SIS', 'Programa CRED'],
        ];
        
        $row = 2;
        foreach ($datos as $data) {
            $col = 1;
            foreach ($data as $value) {
                $cell = Coordinate::stringFromColumnIndex($col) . $row;
                $sheet->setCellValue($cell, $value);
                $col++;
            }
            $row++;
        }
        
        // Ajustar ancho de columnas
        foreach (range(1, count($headers)) as $colNum) {
            $colLetter = Coordinate::stringFromColumnIndex($colNum);
            $sheet->getColumnDimension($colLetter)->setAutoSize(true);
        }
    }
    
    /**
     * Crear hoja de Madre
     */
    protected function crearHojaMadre()
    {
        $sheet = $this->spreadsheet->createSheet();
        $sheet->setTitle('Madre');
        
        // Encabezados
        $headers = ['id_madre', 'id_niño', 'dni', 'apellidos_nombres', 'celular', 'domicilio', 'referencia_direccion'];
        $col = 1;
        foreach ($headers as $header) {
            $cell = Coordinate::stringFromColumnIndex($col) . '1';
            $sheet->setCellValue($cell, $header);
            $col++;
        }
        
        // Estilo de encabezados
        $lastCol = Coordinate::stringFromColumnIndex(count($headers));
        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4472C4']
            ],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ];
        $sheet->getStyle('A1:' . $lastCol . '1')->applyFromArray($headerStyle);
        
        // Datos de ejemplo
        $datos = [
            [50, 1, '12345678', 'María García López', '987654321', 'Jr. Los Olivos 123', 'Frente al parque'],
            [51, 2, '87654321', 'Ana Martínez Ruiz', '987654322', 'Av. Principal 456', 'Cerca del mercado'],
            [52, 3, '11223344', 'Carmen Torres Vásquez', '987654323', 'Mz. A Lt. 5', 'Asentamiento humano'],
        ];
        
        $row = 2;
        foreach ($datos as $data) {
            $col = 1;
            foreach ($data as $value) {
                $cell = Coordinate::stringFromColumnIndex($col) . $row;
                $sheet->setCellValue($cell, $value);
                $col++;
            }
            $row++;
        }
        
        // Ajustar ancho de columnas
        foreach (range(1, count($headers)) as $colNum) {
            $colLetter = Coordinate::stringFromColumnIndex($colNum);
            $sheet->getColumnDimension($colLetter)->setAutoSize(true);
        }
    }
    
    /**
     * Obtener letra de columna por número
     */
    protected function getColumnLetter($number)
    {
        if ($number <= 0) {
            return 'A';
        }
        
        // Si PhpSpreadsheet está disponible, usar su método
        if (class_exists('\PhpOffice\PhpSpreadsheet\Cell\Coordinate')) {
            try {
                return \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($number);
            } catch (\Exception $e) {
                // Fallback a método manual
            }
        }
        
        // Método manual
        $letter = '';
        $number = $number - 1; // Convertir a base 0
        while ($number >= 0) {
            $letter = chr(65 + ($number % 26)) . $letter;
            $number = intval($number / 26) - 1;
        }
        return $letter;
    }
}

