<?php

/**
 * Script para crear un archivo Excel de ejemplo con todas las hojas
 * necesarias para importar datos al sistema SISCADIT
 * 
 * Uso: php crear_excel_ejemplo.php
 */

require __DIR__ . '/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

$spreadsheet = new Spreadsheet();
$spreadsheet->removeSheetByIndex(0); // Eliminar hoja por defecto

// ============================================
// HOJA 1: NIÑOS (OBLIGATORIA)
// ============================================
$sheet = $spreadsheet->createSheet();
$sheet->setTitle('Niños');

$headers = [
    'id_niño',
    'establecimiento',
    'tipo_doc',
    'numero_doc',
    'apellidos_nombres',
    'fecha_nacimiento',
    'genero'
];

$sheet->fromArray([$headers], null, 'A1');
$sheet->getStyle('A1:G1')->applyFromArray([
    'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
    'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'startColor' => ['rgb' => '1e40af']
    ],
    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
]);

// Datos de ejemplo
$datosNinos = [
    [1, 'EESS Modelo', 'DNI', '10000001', 'Prueba 1', '2024-12-05', 'M'],
    [2, 'EESS Modelo', 'DNI', '10000002', 'Prueba 2', '2024-11-15', 'F'],
    [3, 'EESS Modelo', 'DNI', '10000003', 'Prueba 3', '2024-10-20', 'M'],
];

$sheet->fromArray($datosNinos, null, 'A2');
$sheet->getColumnDimension('A')->setAutoSize(true);
$sheet->getColumnDimension('B')->setAutoSize(true);
$sheet->getColumnDimension('C')->setAutoSize(true);
$sheet->getColumnDimension('D')->setAutoSize(true);
$sheet->getColumnDimension('E')->setAutoSize(true);
$sheet->getColumnDimension('F')->setAutoSize(true);
$sheet->getColumnDimension('G')->setAutoSize(true);

// ============================================
// HOJA 2: DATOS EXTRA
// ============================================
$sheet = $spreadsheet->createSheet();
$sheet->setTitle('Datos Extra');

$headers = [
    'id_extra',
    'id_niño',
    'red',
    'microred',
    'eess_nacimiento',
    'distrito',
    'provincia',
    'departamento',
    'seguro',
    'programa'
];

$sheet->fromArray([$headers], null, 'A1');
$sheet->getStyle('A1:J1')->applyFromArray([
    'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
    'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'startColor' => ['rgb' => '059669']
    ],
    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
]);

$datosExtra = [
    [1, 1, 'CORONEL PORTILLO', 'MR1', 'EESS Modelo', 'Callería', 'Coronel Portillo', 'Ucayali', 'SIS', 'Juntos'],
    [2, 2, 'CORONEL PORTILLO', 'MR1', 'EESS Modelo', 'Callería', 'Coronel Portillo', 'Ucayali', 'SIS', null],
    [3, 3, 'CORONEL PORTILLO', 'MR2', 'EESS Modelo', 'Callería', 'Coronel Portillo', 'Ucayali', 'SIS', 'Juntos'],
];

$sheet->fromArray($datosExtra, null, 'A2');
foreach (range('A', 'J') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

// ============================================
// HOJA 3: MADRE
// ============================================
$sheet = $spreadsheet->createSheet();
$sheet->setTitle('Madre');

$headers = [
    'id_madre',
    'id_niño',
    'dni',
    'apellidos_nombres',
    'celular',
    'domicilio',
    'referencia_direccion'
];

$sheet->fromArray([$headers], null, 'A1');
$sheet->getStyle('A1:G1')->applyFromArray([
    'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
    'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'startColor' => ['rgb' => '7c3aed']
    ],
    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
]);

$datosMadre = [
    [1, 1, '140000001', 'Madre1', '987654321', 'Jr. Perú 123', 'Jr. Los Cedros 145'],
    [2, 2, '140000002', 'Madre2', '987654322', 'Jr. Lima 456', null],
    [3, 3, '140000003', 'Madre3', '987654323', 'Jr. Arequipa 789', 'Cerca del mercado'],
];

$sheet->fromArray($datosMadre, null, 'A2');
foreach (range('A', 'G') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

// ============================================
// HOJA 4: CONTROLES RN
// ============================================
$sheet = $spreadsheet->createSheet();
$sheet->setTitle('Controles RN');

$headers = [
    'id_crn',
    'id_niño',
    'numero_control',
    'fecha',
    'peso',
    'talla',
    'perimetro_cefalico'
];

$sheet->fromArray([$headers], null, 'A1');
$sheet->getStyle('A1:G1')->applyFromArray([
    'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
    'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'startColor' => ['rgb' => 'dc2626']
    ],
    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
]);

$datosControlesRN = [
    [1, 1, 1, '2024-12-08', 3.5, 50.0, 35.0],
    [2, 1, 2, '2024-12-15', 3.6, 50.5, 35.5],
    [3, 2, 1, '2024-11-20', 3.4, 49.5, 34.5],
    [4, 3, 1, '2024-10-25', 3.3, 49.0, 34.0],
];

