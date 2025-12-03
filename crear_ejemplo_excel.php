<?php

/**
 * Script para crear archivo Excel de ejemplo
 * Ejecutar desde la línea de comandos: php crear_ejemplo_excel.php
 */

require __DIR__ . '/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use Carbon\Carbon;

$spreadsheet = new Spreadsheet();
$hoy = Carbon::now();

// ===== HOJA 1: NIÑOS =====
$sheetNinos = $spreadsheet->createSheet();
$sheetNinos->setTitle('Niños');

$headersNinos = ['id_niño', 'numero_doc', 'tipo_doc', 'apellidos_nombres', 'fecha_nacimiento', 'genero', 'establecimiento'];
$col = 1;
foreach ($headersNinos as $header) {
    $cell = Coordinate::stringFromColumnIndex($col) . '1';
    $sheetNinos->setCellValue($cell, $header);
    $col++;
}

$headerStyle = [
    'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4472C4']],
    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
];
$lastCol = Coordinate::stringFromColumnIndex(count($headersNinos));
$sheetNinos->getStyle('A1:' . $lastCol . '1')->applyFromArray($headerStyle);

$fechaNac1 = $hoy->copy()->subDays(50);
$fechaNac2 = $hoy->copy()->subDays(120);
$fechaNac3 = $hoy->copy()->subDays(15);

$datosNinos = [
    [1, '73811019', 'DNI', 'Juan Pérez García', $fechaNac1->format('Y-m-d'), 'M', 'Hospital Nacional Dos de Mayo'],
    [2, '87654321', 'DNI', 'María González López', $fechaNac2->format('Y-m-d'), 'F', 'Centro de Salud San Juan'],
    [3, '12345678', 'DNI', 'Carlos Rodríguez Silva', $fechaNac3->format('Y-m-d'), 'M', 'Hospital Nacional Arzobispo Loayza'],
];

$row = 2;
foreach ($datosNinos as $data) {
    $col = 1;
    foreach ($data as $value) {
        $cell = Coordinate::stringFromColumnIndex($col) . $row;
        $sheetNinos->setCellValue($cell, $value);
        $col++;
    }
    $row++;
}

foreach (range(1, count($headersNinos)) as $colNum) {
    $sheetNinos->getColumnDimension(Coordinate::stringFromColumnIndex($colNum))->setAutoSize(true);
}

// ===== HOJA 2: CONTROLES RN =====
$sheetRN = $spreadsheet->createSheet();
$sheetRN->setTitle('Controles RN');

$headersRN = ['id_crn', 'id_niño', 'numero_control', 'fecha', 'peso', 'talla', 'perimetro_cefalico'];
$col = 1;
foreach ($headersRN as $header) {
    $cell = Coordinate::stringFromColumnIndex($col) . '1';
    $sheetRN->setCellValue($cell, $header);
    $col++;
}

$sheetRN->getStyle('A1:' . Coordinate::stringFromColumnIndex(count($headersRN)) . '1')->applyFromArray($headerStyle);

$datosRN = [
    [100, 3, 1, $fechaNac3->copy()->addDays(5)->format('Y-m-d'), '3200', '50.5', '35.2'],
    [101, 3, 2, $fechaNac3->copy()->addDays(12)->format('Y-m-d'), '3400', '51.8', '35.8'],
];

$row = 2;
foreach ($datosRN as $data) {
    $col = 1;
    foreach ($data as $value) {
        $cell = Coordinate::stringFromColumnIndex($col) . $row;
        $sheetRN->setCellValue($cell, $value);
        $col++;
    }
    $row++;
}

foreach (range(1, count($headersRN)) as $colNum) {
    $sheetRN->getColumnDimension(Coordinate::stringFromColumnIndex($colNum))->setAutoSize(true);
}

// ===== HOJA 3: CONTROLES CRED =====
$sheetCRED = $spreadsheet->createSheet();
$sheetCRED->setTitle('Controles CRED');

$headersCRED = ['id_cred', 'id_niño', 'numero_control', 'fecha', 'peso', 'talla', 'perimetro_cefalico', 'estado_cred_once', 'estado_cred_final'];
$col = 1;
foreach ($headersCRED as $header) {
    $cell = Coordinate::stringFromColumnIndex($col) . '1';
    $sheetCRED->setCellValue($cell, $header);
    $col++;
}

$sheetCRED->getStyle('A1:' . Coordinate::stringFromColumnIndex(count($headersCRED)) . '1')->applyFromArray($headerStyle);

$datosCRED = [
    [200, 1, 1, $fechaNac1->copy()->addDays(35)->format('Y-m-d'), '3800', '53.2', '36.8', 'Normal', 'Normal'],
    [201, 2, 3, $fechaNac2->copy()->addDays(95)->format('Y-m-d'), '4500', '58.5', '38.5', 'Normal', 'Normal'],
    [202, 2, 4, $fechaNac2->copy()->addDays(125)->format('Y-m-d'), '4800', '60.2', '39.0', 'Normal', 'Normal'],
];

