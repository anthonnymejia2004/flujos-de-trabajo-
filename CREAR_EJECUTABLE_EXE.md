# Crear Ejecutable .EXE para Pharma-Sync

## 🎯 Objetivo

Crear un archivo `.exe` que se pueda instalar y usar directamente en el escritorio de otras computadoras sin necesidad de abrir el editor de código.

---

## 📋 Opciones Disponibles

### Opción 1: Usar Tauri (RECOMENDADO - Más Simple)
- ✅ Genera .exe nativo
- ✅ Fácil de instalar
- ✅ Bajo consumo de recursos
- ✅ Rápido
- Tiempo: 2-3 horas

### Opción 2: Usar Electron
- ✅ Genera .exe
- ✅ Muy popular
- ❌ Más pesado (~150MB)
- ❌ Más lento
- Tiempo: 3-4 horas

### Opción 3: Usar NativePHP
- ✅ Genera .exe
- ✅ Integrado con Laravel
- ❌ Requiere PHP 8.3+
- ❌ Más complejo
- Tiempo: 4-5 horas

---

## 🚀 OPCIÓN 1: TAURI (RECOMENDADO)

### Paso 1: Instalar Requisitos

```bash
# Instalar Node.js (si no lo tienes)
# Descargar de: https://nodejs.org/

# Instalar Rust (si no lo tienes)
# Descargar de: https://rustup.rs/

# Verificar instalación
node --version
cargo --version
```

### Paso 2: Crear Proyecto Tauri

```bash
# Instalar Tauri CLI
npm install -g @tauri-apps/cli

# Crear proyecto Tauri
tauri init -d . -f npm
```

### Paso 3: Configurar Tauri

Editar `src-tauri/tauri.conf.json`:

```json
{
  "build": {
    "beforeBuildCommand": "npm run build",
    "beforeDevCommand": "npm run dev",
    "devPath": "http://localhost:5173",
    "frontendDist": "../dist"
  },
  "app": {
    "windows": [
      {
        "title": "Pharma-Sync",
        "width": 1400,
        "height": 900,
        "resizable": true,
        "fullscreen": false
      }
    ]
  },
  "bundle": {
    "active": true,
    "targets": ["msi", "nsis"],
    "identifier": "com.pharmasync.app",
    "icon": [
      "icons/32x32.png",
      "icons/128x128.png",
      "icons/128x128@2x.png",
      "icons/icon.icns",
      "icons/icon.ico"
    ]
  }
}
```

### Paso 4: Compilar a .EXE

```bash
# Compilar para Windows
npm run tauri build

# El ejecutable estará en:
# src-tauri/target/release/bundle/msi/
# src-tauri/target/release/bundle/nsis/
```

---

## 🔧 OPCIÓN 2: ELECTRON

### Paso 1: Instalar Electron

```bash
npm install electron --save-dev
npm install electron-builder --save-dev
```

### Paso 2: Crear Archivo Principal

Crear `main.js`:

```javascript
const { app, BrowserWindow } = require('electron');
const path = require('path');

let mainWindow;

function createWindow() {
  mainWindow = new BrowserWindow({
    width: 1400,
    height: 900,
    webPreferences: {
      preload: path.join(__dirname, 'preload.js')
    }
  });

  mainWindow.loadURL('http://localhost:8000');
  mainWindow.webContents.openDevTools();
}

app.on('ready', createWindow);

app.on('window-all-closed', () => {
  if (process.platform !== 'darwin') {
    app.quit();
  }
});
```

### Paso 3: Configurar package.json

```json
{
  "name": "pharma-sync",
  "version": "1.0.0",
  "main": "main.js",
  "scripts": {
    "start": "electron .",
    "build": "electron-builder"
  },
  "build": {
    "appId": "com.pharmasync.app",
    "productName": "Pharma-Sync",
    "files": [
      "main.js",
      "preload.js"
    ],
    "win": {
      "target": [
        "nsis",
        "portable"
      ]
    },
    "nsis": {
      "oneClick": false,
      "allowToChangeInstallationDirectory": true
    }
  }
}
```

### Paso 4: Compilar

```bash
npm run build
```

---

## 📦 OPCIÓN 3: NATIVEPHP

### Paso 1: Instalar NativePHP

```bash
composer require laravel-zero/framework
```

### Paso 2: Compilar

```bash
php artisan native:build windows
```

---

## 🎯 RECOMENDACIÓN FINAL

**Usa TAURI** porque:

✅ Genera .exe pequeño (~50MB)
✅ Rápido de compilar
✅ Fácil de instalar
✅ Bajo consumo de recursos
✅ Mejor rendimiento
✅ Instalador profesional

---

## 📋 PASOS RÁPIDOS PARA TAURI

```bash
# 1. Instalar Node.js y Rust

# 2. Instalar Tauri CLI
npm install -g @tauri-apps/cli

# 3. Inicializar Tauri
tauri init -d . -f npm

# 4. Compilar
npm run tauri build

# 5. El .exe estará en:
# src-tauri/target/release/bundle/nsis/Pharma-Sync_1.0.0_x64-setup.exe
```

---

## 🖼️ ICONOS NECESARIOS

Para compilar, necesitas iconos en `src-tauri/icons/`:

- `icon.ico` (256x256)
- `32x32.png`
- `128x128.png`
- `128x128@2x.png`
- `icon.icns` (para Mac)

Puedes crear estos iconos en:
- https://www.favicon-generator.org/
- https://convertio.co/

---

## 📊 COMPARATIVA

| Aspecto | Tauri | Electron | NativePHP |
|---------|-------|----------|-----------|
| Tamaño | 50MB | 150MB | 200MB |
| Velocidad | Rápido | Medio | Lento |
| Facilidad | Fácil | Medio | Difícil |
| Instalador | Sí | Sí | Sí |
| Tiempo | 2-3h | 3-4h | 4-5h |

---

## ✅ RESULTADO FINAL

Después de compilar, tendrás:

✅ `Pharma-Sync_1.0.0_x64-setup.exe` - Instalador
✅ `Pharma-Sync_1.0.0_x64.exe` - Versión portable
✅ Puedes distribuir a otros usuarios
✅ Se instala como cualquier programa
✅ Icono en el escritorio
✅ Acceso desde menú inicio

---

## 🚀 PRÓXIMOS PASOS

1. Elige una opción (recomiendo TAURI)
2. Instala los requisitos
3. Sigue los pasos
4. Compila
5. Distribuye el .exe

¿Cuál opción prefieres? Te ayudaré a implementarla.

