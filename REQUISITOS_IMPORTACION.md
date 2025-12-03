# 📦 Requisitos para la Funcionalidad de Importación

Esta guía explica **todas las extensiones y dependencias** necesarias para que la funcionalidad de importación de Excel/CSV funcione correctamente.

---

## ⚠️ IMPORTANTE: Versión de PHP

**El sistema requiere PHP 8.1 o superior.**

Si estás usando **PHP 7**, necesitas:
1. **Actualizar a PHP 8.1+** (recomendado)
2. O usar versiones antiguas de las librerías (no recomendado)

### Verificar tu versión de PHP:

```bash
php -v
```

O desde el navegador, crea un archivo `info.php`:
```php
<?php phpinfo(); ?>
```

---

## 📋 Extensiones PHP Requeridas

Para que la importación funcione, necesitas las siguientes extensiones habilitadas:

### 1. **zip** (OBLIGATORIA)
- **Para qué**: Leer archivos Excel (.xlsx)
- **Cómo verificar**: 
  ```bash
  php -m | grep zip
  ```
- **Cómo habilitar en XAMPP**:
  1. Abre `C:\xampp\php\php.ini`
  2. Busca `;extension=zip`
  3. Quita el `;` al inicio: `extension=zip`
  4. Reinicia Apache

### 2. **xml** (OBLIGATORIA)
- **Para qué**: Procesar archivos XML dentro de Excel
- **Cómo verificar**: 
  ```bash
  php -m | grep xml
  ```
- **Cómo habilitar en XAMPP**:
  1. Abre `C:\xampp\php\php.ini`
  2. Busca `;extension=xml`
  3. Quita el `;` al inicio: `extension=xml`
  4. Reinicia Apache

### 3. **simplexml** (OBLIGATORIA)
- **Para qué**: Procesar XML de forma simple
- **Cómo verificar**: 
  ```bash
  php -m | grep simplexml
  ```
- **Cómo habilitar en XAMPP**:
  1. Abre `C:\xampp\php\php.ini`
  2. Busca `;extension=simplexml`
  3. Quita el `;` al inicio: `extension=simplexml`
  4. Reinicia Apache

### 4. **libxml** (OBLIGATORIA)
- **Para qué**: Librería base para procesar XML
- **Cómo verificar**: 
  ```bash
  php -m | grep libxml
  ```
- **Nota**: Generalmente viene habilitada por defecto

### 5. **mbstring** (OBLIGATORIA)
- **Para qué**: Manejar caracteres especiales (ñ, acentos, etc.)
- **Cómo verificar**: 
  ```bash
  php -m | grep mbstring
  ```
- **Cómo habilitar en XAMPP**:
  1. Abre `C:\xampp\php\php.ini`
  2. Busca `;extension=mbstring`
  3. Quita el `;` al inicio: `extension=mbstring`
  4. Reinicia Apache

### 6. **iconv** (OBLIGATORIA)
- **Para qué**: Conversión de caracteres
- **Cómo verificar**: 
  ```bash
  php -m | grep iconv
  ```
- **Nota**: Generalmente viene habilitada por defecto

### 7. **gd** o **imagick** (RECOMENDADA)
- **Para qué**: Procesar imágenes en Excel (opcional)
- **Cómo verificar**: 
  ```bash
  php -m | grep gd
  ```
- **Cómo habilitar en XAMPP**:
  1. Abre `C:\xampp\php\php.ini`
  2. Busca `;extension=gd`
  3. Quita el `;` al inicio: `extension=gd`
  4. Reinicia Apache

### 8. **fileinfo** (RECOMENDADA)
- **Para qué**: Detectar tipos de archivo
- **Cómo verificar**: 
  ```bash
  php -m | grep fileinfo
  ```
- **Nota**: Generalmente viene habilitada por defecto

---

## 🔧 Cómo Habilitar Extensiones en XAMPP (Paso a Paso)

### Paso 1: Localizar php.ini

1. Abre el **Panel de Control de XAMPP**
2. Haz clic en **Config** junto a Apache
3. Selecciona **PHP (php.ini)**
4. Se abrirá el archivo `php.ini` en el Bloc de notas

### Paso 2: Habilitar Extensiones

Busca cada extensión y quita el `;` al inicio:

```ini
; Antes:
;extension=zip
;extension=xml
;extension=simplexml
;extension=mbstring
;extension=gd

; Después:
extension=zip
extension=xml
extension=simplexml
extension=mbstring
extension=gd
```

