<?php
/**
 * Verificación Rápida de ZipArchive desde Web
 * 
 * Accede desde: http://localhost/GEORGE-SISCADIT/public/verificar_zip_web.php
 */

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificación ZipArchive - Web</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .ok { color: #28a745; font-weight: bold; font-size: 18px; }
        .error { color: #dc3545; font-weight: bold; font-size: 18px; }
        .box {
            background: white;
            padding: 20px;
            margin: 20px 0;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        code { background: #f4f4f4; padding: 2px 6px; border-radius: 3px; }
        h1 { color: #333; border-bottom: 3px solid #007bff; padding-bottom: 10px; }
    </style>
</head>
<body>
    <h1>🔍 Verificación de ZipArchive (Web)</h1>
    
    <div class="box">
        <h2>Estado de ZipArchive</h2>
        <?php
        if (class_exists('ZipArchive')) {
            echo '<p class="ok">✅ ZipArchive está DISPONIBLE</p>';
            echo '<p>La extensión está cargada correctamente en Apache.</p>';
            
            // Probar crear instancia
            try {
                $zip = new ZipArchive();
                echo '<p class="ok">✅ ZipArchive puede crear instancias correctamente</p>';
            } catch (Exception $e) {
                echo '<p class="error">❌ Error al crear instancia: ' . htmlspecialchars($e->getMessage()) . '</p>';
            }
        } else {
            echo '<p class="error">❌ ZipArchive NO está disponible</p>';
            echo '<p><strong>SOLUCIÓN:</strong></p>';
            echo '<ol>';
            echo '<li>Abrir XAMPP Control Panel</li>';
            echo '<li>Detener Apache (Stop)</li>';
            echo '<li>Esperar 3-5 segundos</li>';
            echo '<li>Iniciar Apache (Start)</li>';
            echo '<li>Recargar esta página</li>';
            echo '</ol>';
        }
        ?>
    </div>
    
    <div class="box">
        <h2>Información de PHP</h2>
        <p><strong>Versión PHP:</strong> <?php echo phpversion(); ?></p>
        <p><strong>Archivo php.ini:</strong> <code><?php echo php_ini_loaded_file(); ?></code></p>
        <p><strong>Extension dir:</strong> <code><?php echo ini_get('extension_dir'); ?></code></p>
    </div>
    
    <div class="box">
        <h2>PhpSpreadsheet</h2>
        <?php
        if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
            require_once __DIR__ . '/../vendor/autoload.php';
            
            if (class_exists('\PhpOffice\PhpSpreadsheet\IOFactory')) {
                echo '<p class="ok">✅ PhpSpreadsheet está disponible</p>';
            } else {
                echo '<p class="error">❌ PhpSpreadsheet NO está disponible</p>';
            }
        } else {
            echo '<p class="error">❌ Autoload no encontrado</p>';
        }
        ?>
    </div>
    
    <div class="box">
        <h2>Próximos Pasos</h2>
        <?php if (class_exists('ZipArchive')): ?>
            <p class="ok">✅ ¡Todo está correcto! Puedes importar archivos Excel.</p>
            <p><a href="../verificar_phpspreadsheet.php">Ver verificación completa</a></p>
        <?php else: ?>
            <p class="error">⚠️ Reinicia Apache y recarga esta página.</p>
            <p><a href="REINICIAR_APACHE_INSTRUCCIONES.md">Ver instrucciones detalladas</a></p>
        <?php endif; ?>
    </div>
</body>
</html>






