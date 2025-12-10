<?php

/**
 * Script para crear un archivo Excel con 50 niños completos
 * Incluye todos los controles: CNV, Tamizaje, Vacunas, Controles RN, Controles CRED, Visitas
 * 
 * Uso: php crear_excel_50_ninos.php
 */

require __DIR__ . '/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

// Crear nuevo spreadsheet
$spreadsheet = new Spreadsheet();

// Arrays de datos para generar nombres y datos realistas
$apellidos = ['GARCÍA', 'RODRÍGUEZ', 'LÓPEZ', 'MARTÍNEZ', 'GONZÁLEZ', 'PÉREZ', 'SÁNCHEZ', 'RAMÍREZ', 'TORRES', 'FLORES', 'RIVERA', 'GÓMEZ', 'DÍAZ', 'CRUZ', 'MORALES', 'ORTIZ', 'GUTIÉRREZ', 'CHÁVEZ', 'RUIZ', 'MENDOZA'];
$nombres = ['MARÍA', 'JOSÉ', 'JUAN', 'ANA', 'CARLOS', 'LUIS', 'ROSA', 'PEDRO', 'CARMEN', 'FRANCISCO', 'JESÚS', 'LAURA', 'MIGUEL', 'PATRICIA', 'ANTONIO', 'MARTA', 'MANUEL', 'SUSANA', 'RICARDO', 'ELENA'];
$nombresNinos = ['JUAN CARLOS', 'MARÍA ELENA', 'CARLOS ALBERTO', 'ANA SOFÍA', 'LUIS FERNANDO', 'ROSA MARÍA', 'PEDRO ANTONIO', 'CARMEN ROSA', 'FRANCISCO JAVIER', 'JESÚS MANUEL', 'LAURA PATRICIA', 'MIGUEL ÁNGEL', 'PATRICIA ELENA', 'ANTONIO JOSÉ', 'MARTA LUCÍA', 'MANUEL JESÚS', 'SUSANA MARÍA', 'RICARDO ALBERTO', 'ELENA CARMEN', 'SOFÍA ANA'];
$generos = ['M', 'F'];
$establecimientos = ['Hospital Regional de Pucallpa', 'Centro de Salud Callería', 'Centro de Salud Yarinacocha', 'Posta Médica Masisea', 'Centro de Salud Aguaytía'];
$distritos = ['Callería', 'Yarinacocha', 'Masisea', 'Aguaytía', 'Campoverde'];
$provincias = ['Coronel Portillo', 'Padre Abad', 'Atalaya'];
$departamentos = ['Ucayali'];
$seguros = ['SIS', 'ESSALUD', 'Particular', 'FFAA'];
$programas = ['CRED', 'PAMI', 'JUNTOS'];

// Rangos para controles
$rangosRN = [
    1 => ['min' => 2, 'max' => 6],   // Control 1: 2-6 días
    2 => ['min' => 7, 'max' => 13],  // Control 2: 7-13 días
    3 => ['min' => 14, 'max' => 20], // Control 3: 14-20 días
    4 => ['min' => 21, 'max' => 28]  // Control 4: 21-28 días
];

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

$rangosVisitas = [
    1 => ['min' => 28, 'max' => 28],   // Visita 1: 28 días
    2 => ['min' => 60, 'max' => 150],  // Visita 2: 60-150 días
    3 => ['min' => 180, 'max' => 240], // Visita 3: 180-240 días
    4 => ['min' => 270, 'max' => 330]  // Visita 4: 270-330 días
];

// Arrays para almacenar datos
$ninosData = [];
$madresData = [];
$datosExtraData = [];
$recienNacidosData = [];
$tamizajeData = [];
$vacunasData = [];
$controlesRNData = [];
$controlesCREDData = [];
$visitasData = [];