### Paso 3: Guardar y Reiniciar

1. **Guarda** el archivo `php.ini` (Ctrl + S)
2. **Cierra** el Bloc de notas
3. En el Panel de Control de XAMPP:
   - **Detén** Apache (Stop)
   - **Inicia** Apache nuevamente (Start)

### Paso 4: Verificar

Crea un archivo `test_extensions.php` en `C:\xampp\htdocs\GEORGE-SISCADIT\`:

```php
<?php
echo "Verificando extensiones PHP:\n\n";

$extensiones = [
    'zip' => 'OBLIGATORIA - Para leer archivos Excel',
    'xml' => 'OBLIGATORIA - Para procesar XML',
    'simplexml' => 'OBLIGATORIA - Para procesar XML simple',
    'libxml' => 'OBLIGATORIA - Librería base XML',
    'mbstring' => 'OBLIGATORIA - Para caracteres especiales',
    'iconv' => 'OBLIGATORIA - Para conversión de caracteres',
    'gd' => 'RECOMENDADA - Para procesar imágenes',
    'fileinfo' => 'RECOMENDADA - Para detectar tipos de archivo',
];

foreach ($extensiones as $ext => $desc) {
    $status = extension_loaded($ext) ? '✅ HABILITADA' : '❌ NO HABILITADA';
    echo "$ext: $status - $desc\n";
}
?>
```

Accede desde el navegador: `http://localhost/GEORGE-SISCADIT/test_extensions.php`

---

## 📦 Dependencias de Composer

El sistema usa las siguientes librerías para importar:

### 1. **phpoffice/phpspreadsheet** (v5.3+)
- **Para qué**: Leer y escribir archivos Excel
- **Requisitos**: PHP 8.1+
- **Instalación**: Se instala automáticamente con `composer install`

### 2. **maatwebsite/excel** (v1.1+)
- **Para qué**: Wrapper de Laravel para PhpSpreadsheet
- **Requisitos**: PHP 8.1+
- **Instalación**: Se instala automáticamente con `composer install`

### Verificar Instalación:

```bash
cd C:\xampp\htdocs\GEORGE-SISCADIT
composer show phpoffice/phpspreadsheet
composer show maatwebsite/excel
```

---

## 🚨 Problemas Comunes y Soluciones

### Error: "Class 'PhpOffice\PhpSpreadsheet\IOFactory' not found"

**Causa**: PhpSpreadsheet no está instalado o no se encuentra.

**Solución**:
```bash
cd C:\xampp\htdocs\GEORGE-SISCADIT
composer install
# O si ya está instalado:
composer update phpoffice/phpspreadsheet
```

### Error: "Call to undefined function zip_open()"

**Causa**: Extensión `zip` no está habilitada.

**Solución**:
1. Abre `C:\xampp\php\php.ini`
2. Busca `;extension=zip`
3. Quita el `;`: `extension=zip`
4. Reinicia Apache

### Error: "mbstring extension is required"

**Causa**: Extensión `mbstring` no está habilitada.

**Solución**:
1. Abre `C:\xampp\php\php.ini`
2. Busca `;extension=mbstring`
3. Quita el `;`: `extension=mbstring`
4. Reinicia Apache

### Error: "PHP version must be 8.1 or higher"

**Causa**: Estás usando PHP 7.x

**Solución**:
1. **Actualizar XAMPP** a la versión 8.1 o superior
2. O descargar PHP 8.1+ manualmente y configurarlo en XAMPP

### Error: "Memory limit exhausted"

**Causa**: Límite de memoria muy bajo para archivos grandes.

**Solución**:
1. Abre `C:\xampp\php\php.ini`
2. Busca `memory_limit = 128M`
3. Cambia a: `memory_limit = 256M` o `512M`
4. Reinicia Apache

### Error: "Upload file size exceeded"

**Causa**: Límite de tamaño de archivo muy bajo.

**Solución**:
1. Abre `C:\xampp\php\php.ini`
2. Busca:
   - `upload_max_filesize = 2M` → Cambia a `10M`
   - `post_max_size = 8M` → Cambia a `12M`
3. Reinicia Apache

---

## ✅ Checklist de Verificación

Antes de intentar importar, verifica:

