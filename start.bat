@echo off
echo.
echo ========================================
echo   PHARMA-SYNC - Iniciando...
echo ========================================
echo.

REM Verificar Node.js
where node >nul 2>nul
if %ERRORLEVEL% NEQ 0 (
    echo ERROR: Node.js no instalado
    pause
    exit /b 1
)

REM Verificar PHP
where php >nul 2>nul
if %ERRORLEVEL% NEQ 0 (
    echo ERROR: PHP no instalado
    pause
    exit /b 1
)

echo Iniciando Pharma-Sync...
echo.

REM Iniciar Electron
npm start

pause
