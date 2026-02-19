# 🚀 Tauri - Comandos Rápidos

## Desarrollo

### Iniciar en modo desarrollo
```bash
npm run dev
```
- Abre una ventana nativa de Tauri
- Recarga automática al cambiar código
- Acceso a DevTools (F12)

### Compilar frontend solo
```bash
npm run build:web
```
- Compila Vite a `dist/`
- No compila Rust

### Iniciar Vite solo
```bash
npm run dev:web
```
- Inicia servidor en http://localhost:5173
- Útil para desarrollo web rápido

---

## Compilación

### Compilar ejecutable
```bash
npm run build
```
- Compila frontend y Rust
- Genera instalador NSIS
- Crea ejecutable portable
- **Tiempo**: 5-10 minutos

### Ubicación del instalador
```
src-tauri/target/release/bundle/nsis/Pharma-Sync_1.0.0_x64-setup.exe
```

---

## Tauri CLI Directo

### Ver versión
```bash
tauri --version
```

### Crear nuevo proyecto (referencia)
```bash
npm create tauri-app
```

### Limpiar compilación
```bash
cargo clean
```

---

## Solución de Problemas

### Si `npm run dev` falla

1. Verificar Rust instalado:
```bash
rustc --version
cargo --version
```

2. Actualizar PATH:
```bash
$env:PATH = [System.Environment]::GetEnvironmentVariable("PATH","Machine") + ";" + [System.Environment]::GetEnvironmentVariable("PATH","User")
```

3. Limpiar y reintentar:
```bash
cargo clean
npm run dev
```

### Si el puerto 5173 está en uso

Cambiar en `tauri.conf.json`:
```json
"devUrl": "http://localhost:5174"
```

### Si hay errores de compilación

1. Actualizar dependencias:
```bash
npm install
cargo update
```

2. Limpiar caché:
```bash
cargo clean
rm -r node_modules
npm install
```

---

## Archivos Importantes

| Archivo | Propósito |
|---------|-----------|
| `src-tauri/tauri.conf.json` | Configuración principal |
| `src-tauri/src/main.rs` | Punto de entrada Rust |
| `src-tauri/Cargo.toml` | Dependencias Rust |
| `package.json` | Scripts y dependencias Node |
| `resources/js/tauri-init.js` | Inicialización Tauri |

---

## Configuración Rápida

### Cambiar tamaño de ventana
En `src-tauri/tauri.conf.json`:
```json
"windows": [
  {
    "title": "Pharma-Sync",
    "width": 1400,
    "height": 900
  }
]
```

### Cambiar nombre de aplicación
En `src-tauri/tauri.conf.json`:
```json
"productName": "Pharma-Sync"
```

### Cambiar versión
En `src-tauri/tauri.conf.json`:
```json
"version": "1.0.0"
```

---

## Distribución

### Archivo generado
```
Pharma-Sync_1.0.0_x64-setup.exe
```

### Características del instalador
- ✓ Instalación en Program Files
- ✓ Acceso directo en escritorio
- ✓ Entrada en menú Inicio
- ✓ Desinstalador incluido
- ✓ No requiere dependencias externas

### Requisitos del usuario
- Windows 7 o superior
- ~100 MB de espacio en disco
- Nada más (sin Rust, Node.js, etc.)

---

## Monitoreo

### Ver logs en desarrollo
```bash
npm run dev
```
- Abre DevTools con F12
- Ver Console para errores

### Ver logs en compilación
```bash
npm run build 2>&1 | tee build.log
```

---

## Próximos Pasos

1. **Probar**: `npm run dev`
2. **Compilar**: `npm run build`
3. **Distribuir**: Compartir `.exe`
4. **Actualizar**: Cambiar versión y recompilar

---

## Recursos

- [Tauri Docs](https://tauri.app/)
- [Tauri API](https://tauri.app/en/api/js/)
- [Cargo Book](https://doc.rust-lang.org/cargo/)

---

**¡Listo para crear tu aplicación de escritorio!** 🎉
