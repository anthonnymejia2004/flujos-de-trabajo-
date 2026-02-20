# Diseño: Solución Permanente CSS en Electron

## 1. Análisis del Problema

### 1.1 Diagnóstico Técnico

El problema ocurre porque:

1. **Vite genera rutas absolutas** (`/build/assets/app-xxx.css`)
2. **Electron usa protocolo `file://`** en lugar de `http://`
3. **Las rutas absolutas no se resuelven** en `file://` protocol
4. **El manifest de Vite** puede no estar accesible

### 1.2 Soluciones Evaluadas

| Solución | Pros | Contras | Seleccionada |
|----------|------|---------|--------------|
| Rutas relativas en Vite | Simple, compatible | Puede afectar web | ✅ Sí |
| Custom protocol en Electron | Flexible | Complejo | ❌ No |
| Servir assets con servidor local | Funciona siempre | Overhead | ✅ Sí (fallback) |
| Inline CSS | Sin dependencias externas | Archivo grande | ❌ No |

## 2. Arquitectura de la Solución

### 2.1 Flujo de Carga de Assets

```
┌─────────────────┐
│  Laravel Blade  │
│   @vite('...')  │
└────────┬────────┘
         │
         ▼
┌─────────────────┐
│ Vite Manifest   │
│  manifest.json  │
└────────┬────────┘
         │
         ▼
┌─────────────────┐      ┌──────────────────┐
│  Desarrollo     │      │   Producción     │
│  Vite Dev       │      │   Static Files   │
│  Server         │      │   public/build/  │
└────────┬────────┘      └────────┬─────────┘
         │                        │
         └────────┬───────────────┘
                  │
                  ▼
         ┌────────────────┐
         │    Electron    │
         │  BrowserWindow │
         └────────────────┘
```

### 2.2 Componentes Afectados

1. **vite.config.js** - Configuración de build
2. **electron/main.js** - Carga de ventana y protocolo
3. **resources/views/layouts/app.blade.php** - Carga de assets
4. **tailwind.config.js** - Configuración de Tailwind
5. **package.json** - Scripts de build

## 3. Solución Técnica Detallada

### 3.1 Configuración de Vite

**Archivo: `vite.config.js`**

```javascript
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    base: './', // ← CLAVE: Rutas relativas
    build: {
        outDir: 'public/build',
        manifest: true,
        rollupOptions: {
            output: {
                manualChunks: undefined,
            },
        },
    },
    server: {
        host: '127.0.0.1',
        port: 5173,
        strictPort: true,
    },
});
```

**Cambios clave:**
- `base: './'` - Genera rutas relativas en lugar de absolutas
- `outDir: 'public/build'` - Directorio de salida explícito
- `manifest: true` - Genera manifest.json

### 3.2 Configuración de Electron

**Archivo: `electron/main.js`**

```javascript
const { app, BrowserWindow, protocol } = require('electron');
const { spawn } = require('child_process');
const path = require('path');
const fs = require('fs');

let phpServer = null;
let mainWindow = null;

const PHP_PORT = 8000;
const APP_URL = `http://127.0.0.1:${PHP_PORT}`;
const isDev = process.env.NODE_ENV === 'development';

// Registrar protocolo personalizado para assets
function registerFileProtocol() {
  protocol.registerFileProtocol('app', (request, callback) => {
    const url = request.url.substr(6); // Remueve 'app://'
    callback({ path: path.normalize(`${__dirname}/../${url}`) });
  });
}

function startLaravelServer() {
  return new Promise((resolve, reject) => {
    console.log('Iniciando servidor Laravel...');
    
    phpServer = spawn('php', ['artisan', 'serve', `--port=${PHP_PORT}`], {
      cwd: path.join(__dirname, '..'),
      shell: true,
      env: {
        ...process.env,
        APP_ENV: 'production',
        VITE_DEV_SERVER_URL: '', // Desactiva Vite dev server
      }
    });

    phpServer.stdout.on('data', (data) => {
      console.log(`Laravel: ${data}`);
      if (data.toString().includes('started')) {
        resolve();
      }
    });

    phpServer.stderr.on('data', (data) => {
      console.error(`Laravel Error: ${data}`);
    });

    setTimeout(() => resolve(), 10000);
  });
}

