# Script para iniciar Pharma-Sync en modo desarrollo con Tauri

Write-Host "Iniciando Pharma-Sync en modo desarrollo..." -ForegroundColor Green
Write-Host ""

# Actualizar PATH para Rust
$env:PATH = [System.Environment]::GetEnvironmentVariable("PATH","Machine") + ";" + [System.Environment]::GetEnvironmentVariable("PATH","User")

# Iniciar Vite en una ventana separada
Write-Host "Iniciando Vite en puerto 5173..." -ForegroundColor Cyan
Start-Process powershell -ArgumentList "-NoExit", "-Command", "npm run dev:web"

# Esperar a que Vite inicie
Write-Host "Esperando a que Vite inicie..." -ForegroundColor Yellow
Start-Sleep -Seconds 5

# Iniciar Tauri
Write-Host "Iniciando Tauri..." -ForegroundColor Cyan
npm run dev

Read-Host "Presiona Enter para salir"