// Generar 50 niños
for ($i = 1; $i <= 50; $i++) {
    // Fecha de nacimiento aleatoria (últimos 12 meses)
    $mesesAtras = rand(0, 11);
    $diasAtras = rand(0, 30);
    $fechaNacimiento = new DateTime();
    $fechaNacimiento->modify("-{$mesesAtras} months");
    $fechaNacimiento->modify("-{$diasAtras} days");
    
    // Datos del niño
    $apellido1 = $apellidos[array_rand($apellidos)];
    $apellido2 = $apellidos[array_rand($apellidos)];
    $nombre = $nombresNinos[array_rand($nombresNinos)];
    $genero = $generos[array_rand($generos)];
    $numeroDoc = str_pad(rand(10000000, 99999999), 8, '0', STR_PAD_LEFT);
    $establecimiento = $establecimientos[array_rand($establecimientos)];
    
    // Datos de la madre
    $dniMadre = str_pad(rand(10000000, 99999999), 8, '0', STR_PAD_LEFT);
    $apellidoMadre1 = $apellidos[array_rand($apellidos)];
    $apellidoMadre2 = $apellidos[array_rand($apellidos)];
    $nombreMadre = $nombres[array_rand($nombres)];
    $celularMadre = '9' . str_pad(rand(10000000, 99999999), 8, '0', STR_PAD_LEFT);
    $domicilio = 'Jr. ' . $nombres[array_rand($nombres)] . ' ' . rand(100, 999);
    $referencia = 'Frente a ' . ['el mercado', 'la plaza', 'la iglesia', 'el colegio'][array_rand(['el mercado', 'la plaza', 'la iglesia', 'el colegio'])];
    
    // Datos extra
    $distrito = $distritos[array_rand($distritos)];
    $provincia = $provincias[array_rand($provincias)];
    $departamento = $departamentos[array_rand($departamentos)];
    $seguro = $seguros[array_rand($seguros)];
    $programa = $programas[array_rand($programas)];
    
    // Recién Nacido (CNV)
    $pesoGramos = rand(2500, 4000); // 2.5 a 4.0 kg en gramos
    $pesoKg = $pesoGramos / 1000;
    $edadGestacional = rand(36, 42);
    $clasificacion = ($pesoKg < 2.5 || $edadGestacional < 37) ? 'Bajo Peso al Nacer y/o Prematuro' : 'Normal';
    
    // Tamizaje (5-7 días después del nacimiento)
    $fechaTamizaje = clone $fechaNacimiento;
    $fechaTamizaje->modify('+' . rand(5, 7) . ' days');
    $fechaGalen = clone $fechaTamizaje;
    $fechaGalen->modify('+' . rand(2, 4) . ' days');
    
    // Vacunas (0-2 días después del nacimiento)
    $fechaBCG = clone $fechaNacimiento;
    $fechaBCG->modify('+' . rand(0, 2) . ' days');
    $fechaHVB = clone $fechaNacimiento;
    $fechaHVB->modify('+' . rand(0, 2) . ' days');
    
    // Agregar datos a arrays
    $ninosData[] = [
        $i, // id_nino
        'DNI', // tipo_doc
        $numeroDoc, // numero_doc
        "{$apellido1} {$apellido2}, {$nombre}", // apellidos_nombres
        $fechaNacimiento->format('Y-m-d'), // fecha_nacimiento
        $genero, // genero
        $establecimiento // establecimiento
    ];
    
    $madresData[] = [
        $i, // id_nino
        $numeroDoc, // numero_doc
        'DNI', // tipo_doc
        $dniMadre, // dni
        "{$apellidoMadre1} {$apellidoMadre2}, {$nombreMadre}", // apellidos_nombres
        $celularMadre, // celular
        $domicilio, // domicilio
        $referencia // referencia_direccion
    ];
    
    $datosExtraData[] = [
        $i, // id_nino
        $numeroDoc, // numero_doc
        'DNI', // tipo_doc
        strtoupper($establecimiento), // red
        'Microred ' . $distrito, // microred
        $establecimiento, // eess_nacimiento
        $distrito, // distrito
        $provincia, // provincia
        $departamento, // departamento
        $seguro, // seguro
        $programa // programa
    ];
    
    $recienNacidosData[] = [
        $i, // id_nino
        $pesoGramos, // peso (en gramos, se convertirá automáticamente)
        $edadGestacional, // edad_gestacional
        $clasificacion // clasificacion
    ];
    
    $tamizajeData[] = [
        $i, // id_nino
        $numeroDoc, // numero_doc
        'DNI', // tipo_doc
        $fechaTamizaje->format('Y-m-d'), // fecha_tam_neo
        $fechaGalen->format('Y-m-d') // galen_fecha_tam_feo
    ];
    
    $vacunasData[] = [
        $i, // id_nino
        $numeroDoc, // numero_doc
        'DNI', // tipo_doc
        $fechaBCG->format('Y-m-d'), // fecha_bcg
        $fechaHVB->format('Y-m-d') // fecha_hvb
    ];
    
    // Controles RN (4 controles)
    foreach ($rangosRN as $num => $rango) {
        $fechaControl = clone $fechaNacimiento;
        $diasControl = rand($rango['min'], $rango['max']);
        $fechaControl->modify("+{$diasControl} days");
        
        $controlesRNData[] = [
            $i, // id_nino
            $numeroDoc, // numero_doc
            'DNI', // tipo_doc
            $num, // numero_control
            $fechaControl->format('Y-m-d') // fecha
        ];
    }
    
    // Controles CRED (11 controles)
    foreach ($rangosCRED as $num => $rango) {
        $fechaControl = clone $fechaNacimiento;
        $diasControl = rand($rango['min'], $rango['max']);
        $fechaControl->modify("+{$diasControl} days");
        
        $controlesCREDData[] = [
            $i, // id_nino
            $numeroDoc, // numero_documento
            'DNI', // tipo_documento
            $num, // numero_control
            $fechaControl->format('Y-m-d') // fecha
        ];
    }
    
    // Visitas Domiciliarias (4 visitas)
    foreach ($rangosVisitas as $num => $rango) {
        $fechaVisita = clone $fechaNacimiento;
        $diasVisita = rand($rango['min'], $rango['max']);
        $fechaVisita->modify("+{$diasVisita} days");
        
        $visitasData[] = [
            $i, // id_nino
            $numeroDoc, // numero_doc
            'DNI', // tipo_doc
            $num, // control_de_visita
            $fechaVisita->format('Y-m-d') // fecha_visita
        ];
    }
}

