#!/bin/bash

# Script para configurar PHP en el PATH de Git Bash
# Ejecutar: bash configurar_php_path.sh

echo "🔧 CONFIGURANDO PHP EN EL PATH DE GIT BASH..."
echo ""

# Ruta de PHP en XAMPP
PHP_PATH="/c/xampp82/php"
PHP_EXE="$PHP_PATH/php.exe"

# Verificar que PHP existe
if [ ! -f "$PHP_EXE" ]; then
    echo "❌ Error: No se encontró PHP en: $PHP_EXE"
    echo ""
    echo "💡 Verifica que XAMPP esté instalado en C:\xampp82"
    echo "   Si está en otra ubicación, edita este script y cambia la ruta."
    exit 1
fi

echo "✅ PHP encontrado en: $PHP_EXE"
echo ""

# Verificar versión de PHP
echo "📋 Versión de PHP:"
$PHP_EXE --version | head -1
echo ""

# Obtener ruta del archivo .bashrc
BASHRC_FILE="$HOME/.bashrc"

# Verificar si ya está configurado
if grep -q "xampp82/php" "$BASHRC_FILE" 2>/dev/null; then
    echo "⚠️  PHP ya está configurado en .bashrc"
    echo ""
    read -p "¿Deseas reconfigurarlo? (s/n): " respuesta
    if [ "$respuesta" != "s" ] && [ "$respuesta" != "S" ]; then
        echo "Operación cancelada."
        exit 0
    fi
    # Remover configuración anterior
    sed -i '/xampp82\/php/d' "$BASHRC_FILE" 2>/dev/null || sed -i '' '/xampp82\/php/d' "$BASHRC_FILE" 2>/dev/null
fi

# Agregar PHP al PATH
echo "📝 Agregando PHP al PATH..."
echo "" >> "$BASHRC_FILE"
echo "# PHP de XAMPP" >> "$BASHRC_FILE"
echo "export PATH=\"$PHP_PATH:\$PATH\"" >> "$BASHRC_FILE"

echo "✅ Configuración agregada a: $BASHRC_FILE"
echo ""

# Aplicar cambios en la sesión actual
export PATH="$PHP_PATH:$PATH"

# Verificar que funciona
echo "🔍 Verificando configuración..."
if command -v php &> /dev/null; then
    echo "✅ PHP ahora está disponible en el PATH"
    echo ""
    echo "📋 Comando PHP:"
    which php
    echo ""
    echo "📋 Versión:"
    php --version | head -1
    echo ""
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
    echo ""
    echo "✅ ¡CONFIGURACIÓN COMPLETA!"
    echo ""
    echo "Ahora puedes usar:"
    echo "  php artisan serve"
    echo "  php artisan migrate"
    echo "  php artisan --version"
    echo ""
    echo "💡 Nota: Si abres una nueva ventana de Git Bash, PHP estará disponible automáticamente."
    echo ""
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
else
    echo "❌ Error: No se pudo verificar la configuración"
    echo ""
    echo "💡 Intenta cerrar y reabrir Git Bash, luego ejecuta: php --version"
fi


