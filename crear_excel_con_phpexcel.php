<?php
/**
 * Script para crear un archivo Excel de prueba usando PHPExcel
 * 
 * Uso: php crear_excel_con_phpexcel.php
 */

require __DIR__ . '/vendor/autoload.php';

// Verificar si PHPExcel está disponible
if (!class_exists('PHPExcel')) {
    echo "❌ PHPExcel no está disponible.\n";
    echo "✅ Pero ya tienes archivos CSV creados que puedes usar:\n";
    echo "   - ninos_importacion.csv\n";
    echo "   - extra_importacion.csv\n";
    echo "   - madre_importacion.csv\n";
    echo "   - controles_cred_importacion.csv\n\n";
    echo "💡 Puedes usar estos CSV directamente o combinarlos en Excel manualmente.\n";
    exit(1);
}

echo "✅ Creando archivo Excel con PHPExcel...\n\n";

try {
    // Crear nuevo objeto PHPExcel
    $objPHPExcel = new PHPExcel();
    
    // ========== HOJA 1: NIÑOS ==========
    $sheet = $objPHPExcel->getActiveSheet();
    $sheet->setTitle('Niños');
    
    // Encabezados
    $headers = ['id_nino', 'establecimiento', 'tipo_doc', 'numero_doc', 'apellidos_nombres', 'fecha_nacimiento', 'genero'];
    $col = 'A';
    foreach ($headers as $header) {
        $sheet->setCellValue($col . '1', $header);
        $sheet->getStyle($col . '1')->getFont()->setBold(true);
        $sheet->getStyle($col . '1')->getFill()
            ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
            ->getStartColor()->setRGB('4472C4');
        $sheet->getStyle($col . '1')->getFont()->getColor()->setRGB('FFFFFF');
        $col++;
    }
    
    // Datos de ejemplo
    $ninos = [
        [1, 'Hospital Regional de Pucallpa', 'DNI', '10000001', 'García López Juan Carlos', '2024-12-01', 'M'],
        [2, 'Centro de Salud Callería', 'DNI', '10000002', 'Pérez Martínez Ana María', '2024-12-05', 'F'],
        [3, 'Posta de Salud Yarinacocha', 'DNI', '10000003', 'Rodríguez Silva Pedro', '2024-12-10', 'M'],
        [4, 'Centro de Salud Manantay', 'DNI', '10000004', 'Torres Flores María Elena', '2024-12-15', 'F'],
        [5, 'Hospital Amazónico', 'DNI', '10000005', 'Vargas Ríos Luis Alberto', '2024-12-20', 'M'],
    ];
    
    $row = 2;
    foreach ($ninos as $nino) {
        $col = 'A';
        foreach ($nino as $value) {
            $sheet->setCellValue($col . $row, $value);
            $col++;
        }
        $row++;
    }
    
    // Ajustar ancho de columnas
    foreach (range('A', 'G') as $col) {
        $sheet->getColumnDimension($col)->setAutoSize(true);
    }
    
    // ========== HOJA 2: EXTRA ==========
    $sheet2 = $objPHPExcel->createSheet();
    $sheet2->setTitle('Extra');
    
    $headersExtra = ['id_nino', 'red', 'microred', 'eess_nacimiento', 'distrito', 'provincia', 'departamento', 'seguro', 'programa'];
    $col = 'A';
    foreach ($headersExtra as $header) {
        $sheet2->setCellValue($col . '1', $header);
        $sheet2->getStyle($col . '1')->getFont()->setBold(true);
        $sheet2->getStyle($col . '1')->getFill()
            ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
            ->getStartColor()->setRGB('4472C4');
        $sheet2->getStyle($col . '1')->getFont()->getColor()->setRGB('FFFFFF');
        $col++;
    }
    
    $datosExtra = [
        [1, 'CORONEL PORTILLO', 'YARINACOCHA', 'HOSPITAL REGIONAL', 'Calleria', 'Coronel Portillo', 'Ucayali', 'SIS', 'Juntos'],
        [2, 'CORONEL PORTILLO', 'CALLERIA', 'CENTRO DE SALUD CALLERIA', 'Calleria', 'Coronel Portillo', 'Ucayali', 'SIS', 'Cuna Mas'],
        [3, 'CORONEL PORTILLO', 'YARINACOCHA', 'POSTA DE SALUD YARINACOCHA', 'Yarinacocha', 'Coronel Portillo', 'Ucayali', 'SIS', 'Juntos'],
        [4, 'CORONEL PORTILLO', 'MANANTAY', 'CENTRO DE SALUD MANANTAY', 'Manantay', 'Coronel Portillo', 'Ucayali', 'SIS', 'Cuna Mas'],
        [5, 'CORONEL PORTILLO', 'HOSPITAL AMAZONICO', 'HOSPITAL AMAZONICO', 'Yarinacocha', 'Coronel Portillo', 'Ucayali', 'SIS', 'Juntos'],
    ];
    
    $row = 2;
    foreach ($datosExtra as $extra) {
        $col = 'A';
        foreach ($extra as $value) {
            $sheet2->setCellValue($col . $row, $value);
            $col++;
        }
        $row++;
    }
    
    foreach (range('A', 'I') as $col) {
        $sheet2->getColumnDimension($col)->setAutoSize(true);
    }
    
    // ========== HOJA 3: MADRE ==========
    $sheet3 = $objPHPExcel->createSheet();
    $sheet3->setTitle('Madre');
    
    $headersMadre = ['id_nino', 'dni', 'apellidos_nombres', 'celular', 'domicilio', 'referencia_direccion'];
    $col = 'A';
    foreach ($headersMadre as $header) {
        $sheet3->setCellValue($col . '1', $header);
        $sheet3->getStyle($col . '1')->getFont()->setBold(true);
        $sheet3->getStyle($col . '1')->getFill()
            ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
            ->getStartColor()->setRGB('4472C4');
        $sheet3->getStyle($col . '1')->getFont()->getColor()->setRGB('FFFFFF');
        $col++;
    }
    
    $datosMadre = [
        [1, '87654321', 'García López María', '987654321', 'Jr. Los Pinos 123', 'Casa de la esquina'],
        [2, '87654322', 'Pérez Martínez Laura', '987654322', 'Av. Las Palmeras 456', 'Frente al colegio'],
        [3, '87654323', 'Rodríguez Silva Sofía', '987654323', 'Calle Los Girasoles 789', 'Al lado de la tienda'],
        [4, '87654324', 'Torres Flores Carmen', '987654324', 'Jr. Los Tulipanes 101', 'Cerca al mercado'],
        [5, '87654325', 'Vargas Ríos Patricia', '987654325', 'Av. Las Orquídeas 202', 'Detrás de la iglesia'],
    ];
    
    $row = 2;
    foreach ($datosMadre as $madre) {
        $col = 'A';
        foreach ($madre as $value) {
            $sheet3->setCellValue($col . $row, $value);
            $col++;
        }
        $row++;
    }
    
    foreach (range('A', 'F') as $col) {
        $sheet3->getColumnDimension($col)->setAutoSize(true);
    }
    
    // ========== HOJA 4: CONTROLES_CRED ==========
    $sheet4 = $objPHPExcel->createSheet();
    $sheet4->setTitle('Controles_CRED');
    
    $headersCred = ['id_nino', 'numero_control', 'fecha', 'peso', 'talla', 'perimetro_cefalico', 'estado_cred_once', 'estado_cred_final'];
    $col = 'A';
    foreach ($headersCred as $header) {
        $sheet4->setCellValue($col . '1', $header);
        $sheet4->getStyle($col . '1')->getFont()->setBold(true);
        $sheet4->getStyle($col . '1')->getFill()
            ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
            ->getStartColor()->setRGB('4472C4');
        $sheet4->getStyle($col . '1')->getFont()->getColor()->setRGB('FFFFFF');
        $col++;
    }
    
    // Generar controles CRED
    $controlesCred = [
        [1, 1, '2024-12-30', '4.30', '55.0', '36.0', 'CUMPLE', 'CUMPLE'],
        [1, 2, '2025-01-29', '5.10', '60.0', '37.0', 'CUMPLE', 'CUMPLE'],
        [1, 3, '2025-02-28', '5.90', '65.0', '38.0', 'CUMPLE', 'CUMPLE'],
        [1, 4, '2025-03-30', '6.70', '70.0', '39.0', 'CUMPLE', 'CUMPLE'],
        [1, 5, '2025-04-29', '7.50', '75.0', '40.0', 'CUMPLE', 'CUMPLE'],
        [2, 1, '2025-01-03', '4.30', '55.0', '36.0', 'CUMPLE', 'CUMPLE'],
        [2, 2, '2025-02-02', '5.10', '60.0', '37.0', 'CUMPLE', 'CUMPLE'],
        [2, 3, '2025-03-04', '5.90', '65.0', '38.0', 'CUMPLE', 'CUMPLE'],
        [2, 4, '2025-04-03', '6.70', '70.0', '39.0', 'CUMPLE', 'CUMPLE'],
        [2, 5, '2025-05-03', '7.50', '75.0', '40.0', 'CUMPLE', 'CUMPLE'],
        [3, 1, '2025-01-08', '4.30', '55.0', '36.0', 'CUMPLE', 'CUMPLE'],
        [3, 2, '2025-02-07', '5.10', '60.0', '37.0', 'CUMPLE', 'CUMPLE'],
        [3, 3, '2025-03-09', '5.90', '65.0', '38.0', 'CUMPLE', 'CUMPLE'],
        [3, 4, '2025-04-08', '6.70', '70.0', '39.0', 'CUMPLE', 'CUMPLE'],
        [3, 5, '2025-05-08', '7.50', '75.0', '40.0', 'CUMPLE', 'CUMPLE'],
        [4, 1, '2025-01-13', '4.30', '55.0', '36.0', 'CUMPLE', 'CUMPLE'],
        [4, 2, '2025-02-12', '5.10', '60.0', '37.0', 'CUMPLE', 'CUMPLE'],
        [4, 3, '2025-03-14', '5.90', '65.0', '38.0', 'CUMPLE', 'CUMPLE'],
        [4, 4, '2025-04-13', '6.70', '70.0', '39.0', 'CUMPLE', 'CUMPLE'],
        [4, 5, '2025-05-13', '7.50', '75.0', '40.0', 'CUMPLE', 'CUMPLE'],
        [5, 1, '2025-01-18', '4.30', '55.0', '36.0', 'CUMPLE', 'CUMPLE'],
        [5, 2, '2025-02-17', '5.10', '60.0', '37.0', 'CUMPLE', 'CUMPLE'],
        [5, 3, '2025-03-19', '5.90', '65.0', '38.0', 'CUMPLE', 'CUMPLE'],
        [5, 4, '2025-04-18', '6.70', '70.0', '39.0', 'CUMPLE', 'CUMPLE'],
        [5, 5, '2025-05-18', '7.50', '75.0', '40.0', 'CUMPLE', 'CUMPLE'],
    ];
    
    $row = 2;
    foreach ($controlesCred as $control) {
        $col = 'A';
        foreach ($control as $value) {
            $sheet4->setCellValue($col . $row, $value);
            $col++;
        }
        $row++;
    }
    
    foreach (range('A', 'H') as $col) {
        $sheet4->getColumnDimension($col)->setAutoSize(true);
    }
    
    // Activar la primera hoja
    $objPHPExcel->setActiveSheetIndex(0);
    
    // Guardar el archivo
    $filename = 'importacion_prueba_siscadit.xlsx';
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $objWriter->save($filename);
    
    echo "✅ Archivo Excel creado exitosamente: {$filename}\n\n";
    echo "📊 Contenido del archivo:\n";
    echo "   - Hoja 'Niños': 5 niños\n";
    echo "   - Hoja 'Extra': 5 registros de datos extra\n";
    echo "   - Hoja 'Madre': 5 registros de madres\n";
    echo "   - Hoja 'Controles_CRED': 25 controles (5 controles por niño)\n\n";
    echo "📝 Instrucciones para importar:\n";
    echo "   1. Abre el archivo {$filename}\n";
    echo "   2. Ve a 'Controles CRED' en el sistema\n";
    echo "   3. Haz clic en 'Importar desde Excel'\n";
    echo "   4. Selecciona el archivo {$filename}\n";
    echo "   5. Espera el mensaje de éxito\n";
    echo "   6. Los datos aparecerán automáticamente en la tabla\n\n";
    
} catch (\Exception $e) {
    echo "❌ Error al crear el archivo Excel: " . $e->getMessage() . "\n";
    echo "💡 Usa los archivos CSV creados anteriormente:\n";
    echo "   - ninos_importacion.csv\n";
    echo "   - extra_importacion.csv\n";
    echo "   - madre_importacion.csv\n";
    echo "   - controles_cred_importacion.csv\n";
    echo "\n   O combínalos manualmente en Excel.\n";
}

