# Plan de Migración a Electron - Pharma-Sync

## Problema Actual
- Tauri requiere Rust y compilación pesada (20+ minutos)
- Errores de memoria durante compilación
- Configuración compleja para Windows

## Solución: Electron
Electron empaqueta tu aplicación Laravel web en un ejecutable nativo de Windows.

## Pasos de Migración

### 1. Limpieza de Tauri (2 min)
- Eliminar carpeta `src-tauri/`
- Limpiar scripts de Tauri en `package.json`
- Eliminar archivos de configuración Tauri

### 2. Instalación de Electron (3 min)
```bash
npm install --save-dev electron electron-builder
npm install --save-dev concurrently wait-on
```

### 3. Estructura de Electron (5 min)
```
electron/
├── main.js          # Proceso principal de Electron
├── preload.js       # Script de seguridad
└── icon.ico         # Icono de la aplicación
```

### 4. Configuración (3 min)
- Crear `electron/main.js` - Inicia Laravel y abre ventana
- Actualizar `package.json` con scripts de Electron
- Configurar `electron-builder` para crear .exe

### 5. Scripts de Desarrollo (2 min)
```json
"electron:dev": "concurrently \"php artisan serve\" \"wait-on http://127.0.0.1:8000 && electron .\"",
"electron:build": "electron-builder --win --x64"
```

## Ventajas vs Tauri
✅ Sin Rust - solo Node.js
✅ Compilación rápida (2-3 minutos)
✅ Menos uso de RAM
✅ Mejor soporte para Laravel
✅ Más fácil de debuggear

## Resultado Final
- Ejecutable `.exe` de ~150MB
- Incluye PHP embebido
- Base de datos SQLite portátil
- Listo para distribuir

## Tiempo Total Estimado
15 minutos (vs 30+ minutos con Tauri)
