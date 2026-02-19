#!/bin/bash

# Script para iniciar Pharma-Sync en modo Electron

echo ""
echo "========================================"
echo "  PHARMA-SYNC - Sistema de Farmacia"
echo "========================================"
echo ""

# Verificar si Node.js está instalado
if ! command -v node &> /dev/null; then
    echo "ERROR: Node.js no está instalado"
    echo "Descarga Node.js desde: https://nodejs.org/"
    exit 1
fi

# Verificar si PHP está instalado
if ! command -v php &> /dev/null; then
    echo "ERROR: PHP no está instalado"
    echo "Descarga PHP desde: https://www.php.net/downloads"
    exit 1
fi

echo "✓ Node.js detectado"
echo "✓ PHP detectado"
echo ""

# Instalar dependencias si no existen
if [ ! -d "node_modules" ]; then
    echo "📦 Instalando dependencias de Node.js..."
    npm install
    if [ $? -ne 0 ]; then
        echo "ERROR: Fallo al instalar dependencias"
        exit 1
    fi
fi

echo ""
echo "🚀 Iniciando Pharma-Sync..."
echo ""

# Iniciar la aplicación
npm run dev
