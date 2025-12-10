#!/bin/bash

# Script de verificación para ejecutar el proyecto con php artisan serve
# Ejecutar: bash verificar_configuracion.sh

echo "🔍 VERIFICANDO CONFIGURACIÓN DEL PROYECTO..."
echo ""

# Colores
GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Contador de errores
ERRORES=0
EXITOS=0

# Función para verificar
verificar() {
    if [ $1 -eq 0 ]; then
        echo -e "${GREEN}✅ $2${NC}"
        ((EXITOS++))
        return 0
    else
        echo -e "${RED}❌ $2${NC}"
        ((ERRORES++))
        return 1
    fi
}

# 1. Verificar archivo .env
echo "1. Verificando archivo .env..."
if [ -f .env ]; then
    verificar 0 "Archivo .env existe"
else
    verificar 1 "Archivo .env NO existe - Ejecutar: cp .env.example .env"
fi
echo ""

# 2. Verificar dependencias PHP
echo "2. Verificando dependencias PHP..."
if [ -d vendor ]; then
    verificar 0 "Dependencias PHP instaladas (vendor/)"
else
    verificar 1 "Dependencias PHP NO instaladas - Ejecutar: composer install"
fi
echo ""

# 3. Verificar dependencias Node.js
echo "3. Verificando dependencias Node.js..."
if [ -d node_modules ]; then
    verificar 0 "Dependencias Node.js instaladas (node_modules/)"
else
    verificar 1 "Dependencias Node.js NO instaladas - Ejecutar: npm install"
fi
echo ""

# 4. Verificar assets compilados
echo "4. Verificando assets frontend..."
if [ -d public/build ]; then
    verificar 0 "Assets frontend compilados (public/build/)"
else
    verificar 1 "Assets frontend NO compilados - Ejecutar: npm run build"
fi
echo ""

# 5. Verificar PHP
echo "5. Verificando PHP..."
PHP_PATH="/c/xampp82/php/php.exe"
if [ -f "$PHP_PATH" ]; then
    PHP_CMD="$PHP_PATH"
    verificar 0 "PHP encontrado en: $PHP_PATH"
elif command -v php &> /dev/null; then
    PHP_CMD="php"
    verificar 0 "PHP encontrado en PATH"
else
    verificar 1 "PHP NO encontrado - Verificar instalación de XAMPP"
    PHP_CMD=""
fi
echo ""

# 6. Verificar APP_KEY
if [ -n "$PHP_CMD" ]; then
    echo "6. Verificando APP_KEY..."
    APP_KEY=$($PHP_CMD artisan config:show app.key 2>&1 | grep -o "base64:" | head -1)
    if [ -n "$APP_KEY" ]; then
        verificar 0 "APP_KEY configurada"
    else
        verificar 1 "APP_KEY NO configurada - Ejecutar: $PHP_CMD artisan key:generate"
    fi
    echo ""
fi

# 7. Verificar MySQL (conexión a base de datos)
if [ -n "$PHP_CMD" ]; then
    echo "7. Verificando conexión a MySQL..."
    MYSQL_CHECK=$($PHP_CMD artisan migrate:status 2>&1)
    if echo "$MYSQL_CHECK" | grep -q "SQLSTATE\|Connection\|denegó"; then
        verificar 1 "MySQL NO está corriendo o no hay conexión - Iniciar MySQL en XAMPP"
        echo "   💡 Solución: Abrir XAMPP Control Panel y hacer click en 'Start' en MySQL"
    else
        verificar 0 "Conexión a MySQL OK"
    fi
    echo ""
fi

# 8. Verificar base de datos
if [ -n "$PHP_CMD" ]; then
    echo "8. Verificando base de datos..."
    DB_CHECK=$($PHP_CMD artisan migrate:status 2>&1)
    if echo "$DB_CHECK" | grep -q "Unknown database\|does not exist"; then
        verificar 1 "Base de datos NO existe - Crear: george_siscadit"
        echo "   💡 Solución: Abrir phpMyAdmin y crear la base de datos"
    elif echo "$DB_CHECK" | grep -q "SQLSTATE\|Connection\|denegó"; then
        echo -e "${YELLOW}⚠️  No se puede verificar (MySQL no está corriendo)${NC}"
    else
        verificar 0 "Base de datos existe y es accesible"
    fi
    echo ""
fi

# 9. Verificar migraciones
if [ -n "$PHP_CMD" ]; then
    echo "9. Verificando migraciones..."
    MIGRATE_CHECK=$($PHP_CMD artisan migrate:status 2>&1)
    if echo "$MIGRATE_CHECK" | grep -q "SQLSTATE\|Connection\|denegó\|Unknown database"; then
        echo -e "${YELLOW}⚠️  No se puede verificar (problema de conexión)${NC}"
    elif echo "$MIGRATE_CHECK" | grep -q "No migrations found\|Nothing to migrate"; then
        verificar 1 "Migraciones NO ejecutadas - Ejecutar: $PHP_CMD artisan migrate"
    else
        verificar 0 "Migraciones ejecutadas"
    fi
    echo ""
fi

# Resumen final
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo ""
if [ $ERRORES -eq 0 ]; then
    echo -e "${GREEN}✅ ¡TODO LISTO! Puedes ejecutar:${NC}"
    echo ""
    if [ -n "$PHP_CMD" ]; then
        echo "   $PHP_CMD artisan serve"
    else
        echo "   /c/xampp82/php/php.exe artisan serve"
    fi
    echo ""
    echo "   Luego abre en el navegador: http://localhost:8000"
else
    echo -e "${RED}❌ Se encontraron $ERRORES problema(s) que deben resolverse antes de ejecutar el proyecto${NC}"
    echo ""
    echo -e "${YELLOW}📋 Revisa CONFIGURAR_PROYECTO.md para más detalles${NC}"
fi
echo ""
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"

exit $ERRORES


