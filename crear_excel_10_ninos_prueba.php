<?php

/**
 * Script para crear un archivo Excel con 10 niños para pruebas
 * Algunos cumplirán con los rangos y otros no, para probar el sistema
 * 
 * Uso: php crear_excel_10_ninos_prueba.php
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

// Generar 10 niños con fechas variadas
$ninosData = [];
for ($i = 1; $i <= 10; $i++) {
    // Fechas variadas: algunos recién nacidos, algunos más grandes
    // 5 niños recién nacidos (0-30 días), 5 niños mayores (1-11 meses)
    if ($i <= 5) {
        // Recién nacidos: entre 0 y 30 días
        $diasAtras = rand(0, 30);
    } else {
        // Niños mayores: entre 31 y 330 días (1-11 meses)
        $diasAtras = rand(31, 330);
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
    
    // Algunos cumplen (0-2 días), otros no (más de 2 días)
    if ($i <= 6) {
        // 6 niños cumplen: vacuna entre 0-2 días
        $diasVacuna = rand(0, 2);
    } else {
        // 4 niños no cumplen: vacuna después de 2 días
        $diasVacuna = rand(3, 10);
    }
    
    $fechaBCG = clone $fechaNac;
    $fechaBCG->add(new DateInterval('P' . $diasVacuna . 'D'));
    
    $fechaHVB = clone $fechaNac;
    $fechaHVB->add(new DateInterval('P' . rand(0, min(2, $diasVacuna)) . 'D'));
    
    $vacunasData[] = [
        'VACUNAS', // tipo_control
        $ninosData[$i-1][2], // numero_doc
        'DNI', // tipo_doc
        $fechaBCG->format('Y-m-d'), // fecha_bcg
        $fechaHVB->format('Y-m-d') // fecha_hvb
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
        // Algunos cumplen (1-29 días), otros no (después de 29 días)
        if ($i <= 7) {
            // 7 niños cumplen: tamizaje entre 1-29 días
            $diasTamizaje = rand(1, 29);
        } else {
            // 3 niños no cumplen: tamizaje después de 29 días (si es posible)
            $diasTamizaje = rand(30, 35);
        }
        
        $fechaTamizaje = clone $fechaNac;
        $fechaTamizaje->add(new DateInterval('P' . $diasTamizaje . 'D'));
        
        $fechaGalen = clone $fechaTamizaje;
        $fechaGalen->add(new DateInterval('P' . rand(1, 5) . 'D'));
        
        $tamizajesData[] = [
            'TAMIZAJE', // tipo_control
            $ninosData[$i-1][2], // numero_doc
            'DNI', // tipo_doc
            $fechaTamizaje->format('Y-m-d'), // fecha_tamizaje
            $fechaGalen->format('Y-m-d') // galen_fecha
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
    
    // Generar visitas según la edad del niño
    // Control 1: 28-30 días
    if ($edadDias >= 28) {
        // Algunos cumplen (28-30 días), otros no (fuera del rango)
        if ($i <= 5) {
            // 5 niños cumplen: visita entre 28-30 días
            $diasVisita1 = rand(28, 30);
        } else {
            // 5 niños no cumplen: visita fuera del rango
            $diasVisita1 = rand(25, 27); // Antes del rango o después
            if (rand(0, 1)) {
                $diasVisita1 = rand(31, 35); // Después del rango
            }
        }
        
        $fechaVisita1 = clone $fechaNac;
        $fechaVisita1->add(new DateInterval('P' . $diasVisita1 . 'D'));
        
        $visitasData[] = [
            'VISITA', // tipo_control
            $ninosData[$i-1][2], // numero_doc
            'DNI', // tipo_doc
            '1', // control_de_visita
            $fechaVisita1->format('Y-m-d') // fecha_visita
        ];
    }
    
    // Control 2: 60-150 días (solo para niños mayores)
    if ($edadDias >= 60) {
        if ($i <= 5) {
            // 5 niños cumplen: visita entre 60-150 días
            $diasVisita2 = rand(60, 150);
        } else {
            // 5 niños no cumplen: visita fuera del rango
            $diasVisita2 = rand(50, 59); // Antes del rango
            if (rand(0, 1)) {
                $diasVisita2 = rand(151, 170); // Después del rango
            }
        }
        
        $fechaVisita2 = clone $fechaNac;
        $fechaVisita2->add(new DateInterval('P' . $diasVisita2 . 'D'));
        
        $visitasData[] = [
            'VISITA',
            $ninosData[$i-1][2],
            'DNI',
            '2',
            $fechaVisita2->format('Y-m-d')
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
        // Generar algunos controles RN
        $rangosRN = [
            1 => ['min' => 2, 'max' => 6],
            2 => ['min' => 7, 'max' => 13],
            3 => ['min' => 14, 'max' => 20],
            4 => ['min' => 21, 'max' => 28],
        ];
        
        foreach ($rangosRN as $numControl => $rango) {
            if ($edadDias >= $rango['min']) {
                // Algunos cumplen, otros no
                if ($i <= 6) {
                    // 6 niños cumplen: dentro del rango
                    $diasControl = rand($rango['min'], $rango['max']);
                } else {
                    // 4 niños no cumplen: fuera del rango
                    $diasControl = rand($rango['max'] + 1, $rango['max'] + 5);
                }
                
                $fechaControl = clone $fechaNac;
                $fechaControl->add(new DateInterval('P' . $diasControl . 'D'));
                
                $controlesRnData[] = [
                    'CRN', // tipo_control
                    $ninosData[$i-1][2], // numero_doc
                    'DNI', // tipo_doc
                    (string)$numControl, // numero_control
                    $fechaControl->format('Y-m-d') // fecha
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

// Guardar archivo
$writer = new Xlsx($spreadsheet);
$filename = 'importacion_10_ninos_prueba_' . date('Y-m-d_His') . '.xlsx';
$writer->save($filename);

echo "✅ Archivo Excel creado exitosamente: {$filename}\n";
echo "📊 Contiene datos de 10 niños con variaciones:\n";
echo "   - Algunos cumplen con los rangos establecidos\n";
echo "   - Otros no cumplen para probar las validaciones\n";
echo "   - Incluye: Niños, Madres, Vacunas, Tamizajes, Visitas y Controles RN\n";
echo "\n";
echo "📝 Notas:\n";
echo "   - Vacunas: 6 niños cumplen (0-2 días), 4 no cumplen (>2 días)\n";
echo "   - Visitas Control 1: 5 niños cumplen (28-30 días), 5 no cumplen\n";
echo "   - Tamizajes: 7 niños cumplen (1-29 días), 3 no cumplen\n";
echo "   - Controles RN: 6 niños cumplen, 4 no cumplen\n";

