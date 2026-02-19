@echo off
REM Script para iniciar Pharma-Sync en modo desarrollo con Tauri

echo Iniciando Pharma-Sync en modo desarrollo...
echo.

REM Actualizar PATH para Rust
set PATH=%USERPROFILE%\.cargo\bin;%PATH%

REM Iniciar Vite en una ventana separada
echo Iniciando Vite en puerto 5173...
start "Vite Dev Server" cmd /k npm run dev:web

REM Esperar a que Vite inicie
timeout /t 5 /nobreak

REM Iniciar Tauri
echo Iniciando Tauri...
npm run dev

pause
