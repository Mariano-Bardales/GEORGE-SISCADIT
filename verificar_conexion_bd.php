<?php
/**
 * Script de Verificación de Conexión a Base de Datos
 * 
 * Accede desde: http://localhost/GEORGE-SISCADIT/verificar_conexion_bd.php
 */

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificación de Conexión a Base de Datos - GEORGE-SISCADIT</title>
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
            border-bottom: 4px solid #28a745;
            padding-bottom: 10px;
        }
        h2 {
            color: #555;
            margin-top: 30px;
            border-left: 4px solid #28a745;
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
            background: #d4edda;
            padding: 15px;
            border-left: 4px solid #28a745;
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
            background-color: #28a745;
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
    </style>
</head>
<body>
    <h1>🔍 Verificación de Conexión a Base de Datos</h1>

    <?php
    require __DIR__ . '/vendor/autoload.php';
    
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();
    
    $dbHost = $_ENV['DB_HOST'] ?? '127.0.0.1';
    $dbPort = $_ENV['DB_PORT'] ?? '3306';
    $dbDatabase = $_ENV['DB_DATABASE'] ?? '';
    $dbUsername = $_ENV['DB_USERNAME'] ?? 'root';
    $dbPassword = $_ENV['DB_PASSWORD'] ?? '';
    
    $errores = [];
    $exitos = [];
    
    // 1. Verificar configuración
    echo '<div class="section">';
    echo '<h2>1. Configuración de Base de Datos</h2>';
    echo '<table>';
    echo '<tr><th>Parámetro</th><th>Valor</th></tr>';
    echo '<tr><td>Host</td><td><code>' . htmlspecialchars($dbHost) . '</code></td></tr>';
    echo '<tr><td>Puerto</td><td><code>' . htmlspecialchars($dbPort) . '</code></td></tr>';
    echo '<tr><td>Base de Datos</td><td><code>' . htmlspecialchars($dbDatabase) . '</code></td></tr>';
    echo '<tr><td>Usuario</td><td><code>' . htmlspecialchars($dbUsername) . '</code></td></tr>';
    echo '<tr><td>Contraseña</td><td><code>' . (empty($dbPassword) ? '(vacía)' : '***') . '</code></td></tr>';
    echo '</table>';
    echo '</div>';
    
    // 2. Verificar conexión
    echo '<div class="section">';
    echo '<h2>2. Prueba de Conexión</h2>';
    
    try {
        $dsn = "mysql:host=$dbHost;port=$dbPort;dbname=$dbDatabase;charset=utf8mb4";
        $pdo = new PDO($dsn, $dbUsername, $dbPassword);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        echo '<p class="ok">✅ Conexión exitosa a la base de datos</p>';
        $exitos[] = "Conexión establecida correctamente";
        
        // 3. Verificar tablas
        echo '<div class="section">';
        echo '<h2>3. Tablas en la Base de Datos</h2>';
        
        $stmt = $pdo->query("SHOW TABLES");
        $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
        $tableCount = count($tables);
        
        if ($tableCount > 0) {
            echo '<p class="ok">✅ Se encontraron <strong>' . $tableCount . ' tabla(s)</strong> en la base de datos</p>';
            $exitos[] = "$tableCount tablas encontradas";
            
            echo '<table>';
            echo '<tr><th>#</th><th>Nombre de Tabla</th><th>Registros</th></tr>';
            
            foreach ($tables as $index => $table) {
                $countStmt = $pdo->query("SELECT COUNT(*) FROM `$table`");
                $count = $countStmt->fetchColumn();
                
                echo '<tr>';
                echo '<td>' . ($index + 1) . '</td>';
                echo '<td><code>' . htmlspecialchars($table) . '</code></td>';
                echo '<td>' . number_format($count) . '</td>';
                echo '</tr>';
            }
            
            echo '</table>';
        } else {
            echo '<p class="error">❌ No se encontraron tablas en la base de datos</p>';
            $errores[] = "No hay tablas en la base de datos. Ejecutar: php artisan migrate";
        }
        echo '</div>';
        
        // 4. Verificar usuarios
        echo '<div class="section">';
        echo '<h2>4. Usuarios en el Sistema</h2>';
        
        if (in_array('users', $tables)) {
            $stmt = $pdo->query("SELECT id, name, email, created_at FROM users ORDER BY id");
            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            if (count($users) > 0) {
                echo '<p class="ok">✅ Se encontraron <strong>' . count($users) . ' usuario(s)</strong> en el sistema</p>';
                $exitos[] = count($users) . " usuarios encontrados";
                
                echo '<table>';
                echo '<tr><th>ID</th><th>Nombre</th><th>Email</th><th>Fecha Creación</th></tr>';
                
                foreach ($users as $user) {
                    echo '<tr>';
                    echo '<td>' . htmlspecialchars($user['id']) . '</td>';
                    echo '<td>' . htmlspecialchars($user['name']) . '</td>';
                    echo '<td><code>' . htmlspecialchars($user['email']) . '</code></td>';
                    echo '<td>' . htmlspecialchars($user['created_at']) . '</td>';
                    echo '</tr>';
                }
                
                echo '</table>';
            } else {
                echo '<p class="warning">⚠️ No se encontraron usuarios. Ejecutar: php artisan db:seed</p>';
            }
        } else {
            echo '<p class="error">❌ La tabla "users" no existe</p>';
        }
        echo '</div>';
        
        // 5. Verificar migraciones
        echo '<div class="section">';
        echo '<h2>5. Estado de Migraciones</h2>';
        
        if (in_array('migrations', $tables)) {
            $stmt = $pdo->query("SELECT COUNT(*) FROM migrations");
            $migrationCount = $stmt->fetchColumn();
            
            echo '<p class="ok">✅ Se ejecutaron <strong>' . $migrationCount . ' migración(es)</strong></p>';
            $exitos[] = "$migrationCount migraciones ejecutadas";
            
            $stmt = $pdo->query("SELECT migration, batch FROM migrations ORDER BY batch, migration");
            $migrations = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            echo '<table>';
            echo '<tr><th>Migración</th><th>Batch</th></tr>';
            
            foreach ($migrations as $migration) {
                echo '<tr>';
                echo '<td><code>' . htmlspecialchars($migration['migration']) . '</code></td>';
                echo '<td>' . htmlspecialchars($migration['batch']) . '</td>';
                echo '</tr>';
            }
            
            echo '</table>';
        } else {
            echo '<p class="warning">⚠️ La tabla de migraciones no existe</p>';
        }
        echo '</div>';
        
        // 6. Información de la base de datos
        echo '<div class="section">';
        echo '<h2>6. Información de la Base de Datos</h2>';
        
        $stmt = $pdo->query("SELECT 
            SCHEMA_NAME as 'database',
            DEFAULT_CHARACTER_SET_NAME as 'charset',
            DEFAULT_COLLATION_NAME as 'collation'
            FROM information_schema.SCHEMATA 
            WHERE SCHEMA_NAME = '$dbDatabase'");
        $dbInfo = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($dbInfo) {
            echo '<table>';
            echo '<tr><th>Propiedad</th><th>Valor</th></tr>';
            echo '<tr><td>Base de Datos</td><td><code>' . htmlspecialchars($dbInfo['database']) . '</code></td></tr>';
            echo '<tr><td>Charset</td><td><code>' . htmlspecialchars($dbInfo['charset']) . '</code></td></tr>';
            echo '<tr><td>Collation</td><td><code>' . htmlspecialchars($dbInfo['collation']) . '</code></td></tr>';
            echo '</table>';
        }
        
        // Tamaño de la base de datos
        $stmt = $pdo->query("SELECT 
            ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) AS 'size_mb'
            FROM information_schema.tables 
            WHERE table_schema = '$dbDatabase'");
        $size = $stmt->fetchColumn();
        
        echo '<p><strong>Tamaño total:</strong> ' . number_format($size, 2) . ' MB</p>';
        echo '</div>';
        
    } catch (PDOException $e) {
        echo '<p class="error">❌ Error al conectar a la base de datos</p>';
        echo '<p class="info">Error: ' . htmlspecialchars($e->getMessage()) . '</p>';
        $errores[] = "Error de conexión: " . $e->getMessage();
    }
    
    // Resumen final
    echo '<div class="section">';
    echo '<h2>📊 Resumen</h2>';
    
    $totalErrores = count($errores);
    $totalExitos = count($exitos);
    
    if ($totalErrores === 0) {
        echo '<div class="success-box">';
        echo '<h3 style="margin-top:0; color: #155724;">✅ ¡Conexión Perfecta!</h3>';
        echo '<p><strong>La base de datos está conectada y funcionando correctamente.</strong></p>';
        echo '<ul>';
        foreach ($exitos as $exito) {
            echo '<li>' . htmlspecialchars($exito) . '</li>';
        }
        echo '</ul>';
        echo '</div>';
    } else {
        echo '<div class="info" style="background: #f8d7da; border-color: #dc3545;">';
        echo '<h3 style="margin-top:0; color: #721c24;">❌ Se encontraron problemas</h3>';
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
            <li><a href="INSTALACION_COMPLETA.md" target="_blank">INSTALACION_COMPLETA.md</a></li>
            <li><a href="verificar_inicio_proyecto.php" target="_blank">verificar_inicio_proyecto.php</a></li>
        </ul>
    </div>

</body>
</html>






