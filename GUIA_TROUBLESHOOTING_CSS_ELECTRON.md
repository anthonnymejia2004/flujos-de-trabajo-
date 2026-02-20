# Guía de Troubleshooting: CSS en Electron

## Problemas Comunes y Soluciones

### 1. CSS No Carga en Electron

**Síntomas:**
- La interfaz aparece sin estilos (texto plano)
- Los colores y layouts no se aplican
- Consola muestra errores de carga de assets

**Soluciones:**

```bash
# 1. Verificar que vite.config.js tiene base: './'
cat vite.config.js | grep "base:"

# 2. Limpiar y recompilar
npm run clean
npm run build

# 3. Verificar que manifest.json existe
cat public/build/.vite/manifest.json

# 4. Reiniciar Electron
npm run electron:dev
```

### 2. Errores en Consola de Electron

**Error: "Could not resolve './desktop'"**

Solución: Editar `resources/js/app.js` y remover imports no existentes:

```javascript
// ❌ Malo
import './desktop';
import './tauri-init';

// ✅ Correcto
import './bootstrap';
import './theme';
```

### 3. Puerto 8000 en Uso

**Error: "Address already in use"**

```bash
# Encontrar proceso en puerto 8000
netstat -ano | findstr :8000

# Matar proceso (reemplazar PID)
taskkill /PID [número] /F

# O cambiar puerto en electron/main.js
const PHP_PORT = 8001; // Cambiar a otro puerto
```

### 4. Manifest.json No Encontrado

**Error: "manifest.json not found"**

```bash
# Verificar que se generó
ls -la public/build/.vite/manifest.json

# Si no existe, recompilar
npm run build

# Verificar que vite.config.js tiene manifest: true
cat vite.config.js | grep "manifest:"
```

### 5. Tailwind CSS No Funciona

**Síntomas:**
- Clases de Tailwind no se aplican
- Solo estilos inline funcionan

**Soluciones:**

```bash
# 1. Verificar que tailwind.config.js tiene paths correctos
cat tailwind.config.js

# 2. Verificar que app.css tiene directivas Tailwind
head -n 5 resources/css/app.css

# 3. Recompilar
npm run build
```

### 6. Hot Reload No Funciona

**Síntomas:**
- Los cambios en CSS no se reflejan
- Necesita reiniciar Electron manualmente

**Soluciones:**

```bash
# 1. Verificar que Vite dev server está corriendo
npm run dev

# 2. En otra terminal, ejecutar Electron
npm run electron:dev

# 3. Si sigue sin funcionar, limpiar caché
rm -rf node_modules/.vite
npm run build
```

### 7. CSP Bloqueando Estilos

**Error en Consola:**
```
Refused to apply inline style because it violates the following Content Security Policy directive
```

**Solución:**
Verificar que `electron/main.js` tiene CSP configurado:

```javascript
'Content-Security-Policy': [
  "style-src 'self' 'unsafe-inline' http://127.0.0.1:*;"
]
```

## Comandos de Debugging

### Ver Logs de Electron

```bash
# Abrir DevTools
Ctrl+Shift+I en la ventana de Electron

# Ver Console tab para errores
# Ver Network tab para verificar carga de assets
# Ver Elements tab para inspeccionar estilos
```

### Verificar Assets Compilados

```bash
# Ver estructura de build
tree public/build/

# Ver contenido de manifest
cat public/build/.vite/manifest.json | jq

# Ver tamaño de CSS
du -h public/build/assets/*.css

# Verificar que CSS tiene clases de Tailwind
grep "tailwind" public/build/assets/*.css | head -n 5
```

### Limpiar Todo y Empezar de Cero

```bash
# Limpiar caché de Vite
rm -rf public/build
rm -rf node_modules/.vite

# Recompilar
npm run build

# Probar en navegador primero
php artisan serve

# Luego probar en Electron
npm run electron:dev
```

## Checklist de Verificación

Antes de reportar un problema, verificar:

- [ ] `vite.config.js` tiene `base: './'`
- [ ] `tailwind.config.js` tiene paths correctos
- [ ] `resources/css/app.css` tiene directivas Tailwind
- [ ] `resources/js/app.js` no importa archivos no existentes
- [ ] `electron/main.js` tiene CSP configurado
- [ ] `public/build/manifest.json` existe
- [ ] `public/build/assets/app-*.css` existe
- [ ] No hay errores en `npm run build`
- [ ] La aplicación funciona en navegador
- [ ] La aplicación funciona en Electron

## Solución Rápida

Si todo falla, ejecutar:

```bash
npm run clean
npm install
npm run build
npm run electron:dev
```

Esto limpia todo y recompila desde cero.

## Contacto y Soporte

Si el problema persiste:

1. Revisar `.kiro/specs/fix-css-electron/design.md` para detalles técnicos
2. Revisar logs en `storage/logs/laravel.log`
3. Abrir DevTools en Electron (Ctrl+Shift+I)
4. Documentar el error exacto y pasos para reproducirlo
