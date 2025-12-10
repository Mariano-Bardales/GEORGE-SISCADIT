<?php
/**
 * Script para crear un dump completo de la base de datos
 * Ejecutar: php crear_dump_base_datos.php
 */

require __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$dbHost = $_ENV['DB_HOST'] ?? '127.0.0.1';
$dbPort = $_ENV['DB_PORT'] ?? '3306';
$dbDatabase = $_ENV['DB_DATABASE'] ?? '';
$dbUsername = $_ENV['DB_USERNAME'] ?? 'root';
$dbPassword = $_ENV['DB_PASSWORD'] ?? '';

if (empty($dbDatabase)) {
    die("❌ Error: DB_DATABASE no está configurado en .env\n");
}

echo "📦 Creando dump de la base de datos: {$dbDatabase}\n";

// Intentar usar mysqldump primero (más rápido y completo)
$mysqldumpPath = '';
$possiblePaths = [
    'C:/xampp82/mysql/bin/mysqldump.exe',
    'C:/xampp/mysql/bin/mysqldump.exe',
    'mysqldump',
    'C:/Program Files/MySQL/MySQL Server 8.0/bin/mysqldump.exe',
];

foreach ($possiblePaths as $path) {
    if (file_exists($path) || shell_exec("where $path 2>nul")) {
        $mysqldumpPath = $path;
        break;
    }
}

$outputFile = __DIR__ . '/database/dump_' . date('Y-m-d_His') . '.sql';

if ($mysqldumpPath) {
    echo "✅ Usando mysqldump: {$mysqldumpPath}\n";
    
    $command = sprintf(
        '"%s" --host=%s --port=%s --user=%s %s %s > "%s"',
        $mysqldumpPath,
        escapeshellarg($dbHost),
        escapeshellarg($dbPort),
        escapeshellarg($dbUsername),
        !empty($dbPassword) ? '--password=' . escapeshellarg($dbPassword) : '',
        escapeshellarg($dbDatabase),
        escapeshellarg($outputFile)
    );
    
    exec($command . ' 2>&1', $output, $returnVar);
    
    if ($returnVar === 0 && file_exists($outputFile) && filesize($outputFile) > 0) {
        echo "✅ Dump creado exitosamente: {$outputFile}\n";
        echo "📊 Tamaño del archivo: " . number_format(filesize($outputFile) / 1024, 2) . " KB\n";
        exit(0);
    } else {
        echo "⚠️  mysqldump falló, intentando método alternativo...\n";
        if (file_exists($outputFile)) {
            unlink($outputFile);
        }
    }
}

// Método alternativo usando PDO
echo "🔄 Usando método alternativo con PDO...\n";

try {
    $dsn = "mysql:host={$dbHost};port={$dbPort};dbname={$dbDatabase};charset=utf8mb4";
    $pdo = new PDO($dsn, $dbUsername, $dbPassword, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
    
    $outputFile = __DIR__ . '/database/dump_' . date('Y-m-d_His') . '.sql';
    $file = fopen($outputFile, 'w');
    
    if (!$file) {
        die("❌ No se pudo crear el archivo: {$outputFile}\n");
    }
    
    // Escribir encabezado
    fwrite($file, "-- Dump de base de datos: {$dbDatabase}\n");
    fwrite($file, "-- Generado: " . date('Y-m-d H:i:s') . "\n");
    fwrite($file, "-- Host: {$dbHost}:{$dbPort}\n\n");
    fwrite($file, "SET SQL_MODE = \"NO_AUTO_VALUE_ON_ZERO\";\n");
    fwrite($file, "START TRANSACTION;\n");
    fwrite($file, "SET time_zone = \"+00:00\";\n\n");
    
    // Obtener todas las tablas
    $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    
    echo "📋 Encontradas " . count($tables) . " tablas\n";
    
    foreach ($tables as $table) {
        echo "  📄 Procesando tabla: {$table}\n";
        
        // Crear estructura de la tabla
        $createTable = $pdo->query("SHOW CREATE TABLE `{$table}`")->fetch();
        fwrite($file, "\n-- Estructura de tabla: {$table}\n");
        fwrite($file, "DROP TABLE IF EXISTS `{$table}`;\n");
        fwrite($file, $createTable['Create Table'] . ";\n\n");
        
        // Obtener datos
        $rows = $pdo->query("SELECT * FROM `{$table}`")->fetchAll(PDO::FETCH_ASSOC);
        
        if (count($rows) > 0) {
            fwrite($file, "-- Datos de tabla: {$table}\n");
            
            $columns = array_keys($rows[0]);
            $columnList = '`' . implode('`, `', $columns) . '`';
            
            foreach ($rows as $row) {
                $values = [];
                foreach ($row as $value) {
                    if ($value === null) {
                        $values[] = 'NULL';
                    } else {
                        $values[] = $pdo->quote($value);
                    }
                }
                fwrite($file, "INSERT INTO `{$table}` ({$columnList}) VALUES (" . implode(', ', $values) . ");\n");
            }
            fwrite($file, "\n");
        }
    }
    
    fwrite($file, "COMMIT;\n");
    fclose($file);
    
    echo "✅ Dump creado exitosamente: {$outputFile}\n";
    echo "📊 Tamaño del archivo: " . number_format(filesize($outputFile) / 1024, 2) . " KB\n";
    
} catch (PDOException $e) {
    die("❌ Error al crear dump: " . $e->getMessage() . "\n");
}

