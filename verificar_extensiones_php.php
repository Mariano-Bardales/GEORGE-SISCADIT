<?php
/**
 * Script de Verificación de Extensiones PHP Necesarias
 * 
 * Accede desde: http://localhost/GEORGE-SISCADIT/verificar_extensiones_php.php
 */

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificación de Extensiones PHP - GEORGE-SISCADIT</title>
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
            padding: 15px;
            border-left: 4px solid #007bff;
            margin: 15px 0;
            border-radius: 4px;
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
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
        }
        table th, table td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        table th {
            background-color: #007bff;
            color: white;
        }
        table tr:hover {
            background-color: #f5f5f5;
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
    <h1>🔍 Verificación de Extensiones PHP</h1>

    <?php
    $errores = [];
    $advertencias = [];
    $exitos = [];
    
    // Extensiones obligatorias para PhpSpreadsheet
    $extensionesObligatorias = [
        'zip' => [
            'nombre' => 'ZipArchive',
            'descripcion' => 'Necesaria para leer archivos Excel (.xlsx)',
            'clase' => 'ZipArchive'
        ],
        'xml' => [
            'nombre' => 'XML',
            'descripcion' => 'Necesaria para procesar archivos XML',
            'clase' => null
        ],
        'simplexml' => [
            'nombre' => 'SimpleXML',
            'descripcion' => 'Necesaria para procesar XML de forma simple',
            'clase' => 'SimpleXMLElement'
        ],
        'libxml' => [
            'nombre' => 'libxml',
            'descripcion' => 'Librería base para procesar XML',
            'clase' => null
        ],
        'mbstring' => [
            'nombre' => 'mbstring',
            'descripcion' => 'Necesaria para manejar caracteres especiales (ñ, acentos)',
            'clase' => null
        ],
        'iconv' => [
            'nombre' => 'iconv',
            'descripcion' => 'Necesaria para conversión de caracteres',
            'clase' => null
        ],
    ];
    
    // Extensiones recomendadas
    $extensionesRecomendadas = [
        'gd' => [
            'nombre' => 'GD',
            'descripcion' => 'Recomendada para procesar imágenes en Excel',
            'clase' => null
        ],
        'fileinfo' => [
            'nombre' => 'fileinfo',
            'descripcion' => 'Recomendada para detectar tipos de archivo',
            'clase' => null
        ],
    ];
    
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
        $errores[] = "PHP versión incompatible";
    }
    echo '</div>';
    
    // 2. Verificar extensiones obligatorias
    echo '<div class="section">';
    echo '<h2>2. Extensiones Obligatorias para PhpSpreadsheet</h2>';
    echo '<table>';
    echo '<tr><th>Extensión</th><th>Estado</th><th>Descripción</th><th>Verificación</th></tr>';
    
    foreach ($extensionesObligatorias as $ext => $info) {
        $loaded = extension_loaded($ext);
        $claseOk = true;
        
        if ($info['clase'] !== null) {
            $claseOk = class_exists($info['clase']);
        }
        
        $status = $loaded && $claseOk;
        
        if ($status) {
            echo '<tr>';
            echo '<td><code>' . htmlspecialchars($ext) . '</code></td>';
            echo '<td class="ok">✅ Habilitada</td>';
            echo '<td>' . htmlspecialchars($info['descripcion']) . '</td>';
            if ($info['clase'] !== null) {
                echo '<td>Clase <code>' . htmlspecialchars($info['clase']) . '</code> disponible</td>';
            } else {
                echo '<td>-</td>';
            }
            echo '</tr>';
            $exitos[] = "Extensión $ext habilitada";
        } else {
            echo '<tr>';
            echo '<td><code>' . htmlspecialchars($ext) . '</code></td>';
            echo '<td class="error">❌ NO Habilitada</td>';
            echo '<td>' . htmlspecialchars($info['descripcion']) . '</td>';
            if ($info['clase'] !== null && !$claseOk) {
                echo '<td class="error">Clase <code>' . htmlspecialchars($info['clase']) . '</code> NO disponible</td>';
            } else {
                echo '<td>-</td>';
            }
            echo '</tr>';
            $errores[] = "Extensión $ext no habilitada";
        }
    }
    
    echo '</table>';
    echo '</div>';
    
    // 3. Verificar extensiones recomendadas
    echo '<div class="section">';
    echo '<h2>3. Extensiones Recomendadas</h2>';
    echo '<table>';
    echo '<tr><th>Extensión</th><th>Estado</th><th>Descripción</th></tr>';
    
    foreach ($extensionesRecomendadas as $ext => $info) {
        $loaded = extension_loaded($ext);
        
        if ($loaded) {
            echo '<tr>';
            echo '<td><code>' . htmlspecialchars($ext) . '</code></td>';
            echo '<td class="ok">✅ Habilitada</td>';
            echo '<td>' . htmlspecialchars($info['descripcion']) . '</td>';
            echo '</tr>';
        } else {
            echo '<tr>';
            echo '<td><code>' . htmlspecialchars($ext) . '</code></td>';
            echo '<td class="warning">⚠️ NO Habilitada</td>';
            echo '<td>' . htmlspecialchars($info['descripcion']) . '</td>';
            echo '</tr>';
            $advertencias[] = "Extensión $ext no habilitada (recomendada)";
        }
    }
    
    echo '</table>';
    echo '</div>';
    
    // 4. Verificar PhpSpreadsheet
    echo '<div class="section">';
    echo '<h2>4. Verificación de PhpSpreadsheet</h2>';
    
    if (file_exists(__DIR__ . '/vendor/autoload.php')) {
        require_once __DIR__ . '/vendor/autoload.php';
        
        if (class_exists('PhpOffice\PhpSpreadsheet\IOFactory')) {
            echo '<p class="ok">✅ PhpSpreadsheet está instalado y disponible</p>';
            $exitos[] = "PhpSpreadsheet instalado";
            
            // Probar crear una instancia
            try {
                if (class_exists('ZipArchive')) {
                    echo '<p class="ok">✅ ZipArchive está disponible - Puede leer archivos Excel</p>';
                    $exitos[] = "ZipArchive disponible";
                } else {
                    echo '<p class="error">❌ ZipArchive NO está disponible - NO puede leer archivos Excel</p>';
                    $errores[] = "ZipArchive no disponible";
                }
            } catch (Exception $e) {
                echo '<p class="error">❌ Error al verificar PhpSpreadsheet: ' . htmlspecialchars($e->getMessage()) . '</p>';
                $errores[] = "Error en PhpSpreadsheet";
            }
        } else {
            echo '<p class="error">❌ PhpSpreadsheet NO está disponible</p>';
            $errores[] = "PhpSpreadsheet no disponible";
        }
    } else {
        echo '<p class="error">❌ Autoload de Composer no encontrado</p>';
        $errores[] = "Autoload no encontrado";
    }
    echo '</div>';
    
    // 5. Información del php.ini
    echo '<div class="section">';
    echo '<h2>5. Información de Configuración PHP</h2>';
    
    $phpIniPath = php_ini_loaded_file();
    echo '<p><strong>Archivo php.ini:</strong> <code>' . htmlspecialchars($phpIniPath) . '</code></p>';
    
    // Memory limit
    $memoryLimit = ini_get('memory_limit');
    echo '<p><strong>memory_limit:</strong> <code>' . htmlspecialchars($memoryLimit) . '</code></p>';
    
    // Upload max filesize
    $uploadMax = ini_get('upload_max_filesize');
    echo '<p><strong>upload_max_filesize:</strong> <code>' . htmlspecialchars($uploadMax) . '</code></p>';
    
    // Post max size
    $postMax = ini_get('post_max_size');
    echo '<p><strong>post_max_size:</strong> <code>' . htmlspecialchars($postMax) . '</code></p>';
    
    echo '</div>';
    
    // Resumen final
    echo '<div class="section">';
    echo '<h2>📊 Resumen</h2>';
    
    $totalErrores = count($errores);
    $totalAdvertencias = count($advertencias);
    $totalExitos = count($exitos);
    
    if ($totalErrores === 0) {
        echo '<div class="success-box">';
        echo '<h3 style="margin-top:0; color: #155724;">✅ ¡Todas las extensiones están correctas!</h3>';
        echo '<p><strong>El sistema puede importar archivos Excel sin problemas.</strong></p>';
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
    
    if ($totalAdvertencias > 0) {
        echo '<div class="info" style="background: #fff3cd; border-color: #ffc107;">';
        echo '<h3 style="margin-top:0; color: #856404;">⚠️ Advertencias</h3>';
        echo '<ul>';
        foreach ($advertencias as $advertencia) {
            echo '<li>' . htmlspecialchars($advertencia) . '</li>';
        }
        echo '</ul>';
        echo '</div>';
    }
    
    echo '<p><strong>Exitos:</strong> ' . $totalExitos . ' | <strong>Errores:</strong> ' . $totalErrores . ' | <strong>Advertencias:</strong> ' . $totalAdvertencias . '</p>';
    echo '</div>';
    
    // Instrucciones para corregir
    if ($totalErrores > 0) {
        echo '<div class="section">';
        echo '<h2>📝 Instrucciones para Corregir</h2>';
        echo '<ol>';
        echo '<li><strong>Habilitar extensiones PHP:</strong>';
        echo '<ul>';
        echo '<li>Abre el archivo: <code>' . htmlspecialchars($phpIniPath) . '</code></li>';
        echo '<li>Busca las extensiones que faltan (ej: <code>;extension=zip</code>)</li>';
        echo '<li>Quita el <code>;</code> al inicio (ej: <code>extension=zip</code>)</li>';
        echo '<li>Guarda el archivo</li>';
        echo '<li><strong>Reinicia Apache</strong> en XAMPP Control Panel</li>';
        echo '</ul>';
        echo '</li>';
        echo '<li><strong>Verificar que las extensiones estén habilitadas:</strong>';
        echo '<ul>';
        echo '<li>Recarga esta página después de reiniciar Apache</li>';
        echo '<li>O ejecuta: <code>php -m</code> en la terminal</li>';
        echo '</ul>';
        echo '</li>';
        echo '</ol>';
        echo '</div>';
    }
    ?>

    <div class="section">
        <h2>📚 Información Adicional</h2>
        <p>Para más información sobre la instalación, consulta:</p>
        <ul>
            <li><a href="verificar_importacion.php" target="_blank">verificar_importacion.php</a> - Verificación de requisitos para importación</li>
            <li><a href="BASE_DATOS_CONFIGURADA.md" target="_blank">BASE_DATOS_CONFIGURADA.md</a> - Documentación de base de datos</li>
        </ul>
    </div>

</body>
</html>






