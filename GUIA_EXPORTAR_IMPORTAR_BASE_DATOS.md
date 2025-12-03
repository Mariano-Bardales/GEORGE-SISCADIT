# 💾 Guía: Exportar e Importar Base de Datos

Esta guía te explica cómo **exportar** tu base de datos actual a un archivo SQL y cómo **importarla** en otro sistema.

---

## 📋 ÍNDICE

1. [Método 1: Exportar desde phpMyAdmin](#método-1-exportar-desde-phpmyadmin)
2. [Método 2: Exportar desde Línea de Comandos](#método-2-exportar-desde-línea-de-comandos)
3. [Dónde Guardar el Archivo SQL](#dónde-guardar-el-archivo-sql)
4. [Importar Base de Datos en Otro Sistema](#importar-base-de-datos-en-otro-sistema)
5. [Scripts Automáticos](#scripts-automáticos)

---

## 🔽 MÉTODO 1: EXPORTAR DESDE phpMyAdmin

### Paso 1: Abrir phpMyAdmin

1. Inicia **XAMPP**
2. Inicia **Apache** y **MySQL** desde el Panel de Control
3. Abre tu navegador y ve a: `http://localhost/phpmyadmin`
4. Inicia sesión (usuario: `root`, contraseña: vacía por defecto)

### Paso 2: Seleccionar la Base de Datos

1. En el menú lateral izquierdo, haz clic en el nombre de tu base de datos
   - Ejemplo: `george_siscadit` o `siscadit_db`

### Paso 3: Exportar

1. Haz clic en la pestaña **"Exportar"** (arriba)
2. Selecciona el método: **"Rápido"** o **"Personalizado"**

#### Opción A: Exportación Rápida (Recomendada)
- ✅ Método: **Rápido**
- ✅ Formato: **SQL**
- Haz clic en **"Continuar"**

#### Opción B: Exportación Personalizada (Más Control)
- ✅ Método: **Personalizado**
- ✅ Formato: **SQL**
- ✅ Estructura: Marca todas las opciones
- ✅ Datos: Marca "Insertar datos"
- ✅ Opciones adicionales:
  - ✅ Marca "Agregar DROP TABLE / VIEW / PROCEDURE / FUNCTION / EVENT / TRIGGER"
  - ✅ Marca "Agregar CREATE TABLE"
  - ✅ Marca "Agregar CREATE PROCEDURE / FUNCTION / EVENT / TRIGGER"
- Haz clic en **"Continuar"**

### Paso 4: Descargar el Archivo

1. El navegador descargará un archivo `.sql`
2. **Guarda este archivo** en una ubicación segura
3. **Recomendación**: Guárdalo en la carpeta `database/backups/` del proyecto

---

## 💻 MÉTODO 2: EXPORTAR DESDE LÍNEA DE COMANDOS

### Paso 1: Abrir Terminal

- **Windows**: Abre **Git Bash** o **CMD**
- **Linux/Mac**: Abre **Terminal**

### Paso 2: Navegar a la Carpeta del Proyecto

```bash
cd C:\xampp\htdocs\GEORGE-SISCADIT
```

### Paso 3: Ejecutar Comando mysqldump

**Sintaxis básica:**
```bash
mysqldump -u root -p nombre_base_datos > database/backups/backup_YYYY-MM-DD.sql
```

**Ejemplo completo:**
```bash
# Crear carpeta de backups si no existe
mkdir -p database/backups

# Exportar base de datos
mysqldump -u root -p george_siscadit > database/backups/backup_2024-12-15.sql
```

**Explicación del comando:**
- `-u root`: Usuario de MySQL (generalmente `root`)
- `-p`: Pedirá la contraseña (si no tienes, presiona Enter)
- `george_siscadit`: Nombre de tu base de datos
- `>`: Redirige la salida a un archivo
- `database/backups/backup_2024-12-15.sql`: Ruta donde se guardará el archivo

### Paso 4: Verificar el Archivo

```bash
# Verificar que el archivo se creó
ls -lh database/backups/

# Ver el tamaño del archivo
du -h database/backups/backup_2024-12-15.sql
```

---

## 📁 DÓNDE GUARDAR EL ARCHIVO SQL

### Opción 1: Dentro del Proyecto (Recomendada)

**Estructura recomendada:**
```
GEORGE-SISCADIT/
├── database/
│   ├── backups/
│   │   ├── backup_2024-12-15.sql
│   │   ├── backup_2024-12-16.sql
│   │   └── README.md
│   ├── migrations/
│   └── seeders/
```

**Ventajas:**
- ✅ Fácil de encontrar
- ✅ Versionado con Git (opcional)
- ✅ Organizado

**⚠️ IMPORTANTE**: Si usas Git, agrega `database/backups/*.sql` al `.gitignore` para no subir archivos grandes al repositorio.

### Opción 2: Fuera del Proyecto

**Ubicaciones alternativas:**
- `C:\Backups\SISCADIT\backup_2024-12-15.sql`
- `D:\Respaldo\BaseDatos\backup_2024-12-15.sql`
- Carpeta de OneDrive/Google Drive (para respaldo en la nube)

---

## 🔼 IMPORTAR BASE DE DATOS EN OTRO SISTEMA

### Método 1: Importar desde phpMyAdmin

#### Paso 1: Preparar el Nuevo Sistema

1. Instala XAMPP en la nueva PC
2. Inicia Apache y MySQL
3. Abre phpMyAdmin: `http://localhost/phpmyadmin`

#### Paso 2: Crear Base de Datos

1. Haz clic en **"Nuevo"** en el menú lateral
2. Ingresa el nombre de la base de datos (ej: `george_siscadit`)
3. Selecciona **"utf8mb4_unicode_ci"** como intercalación
4. Haz clic en **"Crear"**

#### Paso 3: Importar el Archivo SQL

1. Selecciona la base de datos recién creada
2. Haz clic en la pestaña **"Importar"**
3. Haz clic en **"Elegir archivo"** y selecciona tu archivo `.sql`
4. Verifica las opciones:
   - ✅ Formato: **SQL**
   - ✅ Tamaño máximo: Ajusta si tu archivo es grande
5. Haz clic en **"Continuar"**
6. Espera a que termine la importación (puede tardar varios minutos)

#### Paso 4: Verificar Importación

1. Revisa las tablas en el menú lateral
2. Verifica que todas las tablas estén presentes
3. Revisa algunos registros para confirmar que los datos se importaron correctamente

---

### Método 2: Importar desde Línea de Comandos

#### Paso 1: Crear Base de Datos

```bash
# Conectar a MySQL
mysql -u root -p

# Crear base de datos
CREATE DATABASE george_siscadit CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

# Salir de MySQL
EXIT;
```

#### Paso 2: Importar el Archivo SQL

```bash
# Navegar a la carpeta del proyecto
cd C:\xampp\htdocs\GEORGE-SISCADIT

# Importar base de datos
mysql -u root -p george_siscadit < database/backups/backup_2024-12-15.sql
```

**Explicación:**
- `-u root`: Usuario de MySQL
- `-p`: Pedirá la contraseña
- `george_siscadit`: Nombre de la base de datos
- `<`: Redirige el archivo SQL a MySQL

#### Paso 3: Verificar Importación

```bash
# Conectar a MySQL
mysql -u root -p

# Seleccionar base de datos
USE george_siscadit;

# Ver tablas
SHOW TABLES;

# Contar registros en una tabla
SELECT COUNT(*) FROM niños;

# Salir
EXIT;
```

---

## 🤖 SCRIPTS AUTOMÁTICOS

### Script para Exportar (Windows - Git Bash)

Crea un archivo `exportar_db.sh` en la raíz del proyecto:

```bash
#!/bin/bash

# Configuración
DB_NAME="george_siscadit"
DB_USER="root"
DB_PASS=""
BACKUP_DIR="database/backups"
DATE=$(date +%Y-%m-%d_%H-%M-%S)
BACKUP_FILE="$BACKUP_DIR/backup_$DATE.sql"

# Crear directorio de backups si no existe
mkdir -p "$BACKUP_DIR"

# Exportar base de datos
echo "🔄 Exportando base de datos: $DB_NAME"
mysqldump -u "$DB_USER" $([ -z "$DB_PASS" ] || echo "-p$DB_PASS") "$DB_NAME" > "$BACKUP_FILE"

# Verificar que el archivo se creó
if [ -f "$BACKUP_FILE" ]; then
    FILE_SIZE=$(du -h "$BACKUP_FILE" | cut -f1)
    echo "✅ Backup creado exitosamente: $BACKUP_FILE"
    echo "📦 Tamaño: $FILE_SIZE"
else
    echo "❌ Error al crear el backup"
    exit 1
fi
```

**Uso:**
```bash
chmod +x exportar_db.sh
./exportar_db.sh
```

---

### Script para Importar (Windows - Git Bash)

Crea un archivo `importar_db.sh` en la raíz del proyecto:

```bash
#!/bin/bash

# Configuración
DB_NAME="george_siscadit"
DB_USER="root"
DB_PASS=""
BACKUP_FILE="$1"

# Verificar que se proporcionó el archivo
if [ -z "$BACKUP_FILE" ]; then
    echo "❌ Error: Debes proporcionar la ruta del archivo SQL"
    echo "Uso: ./importar_db.sh database/backups/backup_2024-12-15.sql"
    exit 1
fi

# Verificar que el archivo existe
if [ ! -f "$BACKUP_FILE" ]; then
    echo "❌ Error: El archivo no existe: $BACKUP_FILE"
    exit 1
fi

# Crear base de datos si no existe
echo "🔄 Creando base de datos si no existe..."
mysql -u "$DB_USER" $([ -z "$DB_PASS" ] || echo "-p$DB_PASS") -e "CREATE DATABASE IF NOT EXISTS $DB_NAME CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# Importar base de datos
echo "🔄 Importando base de datos desde: $BACKUP_FILE"
mysql -u "$DB_USER" $([ -z "$DB_PASS" ] || echo "-p$DB_PASS") "$DB_NAME" < "$BACKUP_FILE"

if [ $? -eq 0 ]; then
    echo "✅ Base de datos importada exitosamente"
else
    echo "❌ Error al importar la base de datos"
    exit 1
fi
```

**Uso:**
```bash
chmod +x importar_db.sh
./importar_db.sh database/backups/backup_2024-12-15.sql
```

---

### Script para Windows (CMD/Batch)

Crea un archivo `exportar_db.bat`:

```batch
@echo off
setlocal

REM Configuración
set DB_NAME=george_siscadit
set DB_USER=root
set DB_PASS=
set BACKUP_DIR=database\backups
set DATE=%date:~-4,4%-%date:~-7,2%-%date:~-10,2%_%time:~0,2%-%time:~3,2%-%time:~6,2%
set DATE=%DATE: =0%
set BACKUP_FILE=%BACKUP_DIR%\backup_%DATE%.sql

REM Crear directorio de backups si no existe
if not exist "%BACKUP_DIR%" mkdir "%BACKUP_DIR%"

REM Exportar base de datos
echo Exportando base de datos: %DB_NAME%
if "%DB_PASS%"=="" (
    "C:\xampp\mysql\bin\mysqldump.exe" -u %DB_USER% %DB_NAME% > "%BACKUP_FILE%"
) else (
    "C:\xampp\mysql\bin\mysqldump.exe" -u %DB_USER% -p%DB_PASS% %DB_NAME% > "%BACKUP_FILE%"
)

if exist "%BACKUP_FILE%" (
    echo Backup creado exitosamente: %BACKUP_FILE%
) else (
    echo Error al crear el backup
    exit /b 1
)

pause
```

**Uso:**
- Haz doble clic en `exportar_db.bat`

---

## 📝 ACTUALIZAR .gitignore

Si guardas los backups en `database/backups/`, agrega esto a tu `.gitignore`:

```gitignore
# Backups de base de datos
database/backups/*.sql
database/backups/*.sql.gz

# Pero mantener la carpeta
!database/backups/.gitkeep
```

---

## ✅ CHECKLIST DE EXPORTACIÓN

Antes de exportar, verifica:

- [ ] XAMPP está iniciado (Apache y MySQL)
- [ ] Conoces el nombre de tu base de datos
- [ ] Tienes permisos de escritura en la carpeta de destino
- [ ] Tienes suficiente espacio en disco
- [ ] La base de datos no está siendo usada por otra aplicación

---

## ✅ CHECKLIST DE IMPORTACIÓN

Antes de importar, verifica:

- [ ] XAMPP está iniciado en el nuevo sistema
- [ ] La base de datos está creada (vacía)
- [ ] El archivo SQL existe y no está corrupto
- [ ] Tienes suficiente espacio en disco
- [ ] El archivo `.env` está configurado con el nombre correcto de la base de datos

---

## 🚨 PROBLEMAS COMUNES

### Error: "Access denied for user 'root'@'localhost'"

**Solución:**
- Verifica que el usuario y contraseña sean correctos
- Si no tienes contraseña, deja el campo vacío o usa `-p` sin valor

### Error: "Unknown database"

**Solución:**
- Verifica que el nombre de la base de datos sea correcto
- Crea la base de datos primero si no existe

### Error: "File too large"

**Solución:**
- Aumenta el límite en phpMyAdmin: `php.ini` → `upload_max_filesize = 100M`
- O usa la línea de comandos (mysqldump)

### Error: "Table already exists"

**Solución:**
- Elimina las tablas existentes antes de importar
- O usa la opción "Agregar DROP TABLE" en la exportación

---

## 📚 RESUMEN RÁPIDO

### Exportar:
```bash
# Método rápido (phpMyAdmin)
1. phpMyAdmin → Seleccionar BD → Exportar → Rápido → Continuar

# Método línea de comandos
mysqldump -u root -p george_siscadit > database/backups/backup.sql
```

### Importar:
```bash
# Método rápido (phpMyAdmin)
1. phpMyAdmin → Seleccionar BD → Importar → Elegir archivo → Continuar

# Método línea de comandos
mysql -u root -p george_siscadit < database/backups/backup.sql
```

---

**Última actualización**: Diciembre 2024


