<?php

/**
 * Script para crear un archivo Excel con 1 niño que CUMPLE todos los controles
 * Todos los controles están dentro de los rangos permitidos
 * 
 * Uso: php crear_excel_1_nino_cumple.php
 */

require __DIR__ . '/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

// Crear nuevo spreadsheet
$spreadsheet = new Spreadsheet();

$hoy = new DateTime();
// Niño de aproximadamente 6 meses (180 días) para que pueda tener todos los controles
$fechaNacimiento = clone $hoy;
$fechaNacimiento->sub(new DateInterval('P180D')); // 180 días atrás

// ========== HOJA 1: NIÑOS ==========
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Niños');

// Encabezados
$headers = [
    'tipo_control',
    'tipo_doc',
    'numero_doc',
    'apellidos_nombres',
    'fecha_nacimiento',
    'genero',
    'establecimiento'
];

$sheet->fromArray($headers, null, 'A1');
$sheet->getStyle('A1:G1')->getFont()->setBold(true);
$sheet->getStyle('A1:G1')->getFill()
    ->setFillType(Fill::FILL_SOLID)
    ->getStartColor()->setARGB('FF4472C4');
$sheet->getStyle('A1:G1')->getFont()->getColor()->setARGB('FFFFFFFF');

// Datos del niño
$ninoData = [
    [
        'NINO', // tipo_control
        'DNI', // tipo_doc
        '12345678', // numero_doc
        'GARCÍA LÓPEZ, JUAN CARLOS', // apellidos_nombres
        $fechaNacimiento->format('Y-m-d'), // fecha_nacimiento
        'M', // genero
        'Centro de Salud Callería' // establecimiento
    ]
];

$sheet->fromArray($ninoData, null, 'A2');