// ========== HOJA 1: NIÑOS ==========
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Niños');

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

$sheet->fromArray($ninosData, null, 'A2');

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

$sheet->fromArray($madresData, null, 'A2');

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

$sheet->fromArray($datosExtraData, null, 'A2');

foreach (range('A', 'K') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

// ========== HOJA 4: RECIÉN NACIDOS (CNV) ==========
$sheet = $spreadsheet->createSheet();
$sheet->setTitle('Recién Nacidos');

$headers = [
    'id_nino',
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

$sheet->fromArray($recienNacidosData, null, 'A2');

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

$sheet->fromArray($tamizajeData, null, 'A2');

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

$sheet->fromArray($vacunasData, null, 'A2');

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

$sheet->fromArray($controlesRNData, null, 'A2');

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

$sheet->fromArray($controlesCREDData, null, 'A2');

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

$sheet->fromArray($visitasData, null, 'A2');

foreach (range('A', 'E') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

// Establecer la primera hoja como activa
$spreadsheet->setActiveSheetIndex(0);

// Guardar el archivo
$filename = 'ejemplo_50_ninos_completo.xlsx';
$writer = new Xlsx($spreadsheet);
$writer->save($filename);

echo "✅ Archivo Excel creado exitosamente: {$filename}\n";
echo "\n📋 Contenido del archivo:\n";
echo "   - Hoja 1: Niños (50 niños)\n";
echo "   - Hoja 2: Madres (50 madres)\n";
echo "   - Hoja 3: Datos Extra (50 registros)\n";
echo "   - Hoja 4: Recién Nacidos (50 registros CNV)\n";
echo "   - Hoja 5: Tamizaje (50 registros)\n";
echo "   - Hoja 6: Vacunas (50 registros)\n";
echo "   - Hoja 7: Controles RN (200 controles - 4 por niño)\n";
echo "   - Hoja 8: Controles CRED (550 controles - 11 por niño)\n";
echo "   - Hoja 9: Visitas (200 visitas - 4 por niño)\n";
echo "\n📊 Estadísticas:\n";
echo "   - Total de niños: 50\n";
echo "   - Total de controles RN: " . count($controlesRNData) . "\n";
echo "   - Total de controles CRED: " . count($controlesCREDData) . "\n";
echo "   - Total de visitas: " . count($visitasData) . "\n";
echo "\n💡 Nota: Todas las fechas están calculadas según los rangos permitidos para cada tipo de control.\n";
echo "   Los pesos están en gramos y se convertirán automáticamente a kilogramos durante la importación.\n";


