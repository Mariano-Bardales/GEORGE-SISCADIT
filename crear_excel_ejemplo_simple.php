<?php

/**
 * Script para crear un archivo Excel de ejemplo simple para importar en SISCADIT
 * 
 * Uso: php crear_excel_ejemplo_simple.php
 * 
 * Genera un archivo llamado: ejemplo_importacion_siscadit.xlsx
 */

require __DIR__ . '/vendor/autoload.php';

// PHPExcel usa clases globales, no namespaces

// Crear nuevo objeto PHPExcel
$objPHPExcel = new \PHPExcel();

// Eliminar la hoja por defecto
$objPHPExcel->removeSheetByIndex(0);

// ========== HOJA 1: NIÑOS ==========
$sheetNinos = $objPHPExcel->createSheet();
$sheetNinos->setTitle('Niños');

// Encabezados
$sheetNinos->setCellValue('A1', 'id_nino');
$sheetNinos->setCellValue('B1', 'establecimiento');
$sheetNinos->setCellValue('C1', 'tipo_doc');
$sheetNinos->setCellValue('D1', 'numero_doc');
$sheetNinos->setCellValue('E1', 'apellidos_nombres');
$sheetNinos->setCellValue('F1', 'fecha_nacimiento');
$sheetNinos->setCellValue('G1', 'genero');