foreach (range('A', 'G') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

// ========== HOJA 2: MADRES ==========
$sheet = $spreadsheet->createSheet();
$sheet->setTitle('Madres');

$headers = [
    'tipo_control',
    'numero_doc',
    'tipo_doc',
    'dni_madre',
    'apellidos_nombres_madre',
    'celular_madre',
    'domicilio_madre'
];

$sheet->fromArray($headers, null, 'A1');
$sheet->getStyle('A1:G1')->getFont()->setBold(true);
$sheet->getStyle('A1:G1')->getFill()
    ->setFillType(Fill::FILL_SOLID)
    ->getStartColor()->setARGB('FF4472C4');
$sheet->getStyle('A1:G1')->getFont()->getColor()->setARGB('FFFFFFFF');

$madreData = [
    [
        'MADRE', // tipo_control
        '12345678', // numero_doc del niño
        'DNI', // tipo_doc
        '87654321', // dni_madre
        'LÓPEZ MARTÍNEZ, MARÍA ELENA', // apellidos_nombres_madre
        '987654321', // celular_madre
        'Jr. Los Olivos 123, Callería' // domicilio_madre
    ]
];

$sheet->fromArray($madreData, null, 'A2');

foreach (range('A', 'G') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

// ========== HOJA 3: VACUNAS ==========
$sheet = $spreadsheet->createSheet();
$sheet->setTitle('Vacunas');

$headers = [
    'tipo_control',
    'numero_doc',
    'tipo_doc',
    'fecha_bcg',
    'fecha_hvb'
];

$sheet->fromArray($headers, null, 'A1');
$sheet->getStyle('A1:E1')->getFont()->setBold(true);
$sheet->getStyle('A1:E1')->getFill()
    ->setFillType(Fill::FILL_SOLID)
    ->getStartColor()->setARGB('FF4472C4');
$sheet->getStyle('A1:E1')->getFont()->getColor()->setARGB('FFFFFFFF');

// Vacunas aplicadas el día 0 y día 1 (CUMPLEN: 0-2 días)
$fechaBCG = clone $fechaNacimiento;
$fechaBCG->add(new DateInterval('P0D')); // Día 0

$fechaHVB = clone $fechaNacimiento;
$fechaHVB->add(new DateInterval('P1D')); // Día 1

$vacunasData = [
    [
        'VACUNAS', // tipo_control
        '12345678', // numero_doc
        'DNI', // tipo_doc
        $fechaBCG->format('Y-m-d'), // fecha_bcg (día 0 - CUMPLE)
        $fechaHVB->format('Y-m-d') // fecha_hvb (día 1 - CUMPLE)
    ]
];

$sheet->fromArray($vacunasData, null, 'A2');

foreach (range('A', 'E') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

// ========== HOJA 4: TAMIZAJE ==========
$sheet = $spreadsheet->createSheet();
$sheet->setTitle('Tamizaje');

$headers = [
    'tipo_control',
    'numero_doc',
    'tipo_doc',
    'fecha_tamizaje',
    'galen_fecha'
];

$sheet->fromArray($headers, null, 'A1');
$sheet->getStyle('A1:E1')->getFont()->setBold(true);
$sheet->getStyle('A1:E1')->getFill()
    ->setFillType(Fill::FILL_SOLID)
    ->getStartColor()->setARGB('FF4472C4');
$sheet->getStyle('A1:E1')->getFont()->getColor()->setARGB('FFFFFFFF');

// Tamizaje realizado el día 5 (CUMPLE: 1-29 días)
$fechaTamizaje = clone $fechaNacimiento;
$fechaTamizaje->add(new DateInterval('P5D')); // Día 5

// Tamizaje Galen realizado el día 7 (CUMPLE: 1-29 días)
$fechaGalen = clone $fechaNacimiento;
$fechaGalen->add(new DateInterval('P7D')); // Día 7

$tamizajesData = [
    [
        'TAMIZAJE', // tipo_control
        '12345678', // numero_doc
        'DNI', // tipo_doc
        $fechaTamizaje->format('Y-m-d'), // fecha_tamizaje (día 5 - CUMPLE)
        $fechaGalen->format('Y-m-d') // galen_fecha (día 7 - CUMPLE)
    ]
];

$sheet->fromArray($tamizajesData, null, 'A2');

foreach (range('A', 'E') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

// ========== HOJA 5: VISITAS DOMICILIARIAS ==========
$sheet = $spreadsheet->createSheet();
$sheet->setTitle('Visitas');

$headers = [
    'tipo_control',
    'numero_doc',
    'tipo_doc',
    'control_de_visita',
    'fecha_visita'
];

$sheet->fromArray($headers, null, 'A1');
$sheet->getStyle('A1:E1')->getFont()->setBold(true);
$sheet->getStyle('A1:E1')->getFill()
    ->setFillType(Fill::FILL_SOLID)
    ->getStartColor()->setARGB('FF4472C4');
$sheet->getStyle('A1:E1')->getFont()->getColor()->setARGB('FFFFFFFF');

// Visitas - TODAS CUMPLEN
$visitasData = [];

// Control 1: 28-30 días (CUMPLE: día 29)
$fechaVisita1 = clone $fechaNacimiento;
$fechaVisita1->add(new DateInterval('P29D')); // Día 29 - dentro del rango 28-30
$visitasData[] = [
    'VISITA',
    '12345678',
    'DNI',
    '1',
    $fechaVisita1->format('Y-m-d')
];

// Control 2: 60-150 días (CUMPLE: día 90)
$fechaVisita2 = clone $fechaNacimiento;
$fechaVisita2->add(new DateInterval('P90D')); // Día 90 - dentro del rango 60-150
$visitasData[] = [
    'VISITA',
    '12345678',
    'DNI',
    '2',
    $fechaVisita2->format('Y-m-d')
];

// Control 3: 180-240 días (CUMPLE: día 200)
$fechaVisita3 = clone $fechaNacimiento;
$fechaVisita3->add(new DateInterval('P200D')); // Día 200 - dentro del rango 180-240
$visitasData[] = [
    'VISITA',
    '12345678',
    'DNI',
    '3',
    $fechaVisita3->format('Y-m-d')
];

$sheet->fromArray($visitasData, null, 'A2');

foreach (range('A', 'E') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

// ========== HOJA 6: CONTROLES RECIÉN NACIDO ==========
$sheet = $spreadsheet->createSheet();
$sheet->setTitle('Controles RN');

$headers = [
    'tipo_control',
    'numero_doc',
    'tipo_doc',
    'numero_control',
    'fecha'
];

$sheet->fromArray($headers, null, 'A1');
$sheet->getStyle('A1:E1')->getFont()->setBold(true);
$sheet->getStyle('A1:E1')->getFill()
    ->setFillType(Fill::FILL_SOLID)
    ->getStartColor()->setARGB('FF4472C4');
$sheet->getStyle('A1:E1')->getFont()->getColor()->setARGB('FFFFFFFF');

// Controles RN - TODOS CUMPLEN
$controlesRnData = [];

// Control 1: 2-6 días (CUMPLE: día 4)
$fechaControl1 = clone $fechaNacimiento;
$fechaControl1->add(new DateInterval('P4D'));
$controlesRnData[] = [
    'CRN',
    '12345678',
    'DNI',
    '1',
    $fechaControl1->format('Y-m-d')
];

// Control 2: 7-13 días (CUMPLE: día 10)
$fechaControl2 = clone $fechaNacimiento;
$fechaControl2->add(new DateInterval('P10D'));
$controlesRnData[] = [
    'CRN',
    '12345678',
    'DNI',
    '2',
    $fechaControl2->format('Y-m-d')
];

// Control 3: 14-20 días (CUMPLE: día 17)
$fechaControl3 = clone $fechaNacimiento;
$fechaControl3->add(new DateInterval('P17D'));
$controlesRnData[] = [
    'CRN',
    '12345678',
    'DNI',
    '3',
    $fechaControl3->format('Y-m-d')
];

// Control 4: 21-28 días (CUMPLE: día 25)
$fechaControl4 = clone $fechaNacimiento;
$fechaControl4->add(new DateInterval('P25D'));
$controlesRnData[] = [
    'CRN',
    '12345678',
    'DNI',
    '4',
    $fechaControl4->format('Y-m-d')
];

$sheet->fromArray($controlesRnData, null, 'A2');

foreach (range('A', 'E') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

// ========== HOJA 7: CONTROLES CRED MENSUAL ==========
$sheet = $spreadsheet->createSheet();
$sheet->setTitle('Controles CRED');

$headers = [
    'tipo_control',
    'numero_doc',
    'tipo_doc',
    'numero_control',
    'fecha'
];

$sheet->fromArray($headers, null, 'A1');
$sheet->getStyle('A1:E1')->getFont()->setBold(true);
$sheet->getStyle('A1:E1')->getFill()
    ->setFillType(Fill::FILL_SOLID)
    ->getStartColor()->setARGB('FF4472C4');
$sheet->getStyle('A1:E1')->getFont()->getColor()->setARGB('FFFFFFFF');

// Controles CRED - TODOS CUMPLEN
$controlesCredData = [];

// Control 1: 29-59 días (CUMPLE: día 45)
$fechaCred1 = clone $fechaNacimiento;
$fechaCred1->add(new DateInterval('P45D'));
$controlesCredData[] = [
    'CRED',
    '12345678',
    'DNI',
    '1',
    $fechaCred1->format('Y-m-d')
];

// Control 2: 60-89 días (CUMPLE: día 75)
$fechaCred2 = clone $fechaNacimiento;
$fechaCred2->add(new DateInterval('P75D'));
$controlesCredData[] = [
    'CRED',
    '12345678',
    'DNI',
    '2',
    $fechaCred2->format('Y-m-d')
];

// Control 3: 90-119 días (CUMPLE: día 105)
$fechaCred3 = clone $fechaNacimiento;
$fechaCred3->add(new DateInterval('P105D'));
$controlesCredData[] = [
    'CRED',
    '12345678',
    'DNI',
    '3',
    $fechaCred3->format('Y-m-d')
];

// Control 4: 120-149 días (CUMPLE: día 135)
$fechaCred4 = clone $fechaNacimiento;
$fechaCred4->add(new DateInterval('P135D'));
$controlesCredData[] = [
    'CRED',
    '12345678',
    'DNI',
    '4',
    $fechaCred4->format('Y-m-d')
];

// Control 5: 150-179 días (CUMPLE: día 165)
$fechaCred5 = clone $fechaNacimiento;
$fechaCred5->add(new DateInterval('P165D'));
$controlesCredData[] = [
    'CRED',
    '12345678',
    'DNI',
    '5',
    $fechaCred5->format('Y-m-d')
];

// Control 6: 180-209 días (CUMPLE: día 190)
$fechaCred6 = clone $fechaNacimiento;
$fechaCred6->add(new DateInterval('P190D'));
$controlesCredData[] = [
    'CRED',
    '12345678',
    'DNI',
    '6',
    $fechaCred6->format('Y-m-d')
];

$sheet->fromArray($controlesCredData, null, 'A2');

foreach (range('A', 'E') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

// ========== HOJA 8: RECIÉN NACIDO (CNV) ==========
$sheet = $spreadsheet->createSheet();
$sheet->setTitle('Recién Nacido');

$headers = [
    'tipo_control',
    'numero_doc',
    'tipo_doc',
    'peso_nacer',
    'edad_gestacional',
    'clasificacion'
];

$sheet->fromArray($headers, null, 'A1');
$sheet->getStyle('A1:F1')->getFont()->setBold(true);
$sheet->getStyle('A1:F1')->getFill()
    ->setFillType(Fill::FILL_SOLID)
    ->getStartColor()->setARGB('FF4472C4');
$sheet->getStyle('A1:F1')->getFont()->getColor()->setARGB('FFFFFFFF');

// CNV - Datos del recién nacido
$cnvData = [
    [
        'CNV', // tipo_control
        '12345678', // numero_doc
        'DNI', // tipo_doc
        '3.2', // peso_nacer (en kg - 3200 gramos)
        '38', // edad_gestacional (semanas)
        'normal' // clasificacion
    ]
];

$sheet->fromArray($cnvData, null, 'A2');

foreach (range('A', 'F') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

// Guardar archivo
$writer = new Xlsx($spreadsheet);
$filename = 'importacion_1_nino_cumple_' . date('Y-m-d_His') . '.xlsx';
$writer->save($filename);

echo "✅ Archivo Excel creado exitosamente: {$filename}\n";
echo "\n";
echo "📊 Datos del niño:\n";
echo "   - Nombre: GARCÍA LÓPEZ, JUAN CARLOS\n";
echo "   - DNI: 12345678\n";
echo "   - Fecha de Nacimiento: " . $fechaNacimiento->format('Y-m-d') . " (hace ~6 meses)\n";
echo "   - Género: Masculino\n";
echo "\n";
echo "✅ Todos los controles CUMPLEN con los rangos:\n";
echo "   - Vacunas: BCG (día 0), HVB (día 1) - Rango 0-2 días ✅\n";
echo "   - Tamizaje: Neonatal (día 5), Galen (día 7) - Rango 1-29 días ✅\n";
echo "   - Visitas: Control 1 (día 29), Control 2 (día 90), Control 3 (día 200) ✅\n";
echo "   - Controles RN: Control 1 (día 4), Control 2 (día 10), Control 3 (día 17), Control 4 (día 25) ✅\n";
echo "   - Controles CRED: Controles 1-6 dentro de sus rangos ✅\n";
echo "   - CNV: Peso 3.2 kg, Edad gestacional 38 semanas ✅\n";
echo "\n";
echo "📝 Hojas incluidas:\n";
echo "   1. Niños\n";
echo "   2. Madres\n";
echo "   3. Vacunas\n";
echo "   4. Tamizaje\n";
echo "   5. Visitas\n";
echo "   6. Controles RN\n";
echo "   7. Controles CRED\n";
echo "   8. Recién Nacido (CNV)\n";
echo "\n";
echo "💡 Este archivo es perfecto para verificar que el sistema muestra 'CUMPLE'\n";
echo "   en todos los controles cuando están dentro de los rangos permitidos.\n";