$sheet->fromArray($datosControlesRN, null, 'A2');
foreach (range('A', 'G') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

// ============================================
// HOJA 5: CONTROLES CRED (SIN PESO, TALLA, PC)
// ============================================
$sheet = $spreadsheet->createSheet();
$sheet->setTitle('Controles CRED');

$headers = [
    'id_control',
    'id_niño',
    'nro_control',
    'fecha_contro'
];

$sheet->fromArray([$headers], null, 'A1');
$sheet->getStyle('A1:D1')->applyFromArray([
    'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
    'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'startColor' => ['rgb' => 'ea580c']
    ],
    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
]);

$datosControlesCRED = [
    [1, 1, 1, '2024-12-06'],  // Mes 1 (29-59 días)
    [2, 1, 2, '2024-12-20'],  // Mes 2 (60-89 días)
    [3, 2, 1, '2024-11-25'],  // Mes 1
    [4, 3, 1, '2024-10-30'],  // Mes 1
];

$sheet->fromArray($datosControlesCRED, null, 'A2');
foreach (range('A', 'D') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

// ============================================
// HOJA 6: TAMIZAJE
// ============================================
$sheet = $spreadsheet->createSheet();
$sheet->setTitle('Tamizaje');

$headers = [
    'id_tamizaje',
    'id_niño',
    'numero_control',
    'fecha_tam_neo',
    'galen_fecha_tam_feo',
    'galen_dias'
];

$sheet->fromArray([$headers], null, 'A1');
$sheet->getStyle('A1:F1')->applyFromArray([
    'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
    'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'startColor' => ['rgb' => '0891b2']
    ],
    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
]);

$datosTamizaje = [
    [1, 1, 1, '2024-12-03', '2024-12-03', 30],
    [2, 2, 1, '2024-11-18', '2024-11-18', 30],
    [3, 3, 1, '2024-10-23', null, null],
];

$sheet->fromArray($datosTamizaje, null, 'A2');
foreach (range('A', 'F') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

// ============================================
// HOJA 7: VACUNAS
// ============================================
$sheet = $spreadsheet->createSheet();
$sheet->setTitle('Vacunas');

$headers = [
    'id_vacuna',
    'id_niño',
    'numero_control',
    'fecha_bcg',
    'fecha_hvb'
];

$sheet->fromArray([$headers], null, 'A1');
$sheet->getStyle('A1:E1')->applyFromArray([
    'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
    'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'startColor' => ['rgb' => '16a34a']
    ],
    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
]);

$datosVacunas = [
    [1, 1, 1, '2024-12-06', '2024-12-06'],
    [2, 2, 1, '2024-11-16', '2024-11-16'],
    [3, 3, 1, '2024-10-21', '2024-10-21'],
];

$sheet->fromArray($datosVacunas, null, 'A2');
foreach (range('A', 'E') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

// ============================================
// HOJA 8: VISITAS
// ============================================
$sheet = $spreadsheet->createSheet();
$sheet->setTitle('Visitas');

$headers = [
    'id_visita',
    'id_niño',
    'numero_control',
    'fecha_visita',
    'grupo_visita'
];

$sheet->fromArray([$headers], null, 'A1');
$sheet->getStyle('A1:E1')->applyFromArray([
    'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
    'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'startColor' => ['rgb' => 'c2410c']
    ],
    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
]);

$datosVisitas = [
    [1, 1, 1, '2025-01-02', 'A'],
    [2, 2, 1, '2024-12-10', 'B'],
    [3, 3, 1, '2024-11-15', 'A'],
];

$sheet->fromArray($datosVisitas, null, 'A2');
foreach (range('A', 'E') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

// ============================================
// HOJA 9: RECIEN NACIDO
// ============================================
$sheet = $spreadsheet->createSheet();
$sheet->setTitle('Recien Nacido');

$headers = [
    'id_rn',
    'id_niño',
    'peso',
    'edad_gestacional',
    'clasificacion'
];

$sheet->fromArray([$headers], null, 'A1');
$sheet->getStyle('A1:E1')->applyFromArray([
    'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
    'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'startColor' => ['rgb' => '9333ea']
    ],
    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
]);

$datosRecienNacido = [
    [1, 1, 3200, 38, '9 Normal'],
    [2, 2, 2800, 37, 'Bajo Peso al Nacer y/o Prematuro'],
    [3, 3, 3500, 39, '9 Normal'],
];

$sheet->fromArray($datosRecienNacido, null, 'A2');
foreach (range('A', 'E') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

// ============================================
// GUARDAR ARCHIVO
// ============================================
$writer = new Xlsx($spreadsheet);
$filename = 'ejemplo_importacion_completo.xlsx';
$writer->save($filename);

echo "✅ Archivo Excel creado exitosamente: {$filename}\n";
echo "📋 El archivo contiene las siguientes hojas:\n";
echo "   1. Niños (OBLIGATORIA)\n";
echo "   2. Datos Extra\n";
echo "   3. Madre\n";
echo "   4. Controles RN\n";
echo "   5. Controles CRED (sin peso, talla, perimetro_cefalico)\n";
echo "   6. Tamizaje\n";
echo "   7. Vacunas\n";
echo "   8. Visitas\n";
echo "   9. Recien Nacido\n";
echo "\n";
echo "📝 Notas importantes:\n";
echo "   - La hoja 'Niños' es OBLIGATORIA y debe ir primero\n";
echo "   - Todos los id_niño en las otras hojas deben existir en 'Niños'\n";
echo "   - Las fechas deben estar en formato YYYY-MM-DD\n";
echo "   - Los Controles CRED NO incluyen peso, talla ni perimetro_cefalico\n";
echo "\n";