// Estilo para encabezados
$headerStyle = [
    'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
    'fill' => [
        'type' => \PHPExcel_Style_Fill::FILL_SOLID,
        'color' => ['rgb' => '4472C4']
    ],
    'alignment' => ['horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER]
];
$sheetNinos->getStyle('A1:G1')->applyFromArray($headerStyle);

// Datos de ejemplo - 10 niños
$ninos = [
    [1, 'Hospital Regional de Pucallpa', 'DNI', '10000001', 'García López, Juan Carlos', '2024-01-15', 'M'],
    [2, 'Centro de Salud Callería', 'DNI', '10000002', 'Pérez Martínez, Ana María', '2024-02-20', 'F'],
    [3, 'Posta de Salud Yarinacocha', 'DNI', '10000003', 'Rodríguez Silva, Pedro', '2024-03-10', 'M'],
    [4, 'Centro de Salud Manantay', 'DNI', '10000004', 'Torres Flores, María Elena', '2024-04-05', 'F'],
    [5, 'Hospital Amazónico', 'DNI', '10000005', 'Vargas Ríos, Luis Alberto', '2024-05-12', 'M'],
    [6, 'Centro de Salud Callería', 'DNI', '10000006', 'Mendoza Cruz, Sofía', '2024-06-18', 'F'],
    [7, 'Hospital Regional de Pucallpa', 'DNI', '10000007', 'Ramírez Gutiérrez, Diego', '2024-07-25', 'M'],
    [8, 'Posta de Salud Yarinacocha', 'DNI', '10000008', 'López Sánchez, Camila', '2024-08-30', 'F'],
    [9, 'Centro de Salud Manantay', 'DNI', '10000009', 'Fernández Torres, Andrés', '2024-09-10', 'M'],
    [10, 'Hospital Amazónico', 'DNI', '10000010', 'González Morales, Valentina', '2024-10-15', 'F'],
];

$row = 2;
foreach ($ninos as $nino) {
    $sheetNinos->setCellValue('A' . $row, $nino[0]);
    $sheetNinos->setCellValue('B' . $row, $nino[1]);
    $sheetNinos->setCellValue('C' . $row, $nino[2]);
    $sheetNinos->setCellValue('D' . $row, $nino[3]);
    $sheetNinos->setCellValue('E' . $row, $nino[4]);
    $sheetNinos->setCellValue('F' . $row, $nino[5]);
    $sheetNinos->setCellValue('G' . $row, $nino[6]);
    $row++;
}

// Ajustar ancho de columnas
$sheetNinos->getColumnDimension('A')->setWidth(10);
$sheetNinos->getColumnDimension('B')->setWidth(35);
$sheetNinos->getColumnDimension('C')->setWidth(12);
$sheetNinos->getColumnDimension('D')->setWidth(15);
$sheetNinos->getColumnDimension('E')->setWidth(35);
$sheetNinos->getColumnDimension('F')->setWidth(18);
$sheetNinos->getColumnDimension('G')->setWidth(10);

// ========== HOJA 2: EXTRA ==========
$sheetExtra = $objPHPExcel->createSheet();
$sheetExtra->setTitle('Extra');

// Encabezados
$sheetExtra->setCellValue('A1', 'id_nino');
$sheetExtra->setCellValue('B1', 'red');
$sheetExtra->setCellValue('C1', 'microred');
$sheetExtra->setCellValue('D1', 'eess_nacimiento');
$sheetExtra->setCellValue('E1', 'distrito');
$sheetExtra->setCellValue('F1', 'provincia');
$sheetExtra->setCellValue('G1', 'departamento');
$sheetExtra->setCellValue('H1', 'seguro');
$sheetExtra->setCellValue('I1', 'programa');

$sheetExtra->getStyle('A1:I1')->applyFromArray($headerStyle);

// Datos de ejemplo
$extras = [
    [1, '8', 'Hospital Regional', 'E001', 'Callería', 'Coronel Portillo', 'Ucayali', 'SIS', 'Juntos'],
    [2, '4', 'MicroRed Centro', 'E002', 'Callería', 'Coronel Portillo', 'Ucayali', 'ESSALUD', '-'],
    [3, '4', 'MicroRed Norte', 'E003', 'Yarinacocha', 'Coronel Portillo', 'Ucayali', 'SIS', '-'],
    [4, '6', 'MicroRed Yarinacocha', 'E004', 'Yarinacocha', 'Coronel Portillo', 'Ucayali', 'SIS', 'Juntos'],
    [5, '8', 'Hospital Regional', 'E005', 'Manantay', 'Coronel Portillo', 'Ucayali', 'ESSALUD', '-'],
    [6, '4', 'MicroRed Centro', 'E006', 'Callería', 'Coronel Portillo', 'Ucayali', 'SIS', 'Juntos'],
    [7, '8', 'Hospital Regional', 'E007', 'Callería', 'Coronel Portillo', 'Ucayali', 'SIS', '-'],
    [8, '4', 'MicroRed Norte', 'E008', 'Yarinacocha', 'Coronel Portillo', 'Ucayali', 'ESSALUD', 'Juntos'],
    [9, '6', 'MicroRed Yarinacocha', 'E009', 'Manantay', 'Coronel Portillo', 'Ucayali', 'SIS', '-'],
    [10, '8', 'Hospital Regional', 'E010', 'Callería', 'Coronel Portillo', 'Ucayali', 'SIS', 'Juntos'],
];

$row = 2;
foreach ($extras as $extra) {
    $sheetExtra->setCellValue('A' . $row, $extra[0]);
    $sheetExtra->setCellValue('B' . $row, $extra[1]);
    $sheetExtra->setCellValue('C' . $row, $extra[2]);
    $sheetExtra->setCellValue('D' . $row, $extra[3]);
    $sheetExtra->setCellValue('E' . $row, $extra[4]);
    $sheetExtra->setCellValue('F' . $row, $extra[5]);
    $sheetExtra->setCellValue('G' . $row, $extra[6]);
    $sheetExtra->setCellValue('H' . $row, $extra[7]);
    $sheetExtra->setCellValue('I' . $row, $extra[8]);
    $row++;
}

// Ajustar ancho de columnas
foreach (range('A', 'I') as $col) {
    $sheetExtra->getColumnDimension($col)->setWidth(18);
}

// ========== HOJA 3: MADRE ==========
$sheetMadre = $objPHPExcel->createSheet();
$sheetMadre->setTitle('Madre');

// Encabezados
$sheetMadre->setCellValue('A1', 'id_nino');
$sheetMadre->setCellValue('B1', 'dni');
$sheetMadre->setCellValue('C1', 'apellidos_nombres');
$sheetMadre->setCellValue('D1', 'celular');
$sheetMadre->setCellValue('E1', 'domicilio');
$sheetMadre->setCellValue('F1', 'referencia_direccion');

$sheetMadre->getStyle('A1:F1')->applyFromArray($headerStyle);

// Datos de ejemplo
$madres = [
    [1, '87654321', 'García López, María Elena', '987654321', 'Av. Principal 123', 'Frente al mercado'],
    [2, '12345678', 'Pérez Martínez, Rosa Isabel', '987654322', 'Jr. Los Olivos 456', 'Esquina con parque'],
    [3, '99887766', 'Rodríguez Silva, Carmen', '987654323', 'Av. Universitaria 789', 'Al lado del colegio'],
    [4, '55443322', 'Torres Flores, Juana', '987654324', 'Calle Las Flores 321', 'Cerca del centro de salud'],
    [5, '11223344', 'Vargas Ríos, Patricia', '987654325', 'Av. Libertad 654', 'Frente a la iglesia'],
    [6, '22334455', 'Mendoza Cruz, Elena', '987654326', 'Jr. San Martín 789', 'Cerca del mercado'],
    [7, '33445566', 'Ramírez Gutiérrez, Carmen', '987654327', 'Av. Brasil 123', 'Frente al parque'],
    [8, '44556677', 'López Sánchez, Rosa', '987654328', 'Calle Lima 456', 'Esquina principal'],
    [9, '55667788', 'Fernández Torres, María', '987654329', 'Av. Tacna 321', 'Al lado de la escuela'],
    [10, '66778899', 'González Morales, Ana', '987654330', 'Jr. Ayacucho 654', 'Cerca del hospital'],
];

$row = 2;
foreach ($madres as $madre) {
    $sheetMadre->setCellValue('A' . $row, $madre[0]);
    $sheetMadre->setCellValue('B' . $row, $madre[1]);
    $sheetMadre->setCellValue('C' . $row, $madre[2]);
    $sheetMadre->setCellValue('D' . $row, $madre[3]);
    $sheetMadre->setCellValue('E' . $row, $madre[4]);
    $sheetMadre->setCellValue('F' . $row, $madre[5]);
    $row++;
}

// Ajustar ancho de columnas
$sheetMadre->getColumnDimension('A')->setWidth(10);
$sheetMadre->getColumnDimension('B')->setWidth(15);
$sheetMadre->getColumnDimension('C')->setWidth(35);
$sheetMadre->getColumnDimension('D')->setWidth(15);
$sheetMadre->getColumnDimension('E')->setWidth(30);
$sheetMadre->getColumnDimension('F')->setWidth(30);

// ========== HOJA 4: CONTROLES_CRED ==========
$sheetControles = $objPHPExcel->createSheet();
$sheetControles->setTitle('Controles_CRED');

// Encabezados
$sheetControles->setCellValue('A1', 'id_nino');
$sheetControles->setCellValue('B1', 'numero_control');
$sheetControles->setCellValue('C1', 'fecha');
$sheetControles->setCellValue('D1', 'peso');
$sheetControles->setCellValue('E1', 'talla');
$sheetControles->setCellValue('F1', 'perimetro_cefalico');
$sheetControles->setCellValue('G1', 'estado_cred_once');
$sheetControles->setCellValue('H1', 'estado_cred_final');

$sheetControles->getStyle('A1:H1')->applyFromArray($headerStyle);

// Datos de ejemplo - Controles para los primeros 5 niños
// Niño 1 (nacido 2024-01-15) - 5 controles
$controles = [
    // Niño 1
    [1, 1, '2024-02-14', 5.5, 60.5, 38.5, 'CUMPLE', 'CUMPLE'],
    [1, 2, '2024-03-20', 6.2, 63.0, 39.0, 'CUMPLE', 'CUMPLE'],
    [1, 3, '2024-04-19', 6.8, 65.5, 39.5, 'CUMPLE', 'CUMPLE'],
    [1, 4, '2024-05-18', 7.2, 67.0, 40.0, 'CUMPLE', 'CUMPLE'],
    [1, 5, '2024-06-14', 7.8, 69.5, 40.5, 'CUMPLE', 'CUMPLE'],
    
    // Niño 2 (nacido 2024-02-20) - 4 controles
    [2, 1, '2024-03-20', 4.8, 58.0, 37.5, 'CUMPLE', 'CUMPLE'],
    [2, 2, '2024-04-20', 5.3, 60.0, 38.0, 'CUMPLE', 'CUMPLE'],
    [2, 3, '2024-05-20', 5.8, 62.0, 38.5, 'CUMPLE', 'CUMPLE'],
    [2, 4, '2024-06-19', 6.2, 64.0, 39.0, 'CUMPLE', 'CUMPLE'],
    
    // Niño 3 (nacido 2024-03-10) - 3 controles
    [3, 1, '2024-04-08', 5.0, 59.0, 38.0, 'CUMPLE', 'CUMPLE'],
    [3, 2, '2024-05-09', 5.5, 61.0, 38.5, 'CUMPLE', 'CUMPLE'],
    [3, 3, '2024-06-08', 6.0, 63.0, 39.0, 'CUMPLE', 'CUMPLE'],
    
    // Niño 4 (nacido 2024-04-05) - 2 controles
    [4, 1, '2024-05-04', 4.5, 57.0, 37.0, 'CUMPLE', 'CUMPLE'],
    [4, 2, '2024-06-04', 5.0, 59.0, 37.5, 'CUMPLE', 'CUMPLE'],
    
    // Niño 5 (nacido 2024-05-12) - 1 control
    [5, 1, '2024-06-11', 4.8, 58.0, 37.5, 'CUMPLE', 'CUMPLE'],
];

$row = 2;
foreach ($controles as $control) {
    $sheetControles->setCellValue('A' . $row, $control[0]);
    $sheetControles->setCellValue('B' . $row, $control[1]);
    $sheetControles->setCellValue('C' . $row, $control[2]);
    $sheetControles->setCellValue('D' . $row, $control[3]);
    $sheetControles->setCellValue('E' . $row, $control[4]);
    $sheetControles->setCellValue('F' . $row, $control[5]);
    $sheetControles->setCellValue('G' . $row, $control[6]);
    $sheetControles->setCellValue('H' . $row, $control[7]);
    $row++;
}

// Ajustar ancho de columnas
$sheetControles->getColumnDimension('A')->setWidth(10);
$sheetControles->getColumnDimension('B')->setWidth(15);
$sheetControles->getColumnDimension('C')->setWidth(15);
$sheetControles->getColumnDimension('D')->setWidth(10);
$sheetControles->getColumnDimension('E')->setWidth(10);
$sheetControles->getColumnDimension('F')->setWidth(18);
$sheetControles->getColumnDimension('G')->setWidth(18);
$sheetControles->getColumnDimension('H')->setWidth(18);

// Establecer la primera hoja como activa
$objPHPExcel->setActiveSheetIndex(0);

// Guardar el archivo
$outputFile = __DIR__ . '/ejemplo_importacion_siscadit.xlsx';
$objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save($outputFile);

echo "✅ Archivo Excel creado exitosamente: ejemplo_importacion_siscadit.xlsx\n";
echo "📊 Contenido:\n";
echo "   - 10 niños\n";
echo "   - 10 registros de datos extra\n";
echo "   - 10 registros de datos de madre\n";
echo "   - 15 controles CRED\n";
echo "\n";
echo "📁 Ubicación: " . $outputFile . "\n";
echo "\n";
echo "🚀 Ahora puedes importar este archivo en SISCADIT usando el botón 'Importar Datos'\n";

