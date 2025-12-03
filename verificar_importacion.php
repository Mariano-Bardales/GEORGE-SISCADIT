<?php
/**
 * Script de Verificación de Requisitos para Importación
 * 
 * Accede desde: http://localhost/GEORGE-SISCADIT/verificar_importacion.php
 */

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificación de Requisitos - Importación</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 900px;
            margin: 20px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        h1 {
            color: #333;
            border-bottom: 3px solid #007bff;
            padding-bottom: 10px;
        }
        h2 {
            color: #555;
            margin-top: 30px;
            border-left: 4px solid #007bff;
            padding-left: 10px;
        }
        .ok {
            color: #28a745;
            font-weight: bold;
        }
        .error {
            color: #dc3545;
            font-weight: bold;
        }
        .warning {
            color: #ffc107;
            font-weight: bold;
        }
        .info {
            background: #e7f3ff;
            padding: 10px;
            border-left: 4px solid #007bff;
            margin: 10px 0;
        }
        .section {
            background: white;
            padding: 15px;
            margin: 15px 0;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        code {
            background: #f4f4f4;
            padding: 2px 6px;
            border-radius: 3px;
            font-family: 'Courier New', monospace;
        }
    </style>
</head>
<body>
    <h1>🔍 Verificación de Requisitos para Importación</h1>

    <?php
    $errores = [];
    $advertencias = [];
    $exitos = [];

    // 1. Verificar versión de PHP
    echo '<div class="section">';
    echo '<h2>1. Versión de PHP</h2>';
    $phpVersion = phpversion();
    $phpOk = version_compare($phpVersion, '8.1.0', '>=');
    
    if ($phpOk) {
        echo '<p class="ok">✅ PHP ' . $phpVersion . ' (Compatible)</p>';
        $exitos[] = "PHP versión correcta";
    } else {
        echo '<p class="error">❌ PHP ' . $phpVersion . ' (Se requiere PHP 8.1 o superior)</p>';
        $errores[] = "PHP versión incompatible. Actualiza a PHP 8.1+";
    }
    echo '</div>';

    // 2. Verificar extensiones obligatorias
    echo '<div class="section">';
    echo '<h2>2. Extensiones PHP Obligatorias</h2>';
    
    $extensionesObligatorias = [
        'zip' => 'Para leer archivos Excel (.xlsx)',
        'xml' => 'Para procesar archivos XML',
        'simplexml' => 'Para procesar XML de forma simple',
        'libxml' => 'Librería base para procesar XML',
        'mbstring' => 'Para manejar caracteres especiales (ñ, acentos)',
        'iconv' => 'Para conversión de caracteres',
    ];
    
    foreach ($extensionesObligatorias as $ext => $desc) {
        $loaded = extension_loaded($ext);
        if ($loaded) {
            echo '<p class="ok">✅ ' . $ext . ' - ' . $desc . '</p>';
            $exitos[] = "Extensión $ext habilitada";
        } else {
            echo '<p class="error">❌ ' . $ext . ' - NO HABILITADA - ' . $desc . '</p>';
            $errores[] = "Extensión $ext no habilitada. Habilítala en php.ini";
        }
    }
    echo '</div>';

    // 3. Verificar extensiones recomendadas
    echo '<div class="section">';
    echo '<h2>3. Extensiones PHP Recomendadas</h2>';
    
    $extensionesRecomendadas = [
        'gd' => 'Para procesar imágenes en Excel',
        'fileinfo' => 'Para detectar tipos de archivo',
    ];
    
    foreach ($extensionesRecomendadas as $ext => $desc) {
        $loaded = extension_loaded($ext);
        if ($loaded) {
            echo '<p class="ok">✅ ' . $ext . ' - ' . $desc . '</p>';
        } else {
            echo '<p class="warning">⚠️ ' . $ext . ' - NO HABILITADA - ' . $desc . '</p>';
            $advertencias[] = "Extensión $ext no habilitada (recomendada)";
        }
    }
    echo '</div>';

    // 4. Verificar librerías de Composer
    echo '<div class="section">';
    echo '<h2>4. Librerías de Composer</h2>';
    
    $vendorPath = __DIR__ . '/vendor';
    if (file_exists($vendorPath)) {
        echo '<p class="ok">✅ Carpeta vendor existe</p>';
        
        // Verificar PhpSpreadsheet
        $phpspreadsheet = $vendorPath . '/phpoffice/phpspreadsheet';
        if (file_exists($phpspreadsheet)) {
            echo '<p class="ok">✅ phpoffice/phpspreadsheet instalado</p>';
            $exitos[] = "PhpSpreadsheet instalado";
        } else {
            echo '<p class="error">❌ phpoffice/phpspreadsheet NO instalado</p>';
            $errores[] = "PhpSpreadsheet no instalado. Ejecuta: composer install";
        }
        
        // Verificar Maatwebsite Excel
        $maatwebsite = $vendorPath . '/maatwebsite/excel';
        if (file_exists($maatwebsite)) {
            echo '<p class="ok">✅ maatwebsite/excel instalado</p>';
            $exitos[] = "Maatwebsite Excel instalado";
        } else {
            echo '<p class="error">❌ maatwebsite/excel NO instalado</p>';
            $errores[] = "Maatwebsite Excel no instalado. Ejecuta: composer install";
        }
    } else {
        echo '<p class="error">❌ Carpeta vendor NO existe</p>';
        $errores[] = "Carpeta vendor no existe. Ejecuta: composer install";
    }
    echo '</div>';

    // 5. Verificar permisos de carpetas
    echo '<div class="section">';
    echo '<h2>5. Permisos de Carpetas</h2>';
    
    $carpetas = [
        'storage' => 'Para guardar archivos temporales',
        'bootstrap/cache' => 'Para cache de Laravel',
    ];
    
    foreach ($carpetas as $carpeta => $desc) {
        $ruta = __DIR__ . '/' . $carpeta;
        if (file_exists($ruta)) {
            $writable = is_writable($ruta);
            if ($writable) {
                echo '<p class="ok">✅ ' . $carpeta . ' (escribible) - ' . $desc . '</p>';
                $exitos[] = "Carpeta $carpeta con permisos correctos";
            } else {
                echo '<p class="error">❌ ' . $carpeta . ' (NO escribible) - ' . $desc . '</p>';
                $errores[] = "Carpeta $carpeta sin permisos de escritura";
            }
        } else {
            echo '<p class="error">❌ ' . $carpeta . ' (NO existe) - ' . $desc . '</p>';
            $errores[] = "Carpeta $carpeta no existe";
        }
    }
    echo '</div>';

    // 6. Verificar configuración PHP
    echo '<div class="section">';
    echo '<h2>6. Configuración PHP</h2>';
    
    // Memory limit
    $memoryLimit = ini_get('memory_limit');
    $memoryBytes = return_bytes($memoryLimit);
    $memoryOk = $memoryBytes >= 128 * 1024 * 1024; // 128MB mínimo
    
    if ($memoryOk) {
        echo '<p class="ok">✅ memory_limit: ' . $memoryLimit . '</p>';
    } else {
        echo '<p class="warning">⚠️ memory_limit: ' . $memoryLimit . ' (Recomendado: 256M o más)</p>';
        $advertencias[] = "Memory limit bajo. Considera aumentarlo a 256M";
    }
    
    // Upload max filesize
    $uploadMax = ini_get('upload_max_filesize');
    $uploadBytes = return_bytes($uploadMax);
    $uploadOk = $uploadBytes >= 10 * 1024 * 1024; // 10MB mínimo
    
    if ($uploadOk) {
        echo '<p class="ok">✅ upload_max_filesize: ' . $uploadMax . '</p>';
    } else {
        echo '<p class="warning">⚠️ upload_max_filesize: ' . $uploadMax . ' (Recomendado: 10M o más)</p>';
        $advertencias[] = "Upload max filesize bajo. Considera aumentarlo a 10M";
    }
    
    // Post max size
    $postMax = ini_get('post_max_size');
    $postBytes = return_bytes($postMax);
    $postOk = $postBytes >= 12 * 1024 * 1024; // 12MB mínimo
    
    if ($postOk) {
        echo '<p class="ok">✅ post_max_size: ' . $postMax . '</p>';
    } else {
        echo '<p class="warning">⚠️ post_max_size: ' . $postMax . ' (Recomendado: 12M o más)</p>';
        $advertencias[] = "Post max size bajo. Considera aumentarlo a 12M";
    }
    echo '</div>';

    // Resumen final
    echo '<div class="section">';
    echo '<h2>📊 Resumen</h2>';
    
    $totalErrores = count($errores);
    $totalAdvertencias = count($advertencias);
    $totalExitos = count($exitos);
    
    if ($totalErrores === 0 && $totalAdvertencias === 0) {
        echo '<div class="info">';
        echo '<p class="ok"><strong>✅ ¡Todo está correcto! El sistema está listo para importar archivos.</strong></p>';
        echo '</div>';
    } else {
        if ($totalErrores > 0) {
            echo '<div class="info">';
            echo '<p class="error"><strong>❌ Se encontraron ' . $totalErrores . ' error(es) crítico(s):</strong></p>';
            echo '<ul>';
            foreach ($errores as $error) {
                echo '<li>' . $error . '</li>';
            }
            echo '</ul>';
            echo '</div>';
        }
        
        if ($totalAdvertencias > 0) {
            echo '<div class="info">';
            echo '<p class="warning"><strong>⚠️ Se encontraron ' . $totalAdvertencias . ' advertencia(s):</strong></p>';
            echo '<ul>';
            foreach ($advertencias as $advertencia) {
                echo '<li>' . $advertencia . '</li>';
            }
            echo '</ul>';
            echo '</div>';
        }
    }
    
    echo '<p><strong>Exitos:</strong> ' . $totalExitos . ' | <strong>Errores:</strong> ' . $totalErrores . ' | <strong>Advertencias:</strong> ' . $totalAdvertencias . '</p>';
    echo '</div>';

    // Instrucciones
    if ($totalErrores > 0) {
        echo '<div class="section">';
        echo '<h2>📝 Instrucciones para Corregir</h2>';
        echo '<ol>';
        echo '<li><strong>Habilitar extensiones PHP:</strong>';
        echo '<ul>';
        echo '<li>Abre <code>C:\\xampp\\php\\php.ini</code></li>';
        echo '<li>Busca las extensiones que faltan (ej: <code>;extension=zip</code>)</li>';
        echo '<li>Quita el <code>;</code> al inicio (ej: <code>extension=zip</code>)</li>';
        echo '<li>Guarda el archivo y reinicia Apache</li>';
        echo '</ul>';
        echo '</li>';
        echo '<li><strong>Instalar dependencias de Composer:</strong>';
        echo '<ul>';
        echo '<li>Abre Git Bash o Terminal</li>';
        echo '<li>Navega a: <code>cd C:\\xampp\\htdocs\\GEORGE-SISCADIT</code></li>';
        echo '<li>Ejecuta: <code>composer install</code></li>';
        echo '</ul>';
        echo '</li>';
        echo '<li><strong>Corregir permisos:</strong>';
        echo '<ul>';
        echo '<li>En Windows, asegúrate de que las carpetas <code>storage</code> y <code>bootstrap/cache</code> tengan permisos de escritura</li>';
        echo '</ul>';
        echo '</li>';
        echo '</ol>';
        echo '</div>';
    }
    ?>

    <div class="section">
        <h2>📚 Documentación</h2>
        <p>Para más información, consulta el archivo <code>REQUISITOS_IMPORTACION.md</code></p>
    </div>

</body>
</html>

<?php
/**
 * Función auxiliar para convertir valores de memoria a bytes
 */
function return_bytes($val) {
    $val = trim($val);
    $last = strtolower($val[strlen($val)-1]);
    $val = (int)$val;
    
    switch($last) {
        case 'g':
            $val *= 1024;
        case 'm':
            $val *= 1024;
        case 'k':
            $val *= 1024;
    }
    
    return $val;
}
?>


