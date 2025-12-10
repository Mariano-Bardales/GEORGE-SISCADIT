#!/bin/bash

# Script para solucionar el problema de PHP en Git Bash
# Ejecutar: bash fix_php_path.sh

echo "🔧 SOLUCIONANDO PROBLEMA DE PHP EN GIT BASH..."
echo ""

PHP_PATH="/c/xampp82/php"
PHP_EXE="$PHP_PATH/php.exe"

# Verificar que PHP existe
if [ ! -f "$PHP_EXE" ]; then
    echo "❌ Error: No se encontró PHP en: $PHP_EXE"
    exit 1
fi

echo "✅ PHP encontrado en: $PHP_EXE"
echo ""

# Crear o actualizar .bash_profile para que cargue .bashrc
BASHPROFILE="$HOME/.bash_profile"
BASHRC="$HOME/.bashrc"

echo "📝 Configurando .bash_profile..."

# Si .bash_profile no existe o no carga .bashrc, crearlo/actualizarlo
if [ ! -f "$BASHPROFILE" ] || ! grep -q "source.*bashrc\|\. .*bashrc" "$BASHPROFILE" 2>/dev/null; then
    echo "" >> "$BASHPROFILE"
    echo "# Cargar .bashrc si existe" >> "$BASHPROFILE"
    echo "if [ -f ~/.bashrc ]; then" >> "$BASHPROFILE"
    echo "    source ~/.bashrc" >> "$BASHPROFILE"
    echo "fi" >> "$BASHPROFILE"
    echo "✅ .bash_profile configurado para cargar .bashrc"
else
    echo "✅ .bash_profile ya carga .bashrc"
fi

# Asegurar que PHP esté en .bashrc
if ! grep -q "xampp82/php" "$BASHRC" 2>/dev/null; then
    echo "" >> "$BASHRC"
    echo "# PHP de XAMPP" >> "$BASHRC"
    echo "export PATH=\"$PHP_PATH:\$PATH\"" >> "$BASHRC"
    echo "✅ PHP agregado a .bashrc"
else
    echo "✅ PHP ya está en .bashrc"
fi

# También agregar directamente a .bash_profile por si acaso
if ! grep -q "xampp82/php" "$BASHPROFILE" 2>/dev/null; then
    echo "" >> "$BASHPROFILE"
    echo "# PHP de XAMPP (directo)" >> "$BASHPROFILE"
    echo "export PATH=\"$PHP_PATH:\$PATH\"" >> "$BASHPROFILE"
    echo "✅ PHP agregado directamente a .bash_profile"
else
    echo "✅ PHP ya está en .bash_profile"
fi

# Aplicar en la sesión actual
export PATH="$PHP_PATH:$PATH"

echo ""
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo ""

# Verificar
if command -v php &> /dev/null; then
    echo "✅ ¡CONFIGURACIÓN EXITOSA!"
    echo ""
    echo "📋 PHP ahora disponible:"
    which php
    php --version | head -1
    echo ""
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
    echo ""
    echo "✅ Ahora puedes usar: php artisan serve"
    echo ""
    echo "💡 IMPORTANTE: Cierra y reabre Git Bash para que los cambios surtan efecto."
    echo "   O ejecuta: source ~/.bash_profile"
else
    echo "⚠️  Configuración aplicada, pero necesitas recargar."
    echo ""
    echo "Ejecuta: source ~/.bash_profile"
fi

echo ""


