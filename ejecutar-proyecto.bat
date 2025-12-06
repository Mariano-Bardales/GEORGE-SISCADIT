@echo off
echo ========================================
echo   EJECUTAR PROYECTO SISCADIT
echo ========================================
echo.

REM Verificar PHP
echo [1/5] Verificando PHP...
C:\xampp1\php\php.exe -v 2>nul
if %errorlevel% neq 0 (
    echo ERROR: PHP no encontrado en C:\xampp1\php\php.exe
    echo.
    echo Por favor, actualiza PHP a version 8.1 o superior.
    echo Ve a: https://windows.php.net/download/
    pause
    exit /b 1
)

REM Verificar versión de PHP
for /f "tokens=2" %%i in ('C:\xampp1\php\php.exe -v ^| findstr /R "PHP [0-9]"') do set PHP_VERSION=%%i
echo PHP encontrado: %PHP_VERSION%
echo.

REM Verificar si PHP es 8.1 o superior
echo [2/5] Verificando version de PHP...
C:\xampp1\php\php.exe -r "if (version_compare(PHP_VERSION, '8.1.0', '<')) { exit(1); }" 2>nul
if %errorlevel% neq 0 (
    echo.
    echo ========================================
    echo   ERROR CRITICO
    echo ========================================
    echo.
    echo Tu version de PHP (%PHP_VERSION%) es menor a 8.1.0
    echo El proyecto requiere PHP 8.1 o superior.
    echo.
    echo SOLUCION:
    echo 1. Descarga PHP 8.1+ desde: https://windows.php.net/download/
    echo 2. Reemplaza la carpeta C:\xampp1\php con la nueva version
    echo 3. O instala una nueva version de XAMPP con PHP 8.1+
    echo.
    echo Ve la guia completa en: GUIA_EJECUTAR_PROYECTO.md
    echo.
    pause
    exit /b 1
)

echo PHP version OK!
echo.

REM Verificar autoload
echo [3/5] Verificando dependencias...
if not exist "vendor\autoload.php" (
    echo ERROR: vendor\autoload.php no existe
    echo Ejecutando composer install...
    C:\xampp1\php\php.exe composer.phar install --no-interaction
    if %errorlevel% neq 0 (
        echo ERROR al instalar dependencias
        pause
        exit /b 1
    )
)
echo Dependencias OK!
echo.

REM Verificar .env
echo [4/5] Verificando configuracion...
if not exist ".env" (
    echo ERROR: Archivo .env no existe
    echo Copiando desde .env.example...
    copy .env.example .env
    echo.
    echo IMPORTANTE: Edita el archivo .env con tus datos de base de datos
    echo.
    pause
)
echo Configuracion OK!
echo.

REM Verificar APP_KEY
echo [5/5] Verificando APP_KEY...
C:\xampp1\php\php.exe artisan key:generate --show 2>nul | findstr "base64:" >nul
if %errorlevel% neq 0 (
    echo Generando APP_KEY...
    C:\xampp1\php\php.exe artisan key:generate
)
echo.

REM Ejecutar servidor
echo ========================================
echo   INICIANDO SERVIDOR
echo ========================================
echo.
echo El servidor se iniciara en: http://localhost:8000
echo Presiona Ctrl+C para detener el servidor
echo.
C:\xampp1\php\php.exe artisan serve

pause

