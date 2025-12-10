#!/bin/bash

# Script de verificación de instalación del proyecto GEORGE-SISCADIT
# Ejecutar desde la raíz del proyecto: bash verificar_instalacion.sh

echo "🔍 Verificando instalación del proyecto GEORGE-SISCADIT..."
echo ""

# Colores para output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Contador de errores
ERRORES=0

# Función para verificar archivo/directorio
verificar() {
    if [ -e "$1" ]; then
        echo -e "${GREEN}✅ $2 existe${NC}"
        return 0
    else
        echo -e "${RED}❌ $2 NO existe${NC}"
        ERRORES=$((ERRORES + 1))
        return 1
    fi
}

# Función para verificar comando
verificar_comando() {
    if command -v "$1" &> /dev/null; then
        echo -e "${GREEN}✅ $1 está instalado${NC}"
        return 0
    else
        echo -e "${RED}❌ $1 NO está instalado${NC}"
        ERRORES=$((ERRORES + 1))
        return 1
    fi
}

echo "📦 Verificando herramientas necesarias..."
verificar_comando "php"
verificar_comando "composer"
verificar_comando "node"
verificar_comando "npm"
echo ""

echo "📁 Verificando archivos y directorios del proyecto..."
verificar ".env" "Archivo .env"
verificar "vendor" "Directorio vendor/ (dependencias PHP)"
verificar "node_modules" "Directorio node_modules/ (dependencias Node.js)"
verificar "public/build" "Directorio public/build/ (assets compilados)"
echo ""

echo "🔑 Verificando configuración..."
if [ -f ".env" ]; then
    # Verificar que APP_KEY esté configurado
    if grep -q "APP_KEY=base64:" .env 2>/dev/null; then
        echo -e "${GREEN}✅ APP_KEY está configurado${NC}"
    else
        echo -e "${YELLOW}⚠️  APP_KEY no está configurado. Ejecutar: php artisan key:generate${NC}"
        ERRORES=$((ERRORES + 1))
    fi
    
    # Verificar configuración de base de datos
    if grep -q "DB_DATABASE=" .env 2>/dev/null; then
        DB_NAME=$(grep "DB_DATABASE=" .env | cut -d '=' -f2 | tr -d '"' | tr -d "'")
        echo -e "${GREEN}✅ DB_DATABASE configurado: $DB_NAME${NC}"
    else
        echo -e "${RED}❌ DB_DATABASE no está configurado en .env${NC}"
        ERRORES=$((ERRORES + 1))
    fi
else
    echo -e "${RED}❌ No se puede verificar configuración: .env no existe${NC}"
fi
echo ""

echo "🗄️  Verificando base de datos..."
if [ -f ".env" ]; then
    DB_NAME=$(grep "DB_DATABASE=" .env | cut -d '=' -f2 | tr -d '"' | tr -d "'" | xargs)
    DB_USER=$(grep "DB_USERNAME=" .env | cut -d '=' -f2 | tr -d '"' | tr -d "'" | xargs)
    DB_PASS=$(grep "DB_PASSWORD=" .env | cut -d '=' -f2 | tr -d '"' | tr -d "'" | xargs)
    
    if [ -n "$DB_NAME" ]; then
        # Intentar conectar a MySQL (requiere que MySQL esté corriendo)
        if command -v mysql &> /dev/null; then
            if [ -z "$DB_PASS" ]; then
                mysql -u "$DB_USER" -e "USE $DB_NAME;" 2>/dev/null
            else
                mysql -u "$DB_USER" -p"$DB_PASS" -e "USE $DB_NAME;" 2>/dev/null
            fi
            
            if [ $? -eq 0 ]; then
                echo -e "${GREEN}✅ Base de datos '$DB_NAME' existe y es accesible${NC}"
                
                # Verificar si hay tablas
                TABLE_COUNT=$(php artisan db:show --count 2>/dev/null | grep -o '[0-9]*' | head -1)
                if [ -n "$TABLE_COUNT" ] && [ "$TABLE_COUNT" -gt 0 ]; then
                    echo -e "${GREEN}✅ Base de datos tiene $TABLE_COUNT tablas${NC}"
                else
                    echo -e "${YELLOW}⚠️  Base de datos existe pero no tiene tablas. Ejecutar: php artisan migrate${NC}"
                fi
            else
                echo -e "${YELLOW}⚠️  No se pudo conectar a la base de datos. Verificar que MySQL esté corriendo${NC}"
            fi
        else
            echo -e "${YELLOW}⚠️  Comando 'mysql' no disponible. Verificar manualmente la base de datos${NC}"
        fi
    fi
fi
echo ""

echo "📊 Resumen de verificación:"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"

if [ $ERRORES -eq 0 ]; then
    echo -e "${GREEN}✅ Todo está correcto. El proyecto debería funcionar correctamente.${NC}"
    echo ""
    echo "Para iniciar el proyecto:"
    echo "  1. npm run dev (en una terminal)"
    echo "  2. php artisan serve (en otra terminal)"
    echo "  3. Abrir http://localhost:8000 en el navegador"
else
    echo -e "${RED}❌ Se encontraron $ERRORES problema(s) que deben resolverse antes de iniciar el proyecto.${NC}"
    echo ""
    echo "Revisar CHECKLIST_INICIO_PROYECTO.md para más detalles."
fi

echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"




