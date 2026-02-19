# Guía Rápida - Pharma-Sync con Electron

## ✅ Migración Completada

Pharma-Sync ahora usa Electron en lugar de Tauri.

## 🚀 Comandos Disponibles

### Desarrollo
```bash
npm run electron:dev
```
Inicia Laravel y abre la aplicación en Electron automáticamente.

### Desarrollo Simple (solo Electron)
```bash
# Terminal 1: Inicia Laravel
php artisan serve --port=8000

# Terminal 2: Inicia Electron
npm run dev
```

### Crear Ejecutable .exe
```bash
npm run build
```
El instalador se creará en la carpeta `out/`

## 📁 Estructura de Archivos

```
pharma-sync/
├── electron/
│   ├── main.js       # Proceso principal de Electron
│   ├── preload.js    # Script de seguridad
│   └── icon.ico      # Icono de la aplicación
├── app/              # Laravel backend
├── resources/        # Laravel frontend
└── package.json      # Configuración de Electron
```

## 🔧 Cómo Funciona

1. Electron inicia el servidor PHP de Laravel en el puerto 8000
2. Abre una ventana que carga `http://127.0.0.1:8000`
3. Tu aplicación Laravel funciona normalmente
4. Al cerrar, Electron detiene el servidor PHP automáticamente

## 📦 Crear Instalador

```bash
npm run build
```

Esto genera:
- `out/Pharma-Sync-Setup-1.0.0.exe` - Instalador NSIS
- Tamaño aproximado: 150-200 MB
- Incluye todo: PHP, Laravel, base de datos SQLite

## 🎨 Personalizar Icono

Reemplaza `electron/icon.ico` con tu propio icono:
- Formato: .ico
- Tamaño recomendado: 256x256 px
- Puedes usar https://convertio.co/png-ico/

## ⚙️ Configuración Avanzada

Edita `electron/main.js` para:
- Cambiar tamaño de ventana
- Modificar puerto de Laravel
- Agregar menús personalizados
- Configurar splash screen

## 🐛 Solución de Problemas

### Error: Puerto 8000 en uso
```bash
# Mata el proceso en el puerto 8000
netstat -ano | findstr :8000
taskkill /PID [número] /F
```

### Error al compilar
```bash
# Limpia caché de Electron
npm run clean
npm install
```

### Ventana en blanco
- Verifica que Laravel esté corriendo en http://127.0.0.1:8000
- Abre DevTools: Ctrl+Shift+I en la ventana de Electron

## 📝 Notas Importantes

- La primera compilación puede tardar 5-10 minutos
- Compilaciones posteriores son más rápidas (2-3 minutos)
- El ejecutable incluye PHP embebido
- La base de datos SQLite se copia con la aplicación

## 🎯 Próximos Pasos

1. Prueba la aplicación: `npm run electron:dev`
2. Personaliza el icono en `electron/icon.ico`
3. Crea el instalador: `npm run build`
4. Distribuye `out/Pharma-Sync-Setup-1.0.0.exe`

## 🆚 Ventajas vs Tauri

✅ Sin Rust - solo Node.js
✅ Compilación rápida (2-3 min vs 20+ min)
✅ Menos uso de RAM durante compilación
✅ Mejor soporte para Laravel
✅ Más fácil de debuggear
✅ Documentación más amplia

❌ Ejecutable más grande (~150MB vs ~10MB)
❌ Usa más RAM en ejecución (~100MB vs ~50MB)

Para una aplicación de gestión como Pharma-Sync, Electron es la mejor opción.
