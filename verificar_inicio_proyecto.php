<?php
/**
 * Script de Verificación de Instalación del Proyecto GEORGE-SISCADIT
 * 
 * Accede desde: http://localhost/GEORGE-SISCADIT/verificar_inicio_proyecto.php
 */

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificación de Instalación - GEORGE-SISCADIT</title>
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
        .command {
            background: #2d2d2d;
            color: #f8f8f2;
            padding: 10px;
            border-radius: 4px;
            font-family: 'Courier New', monospace;
            margin: 5px 0;
            overflow-x: auto;
        }
        .summary-box {
            background: #fff3cd;
            border: 2px solid #ffc107;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .summary-box.error {
            background: #f8d7da;
            border-color: #dc3545;
        }
        .summary-box.success {
            background: #d4edda;
            border-color: #28a745;
        }
        ul {
            line-height: 1.8;
        }
    </style>
</head>
<body>
    <h1>🔍 Verificación de Instalación - GEORGE-SISCADIT</h1>

    <?php
    $errores = [];
    $advertencias = [];
    $exitos = [];

    // Función auxiliar para convertir valores de memoria a bytes
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

    // 1. Verificar archivo .env
    echo '<div class="section">';
    echo '<h2>1. Archivo de Configuración (.env)</h2>';
    
    $envPath = __DIR__ . '/.env';
    if (file_exists($envPath)) {
        echo '<p class="ok">✅ Archivo .env existe</p>';
        $exitos[] = "Archivo .env existe";
        
        // Leer contenido del .env
        $envContent = file_get_contents($envPath);
        
        // Verificar APP_KEY
        if (preg_match('/APP_KEY=base64:/', $envContent)) {
            echo '<p class="ok">✅ APP_KEY está configurado</p>';
            $exitos[] = "APP_KEY configurado";
        } else {
            echo '<p class="error">❌ APP_KEY no está configurado</p>';
            echo '<p class="command">Ejecutar: php artisan key:generate</p>';
            $errores[] = "APP_KEY no configurado";
        }
        
        // Verificar configuración de base de datos
        if (preg_match('/DB_DATABASE=(.+)/', $envContent, $matches)) {
            $dbName = trim($matches[1], '"\'');
            echo '<p class="ok">✅ DB_DATABASE configurado: <code>' . htmlspecialchars($dbName) . '</code></p>';
        } else {
            echo '<p class="error">❌ DB_DATABASE no está configurado</p>';
            $errores[] = "DB_DATABASE no configurado";
        }
        
        if (preg_match('/DB_USERNAME=(.+)/', $envContent, $matches)) {
            $dbUser = trim($matches[1], '"\'');
            echo '<p class="ok">✅ DB_USERNAME configurado: <code>' . htmlspecialchars($dbUser) . '</code></p>';
        }
        
    } else {
        echo '<p class="error">❌ Archivo .env NO existe</p>';
        echo '<p class="command">Crear desde .env.example: cp .env.example .env</p>';
        echo '<p class="command">O crear manualmente con la configuración básica</p>';
        $errores[] = "Archivo .env no existe";
    }
    echo '</div>';

    // 2. Verificar dependencias PHP (vendor)
    echo '<div class="section">';
    echo '<h2>2. Dependencias PHP (Composer)</h2>';
    
    $vendorPath = __DIR__ . '/vendor';
    if (file_exists($vendorPath) && is_dir($vendorPath)) {
        echo '<p class="ok">✅ Directorio vendor/ existe</p>';
        $exitos[] = "Dependencias PHP instaladas";
        
        // Verificar autoload
        $autoloadPath = $vendorPath . '/autoload.php';
        if (file_exists($autoloadPath)) {
            echo '<p class="ok">✅ Autoload de Composer existe</p>';
        } else {
            echo '<p class="error">❌ Autoload de Composer no existe</p>';
            $errores[] = "Autoload de Composer no encontrado";
        }
        
        // Verificar Laravel Framework
        $laravelPath = $vendorPath . '/laravel/framework';
        if (file_exists($laravelPath)) {
            echo '<p class="ok">✅ Laravel Framework instalado</p>';
        } else {
            echo '<p class="error">❌ Laravel Framework NO instalado</p>';
            $errores[] = "Laravel Framework no instalado";
        }
        
        // Verificar PhpSpreadsheet
        $phpspreadsheet = $vendorPath . '/phpoffice/phpspreadsheet';
        if (file_exists($phpspreadsheet)) {
            echo '<p class="ok">✅ phpoffice/phpspreadsheet instalado</p>';
        } else {
            echo '<p class="error">❌ phpoffice/phpspreadsheet NO instalado</p>';
            $errores[] = "PhpSpreadsheet no instalado";
        }
        
    } else {
        echo '<p class="error">❌ Directorio vendor/ NO existe</p>';
        echo '<p class="command">Ejecutar: composer install</p>';
        $errores[] = "Dependencias PHP no instaladas";
    }
    echo '</div>';

    // 3. Verificar dependencias Node.js
    echo '<div class="section">';
    echo '<h2>3. Dependencias Node.js (npm)</h2>';
    
    $nodeModulesPath = __DIR__ . '/node_modules';
    if (file_exists($nodeModulesPath) && is_dir($nodeModulesPath)) {
        echo '<p class="ok">✅ Directorio node_modules/ existe</p>';
        $exitos[] = "Dependencias Node.js instaladas";
        
        // Verificar Vite
        $vitePath = $nodeModulesPath . '/vite';
        if (file_exists($vitePath)) {
            echo '<p class="ok">✅ Vite instalado</p>';
        } else {
            echo '<p class="warning">⚠️ Vite no encontrado</p>';
            $advertencias[] = "Vite no encontrado";
        }
        
    } else {
        echo '<p class="error">❌ Directorio node_modules/ NO existe</p>';
        echo '<p class="command">Ejecutar: npm install</p>';
        $errores[] = "Dependencias Node.js no instaladas";
    }
    echo '</div>';

    // 4. Verificar assets compilados
    echo '<div class="section">';
    echo '<h2>4. Assets Frontend Compilados</h2>';
    
    $buildPath = __DIR__ . '/public/build';
    if (file_exists($buildPath) && is_dir($buildPath)) {
        echo '<p class="ok">✅ Directorio public/build/ existe</p>';
        $exitos[] = "Assets compilados";
        
        // Verificar manifest
        $manifestPath = $buildPath . '/.vite/manifest.json';
        if (file_exists($manifestPath)) {
            echo '<p class="ok">✅ Manifest de Vite existe</p>';
        } else {
            echo '<p class="warning">⚠️ Manifest de Vite no encontrado</p>';
            $advertencias[] = "Manifest de Vite no encontrado";
        }
        
    } else {
        echo '<p class="warning">⚠️ Directorio public/build/ NO existe</p>';
        echo '<p class="command">Para desarrollo: npm run dev</p>';
        echo '<p class="command">Para producción: npm run build</p>';
        $advertencias[] = "Assets no compilados (necesario para producción)";
    }
    echo '</div>';

    // 5. Verificar base de datos
    echo '<div class="section">';
    echo '<h2>5. Base de Datos</h2>';
    
    if (file_exists($envPath)) {
        $envContent = file_get_contents($envPath);
        
        if (preg_match('/DB_DATABASE=(.+)/', $envContent, $matches)) {
            $dbName = trim($matches[1], '"\'');
            $dbUser = 'root';
            $dbPass = '';
            
            if (preg_match('/DB_USERNAME=(.+)/', $envContent, $userMatches)) {
                $dbUser = trim($userMatches[1], '"\'');
            }
            if (preg_match('/DB_PASSWORD=(.+)/', $envContent, $passMatches)) {
                $dbPass = trim($passMatches[1], '"\'');
            }
            
            // Intentar conectar (requiere extensión PDO)
            if (extension_loaded('pdo_mysql')) {
                try {
                    $dsn = "mysql:host=127.0.0.1;dbname=$dbName;charset=utf8mb4";
                    $pdo = new PDO($dsn, $dbUser, $dbPass);
                    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    
                    echo '<p class="ok">✅ Conexión a base de datos exitosa</p>';
                    $exitos[] = "Conexión a base de datos exitosa";
                    
                    // Verificar si hay tablas
                    $stmt = $pdo->query("SHOW TABLES");
                    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
                    $tableCount = count($tables);
                    
                    if ($tableCount > 0) {
                        echo '<p class="ok">✅ Base de datos tiene ' . $tableCount . ' tabla(s)</p>';
                        $exitos[] = "Base de datos tiene tablas";
                    } else {
                        echo '<p class="warning">⚠️ Base de datos existe pero no tiene tablas</p>';
                        echo '<p class="command">Ejecutar: php artisan migrate</p>';
                        $advertencias[] = "Migraciones no ejecutadas";
                    }
                    
                } catch (PDOException $e) {
                    echo '<p class="error">❌ No se pudo conectar a la base de datos</p>';
                    echo '<p class="info">Error: ' . htmlspecialchars($e->getMessage()) . '</p>';
                    echo '<p class="command">Verificar que MySQL esté corriendo en XAMPP</p>';
                    $errores[] = "No se pudo conectar a la base de datos";
                }
            } else {
                echo '<p class="error">❌ Extensión PDO MySQL no está habilitada</p>';
                echo '<p class="command">Habilitar extension=pdo_mysql en php.ini</p>';
                $errores[] = "Extensión PDO MySQL no habilitada";
            }
        }
    } else {
        echo '<p class="warning">⚠️ No se puede verificar base de datos sin archivo .env</p>';
    }
    echo '</div>';

    // 6. Verificar permisos de storage
    echo '<div class="section">';
    echo '<h2>6. Permisos de Storage</h2>';
    
    $storagePath = __DIR__ . '/storage';
    $cachePath = __DIR__ . '/bootstrap/cache';
    
    if (file_exists($storagePath)) {
        if (is_writable($storagePath)) {
            echo '<p class="ok">✅ storage/ es escribible</p>';
            $exitos[] = "Storage con permisos correctos";
        } else {
            echo '<p class="error">❌ storage/ NO es escribible</p>';
            $errores[] = "Storage sin permisos de escritura";
        }
    } else {
        echo '<p class="error">❌ storage/ NO existe</p>';
        $errores[] = "Directorio storage no existe";
    }
    
    if (file_exists($cachePath)) {
        if (is_writable($cachePath)) {
            echo '<p class="ok">✅ bootstrap/cache/ es escribible</p>';
        } else {
            echo '<p class="warning">⚠️ bootstrap/cache/ NO es escribible</p>';
            $advertencias[] = "Bootstrap cache sin permisos de escritura";
        }
    } else {
        echo '<p class="warning">⚠️ bootstrap/cache/ NO existe</p>';
        $advertencias[] = "Directorio bootstrap/cache no existe";
    }
    echo '</div>';

    // Resumen final
    echo '<div class="section">';
    echo '<h2>📊 Resumen de Verificación</h2>';
    
    $totalErrores = count($errores);
    $totalAdvertencias = count($advertencias);
    $totalExitos = count($exitos);
    
    if ($totalErrores === 0 && $totalAdvertencias === 0) {
        echo '<div class="summary-box success">';
        echo '<h3 style="margin-top:0; color: #155724;">✅ ¡Todo está correcto!</h3>';
        echo '<p>El proyecto está listo para iniciar. Ejecuta los siguientes comandos:</p>';
        echo '<div class="command">npm run dev</div>';
        echo '<p>Y en otra terminal:</p>';
        echo '<div class="command">php artisan serve</div>';
        echo '<p>Luego abre: <a href="http://localhost:8000" target="_blank">http://localhost:8000</a></p>';
        echo '</div>';
    } else {
        if ($totalErrores > 0) {
            echo '<div class="summary-box error">';
            echo '<h3 style="margin-top:0; color: #721c24;">❌ Se encontraron ' . $totalErrores . ' error(es) crítico(s)</h3>';
            echo '<p>Debes resolver estos problemas antes de iniciar el proyecto:</p>';
            echo '<ul>';
            foreach ($errores as $error) {
                echo '<li>' . htmlspecialchars($error) . '</li>';
            }
            echo '</ul>';
            echo '</div>';
        }
        
        if ($totalAdvertencias > 0) {
            echo '<div class="summary-box">';
            echo '<h3 style="margin-top:0; color: #856404;">⚠️ Se encontraron ' . $totalAdvertencias . ' advertencia(s)</h3>';
            echo '<p>Estos problemas no impiden el funcionamiento pero son recomendados:</p>';
            echo '<ul>';
            foreach ($advertencias as $advertencia) {
                echo '<li>' . htmlspecialchars($advertencia) . '</li>';
            }
            echo '</ul>';
            echo '</div>';
        }
    }
    
    echo '<p><strong>Exitos:</strong> ' . $totalExitos . ' | <strong>Errores:</strong> ' . $totalErrores . ' | <strong>Advertencias:</strong> ' . $totalAdvertencias . '</p>';
    echo '</div>';

    // Comandos de instalación
    if ($totalErrores > 0) {
        echo '<div class="section">';
        echo '<h2>📝 Comandos de Instalación</h2>';
        echo '<p>Ejecuta estos comandos en orden para completar la instalación:</p>';
        echo '<ol>';
        
        if (!file_exists($vendorPath)) {
            echo '<li><strong>Instalar dependencias PHP:</strong>';
            echo '<div class="command">composer install</div>';
            echo '</li>';
        }
        
        if (!file_exists($nodeModulesPath)) {
            echo '<li><strong>Instalar dependencias Node.js:</strong>';
            echo '<div class="command">npm install</div>';
            echo '</li>';
        }
        
        if (!file_exists($envPath)) {
            echo '<li><strong>Crear archivo .env:</strong>';
            echo '<div class="command">cp .env.example .env</div>';
            echo '<p>O crear manualmente con la configuración básica</p>';
            echo '</li>';
        }
        
        if (file_exists($envPath)) {
            $envContent = file_get_contents($envPath);
            if (!preg_match('/APP_KEY=base64:/', $envContent)) {
                echo '<li><strong>Generar Application Key:</strong>';
                echo '<div class="command">php artisan key:generate</div>';
                echo '</li>';
            }
        }
        
        echo '<li><strong>Ejecutar migraciones:</strong>';
        echo '<div class="command">php artisan migrate</div>';
        echo '</li>';
        
        echo '<li><strong>Ejecutar seeders (crear usuario admin):</strong>';
        echo '<div class="command">php artisan db:seed</div>';
        echo '</li>';
        
        if (!file_exists($buildPath)) {
            echo '<li><strong>Compilar assets:</strong>';
            echo '<div class="command">npm run dev</div>';
            echo '<p>O para producción: <code>npm run build</code></p>';
            echo '</li>';
        }
        
        echo '</ol>';
        echo '</div>';
    }
    ?>

    <div class="section">
        <h2>📚 Documentación Adicional</h2>
        <ul>
            <li><a href="CHECKLIST_INICIO_PROYECTO.md" target="_blank">CHECKLIST_INICIO_PROYECTO.md</a> - Lista completa de verificación</li>
            <li><a href="GUIA_INSTALACION_SISTEMA.md" target="_blank">GUIA_INSTALACION_SISTEMA.md</a> - Guía completa de instalación</li>
            <li><a href="README.md" target="_blank">README.md</a> - Documentación del proyecto</li>
        </ul>
    </div>

</body>
</html>