function createWindow() {
  mainWindow = new BrowserWindow({
    width: 1400,
    height: 900,
    minWidth: 1000,
    minHeight: 600,
    icon: path.join(__dirname, 'icon.ico'),
    webPreferences: {
      nodeIntegration: false,
      contextIsolation: true,
      preload: path.join(__dirname, 'preload.js'),
      webSecurity: true, // Mantener seguridad
    },
    autoHideMenuBar: true,
    title: 'Pharma-Sync',
    backgroundColor: '#ffffff',
  });

  // Configurar CSP para permitir estilos
  mainWindow.webContents.session.webRequest.onHeadersReceived((details, callback) => {
    callback({
      responseHeaders: {
        ...details.responseHeaders,
        'Content-Security-Policy': [
          "default-src 'self' http://127.0.0.1:* http://localhost:*; " +
          "style-src 'self' 'unsafe-inline' http://127.0.0.1:* http://localhost:*; " +
          "script-src 'self' 'unsafe-inline' 'unsafe-eval' http://127.0.0.1:* http://localhost:*; " +
          "img-src 'self' data: http://127.0.0.1:* http://localhost:*; " +
          "font-src 'self' data: http://127.0.0.1:* http://localhost:*;"
        ]
      }
    });
  });

  mainWindow.loadURL(APP_URL);

  if (isDev) {
    mainWindow.webContents.openDevTools();
  }

  // Debug: Log de errores de carga
  mainWindow.webContents.on('did-fail-load', (event, errorCode, errorDescription) => {
    console.error('Failed to load:', errorCode, errorDescription);
  });

  mainWindow.on('closed', () => {
    mainWindow = null;
  });
}

app.whenReady().then(async () => {
  registerFileProtocol();
  
  try {
    await startLaravelServer();
    createWindow();
  } catch (error) {
    console.error('Error al iniciar la aplicación:', error);
    app.quit();
  }
});

app.on('window-all-closed', () => {
  if (phpServer) {
    phpServer.kill();
  }
  app.quit();
});

app.on('before-quit', () => {
  if (phpServer) {
    phpServer.kill();
  }
});
```

**Cambios clave:**
- CSP configurado para permitir estilos inline
- Protocolo personalizado para assets
- Variables de entorno para desactivar Vite dev server en producción

### 3.3 Layout de Laravel

**Archivo: `resources/views/layouts/app.blade.php`**

Asegurar que use `@vite` correctamente:

```blade
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Pharma-Sync') }}</title>
    
    {{-- Vite Assets --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    @yield('content')
</body>
</html>
```

### 3.4 Configuración de Tailwind

**Archivo: `tailwind.config.js`**

```javascript
/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {
      colors: {
        primary: '#3b82f6',
        secondary: '#10b981',
        danger: '#ef4444',
      },
    },
  },
  plugins: [],
}
```

### 3.5 CSS Principal

**Archivo: `resources/css/app.css`**

```css
@tailwind base;
@tailwind components;
@tailwind utilities;

/* Estilos personalizados */
@layer components {
  .btn-primary {
    @apply bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition-colors;
  }
  
  .card {
    @apply bg-white rounded-lg shadow-md p-6;
  }
}
```

## 4. Scripts de Build

### 4.1 Package.json Scripts

```json
{
  "scripts": {
    "dev": "vite",
    "build": "vite build",
    "electron:dev": "concurrently \"php artisan serve --port=8000\" \"wait-on http://127.0.0.1:8000 && electron .\"",
    "electron:build": "npm run build && electron-builder --win --x64",
    "clean": "rm -rf public/build node_modules/.vite",
    "rebuild": "npm run clean && npm install && npm run build"
  }
}
```

### 4.2 Proceso de Build

1. **Desarrollo Web:**
   ```bash
   npm run dev
   php artisan serve
   ```

2. **Desarrollo Electron:**
   ```bash
   npm run build  # Compilar assets primero
   npm run electron:dev
   ```

3. **Producción:**
   ```bash
   npm run build
   npm run electron:build
   ```

## 5. Verificación y Testing

### 5.1 Checklist de Verificación

```markdown
## Pre-Build Checklist

- [ ] `vite.config.js` tiene `base: './'`
- [ ] `tailwind.config.js` incluye todas las rutas de templates
- [ ] `resources/css/app.css` tiene las directivas de Tailwind
- [ ] `resources/views/layouts/app.blade.php` usa `@vite`
- [ ] `electron/main.js` tiene CSP configurado
- [ ] `.env` tiene `APP_ENV=production` para build

## Post-Build Checklist

- [ ] Existe `public/build/manifest.json`
- [ ] Existe `public/build/assets/app-[hash].css`
- [ ] El CSS compilado contiene clases de Tailwind
- [ ] No hay errores en `npm run build`
- [ ] La aplicación web funciona correctamente
- [ ] La aplicación Electron carga los estilos
```

### 5.2 Comandos de Debugging

```bash
# Ver manifest generado
cat public/build/manifest.json

