<?php

/**
 * Script para crear un archivo Excel de ejemplo con un niño completo
 * Incluye todos los controles: CNV, Tamizaje, Vacunas, Controles RN, Controles CRED, Visitas
 * 
 * Uso: php crear_excel_ejemplo.php
 */

require __DIR__ . '/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

// Crear nuevo spreadsheet
$spreadsheet = new Spreadsheet();

// Fecha base para el ejemplo (hace 6 meses)
$fechaNacimiento = new DateTime('2024-06-15');
$hoy = new DateTime();

// ========== HOJA 1: NIÑOS ==========
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Niños');

// Encabezados (usar nombres exactos que esperan los importadores)
$headers = [
    'id_nino',
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
    1, // id_nino
    'DNI', // tipo_doc
    '12345678', // numero_doc
    'PÉREZ GARCÍA, JUAN CARLOS', // apellidos_nombres
    $fechaNacimiento->format('Y-m-d'), // fecha_nacimiento
    'M', // genero
    'Hospital Regional de Pucallpa' // establecimiento
];

$sheet->fromArray([$ninoData], null, 'A2');

// Ajustar ancho de columnas
foreach (range('A', 'G') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

// ========== HOJA 2: MADRES ==========
$sheet = $spreadsheet->createSheet();
$sheet->setTitle('Madres');

$headers = [
    'id_nino',
    'numero_doc',
    'tipo_doc',
    'dni',
    'apellidos_nombres',
    'celular',
    'domicilio',
    'referencia_direccion'
];

$sheet->fromArray($headers, null, 'A1');
$sheet->getStyle('A1:H1')->getFont()->setBold(true);
$sheet->getStyle('A1:H1')->getFill()
    ->setFillType(Fill::FILL_SOLID)
    ->getStartColor()->setARGB('FF4472C4');
$sheet->getStyle('A1:H1')->getFont()->getColor()->setARGB('FFFFFFFF');

$madreData = [
    1, // id_nino
    '12345678', // numero_doc
    'DNI', // tipo_doc
    '87654321', // dni
    'GARCÍA LÓPEZ, MARÍA ELENA', // apellidos_nombres
    '987654321', // celular
    'Jr. Los Olivos 123', // domicilio
    'Frente al mercado central' // referencia_direccion
];

$sheet->fromArray([$madreData], null, 'A2');

foreach (range('A', 'H') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

// ========== HOJA 3: DATOS EXTRA ==========
$sheet = $spreadsheet->createSheet();
$sheet->setTitle('Datos Extra');

$headers = [
    'id_nino',
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
    1, // id_nino
    '12345678', // numero_doc
    'DNI', // tipo_doc
    'HOSPITAL REGIONAL DE PUCALLPA', // red
    'Microred Centro', // microred
    'Hospital Regional de Pucallpa', // eess_nacimiento
    'Callería', // distrito
    'Coronel Portillo', // provincia
    'Ucayali', // departamento
    'SIS', // seguro
    'CRED' // programa
];

$sheet->fromArray([$datosExtraData], null, 'A2');

foreach (range('A', 'K') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

// ========== HOJA 4: RECIÉN NACIDOS (CNV) ==========
$sheet = $spreadsheet->createSheet();
$sheet->setTitle('Recién Nacidos');

// Solo los campos que se guardan en la base de datos: id_niño, peso, edad_gestacional, clasificacion
// El id_nino se usa para identificar al niño (no se guarda en esta tabla)
$headers = [
    'id_nino', // Se usa para identificar al niño, no se guarda en recien_nacidos
    'peso',
    'edad_gestacional',
    'clasificacion'
];

$sheet->fromArray($headers, null, 'A1');
$sheet->getStyle('A1:D1')->getFont()->setBold(true);
$sheet->getStyle('A1:D1')->getFill()
    ->setFillType(Fill::FILL_SOLID)
    ->getStartColor()->setARGB('FF4472C4');
$sheet->getStyle('A1:D1')->getFont()->getColor()->setARGB('FFFFFFFF');

// Datos del CNV - peso puede venir en gramos (se convertirá automáticamente) o en kg
// Ejemplo con peso en gramos (3200 gramos = 3.2 kg)
$cnvData = [
    1, // id_nino (se usa para identificar al niño)
    3200, // peso (en gramos, se convertirá automáticamente a 3.2 kg si es > 10)
    38, // edad_gestacional (semanas) - debe ser entre 36 y 42 semanas típicamente
    'Normal' // clasificacion (debe ser exactamente: 'Normal' o 'Bajo Peso al Nacer y/o Prematuro')
];

$sheet->fromArray([$cnvData], null, 'A2');

// Agregar nota informativa en la celda E2
$sheet->setCellValue('E2', 'Nota: id_nino se usa para identificar al niño. El peso puede venir en gramos (ej: 3200) o kg (ej: 3.2). Si es > 10, se convertirá a kg automáticamente.');
$sheet->getStyle('E2')->getFont()->setItalic(true);
$sheet->getStyle('E2')->getFont()->setSize(9);
$sheet->getStyle('E2')->getFont()->getColor()->setARGB('FF666666');
$sheet->getColumnDimension('E')->setWidth(80);

foreach (range('A', 'D') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

// ========== HOJA 5: TAMIZAJE ==========
$sheet = $spreadsheet->createSheet();
$sheet->setTitle('Tamizaje');

$headers = [
    'id_nino',
    'numero_doc',
    'tipo_doc',
    'fecha_tam_neo',
    'galen_fecha_tam_feo'
];

$sheet->fromArray($headers, null, 'A1');
$sheet->getStyle('A1:E1')->getFont()->setBold(true);
$sheet->getStyle('A1:E1')->getFill()
    ->setFillType(Fill::FILL_SOLID)
    ->getStartColor()->setARGB('FF4472C4');
$sheet->getStyle('A1:E1')->getFont()->getColor()->setARGB('FFFFFFFF');

$fechaTamizaje = clone $fechaNacimiento;
$fechaTamizaje->modify('+5 days'); // 5 días después del nacimiento

$fechaGalen = clone $fechaTamizaje;
$fechaGalen->modify('+3 days');
$tamizajeData = [
    1, // id_nino
    '12345678', // numero_doc
    'DNI', // tipo_doc
    $fechaTamizaje->format('Y-m-d'), // fecha_tam_neo
    $fechaGalen->format('Y-m-d') // galen_fecha_tam_feo (opcional)
];

$sheet->fromArray([$tamizajeData], null, 'A2');

foreach (range('A', 'E') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

// ========== HOJA 6: VACUNAS ==========
$sheet = $spreadsheet->createSheet();
$sheet->setTitle('Vacunas');

$headers = [
    'id_nino',
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

$fechaBCG = clone $fechaNacimiento;
$fechaBCG->modify('+1 day'); // 1 día después del nacimiento
$fechaHVB = clone $fechaNacimiento;
$fechaHVB->modify('+1 day'); // 1 día después del nacimiento

$vacunasData = [
    1, // id_nino
    '12345678', // numero_doc
    'DNI', // tipo_doc
    $fechaBCG->format('Y-m-d'), // fecha_bcg
    $fechaHVB->format('Y-m-d') // fecha_hvb
];

$sheet->fromArray([$vacunasData], null, 'A2');

foreach (range('A', 'E') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

// ========== HOJA 7: CONTROLES RN ==========
$sheet = $spreadsheet->createSheet();
$sheet->setTitle('Controles RN');

$headers = [
    'id_nino',
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

// Crear 4 controles RN
$controlesRN = [];
$rangosRN = [
    1 => ['min' => 2, 'max' => 6],   // Control 1: 2-6 días
    2 => ['min' => 7, 'max' => 13],  // Control 2: 7-13 días
    3 => ['min' => 14, 'max' => 20], // Control 3: 14-20 días
    4 => ['min' => 21, 'max' => 28]  // Control 4: 21-28 días
];

foreach ($rangosRN as $num => $rango) {
    $fechaControl = clone $fechaNacimiento;
    $fechaControl->modify('+' . (($rango['min'] + $rango['max']) / 2) . ' days'); // Fecha promedio del rango
    
    $controlesRN[] = [
        1, // id_nino
        '12345678', // numero_doc
        'DNI', // tipo_doc
        $num, // numero_control
        $fechaControl->format('Y-m-d') // fecha
    ];
}

$sheet->fromArray($controlesRN, null, 'A2');

foreach (range('A', 'E') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

// ========== HOJA 8: CONTROLES CRED ==========
$sheet = $spreadsheet->createSheet();
$sheet->setTitle('Controles CRED');

$headers = [
    'id_nino',
    'numero_documento',
    'tipo_documento',
    'numero_control',
    'fecha'
];

$sheet->fromArray($headers, null, 'A1');
$sheet->getStyle('A1:E1')->getFont()->setBold(true);
$sheet->getStyle('A1:E1')->getFill()
    ->setFillType(Fill::FILL_SOLID)
    ->getStartColor()->setARGB('FF4472C4');
$sheet->getStyle('A1:E1')->getFont()->getColor()->setARGB('FFFFFFFF');

// Crear 11 controles CRED
$controlesCRED = [];
$rangosCRED = [
    1 => ['min' => 29, 'max' => 59],   // Control 1: 29-59 días
    2 => ['min' => 60, 'max' => 89],   // Control 2: 60-89 días
    3 => ['min' => 90, 'max' => 119],  // Control 3: 90-119 días
    4 => ['min' => 120, 'max' => 149], // Control 4: 120-149 días
    5 => ['min' => 150, 'max' => 179], // Control 5: 150-179 días
    6 => ['min' => 180, 'max' => 209], // Control 6: 180-209 días
    7 => ['min' => 210, 'max' => 239], // Control 7: 210-239 días
    8 => ['min' => 240, 'max' => 269], // Control 8: 240-269 días
    9 => ['min' => 270, 'max' => 299], // Control 9: 270-299 días
    10 => ['min' => 300, 'max' => 329], // Control 10: 300-329 días
    11 => ['min' => 330, 'max' => 359]  // Control 11: 330-359 días
];

foreach ($rangosCRED as $num => $rango) {
    $fechaControl = clone $fechaNacimiento;
    $fechaControl->modify('+' . (($rango['min'] + $rango['max']) / 2) . ' days'); // Fecha promedio del rango
    
    $controlesCRED[] = [
        1, // id_nino
        '12345678', // numero_doc
        'DNI', // tipo_doc
        $num, // numero_control
        $fechaControl->format('Y-m-d') // fecha
    ];
}

$sheet->fromArray($controlesCRED, null, 'A2');

foreach (range('A', 'E') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

// ========== HOJA 9: VISITAS DOMICILIARIAS ==========
$sheet = $spreadsheet->createSheet();
$sheet->setTitle('Visitas');

$headers = [
    'id_nino',
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

// Crear 4 visitas domiciliarias
$visitas = [];
$rangosVisitas = [
    1 => ['min' => 28, 'max' => 28],   // Visita 1: 28 días
    2 => ['min' => 60, 'max' => 150],  // Visita 2: 60-150 días
    3 => ['min' => 180, 'max' => 240], // Visita 3: 180-240 días
    4 => ['min' => 270, 'max' => 330]  // Visita 4: 270-330 días
];

foreach ($rangosVisitas as $num => $rango) {
    $fechaVisita = clone $fechaNacimiento;
    $fechaVisita->modify('+' . (($rango['min'] + $rango['max']) / 2) . ' days'); // Fecha promedio del rango
    
    $visitas[] = [
        1, // id_nino
        '12345678', // numero_doc
        'DNI', // tipo_doc
        $num, // control_de_visita (1, 2, 3, 4)
        $fechaVisita->format('Y-m-d') // fecha_visita
    ];
}

$sheet->fromArray($visitas, null, 'A2');

foreach (range('A', 'E') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

// Establecer la primera hoja como activa
$spreadsheet->setActiveSheetIndex(0);

// Guardar el archivo
$filename = 'ejemplo_nino_completo_cnv_corregido.xlsx';
$writer = new Xlsx($spreadsheet);
$writer->save($filename);

echo "✅ Archivo Excel creado exitosamente: {$filename}\n";
echo "\n📋 Contenido del archivo:\n";
echo "   - Hoja 1: Niños (1 niño)\n";
echo "   - Hoja 2: Madres (1 madre)\n";
echo "   - Hoja 3: Datos Extra (datos adicionales)\n";
echo "   - Hoja 4: Recién Nacidos (CNV)\n";
echo "   - Hoja 5: Tamizaje (tamizaje neonatal)\n";
echo "   - Hoja 6: Vacunas (BCG y HVB)\n";
echo "   - Hoja 7: Controles RN (4 controles)\n";
echo "   - Hoja 8: Controles CRED (11 controles)\n";
echo "   - Hoja 9: Visitas (4 visitas domiciliarias)\n";
echo "\n📝 Datos del niño de ejemplo:\n";
echo "   - Nombre: PÉREZ GARCÍA, JUAN CARLOS\n";
echo "   - DNI: 12345678\n";
echo "   - Fecha de Nacimiento: " . $fechaNacimiento->format('Y-m-d') . "\n";
echo "   - Género: Masculino\n";
echo "\n💡 Nota: Todas las fechas están calculadas según los rangos permitidos para cada tipo de control.\n";

