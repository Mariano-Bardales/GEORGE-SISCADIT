<?php

/**
 * Script para crear un archivo Excel con 1 niño completo
 * Todos los controles están MAL (fuera de rango) EXCEPTO los controles de recién nacido
 * 
 * Uso: php crear_excel_nino_controles_malos.php
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
        '99999999', // numero_doc
        'RODRÍGUEZ MARTÍNEZ, ANA SOFÍA', // apellidos_nombres
        $fechaNacimiento->format('Y-m-d'), // fecha_nacimiento
        'F', // genero
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
        '99999999', // numero_doc del niño
        'DNI', // tipo_doc
        '88888888', // dni_madre
        'MARTÍNEZ LÓPEZ, MARÍA ELENA', // apellidos_nombres_madre
        '987654321', // celular_madre
        'Jr. Los Olivos 456, Callería' // domicilio_madre
    ]
];

$sheet->fromArray($madreData, null, 'A2');

foreach (range('A', 'G') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

// ========== HOJA 3: DATOS EXTRA ==========
$sheet = $spreadsheet->createSheet();
$sheet->setTitle('Datos Extra');

$headers = [
    'tipo_control',
    'numero_doc',
    'tipo_doc',
    'red',
    'microred',
    'eess_nacimiento',
    'distrito',
    'provincia',
    'departamento',
    'seguro',
    'programa'
];

$sheet->fromArray($headers, null, 'A1');
$sheet->getStyle('A1:K1')->getFont()->setBold(true);
$sheet->getStyle('A1:K1')->getFill()
    ->setFillType(Fill::FILL_SOLID)
    ->getStartColor()->setARGB('FF4472C4');
$sheet->getStyle('A1:K1')->getFont()->getColor()->setARGB('FFFFFFFF');

$datosExtraData = [
    [
        'DATOS EXTRA', // tipo_control
        '99999999', // numero_doc
        'DNI', // tipo_doc
        'HOSPITAL REGIONAL DE PUCALLPA', // red
        'Microred Centro', // microred
        'Centro de Salud Callería', // eess_nacimiento
        'Callería', // distrito
        'Coronel Portillo', // provincia
        'Ucayali', // departamento
        'SIS', // seguro
        'CRED' // programa
    ]
];

$sheet->fromArray($datosExtraData, null, 'A2');

foreach (range('A', 'K') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

// ========== HOJA 4: RECIÉN NACIDO (CNV) ==========
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
        '99999999', // numero_doc
        'DNI', // tipo_doc
        '3.1', // peso_nacer (en kg - 3100 gramos)
        '38', // edad_gestacional (semanas)
        'normal' // clasificacion
    ]
];

$sheet->fromArray($cnvData, null, 'A2');

foreach (range('A', 'F') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

// ========== HOJA 5: CONTROLES RECIÉN NACIDO (TODOS CUMPLEN) ==========
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

// Controles RN - TODOS CUMPLEN (dentro del rango)
$controlesRnData = [];

// Control 1: 2-6 días (CUMPLE: día 4)
$fechaControl1 = clone $fechaNacimiento;
$fechaControl1->add(new DateInterval('P4D'));
$controlesRnData[] = [
    'CRN',
    '99999999',
    'DNI',
    '1',
    $fechaControl1->format('Y-m-d')
];

// Control 2: 7-13 días (CUMPLE: día 10)
$fechaControl2 = clone $fechaNacimiento;
$fechaControl2->add(new DateInterval('P10D'));
$controlesRnData[] = [
    'CRN',
    '99999999',
    'DNI',
    '2',
    $fechaControl2->format('Y-m-d')
];

// Control 3: 14-20 días (CUMPLE: día 17)
$fechaControl3 = clone $fechaNacimiento;
$fechaControl3->add(new DateInterval('P17D'));
$controlesRnData[] = [
    'CRN',
    '99999999',
    'DNI',
    '3',
    $fechaControl3->format('Y-m-d')
];

// Control 4: 21-28 días (CUMPLE: día 25)
$fechaControl4 = clone $fechaNacimiento;
$fechaControl4->add(new DateInterval('P25D'));
$controlesRnData[] = [
    'CRN',
    '99999999',
    'DNI',
    '4',
    $fechaControl4->format('Y-m-d')
];

$sheet->fromArray($controlesRnData, null, 'A2');

foreach (range('A', 'E') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

// ========== HOJA 6: CONTROLES CRED (TODOS NO CUMPLEN) ==========
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

// Controles CRED - TODOS NO CUMPLEN (fuera del rango)
$controlesCredData = [];

// Control 1: Rango 29-59 días, pero lo hacemos a los 65 días (NO CUMPLE)
$fechaCred1 = clone $fechaNacimiento;
$fechaCred1->add(new DateInterval('P65D')); // Fuera del rango (debería ser 29-59)
$controlesCredData[] = [
    'CRED',
    '99999999',
    'DNI',
    '1',
    $fechaCred1->format('Y-m-d')
];

// Control 2: Rango 60-89 días, pero lo hacemos a los 95 días (NO CUMPLE)
$fechaCred2 = clone $fechaNacimiento;
$fechaCred2->add(new DateInterval('P95D')); // Fuera del rango (debería ser 60-89)
$controlesCredData[] = [
    'CRED',
    '99999999',
    'DNI',
    '2',
    $fechaCred2->format('Y-m-d')
];

// Control 3: Rango 90-119 días, pero lo hacemos a los 130 días (NO CUMPLE)
$fechaCred3 = clone $fechaNacimiento;
$fechaCred3->add(new DateInterval('P130D')); // Fuera del rango (debería ser 90-119)
$controlesCredData[] = [
    'CRED',
    '99999999',
    'DNI',
    '3',
    $fechaCred3->format('Y-m-d')
];

// Control 4: Rango 120-149 días, pero lo hacemos a los 160 días (NO CUMPLE)
$fechaCred4 = clone $fechaNacimiento;
$fechaCred4->add(new DateInterval('P160D')); // Fuera del rango (debería ser 120-149)
$controlesCredData[] = [
    'CRED',
    '99999999',
    'DNI',
    '4',
    $fechaCred4->format('Y-m-d')
];

// Control 5: Rango 150-179 días, pero lo hacemos a los 190 días (NO CUMPLE)
$fechaCred5 = clone $fechaNacimiento;
$fechaCred5->add(new DateInterval('P190D')); // Fuera del rango (debería ser 150-179)
$controlesCredData[] = [
    'CRED',
    '99999999',
    'DNI',
    '5',
    $fechaCred5->format('Y-m-d')
];

// Control 6: Rango 180-209 días, pero lo hacemos a los 220 días (NO CUMPLE)
$fechaCred6 = clone $fechaNacimiento;
$fechaCred6->add(new DateInterval('P220D')); // Fuera del rango (debería ser 180-209)
$controlesCredData[] = [
    'CRED',
    '99999999',
    'DNI',
    '6',
    $fechaCred6->format('Y-m-d')
];

$sheet->fromArray($controlesCredData, null, 'A2');

foreach (range('A', 'E') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

// ========== HOJA 7: VISITAS DOMICILIARIAS (TODAS NO CUMPLEN) ==========
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

// Visitas - TODAS NO CUMPLEN (fuera del rango)
$visitasData = [];

// Control 1: Rango 28-30 días, pero lo hacemos a los 35 días (NO CUMPLE)
$fechaVisita1 = clone $fechaNacimiento;
$fechaVisita1->add(new DateInterval('P35D')); // Fuera del rango (debería ser 28-30)
$visitasData[] = [
    'VISITA',
    '99999999',
    'DNI',
    '1',
    $fechaVisita1->format('Y-m-d')
];

// Control 2: Rango 60-150 días, pero lo hacemos a los 155 días (NO CUMPLE)
$fechaVisita2 = clone $fechaNacimiento;
$fechaVisita2->add(new DateInterval('P155D')); // Fuera del rango (debería ser 60-150)
$visitasData[] = [
    'VISITA',
    '99999999',
    'DNI',
    '2',
    $fechaVisita2->format('Y-m-d')
];

// Control 3: Rango 180-240 días, pero lo hacemos a los 250 días (NO CUMPLE)
$fechaVisita3 = clone $fechaNacimiento;
$fechaVisita3->add(new DateInterval('P250D')); // Fuera del rango (debería ser 180-240)
$visitasData[] = [
    'VISITA',
    '99999999',
    'DNI',
    '3',
    $fechaVisita3->format('Y-m-d')
];

$sheet->fromArray($visitasData, null, 'A2');

foreach (range('A', 'E') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

// ========== HOJA 8: VACUNAS (NO CUMPLEN) ==========
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

// Vacunas aplicadas fuera del rango (NO CUMPLEN: deberían ser 0-2 días)
// BCG aplicada el día 5 (NO CUMPLE: debería ser 0-2 días)
$fechaBCG = clone $fechaNacimiento;
$fechaBCG->add(new DateInterval('P5D')); // Día 5 - fuera del rango 0-2

// HVB aplicada el día 4 (NO CUMPLE: debería ser 0-2 días)
$fechaHVB = clone $fechaNacimiento;
$fechaHVB->add(new DateInterval('P4D')); // Día 4 - fuera del rango 0-2

$vacunasData = [
    [
        'VACUNAS', // tipo_control
        '99999999', // numero_doc
        'DNI', // tipo_doc
        $fechaBCG->format('Y-m-d'), // fecha_bcg (día 5 - NO CUMPLE)
        $fechaHVB->format('Y-m-d') // fecha_hvb (día 4 - NO CUMPLE)
    ]
];

$sheet->fromArray($vacunasData, null, 'A2');

foreach (range('A', 'E') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

// ========== HOJA 9: TAMIZAJE (NO CUMPLE) ==========
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

// Tamizaje realizado fuera del rango (NO CUMPLE: debería ser 1-29 días)
// Tamizaje Neonatal realizado el día 35 (NO CUMPLE: debería ser 1-29 días)
$fechaTamizaje = clone $fechaNacimiento;
$fechaTamizaje->add(new DateInterval('P35D')); // Día 35 - fuera del rango 1-29

// Tamizaje Galen realizado el día 40 (NO CUMPLE: debería ser 1-29 días)
$fechaGalen = clone $fechaNacimiento;
$fechaGalen->add(new DateInterval('P40D')); // Día 40 - fuera del rango 1-29

$tamizajesData = [
    [
        'TAMIZAJE', // tipo_control
        '99999999', // numero_doc
        'DNI', // tipo_doc
        $fechaTamizaje->format('Y-m-d'), // fecha_tamizaje (día 35 - NO CUMPLE)
        $fechaGalen->format('Y-m-d') // galen_fecha (día 40 - NO CUMPLE)
    ]
];

$sheet->fromArray($tamizajesData, null, 'A2');

foreach (range('A', 'E') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

// Guardar archivo
$writer = new Xlsx($spreadsheet);
$filename = 'importacion_nino_controles_malos_' . date('Y-m-d_His') . '.xlsx';
$writer->save($filename);

echo "✅ Archivo Excel creado exitosamente: {$filename}\n";
echo "\n";
echo "📊 Datos del niño:\n";
echo "   - Nombre: RODRÍGUEZ MARTÍNEZ, ANA SOFÍA\n";
echo "   - DNI: 99999999\n";
echo "   - Fecha de Nacimiento: " . $fechaNacimiento->format('Y-m-d') . " (hace ~6 meses)\n";
echo "   - Género: Femenino\n";
echo "\n";
echo "✅ Controles de Recién Nacido (CUMPLEN):\n";
echo "   - Control 1: día 4 (rango 2-6 días) ✅\n";
echo "   - Control 2: día 10 (rango 7-13 días) ✅\n";
echo "   - Control 3: día 17 (rango 14-20 días) ✅\n";
echo "   - Control 4: día 25 (rango 21-28 días) ✅\n";
echo "\n";
echo "❌ Controles CRED (NO CUMPLEN - fuera de rango):\n";
echo "   - Control 1: día 65 (rango 29-59 días) ❌\n";
echo "   - Control 2: día 95 (rango 60-89 días) ❌\n";
echo "   - Control 3: día 130 (rango 90-119 días) ❌\n";
echo "   - Control 4: día 160 (rango 120-149 días) ❌\n";
echo "   - Control 5: día 190 (rango 150-179 días) ❌\n";
echo "   - Control 6: día 220 (rango 180-209 días) ❌\n";
echo "\n";
echo "❌ Visitas Domiciliarias (NO CUMPLEN - fuera de rango):\n";
echo "   - Control 1: día 35 (rango 28-30 días) ❌\n";
echo "   - Control 2: día 155 (rango 60-150 días) ❌\n";
echo "   - Control 3: día 250 (rango 180-240 días) ❌\n";
echo "\n";
echo "❌ Vacunas (NO CUMPLEN - fuera de rango):\n";
echo "   - BCG: día 5 (rango 0-2 días) ❌\n";
echo "   - HVB: día 4 (rango 0-2 días) ❌\n";
echo "\n";
echo "❌ Tamizajes (NO CUMPLEN - fuera de rango):\n";
echo "   - Tamizaje Neonatal: día 35 (rango 1-29 días) ❌\n";
echo "   - Tamizaje Galen: día 40 (rango 1-29 días) ❌\n";
echo "\n";
echo "📝 Hojas incluidas:\n";
echo "   1. Niños\n";
echo "   2. Madres\n";
echo "   3. Datos Extra\n";
echo "   4. Recién Nacido (CNV)\n";
echo "   5. Controles RN (todos cumplen)\n";
echo "   6. Controles CRED (todos no cumplen)\n";
echo "   7. Visitas (todas no cumplen)\n";
echo "   8. Vacunas (no cumplen)\n";
echo "   9. Tamizaje (no cumple)\n";
echo "\n";
echo "💡 Este archivo es ideal para probar cómo el sistema maneja controles fuera de rango.\n";

