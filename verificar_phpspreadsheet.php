<?php
/**
 * Script de Verificación de PhpSpreadsheet y ZipArchive
 * 
 * Accede desde: http://localhost/GEORGE-SISCADIT/verificar_phpspreadsheet.php
 */

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificación PhpSpreadsheet - GEORGE-SISCADIT</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            max-width: 1000px;
            margin: 20px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        h1 {
            color: #333;
            border-bottom: 4px solid #007bff;
            padding-bottom: 10px;
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
        .section {
            background: white;
            padding: 20px;
            margin: 15px 0;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        code {
            background: #f4f4f4;
            padding: 2px 6px;
            border-radius: 3px;
            font-family: 'Courier New', monospace;
            color: #d63384;
        }
        .success-box {
            background: #d4edda;
            border: 2px solid #28a745;
            padding: 20px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .error-box {
            background: #f8d7da;
            border: 2px solid #dc3545;
            padding: 20px;
            border-radius: 5px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <h1>🔍 Verificación de PhpSpreadsheet y ZipArchive</h1>

    <?php
    $errores = [];
    $exitos = [];
    
    // 1. Verificar PhpSpreadsheet
    echo '<div class="section">';
    echo '<h2>1. PhpSpreadsheet</h2>';
    
    if (file_exists(__DIR__ . '/vendor/autoload.php')) {
        require_once __DIR__ . '/vendor/autoload.php';
        
        if (class_exists('\PhpOffice\PhpSpreadsheet\IOFactory')) {
            echo '<p class="ok">✅ PhpSpreadsheet está instalado y disponible</p>';
            $exitos[] = "PhpSpreadsheet instalado";
            
            // Probar cargar un archivo de prueba
            try {
                // Intentar crear un objeto Spreadsheet vacío para verificar que funciona
                $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
                echo '<p class="ok">✅ PhpSpreadsheet puede crear objetos correctamente</p>';
                $exitos[] = "PhpSpreadsheet funcional";
            } catch (\Exception $e) {
                echo '<p class="error">❌ Error al crear objeto Spreadsheet: ' . htmlspecialchars($e->getMessage()) . '</p>';
                $errores[] = "Error en PhpSpreadsheet: " . $e->getMessage();
            }
        } else {
            echo '<p class="error">❌ PhpSpreadsheet NO está disponible</p>';
            echo '<p class="error">Ejecute: <code>composer require phpoffice/phpspreadsheet</code></p>';
            $errores[] = "PhpSpreadsheet no disponible";
        }
    } else {
        echo '<p class="error">❌ Autoload de Composer no encontrado</p>';
        $errores[] = "Autoload no encontrado";
    }
    echo '</div>';
    
    // 2. Verificar ZipArchive
    echo '<div class="section">';
    echo '<h2>2. Extensión ZipArchive</h2>';
    
    if (class_exists('ZipArchive')) {
        echo '<p class="ok">✅ ZipArchive está disponible</p>';
        $exitos[] = "ZipArchive disponible";
        
        // Probar crear una instancia
        try {
            $zip = new ZipArchive();
            echo '<p class="ok">✅ ZipArchive puede crear instancias correctamente</p>';
            $exitos[] = "ZipArchive funcional";
        } catch (\Exception $e) {
            echo '<p class="error">❌ Error al crear instancia ZipArchive: ' . htmlspecialchars($e->getMessage()) . '</p>';
            $errores[] = "Error en ZipArchive: " . $e->getMessage();
        }
    } else {
        echo '<p class="error">❌ ZipArchive NO está disponible</p>';
        echo '<p class="error">SOLUCIÓN:</p>';
        echo '<ol>';
        echo '<li>Abrir: <code>C:\\xampp82\\php\\php.ini</code></li>';
        echo '<li>Buscar: <code>;extension=zip</code></li>';
        echo '<li>Quitar el <code>;</code>: <code>extension=zip</code></li>';
        echo '<li>Guardar el archivo</li>';
        echo '<li><strong>Reiniciar Apache</strong> en XAMPP Control Panel</li>';
        echo '</ol>';
        $errores[] = "ZipArchive no disponible";
    }
    echo '</div>';
    
    // 3. Verificar PHPExcel (no debería estar)
    echo '<div class="section">';
    echo '<h2>3. PHPExcel (Antigua - No Compatible con PHP 8)</h2>';
    
    if (class_exists('\PHPExcel_IOFactory')) {
        echo '<p class="warning">⚠️ PHPExcel está instalado (NO compatible con PHP 8)</p>';
        echo '<p>El sistema intentará usar PhpSpreadsheet primero, pero PHPExcel puede causar conflictos.</p>';
        echo '<p>Recomendación: Desinstalar PHPExcel si no es necesario.</p>';
    } else {
        echo '<p class="ok">✅ PHPExcel no está instalado (correcto para PHP 8)</p>';
        $exitos[] = "PHPExcel no instalado";
    }
    echo '</div>';
    
    // 4. Información de PHP
    echo '<div class="section">';
    echo '<h2>4. Información de PHP</h2>';
    
    $phpVersion = phpversion();
    echo '<p><strong>Versión PHP:</strong> <code>' . htmlspecialchars($phpVersion) . '</code></p>';
    
    $phpIniPath = php_ini_loaded_file();
    echo '<p><strong>Archivo php.ini:</strong> <code>' . htmlspecialchars($phpIniPath) . '</code></p>';
    
    // Verificar extensión zip en php.ini
    if ($phpIniPath && file_exists($phpIniPath)) {
        $phpIniContent = file_get_contents($phpIniPath);
        if (preg_match('/^\s*extension\s*=\s*zip\s*$/m', $phpIniContent)) {
            echo '<p class="ok">✅ extension=zip está habilitada en php.ini</p>';
        } elseif (preg_match('/^\s*;extension\s*=\s*zip\s*$/m', $phpIniContent)) {
            echo '<p class="error">❌ extension=zip está comentada (;) en php.ini</p>';
            echo '<p>Necesitas quitar el <code>;</code> y reiniciar Apache.</p>';
        } else {
            echo '<p class="warning">⚠️ No se encontró extension=zip en php.ini</p>';
        }
    }
    
    echo '</div>';
    
    // Resumen final
    echo '<div class="section">';
    echo '<h2>📊 Resumen</h2>';
    
    $totalErrores = count($errores);
    $totalExitos = count($exitos);
    
    if ($totalErrores === 0) {
        echo '<div class="success-box">';
        echo '<h3 style="margin-top:0; color: #155724;">✅ ¡Todo está correcto!</h3>';
        echo '<p><strong>PhpSpreadsheet y ZipArchive están listos para importar archivos Excel.</strong></p>';
        echo '<ul>';
        foreach ($exitos as $exito) {
            echo '<li>' . htmlspecialchars($exito) . '</li>';
        }
        echo '</ul>';
        echo '</div>';
    } else {
        echo '<div class="error-box">';
        echo '<h3 style="margin-top:0; color: #721c24;">❌ Se encontraron problemas</h3>';
        echo '<p><strong>Debes resolver estos problemas para poder importar archivos Excel:</strong></p>';
        echo '<ul>';
        foreach ($errores as $error) {
            echo '<li>' . htmlspecialchars($error) . '</li>';
        }
        echo '</ul>';
        echo '</div>';
    }
    
    echo '<p><strong>Exitos:</strong> ' . $totalExitos . ' | <strong>Errores:</strong> ' . $totalErrores . '</p>';
    echo '</div>';
    ?>

    <div class="section">
        <h2>📚 Información Adicional</h2>
        <p>Para más información sobre la instalación, consulta:</p>
        <ul>
            <li><a href="EXTENSION_ZIP_HABILITADA.md" target="_blank">EXTENSION_ZIP_HABILITADA.md</a></li>
            <li><a href="verificar_extensiones_php.php" target="_blank">verificar_extensiones_php.php</a></li>
        </ul>
    </div>

</body>
</html>






