<?php

/**
 * Script para crear un archivo Excel con 1 niño completo
 * TODOS los controles están fuera de rango (sobrepasan el límite) para mostrar "NO CUMPLE"
 * 
 * Uso: php crear_excel_1_nino_todos_no_cumplen.php
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
// Niño de aproximadamente 11 meses (330 días) para que pueda tener todos los controles
$fechaNacimiento = clone $hoy;
$fechaNacimiento->sub(new DateInterval('P330D')); // 330 días atrás

// ========== HOJA 1: Niños ==========
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Niños');

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

$ninoData = [
    [
        'NINO',
        'DNI',
        '88888888',
        'GARCÍA LÓPEZ, CARLOS ANTONIO',
        $fechaNacimiento->format('Y-m-d'),
        'M',
        'Centro de Salud Callería'
    ]
];

$sheet->fromArray($ninoData, null, 'A2');
foreach (range('A', 'G') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

// ========== HOJA 2: Madres ==========
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
        'MADRE',
        '88888888',
        'DNI',
        '77777777',
        'LÓPEZ MARTÍNEZ, MARÍA ELENA',
        '987654321',
        'Jr. Los Olivos 789, Callería'
    ]
];

$sheet->fromArray($madreData, null, 'A2');
foreach (range('A', 'G') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

// ========== HOJA 3: Datos Extra ==========
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
        'DATOS EXTRA',
        '88888888',
        'DNI',
        'HOSPITAL REGIONAL DE PUCALLPA',
        'Microred Centro',
        'Centro de Salud Callería',
        'Callería',
        'Coronel Portillo',
        'Ucayali',
        'SIS',
        'CRED'
    ]
];

$sheet->fromArray($datosExtraData, null, 'A2');
foreach (range('A', 'K') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

// ========== HOJA 4: Recién Nacido (CNV) ==========
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

$cnvData = [
    [
        'CNV',
        '88888888',
        'DNI',
        '3.2',
        '38',
        'normal'
    ]
];

$sheet->fromArray($cnvData, null, 'A2');
foreach (range('A', 'F') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

// ========== HOJA 5: Controles RN (TODOS FUERA DE RANGO) ==========
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

$controlesRnData = [];

// Control 1: Rango 2-6 días, pero lo hacemos a los 10 días (SOBREPASA el límite)
$fechaControl1 = clone $fechaNacimiento;
$fechaControl1->add(new DateInterval('P10D')); // Día 10 - sobrepasa el límite de 6
$controlesRnData[] = [
    'CRN',
    '88888888',
    'DNI',
    '1',
    $fechaControl1->format('Y-m-d')
];

// Control 2: Rango 7-13 días, pero lo hacemos a los 18 días (SOBREPASA el límite)
$fechaControl2 = clone $fechaNacimiento;
$fechaControl2->add(new DateInterval('P18D')); // Día 18 - sobrepasa el límite de 13
$controlesRnData[] = [
    'CRN',
    '88888888',
    'DNI',
    '2',
    $fechaControl2->format('Y-m-d')
];

// Control 3: Rango 14-20 días, pero lo hacemos a los 25 días (SOBREPASA el límite)
$fechaControl3 = clone $fechaNacimiento;
$fechaControl3->add(new DateInterval('P25D')); // Día 25 - sobrepasa el límite de 20
$controlesRnData[] = [
    'CRN',
    '88888888',
    'DNI',
    '3',
    $fechaControl3->format('Y-m-d')
];

// Control 4: Rango 21-28 días, pero lo hacemos a los 35 días (SOBREPASA el límite)
$fechaControl4 = clone $fechaNacimiento;
$fechaControl4->add(new DateInterval('P35D')); // Día 35 - sobrepasa el límite de 28
$controlesRnData[] = [
    'CRN',
    '88888888',
    'DNI',
    '4',
    $fechaControl4->format('Y-m-d')
];

$sheet->fromArray($controlesRnData, null, 'A2');
foreach (range('A', 'E') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

// ========== HOJA 6: Controles CRED (TODOS FUERA DE RANGO) ==========
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

$controlesCredData = [];

// Control 1: Rango 29-59 días, pero lo hacemos a los 65 días (SOBREPASA el límite)
$fechaCred1 = clone $fechaNacimiento;
$fechaCred1->add(new DateInterval('P65D')); // Día 65 - sobrepasa el límite de 59
$controlesCredData[] = ['CRED', '88888888', 'DNI', '1', $fechaCred1->format('Y-m-d')];

// Control 2: Rango 60-89 días, pero lo hacemos a los 95 días (SOBREPASA el límite)
$fechaCred2 = clone $fechaNacimiento;
$fechaCred2->add(new DateInterval('P95D')); // Día 95 - sobrepasa el límite de 89
$controlesCredData[] = ['CRED', '88888888', 'DNI', '2', $fechaCred2->format('Y-m-d')];

// Control 3: Rango 90-119 días, pero lo hacemos a los 130 días (SOBREPASA el límite)
$fechaCred3 = clone $fechaNacimiento;
$fechaCred3->add(new DateInterval('P130D')); // Día 130 - sobrepasa el límite de 119
$controlesCredData[] = ['CRED', '88888888', 'DNI', '3', $fechaCred3->format('Y-m-d')];

// Control 4: Rango 120-149 días, pero lo hacemos a los 160 días (SOBREPASA el límite)
$fechaCred4 = clone $fechaNacimiento;
$fechaCred4->add(new DateInterval('P160D')); // Día 160 - sobrepasa el límite de 149
$controlesCredData[] = ['CRED', '88888888', 'DNI', '4', $fechaCred4->format('Y-m-d')];

// Control 5: Rango 150-179 días, pero lo hacemos a los 190 días (SOBREPASA el límite)
$fechaCred5 = clone $fechaNacimiento;
$fechaCred5->add(new DateInterval('P190D')); // Día 190 - sobrepasa el límite de 179
$controlesCredData[] = ['CRED', '88888888', 'DNI', '5', $fechaCred5->format('Y-m-d')];

// Control 6: Rango 180-209 días, pero lo hacemos a los 220 días (SOBREPASA el límite)
$fechaCred6 = clone $fechaNacimiento;
$fechaCred6->add(new DateInterval('P220D')); // Día 220 - sobrepasa el límite de 209
$controlesCredData[] = ['CRED', '88888888', 'DNI', '6', $fechaCred6->format('Y-m-d')];

// Control 7: Rango 210-239 días, pero lo hacemos a los 250 días (SOBREPASA el límite)
$fechaCred7 = clone $fechaNacimiento;
$fechaCred7->add(new DateInterval('P250D')); // Día 250 - sobrepasa el límite de 239
$controlesCredData[] = ['CRED', '88888888', 'DNI', '7', $fechaCred7->format('Y-m-d')];

// Control 8: Rango 240-269 días, pero lo hacemos a los 280 días (SOBREPASA el límite)
$fechaCred8 = clone $fechaNacimiento;
$fechaCred8->add(new DateInterval('P280D')); // Día 280 - sobrepasa el límite de 269
$controlesCredData[] = ['CRED', '88888888', 'DNI', '8', $fechaCred8->format('Y-m-d')];

// Control 9: Rango 270-299 días, pero lo hacemos a los 310 días (SOBREPASA el límite)
$fechaCred9 = clone $fechaNacimiento;
$fechaCred9->add(new DateInterval('P310D')); // Día 310 - sobrepasa el límite de 299
$controlesCredData[] = ['CRED', '88888888', 'DNI', '9', $fechaCred9->format('Y-m-d')];

// Control 10: Rango 300-329 días, pero lo hacemos a los 340 días (SOBREPASA el límite)
$fechaCred10 = clone $fechaNacimiento;
$fechaCred10->add(new DateInterval('P340D')); // Día 340 - sobrepasa el límite de 329
$controlesCredData[] = ['CRED', '88888888', 'DNI', '10', $fechaCred10->format('Y-m-d')];

// Control 11: Rango 330-359 días, pero lo hacemos a los 370 días (SOBREPASA el límite)
$fechaCred11 = clone $fechaNacimiento;
$fechaCred11->add(new DateInterval('P370D')); // Día 370 - sobrepasa el límite de 359
$controlesCredData[] = ['CRED', '88888888', 'DNI', '11', $fechaCred11->format('Y-m-d')];

$sheet->fromArray($controlesCredData, null, 'A2');
foreach (range('A', 'E') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

// ========== HOJA 7: Visitas Domiciliarias (TODAS FUERA DE RANGO) ==========
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

$visitasData = [];

// Control 1: Rango 28-30 días, pero lo hacemos a los 35 días (SOBREPASA el límite)
$fechaVisita1 = clone $fechaNacimiento;
$fechaVisita1->add(new DateInterval('P35D')); // Día 35 - sobrepasa el límite de 30
$visitasData[] = ['VISITA', '88888888', 'DNI', '1', $fechaVisita1->format('Y-m-d')];

// Control 2: Rango 60-150 días, pero lo hacemos a los 155 días (SOBREPASA el límite)
$fechaVisita2 = clone $fechaNacimiento;
$fechaVisita2->add(new DateInterval('P155D')); // Día 155 - sobrepasa el límite de 150
$visitasData[] = ['VISITA', '88888888', 'DNI', '2', $fechaVisita2->format('Y-m-d')];

// Control 3: Rango 180-240 días, pero lo hacemos a los 250 días (SOBREPASA el límite)
$fechaVisita3 = clone $fechaNacimiento;
$fechaVisita3->add(new DateInterval('P250D')); // Día 250 - sobrepasa el límite de 240
$visitasData[] = ['VISITA', '88888888', 'DNI', '3', $fechaVisita3->format('Y-m-d')];

// Control 4: Rango 270-330 días, pero lo hacemos a los 340 días (SOBREPASA el límite)
$fechaVisita4 = clone $fechaNacimiento;
$fechaVisita4->add(new DateInterval('P340D')); // Día 340 - sobrepasa el límite de 330
$visitasData[] = ['VISITA', '88888888', 'DNI', '4', $fechaVisita4->format('Y-m-d')];

$sheet->fromArray($visitasData, null, 'A2');
foreach (range('A', 'E') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

// ========== HOJA 8: Vacunas (FUERA DE RANGO) ==========
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

// Vacunas aplicadas fuera del rango (SOBREPASAN el límite de 0-2 días)
// BCG aplicada el día 5 (SOBREPASA el límite de 2 días)
$fechaBCG = clone $fechaNacimiento;
$fechaBCG->add(new DateInterval('P5D')); // Día 5 - sobrepasa el límite de 2

// HVB aplicada el día 4 (SOBREPASA el límite de 2 días)
$fechaHVB = clone $fechaNacimiento;
$fechaHVB->add(new DateInterval('P4D')); // Día 4 - sobrepasa el límite de 2

$vacunasData = [
    [
        'VACUNAS',
        '88888888',
        'DNI',
        $fechaBCG->format('Y-m-d'),
        $fechaHVB->format('Y-m-d')
    ]
];

$sheet->fromArray($vacunasData, null, 'A2');
foreach (range('A', 'E') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

// ========== HOJA 9: Tamizaje (FUERA DE RANGO) ==========
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

// Tamizajes realizados fuera del rango (SOBREPASAN el límite de 1-29 días)
// Tamizaje Neonatal realizado el día 35 (SOBREPASA el límite de 29 días)
$fechaTamizaje = clone $fechaNacimiento;
$fechaTamizaje->add(new DateInterval('P35D')); // Día 35 - sobrepasa el límite de 29

// Tamizaje Galen realizado el día 40 (SOBREPASA el límite de 29 días)
$fechaGalen = clone $fechaNacimiento;
$fechaGalen->add(new DateInterval('P40D')); // Día 40 - sobrepasa el límite de 29

$tamizajesData = [
    [
        'TAMIZAJE',
        '88888888',
        'DNI',
        $fechaTamizaje->format('Y-m-d'),
        $fechaGalen->format('Y-m-d')
    ]
];

$sheet->fromArray($tamizajesData, null, 'A2');
foreach (range('A', 'E') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

// Guardar archivo
$writer = new Xlsx($spreadsheet);
$filename = 'importacion_1_nino_todos_no_cumplen_' . date('Y-m-d_His') . '.xlsx';
$writer->save($filename);

echo "✅ Archivo Excel creado exitosamente: {$filename}\n";
echo "\n";
echo "📊 Datos del niño:\n";
echo "   - Nombre: GARCÍA LÓPEZ, CARLOS ANTONIO\n";
echo "   - DNI: 88888888\n";
echo "   - Fecha de Nacimiento: " . $fechaNacimiento->format('Y-m-d') . " (hace ~11 meses)\n";
echo "   - Género: Masculino\n";
echo "\n";
echo "❌ TODOS LOS CONTROLES SOBREPASAN EL LÍMITE (NO CUMPLEN):\n";
echo "\n";
echo "❌ Controles RN (todos sobrepasan el límite):\n";
echo "   - Control 1: día 10 (rango 2-6 días, límite: 6) ❌\n";
echo "   - Control 2: día 18 (rango 7-13 días, límite: 13) ❌\n";
echo "   - Control 3: día 25 (rango 14-20 días, límite: 20) ❌\n";
echo "   - Control 4: día 35 (rango 21-28 días, límite: 28) ❌\n";
echo "\n";
echo "❌ Controles CRED (todos sobrepasan el límite):\n";
echo "   - Control 1: día 65 (rango 29-59 días, límite: 59) ❌\n";
echo "   - Control 2: día 95 (rango 60-89 días, límite: 89) ❌\n";
echo "   - Control 3: día 130 (rango 90-119 días, límite: 119) ❌\n";
echo "   - Control 4: día 160 (rango 120-149 días, límite: 149) ❌\n";
echo "   - Control 5: día 190 (rango 150-179 días, límite: 179) ❌\n";
echo "   - Control 6: día 220 (rango 180-209 días, límite: 209) ❌\n";
echo "   - Control 7: día 250 (rango 210-239 días, límite: 239) ❌\n";
echo "   - Control 8: día 280 (rango 240-269 días, límite: 269) ❌\n";
echo "   - Control 9: día 310 (rango 270-299 días, límite: 299) ❌\n";
echo "   - Control 10: día 340 (rango 300-329 días, límite: 329) ❌\n";
echo "   - Control 11: día 370 (rango 330-359 días, límite: 359) ❌\n";
echo "\n";
echo "❌ Visitas Domiciliarias (todas sobrepasan el límite):\n";
echo "   - Control 1: día 35 (rango 28-30 días, límite: 30) ❌\n";
echo "   - Control 2: día 155 (rango 60-150 días, límite: 150) ❌\n";
echo "   - Control 3: día 250 (rango 180-240 días, límite: 240) ❌\n";
echo "   - Control 4: día 340 (rango 270-330 días, límite: 330) ❌\n";
echo "\n";
echo "❌ Vacunas (sobrepasan el límite):\n";
echo "   - BCG: día 5 (rango 0-2 días, límite: 2) ❌\n";
echo "   - HVB: día 4 (rango 0-2 días, límite: 2) ❌\n";
echo "\n";
echo "❌ Tamizajes (sobrepasan el límite):\n";
echo "   - Tamizaje Neonatal: día 35 (rango 1-29 días, límite: 29) ❌\n";
echo "   - Tamizaje Galen: día 40 (rango 1-29 días, límite: 29) ❌\n";
echo "\n";
echo "📝 Hojas incluidas:\n";
echo "   1. Niños\n";
echo "   2. Madres\n";
echo "   3. Datos Extra\n";
echo "   4. Recién Nacido (CNV)\n";
echo "   5. Controles RN (4 controles - todos no cumplen)\n";
echo "   6. Controles CRED (11 controles - todos no cumplen)\n";
echo "   7. Visitas (4 visitas - todas no cumplen)\n";
echo "   8. Vacunas (BCG y HVB - no cumplen)\n";
echo "   9. Tamizaje (Neonatal y Galen - no cumplen)\n";
echo "\n";
echo "💡 Este archivo contiene TODOS los controles posibles, pero TODOS están\n";
echo "   fuera del rango permitido (sobrepasan el límite máximo) para que muestren 'NO CUMPLE'.\n";