- [ ] PHP 8.1 o superior instalado
- [ ] Extensión `zip` habilitada
- [ ] Extensión `xml` habilitada
- [ ] Extensión `simplexml` habilitada
- [ ] Extensión `mbstring` habilitada
- [ ] Extensión `iconv` habilitada
- [ ] Extensión `gd` habilitada (recomendada)
- [ ] `phpoffice/phpspreadsheet` instalado
- [ ] `maatwebsite/excel` instalado
- [ ] Apache reiniciado después de cambios
- [ ] Permisos de escritura en `storage/` y `bootstrap/cache/`

---

## 🧪 Script de Verificación Automática

Crea un archivo `verificar_importacion.php` en la raíz del proyecto:

```php
<?php
echo "<h1>Verificación de Requisitos para Importación</h1>";

// Verificar versión de PHP
$phpVersion = phpversion();
$phpOk = version_compare($phpVersion, '8.1.0', '>=');
echo "<h2>Versión de PHP: $phpVersion</h2>";
echo $phpOk ? "✅ PHP 8.1+ detectado" : "❌ Se requiere PHP 8.1+ (actual: $phpVersion)";
echo "<br><br>";

// Verificar extensiones
$extensiones = ['zip', 'xml', 'simplexml', 'libxml', 'mbstring', 'iconv', 'gd', 'fileinfo'];
echo "<h2>Extensiones PHP:</h2>";
foreach ($extensiones as $ext) {
    $loaded = extension_loaded($ext);
    echo $loaded ? "✅ $ext" : "❌ $ext (NO HABILITADA)";
    echo "<br>";
}

// Verificar librerías de Composer
echo "<h2>Librerías de Composer:</h2>";
$vendorPath = __DIR__ . '/vendor';
if (file_exists($vendorPath)) {
    echo "✅ Carpeta vendor existe<br>";
    
    // Verificar PhpSpreadsheet
    $phpspreadsheet = $vendorPath . '/phpoffice/phpspreadsheet';
    if (file_exists($phpspreadsheet)) {
        echo "✅ phpoffice/phpspreadsheet instalado<br>";
    } else {
        echo "❌ phpoffice/phpspreadsheet NO instalado<br>";
    }
    
    // Verificar Maatwebsite Excel
    $maatwebsite = $vendorPath . '/maatwebsite/excel';
    if (file_exists($maatwebsite)) {
        echo "✅ maatwebsite/excel instalado<br>";
    } else {
        echo "❌ maatwebsite/excel NO instalado<br>";
    }
} else {
    echo "❌ Carpeta vendor NO existe. Ejecuta: composer install<br>";
}

// Verificar permisos
echo "<h2>Permisos de Carpetas:</h2>";
$carpetas = ['storage', 'bootstrap/cache'];
foreach ($carpetas as $carpeta) {
    $ruta = __DIR__ . '/' . $carpeta;
    if (file_exists($ruta)) {
        $writable = is_writable($ruta);
        echo $writable ? "✅ $carpeta (escribible)" : "❌ $carpeta (NO escribible)";
        echo "<br>";
    } else {
        echo "❌ $carpeta (NO existe)";
        echo "<br>";
    }
}
?>
```

Accede desde: `http://localhost/GEORGE-SISCADIT/verificar_importacion.php`

---

## 📞 Si Aún No Funciona

Si después de seguir todos los pasos la importación no funciona:

1. **Revisa los logs de Laravel**:
   - `storage/logs/laravel.log`

2. **Revisa los logs de Apache**:
   - `C:\xampp\apache\logs\error.log`

3. **Verifica el error específico**:
   - Intenta importar un archivo pequeño
   - Copia el mensaje de error exacto
   - Revisa la consola del navegador (F12)

4. **Verifica que el archivo sea válido**:
   - Abre el Excel en Microsoft Excel o LibreOffice
   - Guarda como `.xlsx` (no `.xls`)
   - Verifica que no esté corrupto

---

## 📝 Resumen Rápido

**Para que funcione la importación necesitas:**

1. ✅ **PHP 8.1+** (no PHP 7)
2. ✅ **Extensiones habilitadas**: zip, xml, simplexml, mbstring, iconv
3. ✅ **Composer instalado** y ejecutado (`composer install`)
4. ✅ **Apache reiniciado** después de cambios en php.ini
5. ✅ **Permisos correctos** en `storage/` y `bootstrap/cache/`

---

**Última actualización**: Diciembre 2024