$row = 2;
foreach ($datosCRED as $data) {
    $col = 1;
    foreach ($data as $value) {
        $cell = Coordinate::stringFromColumnIndex($col) . $row;
        $sheetCRED->setCellValue($cell, $value);
        $col++;
    }
    $row++;
}

foreach (range(1, count($headersCRED)) as $colNum) {
    $sheetCRED->getColumnDimension(Coordinate::stringFromColumnIndex($colNum))->setAutoSize(true);
}

// ===== HOJA 4: DATOS EXTRA =====
$sheetExtra = $spreadsheet->createSheet();
$sheetExtra->setTitle('Datos Extra');

$headersExtra = ['id_extra', 'id_niño', 'red', 'microred', 'eess_nacimiento', 'distrito', 'provincia', 'departamento', 'seguro', 'programa'];
$col = 1;
foreach ($headersExtra as $header) {
    $cell = Coordinate::stringFromColumnIndex($col) . '1';
    $sheetExtra->setCellValue($cell, $header);
    $col++;
}

$sheetExtra->getStyle('A1:' . Coordinate::stringFromColumnIndex(count($headersExtra)) . '1')->applyFromArray($headerStyle);

$datosExtra = [
    [10, 1, 'CORONEL PORTILLO', 'Microred 01', 'Hospital Nacional Dos de Mayo', 'Callao', 'Callao', 'Lima', 'SIS', 'Programa CRED'],
    [11, 2, 'CORONEL PORTILLO', 'Microred 02', 'Centro de Salud San Juan', 'San Juan de Lurigancho', 'Lima', 'Lima', 'SIS', 'Programa CRED'],
    [12, 3, 'CORONEL PORTILLO', 'Microred 01', 'Hospital Nacional Arzobispo Loayza', 'Lima', 'Lima', 'Lima', 'SIS', 'Programa CRED'],
];

$row = 2;
foreach ($datosExtra as $data) {
    $col = 1;
    foreach ($data as $value) {
        $cell = Coordinate::stringFromColumnIndex($col) . $row;
        $sheetExtra->setCellValue($cell, $value);
        $col++;
    }
    $row++;
}

foreach (range(1, count($headersExtra)) as $colNum) {
    $sheetExtra->getColumnDimension(Coordinate::stringFromColumnIndex($colNum))->setAutoSize(true);
}

// ===== HOJA 5: MADRE =====
$sheetMadre = $spreadsheet->createSheet();
$sheetMadre->setTitle('Madre');

$headersMadre = ['id_madre', 'id_niño', 'dni', 'apellidos_nombres', 'celular', 'domicilio', 'referencia_direccion'];
$col = 1;
foreach ($headersMadre as $header) {
    $cell = Coordinate::stringFromColumnIndex($col) . '1';
    $sheetMadre->setCellValue($cell, $header);
    $col++;
}

$sheetMadre->getStyle('A1:' . Coordinate::stringFromColumnIndex(count($headersMadre)) . '1')->applyFromArray($headerStyle);

$datosMadre = [
    [50, 1, '12345678', 'María García López', '987654321', 'Jr. Los Olivos 123', 'Frente al parque'],
    [51, 2, '87654321', 'Ana Martínez Ruiz', '987654322', 'Av. Principal 456', 'Cerca del mercado'],
    [52, 3, '11223344', 'Carmen Torres Vásquez', '987654323', 'Mz. A Lt. 5', 'Asentamiento humano'],
];

$row = 2;
foreach ($datosMadre as $data) {
    $col = 1;
    foreach ($data as $value) {
        $cell = Coordinate::stringFromColumnIndex($col) . $row;
        $sheetMadre->setCellValue($cell, $value);
        $col++;
    }
    $row++;
}

foreach (range(1, count($headersMadre)) as $colNum) {
    $sheetMadre->getColumnDimension(Coordinate::stringFromColumnIndex($colNum))->setAutoSize(true);
}

// Eliminar hoja por defecto
$sheetIndex = $spreadsheet->getIndex($spreadsheet->getSheetByName('Worksheet'));
if ($sheetIndex !== false) {
    $spreadsheet->removeSheetByIndex($sheetIndex);
}

// Guardar archivo
$filePath = __DIR__ . '/ejemplo_importacion.xlsx';
$writer = new Xlsx($spreadsheet);
$writer->save($filePath);

echo "✅ Archivo Excel creado exitosamente: {$filePath}\n";
echo "📊 El archivo contiene 5 hojas:\n";
echo "   1. Niños\n";
echo "   2. Controles RN\n";
echo "   3. Controles CRED\n";
echo "   4. Datos Extra\n";
echo "   5. Madre\n";
echo "\n💡 Ya puedes usar este archivo para importar y probar el sistema.\n";