# Ver tamaño del CSS compilado
ls -lh public/build/assets/*.css

# Verificar que Tailwind está incluido
grep "tailwind" public/build/assets/*.css

# Limpiar caché y recompilar
npm run clean && npm run build

# Probar en navegador primero
php artisan serve
# Abrir http://127.0.0.1:8000

# Luego probar en Electron
npm run electron:dev
```

### 5.3 Errores Comunes y Soluciones

| Error | Causa | Solución |
|-------|-------|----------|
| CSS no carga | Rutas absolutas | Agregar `base: './'` en vite.config.js |
| Estilos bloqueados | CSP restrictivo | Configurar CSP en electron/main.js |
| Manifest no encontrado | Build incompleto | Ejecutar `npm run build` antes |
| Clases Tailwind no funcionan | Purge incorrecto | Verificar paths en tailwind.config.js |
| Hot reload no funciona | Puerto ocupado | Cambiar puerto en vite.config.js |

## 6. Propiedades de Correctness

### 6.1 Propiedad 1: Consistencia Visual
**Validates: Requirements 2.1, 2.2**

```
∀ página P en la aplicación:
  apariencia(P, navegador) = apariencia(P, electron)
```

**Prueba:**
- Capturar screenshot de cada página en navegador
- Capturar screenshot de la misma página en Electron
- Comparar píxel por píxel (permitir diferencias < 1%)

### 6.2 Propiedad 2: Carga Completa de Assets
**Validates: Requirements 3.1, 3.2**

```
∀ asset A referenciado en manifest.json:
  existe(A) ∧ accesible(A) ∧ válido(A)
```

**Prueba:**
- Parsear manifest.json
- Verificar que cada archivo existe en disco
- Verificar que cada archivo es accesible vía HTTP
- Verificar que el contenido es válido (CSS parseable)

### 6.3 Propiedad 3: No Errores de Consola
**Validates: Requirements 4.1, 4.2**

```
∀ página P cargada en Electron:
  errores_css(P) = ∅
```

**Prueba:**
- Interceptar console.error en Electron
- Filtrar errores relacionados con CSS/assets
- Verificar que la lista está vacía

### 6.4 Propiedad 4: Rutas Relativas
**Validates: Requirements 3.2**

```
∀ referencia R en manifest.json:
  ¬ comienza_con(R, '/') ∨ comienza_con(R, 'http')
```

**Prueba:**
- Parsear manifest.json
- Verificar que ninguna ruta comienza con '/'
- O que todas las rutas son relativas ('./')

## 7. Plan de Implementación

### Fase 1: Configuración Base (30 min)
1. Actualizar `vite.config.js` con `base: './'`
2. Verificar `tailwind.config.js`
3. Limpiar y recompilar: `npm run clean && npm run build`
4. Probar en navegador

### Fase 2: Integración Electron (20 min)
5. Actualizar `electron/main.js` con CSP
6. Agregar protocolo personalizado
7. Configurar variables de entorno
8. Probar en Electron

### Fase 3: Verificación (15 min)
9. Ejecutar checklist de verificación
10. Probar todas las páginas principales
11. Verificar consola de errores
12. Documentar cualquier issue

### Fase 4: Documentación (10 min)
13. Crear guía de troubleshooting
14. Documentar proceso de build
15. Crear scripts de verificación automática

## 8. Rollback Plan

Si la solución falla:

1. **Revertir vite.config.js:**
   ```bash
   git checkout vite.config.js
   ```

2. **Revertir electron/main.js:**
   ```bash
   git checkout electron/main.js
   ```

3. **Limpiar build:**
   ```bash
   npm run clean
   npm run build
   ```

4. **Verificar que la versión web funciona:**
   ```bash
   php artisan serve
   ```

## 9. Métricas de Éxito

- ✅ 0 errores de CSS en consola de Electron
- ✅ 100% de páginas con estilos correctos
- ✅ Tiempo de carga de CSS < 500ms
- ✅ Tamaño de CSS compilado < 500KB
- ✅ Build exitoso en primer intento
- ✅ Documentación completa y clara

## 10. Mantenimiento Futuro

### 10.1 Actualizaciones de Dependencias

Antes de actualizar Vite, Tailwind o Electron:

1. Crear branch de prueba
2. Actualizar dependencia
3. Ejecutar `npm run build`
4. Probar en navegador y Electron
5. Verificar que CSS carga correctamente
6. Mergear solo si todo funciona

### 10.2 Agregar Nuevos Estilos

1. Agregar clases en `resources/css/app.css`
2. Ejecutar `npm run build`
3. Probar en navegador primero
4. Luego probar en Electron
5. Commit solo si ambos funcionan

### 10.3 Debugging Continuo

Mantener estos comandos a mano:

```bash
# Ver logs de Electron
npm run electron:dev

# Ver manifest
cat public/build/manifest.json | jq

# Ver CSS compilado
cat public/build/assets/app-*.css | head -n 50

# Limpiar todo y empezar de cero
npm run clean && npm install && npm run build
```
