<?php

namespace App\Exports;

use PHPExcel;
use PHPExcel_IOFactory;
use PHPExcel_Style_Fill;
use PHPExcel_Style_Alignment;
use Carbon\Carbon;

class EjemploMultiHojasExport
{
    public function download()
    {
        $objPHPExcel = new PHPExcel();
        
        // Eliminar hoja por defecto
        $objPHPExcel->removeSheetByIndex(0);
        
        // Crear hoja de niños
        $sheetNinos = $objPHPExcel->createSheet();
        $sheetNinos->setTitle('Niños');
        $this->createNinosSheet($sheetNinos);
        
        // Crear hoja de extra
        $sheetExtra = $objPHPExcel->createSheet();
        $sheetExtra->setTitle('Extra');
        $this->createExtraSheet($sheetExtra);
        
        // Crear hoja de madre
        $sheetMadre = $objPHPExcel->createSheet();
        $sheetMadre->setTitle('Madre');
        $this->createMadreSheet($sheetMadre);
        
        // Crear hoja de controles CRED
        $sheetControles = $objPHPExcel->createSheet();
        $sheetControles->setTitle('Controles_CRED');
        $this->createControlesCredSheet($sheetControles);
        
        // Guardar en memoria
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $tempFile = tempnam(sys_get_temp_dir(), 'excel_');
        $objWriter->save($tempFile);
        
        return $tempFile;
    }

    protected function createNinosSheet($sheet)
    {
        $fechaNacimiento = Carbon::now()->subMonths(3)->subDays(15);
        
        // Encabezados
        $headers = ['id_nino', 'establecimiento', 'tipo_doc', 'numero_doc', 'apellidos_nombres', 'fecha_nacimiento', 'genero'];
        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . '1', $header);
            $col++;
        }
        
        // Estilo de encabezados
        $sheet->getStyle('A1:G1')->applyFromArray([
            'font' => ['bold' => true],
            'fill' => [
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'startcolor' => ['rgb' => '4472C4']
            ],
            'alignment' => ['horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER]
        ]);
        
        // Datos
        $data = [
            1,
            'HOSPITAL REGIONAL',
            'DNI',
            '73811019',
            'Juan Pérez García',
            $fechaNacimiento->format('Y-m-d'),
            'M'
        ];
        
        $col = 'A';
        foreach ($data as $value) {
            $sheet->setCellValue($col . '2', $value);
            $col++;
        }
        
        // Ajustar ancho de columnas
        $sheet->getColumnDimension('A')->setWidth(12);
        $sheet->getColumnDimension('B')->setWidth(25);
        $sheet->getColumnDimension('C')->setWidth(15);
        $sheet->getColumnDimension('D')->setWidth(20);
        $sheet->getColumnDimension('E')->setWidth(30);
        $sheet->getColumnDimension('F')->setWidth(18);
        $sheet->getColumnDimension('G')->setWidth(12);
    }

    protected function createExtraSheet($sheet)
    {
        $headers = ['id_nino', 'red', 'microred', 'eess_nacimiento', 'distrito', 'provincia', 'departamento', 'seguro', 'programa'];
        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . '1', $header);
            $col++;
        }
        
        $sheet->getStyle('A1:J1')->applyFromArray([
            'font' => ['bold' => true],
            'fill' => [
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'startcolor' => ['rgb' => '4472C4']
            ],
            'alignment' => ['horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER]
        ]);
        
        $data = [
            1, '8', 'Hospital Regional', 'E001',
            'Calleria', 'Coronel Portillo', 'Ucayali', 'SIS', 'Juntos'
        ];
        
        $col = 'A';
        foreach ($data as $value) {
            $sheet->setCellValue($col . '2', $value);
            $col++;
        }
        
        $sheet->getColumnDimension('A')->setWidth(12);
        $sheet->getColumnDimension('B')->setWidth(12);
        $sheet->getColumnDimension('C')->setWidth(25);
        $sheet->getColumnDimension('D')->setWidth(20);
        $sheet->getColumnDimension('E')->setWidth(20);
        $sheet->getColumnDimension('F')->setWidth(20);
        $sheet->getColumnDimension('G')->setWidth(20);
        $sheet->getColumnDimension('H')->setWidth(15);
        $sheet->getColumnDimension('I')->setWidth(15);
    }

    protected function createMadreSheet($sheet)
    {
        $headers = ['id_nino', 'dni', 'apellidos_nombres', 'celular', 'domicilio', 'referencia_direccion'];
        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . '1', $header);
            $col++;
        }
        
        $sheet->getStyle('A1:G1')->applyFromArray([
            'font' => ['bold' => true],
            'fill' => [
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'startcolor' => ['rgb' => '4472C4']
            ],
            'alignment' => ['horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER]
        ]);
        
        $data = [
            1, '87654321', 'María García López', '987654321', 'Jr. Ejemplo 123', 'Frente al parque'
        ];
        
        $col = 'A';
        foreach ($data as $value) {
            $sheet->setCellValue($col . '2', $value);
            $col++;
        }
        
        $sheet->getColumnDimension('A')->setWidth(12);
        $sheet->getColumnDimension('B')->setWidth(15);
        $sheet->getColumnDimension('C')->setWidth(30);
        $sheet->getColumnDimension('D')->setWidth(15);
        $sheet->getColumnDimension('E')->setWidth(25);
        $sheet->getColumnDimension('F')->setWidth(25);
    }

    protected function createControlesCredSheet($sheet)
    {
        $fechaNacimiento = Carbon::now()->subMonths(3)->subDays(15);
        
        // Encabezados para Controles CRED
        $headers = ['id_nino', 'numero_control', 'fecha', 'peso', 'talla', 'perimetro_cefalico', 'estado_cred_once', 'estado_cred_final'];
        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . '1', $header);
            $col++;
        }
        
        $sheet->getStyle('A1:H1')->applyFromArray([
            'font' => ['bold' => true],
            'fill' => [
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'startcolor' => ['rgb' => '4472C4']
            ],
            'alignment' => ['horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER]
        ]);
        
        // Datos de controles CRED (para niño con id_nino=1)
        // Control 1: 30 días después
        // Control 2: 60 días después
        // Control 3: 90 días después
        $controles = [
            [1, 1, $fechaNacimiento->copy()->addDays(30)->format('Y-m-d'), 5.5, 60.5, 38.5, 'CUMPLE', 'CUMPLE'],
            [1, 2, $fechaNacimiento->copy()->addDays(60)->format('Y-m-d'), 6.2, 63.0, 39.0, 'CUMPLE', 'CUMPLE'],
            [1, 3, $fechaNacimiento->copy()->addDays(90)->format('Y-m-d'), 6.8, 65.5, 39.5, 'CUMPLE', 'CUMPLE'],
        ];
        
        $row = 2;
        foreach ($controles as $control) {
            $col = 'A';
            foreach ($control as $value) {
                $sheet->setCellValue($col . $row, $value);
                $col++;
            }
            $row++;
        }
        
        $sheet->getColumnDimension('A')->setWidth(12);
        $sheet->getColumnDimension('B')->setWidth(15);
        $sheet->getColumnDimension('C')->setWidth(15);
        $sheet->getColumnDimension('D')->setWidth(10);
        $sheet->getColumnDimension('E')->setWidth(10);
        $sheet->getColumnDimension('F')->setWidth(18);
        $sheet->getColumnDimension('G')->setWidth(18);
        $sheet->getColumnDimension('H')->setWidth(18);
    }
}

