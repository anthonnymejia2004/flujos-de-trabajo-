@echo off
REM Script para iniciar Pharma-Sync en modo Electron

echo.
echo ========================================
echo   PHARMA-SYNC - Sistema de Farmacia
echo ========================================
echo.

REM Verificar si Node.js está instalado
where node >nul 2>nul
if %ERRORLEVEL% NEQ 0 (
    echo ERROR: Node.js no está instalado o no está en el PATH
    echo Descarga Node.js desde: https://nodejs.org/
    pause
    exit /b 1
)

REM Verificar si PHP está instalado
where php >nul 2>nul
if %ERRORLEVEL% NEQ 0 (
    echo ERROR: PHP no está instalado o no está en el PATH
    echo Descarga PHP desde: https://www.php.net/downloads
    pause
    exit /b 1
)

echo ✓ Node.js detectado
echo ✓ PHP detectado
echo.

REM Instalar dependencias si no existen
if not exist "node_modules" (
    echo 📦 Instalando dependencias de Node.js...
    call npm install
    if %ERRORLEVEL% NEQ 0 (
        echo ERROR: Fallo al instalar dependencias
        pause
        exit /b 1
    )
)

echo.
echo 🚀 Iniciando Pharma-Sync...
echo.

REM Iniciar la aplicación
call npm run dev

pause
