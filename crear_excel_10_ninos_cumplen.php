<?php

/**
 * Script para crear un archivo Excel con 10 niños donde TODOS cumplen con los rangos
 * Todos los controles están dentro de los rangos permitidos
 * 
 * Uso: php crear_excel_10_ninos_cumplen.php
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
$fechaBase = clone $hoy;

// Nombres y apellidos aleatorios para generar datos variados
$nombres = ['JUAN', 'MARÍA', 'CARLOS', 'ANA', 'LUIS', 'SOFÍA', 'PEDRO', 'LAURA', 'JOSÉ', 'ELENA'];
$apellidos = ['GARCÍA', 'RODRÍGUEZ', 'LÓPEZ', 'MARTÍNEZ', 'GONZÁLEZ', 'PÉREZ', 'SÁNCHEZ', 'RAMÍREZ', 'TORRES', 'FLORES'];

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

// Generar 10 niños con fechas variadas pero que permitan cumplir todos los controles
$ninosData = [];
for ($i = 1; $i <= 10; $i++) {
    // Distribuir edades para que todos puedan tener controles que cumplan
    // Algunos recién nacidos, algunos intermedios, algunos mayores
    if ($i <= 3) {
        // 3 niños recién nacidos (0-30 días) - pueden tener vacunas, tamizaje, controles RN
        $diasAtras = rand(0, 30);
    } elseif ($i <= 6) {
        // 3 niños intermedios (31-180 días) - pueden tener controles CRED y visitas
        $diasAtras = rand(31, 180);
    } else {
        // 4 niños mayores (181-330 días) - pueden tener todos los controles CRED y visitas
        $diasAtras = rand(181, 330);
    }
    
    $fechaNac = clone $fechaBase;
    $fechaNac->sub(new DateInterval('P' . $diasAtras . 'D'));
    
    $nombre = $nombres[array_rand($nombres)];
    $apellido1 = $apellidos[array_rand($apellidos)];
    $apellido2 = $apellidos[array_rand($apellidos)];
    
    $ninosData[] = [
        'NINO', // tipo_control
        'DNI', // tipo_doc
        str_pad(rand(10000000, 99999999), 8, '0', STR_PAD_LEFT), // numero_doc
        $apellido1 . ' ' . $apellido2 . ', ' . $nombre, // apellidos_nombres
        $fechaNac->format('Y-m-d'), // fecha_nacimiento
        ($i % 2 == 0) ? 'F' : 'M', // genero alternado
        'Centro de Salud ' . ['Callería', 'Masisea', 'Yarinacocha', 'Campoverde', 'Nueva Requena'][array_rand(['Callería', 'Masisea', 'Yarinacocha', 'Campoverde', 'Nueva Requena'])] // establecimiento
    ];
}

$sheet->fromArray($ninosData, null, 'A2');

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

$madresData = [];
for ($i = 1; $i <= 10; $i++) {
    $madreNombre = $nombres[array_rand($nombres)];
    $madreApellido1 = $apellidos[array_rand($apellidos)];
    $madreApellido2 = $apellidos[array_rand($apellidos)];
    
    $madresData[] = [
        'MADRE', // tipo_control
        $ninosData[$i-1][2], // numero_doc del niño
        'DNI', // tipo_doc
        str_pad(rand(10000000, 99999999), 8, '0', STR_PAD_LEFT), // dni_madre
        $madreApellido1 . ' ' . $madreApellido2 . ', ' . $madreNombre, // apellidos_nombres_madre
        '9' . str_pad(rand(10000000, 99999999), 8, '0', STR_PAD_LEFT), // celular_madre
        'Jr. ' . ['Los Olivos', 'San Martín', 'Ucayali', 'Lima', 'Arequipa'][array_rand(['Los Olivos', 'San Martín', 'Ucayali', 'Lima', 'Arequipa'])] . ' ' . rand(100, 999) // domicilio_madre
    ];
}

$sheet->fromArray($madresData, null, 'A2');

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

$vacunasData = [];
for ($i = 1; $i <= 10; $i++) {
    $fechaNacStr = $ninosData[$i-1][4];
    $fechaNac = DateTime::createFromFormat('Y-m-d', $fechaNacStr);
    $edadDias = (new DateTime())->diff($fechaNac)->days;
    
    // Solo generar vacunas para niños menores de 2 días (o simular que se aplicaron a tiempo)
    // TODOS cumplen: vacuna entre 0-2 días
    $diasBCG = rand(0, 2);
    $diasHVB = rand(0, 2);
    
    $fechaBCG = clone $fechaNac;
    $fechaBCG->add(new DateInterval('P' . $diasBCG . 'D'));
    
    $fechaHVB = clone $fechaNac;
    $fechaHVB->add(new DateInterval('P' . $diasHVB . 'D'));
    
    $vacunasData[] = [
        'VACUNAS', // tipo_control
        $ninosData[$i-1][2], // numero_doc
        'DNI', // tipo_doc
        $fechaBCG->format('Y-m-d'), // fecha_bcg (SIEMPRE dentro de 0-2 días)
        $fechaHVB->format('Y-m-d') // fecha_hvb (SIEMPRE dentro de 0-2 días)
    ];
}

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

$tamizajesData = [];
for ($i = 1; $i <= 10; $i++) {
    $fechaNacStr = $ninosData[$i-1][4];
    $fechaNac = DateTime::createFromFormat('Y-m-d', $fechaNacStr);
    $edadDias = (new DateTime())->diff($fechaNac)->days;
    
    // Solo generar tamizaje para niños menores de 29 días
    if ($edadDias <= 29) {
        // TODOS cumplen: tamizaje entre 1-29 días
        $diasTamizaje = rand(1, 29);
        
        $fechaTamizaje = clone $fechaNac;
        $fechaTamizaje->add(new DateInterval('P' . $diasTamizaje . 'D'));
        
        $fechaGalen = clone $fechaTamizaje;
        $fechaGalen->add(new DateInterval('P' . rand(1, 5) . 'D'));
        
        // Asegurar que Galen también esté dentro del rango (1-29 días)
        $diasGalen = (new DateTime())->diff($fechaGalen)->days;
        if ($diasGalen > 29) {
            $fechaGalen = clone $fechaNac;
            $fechaGalen->add(new DateInterval('P' . rand(1, 29) . 'D'));
        }
        
        $tamizajesData[] = [
            'TAMIZAJE', // tipo_control
            $ninosData[$i-1][2], // numero_doc
            'DNI', // tipo_doc
            $fechaTamizaje->format('Y-m-d'), // fecha_tamizaje (SIEMPRE dentro de 1-29 días)
            $fechaGalen->format('Y-m-d') // galen_fecha (SIEMPRE dentro de 1-29 días)
        ];
    }
}

if (!empty($tamizajesData)) {
    $sheet->fromArray($tamizajesData, null, 'A2');
}

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

$visitasData = [];
for ($i = 1; $i <= 10; $i++) {
    $fechaNacStr = $ninosData[$i-1][4];
    $fechaNac = DateTime::createFromFormat('Y-m-d', $fechaNacStr);
    $edadDias = (new DateTime())->diff($fechaNac)->days;
    
    // Control 1: 28-30 días - TODOS cumplen
    if ($edadDias >= 28) {
        $diasVisita1 = rand(28, 30); // SIEMPRE dentro del rango
        
        $fechaVisita1 = clone $fechaNac;
        $fechaVisita1->add(new DateInterval('P' . $diasVisita1 . 'D'));
        
        $visitasData[] = [
            'VISITA', // tipo_control
            $ninosData[$i-1][2], // numero_doc
            'DNI', // tipo_doc
            '1', // control_de_visita
            $fechaVisita1->format('Y-m-d') // fecha_visita (SIEMPRE dentro de 28-30 días)
        ];
    }
    
    // Control 2: 60-150 días - TODOS cumplen
    if ($edadDias >= 60) {
        $diasVisita2 = rand(60, 150); // SIEMPRE dentro del rango
        
        $fechaVisita2 = clone $fechaNac;
        $fechaVisita2->add(new DateInterval('P' . $diasVisita2 . 'D'));
        
        $visitasData[] = [
            'VISITA',
            $ninosData[$i-1][2],
            'DNI',
            '2',
            $fechaVisita2->format('Y-m-d') // fecha_visita (SIEMPRE dentro de 60-150 días)
        ];
    }
    
    // Control 3: 180-240 días - TODOS cumplen
    if ($edadDias >= 180) {
        $diasVisita3 = rand(180, 240); // SIEMPRE dentro del rango
        
        $fechaVisita3 = clone $fechaNac;
        $fechaVisita3->add(new DateInterval('P' . $diasVisita3 . 'D'));
        
        $visitasData[] = [
            'VISITA',
            $ninosData[$i-1][2],
            'DNI',
            '3',
            $fechaVisita3->format('Y-m-d') // fecha_visita (SIEMPRE dentro de 180-240 días)
        ];
    }
    
    // Control 4: 270-330 días - TODOS cumplen
    if ($edadDias >= 270) {
        $diasVisita4 = rand(270, 330); // SIEMPRE dentro del rango
        
        $fechaVisita4 = clone $fechaNac;
        $fechaVisita4->add(new DateInterval('P' . $diasVisita4 . 'D'));
        
        $visitasData[] = [
            'VISITA',
            $ninosData[$i-1][2],
            'DNI',
            '4',
            $fechaVisita4->format('Y-m-d') // fecha_visita (SIEMPRE dentro de 270-330 días)
        ];
    }
}

if (!empty($visitasData)) {
    $sheet->fromArray($visitasData, null, 'A2');
}

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

$controlesRnData = [];
for ($i = 1; $i <= 10; $i++) {
    $fechaNacStr = $ninosData[$i-1][4];
    $fechaNac = DateTime::createFromFormat('Y-m-d', $fechaNacStr);
    $edadDias = (new DateTime())->diff($fechaNac)->days;
    
    // Solo generar controles RN para niños menores de 28 días
    if ($edadDias <= 28) {
        // Generar controles RN - TODOS cumplen con los rangos
        $rangosRN = [
            1 => ['min' => 2, 'max' => 6],
            2 => ['min' => 7, 'max' => 13],
            3 => ['min' => 14, 'max' => 20],
            4 => ['min' => 21, 'max' => 28],
        ];
        
        foreach ($rangosRN as $numControl => $rango) {
            if ($edadDias >= $rango['min']) {
                // SIEMPRE dentro del rango
                $diasControl = rand($rango['min'], $rango['max']);
                
                $fechaControl = clone $fechaNac;
                $fechaControl->add(new DateInterval('P' . $diasControl . 'D'));
                
                $controlesRnData[] = [
                    'CRN', // tipo_control
                    $ninosData[$i-1][2], // numero_doc
                    'DNI', // tipo_doc
                    (string)$numControl, // numero_control
                    $fechaControl->format('Y-m-d') // fecha (SIEMPRE dentro del rango)
                ];
            }
        }
    }
}

if (!empty($controlesRnData)) {
    $sheet->fromArray($controlesRnData, null, 'A2');
}

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

$controlesCredData = [];
for ($i = 1; $i <= 10; $i++) {
    $fechaNacStr = $ninosData[$i-1][4];
    $fechaNac = DateTime::createFromFormat('Y-m-d', $fechaNacStr);
    $edadDias = (new DateTime())->diff($fechaNac)->days;
    
    // Solo generar controles CRED para niños mayores de 29 días
    if ($edadDias >= 29) {
        // Generar controles CRED - TODOS cumplen con los rangos
        $rangosCRED = [
            1 => ['min' => 29, 'max' => 59],
            2 => ['min' => 60, 'max' => 89],
            3 => ['min' => 90, 'max' => 119],
            4 => ['min' => 120, 'max' => 149],
            5 => ['min' => 150, 'max' => 179],
            6 => ['min' => 180, 'max' => 209],
            7 => ['min' => 210, 'max' => 239],
            8 => ['min' => 240, 'max' => 269],
            9 => ['min' => 270, 'max' => 299],
            10 => ['min' => 300, 'max' => 329],
            11 => ['min' => 330, 'max' => 359],
        ];
        
        foreach ($rangosCRED as $numControl => $rango) {
            if ($edadDias >= $rango['min']) {
                // SIEMPRE dentro del rango
                $diasControl = rand($rango['min'], min($rango['max'], $edadDias));
                
                $fechaControl = clone $fechaNac;
                $fechaControl->add(new DateInterval('P' . $diasControl . 'D'));
                
                $controlesCredData[] = [
                    'CRED', // tipo_control
                    $ninosData[$i-1][2], // numero_doc
                    'DNI', // tipo_doc
                    (string)$numControl, // numero_control
                    $fechaControl->format('Y-m-d') // fecha (SIEMPRE dentro del rango)
                ];
            }
        }
    }
}

if (!empty($controlesCredData)) {
    $sheet->fromArray($controlesCredData, null, 'A2');
}

foreach (range('A', 'E') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

// Guardar archivo
$writer = new Xlsx($spreadsheet);
$filename = 'importacion_10_ninos_todos_cumplen_' . date('Y-m-d_His') . '.xlsx';
$writer->save($filename);

echo "✅ Archivo Excel creado exitosamente: {$filename}\n";
echo "📊 Contiene datos de 10 niños donde TODOS los controles CUMPLEN:\n";
echo "   - ✅ Todas las vacunas aplicadas entre 0-2 días\n";
echo "   - ✅ Todos los tamizajes realizados entre 1-29 días\n";
echo "   - ✅ Todas las visitas dentro de sus rangos (28-30, 60-150, 180-240, 270-330 días)\n";
echo "   - ✅ Todos los controles RN dentro de sus rangos (2-6, 7-13, 14-20, 21-28 días)\n";
echo "   - ✅ Todos los controles CRED dentro de sus rangos (29-59, 60-89, etc.)\n";
echo "\n";
echo "📝 Incluye las siguientes hojas:\n";
echo "   1. Niños (10 niños con diferentes edades)\n";
echo "   2. Madres (datos de las madres)\n";
echo "   3. Vacunas (BCG y HVB - todas cumplen)\n";
echo "   4. Tamizaje (todos cumplen)\n";
echo "   5. Visitas (todas cumplen con sus rangos)\n";
echo "   6. Controles RN (todos cumplen)\n";
echo "   7. Controles CRED (todos cumplen)\n";
echo "\n";
echo "💡 Este archivo es ideal para verificar que el sistema reconoce correctamente\n";
echo "   cuando todos los controles están dentro de los rangos permitidos.\n";

