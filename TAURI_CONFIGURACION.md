# Configuración de Tauri para Pharma-Sync

## ✅ Instalación Completada

Se ha configurado exitosamente Tauri para Pharma-Sync.

### Archivos Creados

1. **src-tauri/tauri.conf.json** - Configuración principal de Tauri
2. **src-tauri/Cargo.toml** - Dependencias de Rust
3. **src-tauri/src/main.rs** - Punto de entrada de Tauri
4. **src-tauri/build.rs** - Script de compilación
5. **resources/js/tauri-init.js** - Inicialización de Tauri en JavaScript
6. **package.json** (actualizado) - Scripts de Tauri

### Estructura de Carpetas

```
pharma-sync/
├── src-tauri/
│   ├── src/
│   │   └── main.rs
│   ├── Cargo.toml
│   ├── build.rs
│   ├── tauri.conf.json
│   └── tauri.windows.conf.json
├── resources/
│   ├── js/
│   │   └── tauri-init.js
│   └── ...
├── package.json (actualizado)
└── ...
```

---

## 🚀 Cómo Usar

### Desarrollo

```bash
# Instalar dependencias
npm install

# Ejecutar en modo desarrollo
npm run dev
```

Esto abrirá la aplicación en una ventana nativa de Tauri.

### Compilación

```bash
# Compilar para Windows
npm run build
```

El ejecutable estará en: `src-tauri/target/release/bundle/nsis/`

---

## 📋 Requisitos

- ✅ Node.js v22.21.1
- ✅ npm 10.9.4
- ✅ Rust 1.93.1
- ✅ Tauri CLI instalado

---

## 🔧 Configuración de Tauri

### Ventana Principal

```json
{
  "title": "Pharma-Sync",
  "width": 1400,
  "height": 900,
  "minWidth": 1000,
  "minHeight": 600,
  "resizable": true
}
```

### Compilación

- **Targets**: NSIS (instalador) y MSI
- **Identificador**: com.pharmasync.app
- **Versión**: 1.0.0

---

## 📦 Próximos Pasos

### 1. Crear Iconos

Necesitas crear iconos en `src-tauri/icons/`:

- `icon.ico` (256x256)
- `32x32.png`
- `128x128.png`
- `128x128@2x.png`
- `icon.icns` (para Mac)

Puedes crear estos en:
- https://www.favicon-generator.org/
- https://convertio.co/

### 2. Compilar

```bash
npm run build
```

### 3. Distribuir

El archivo `.exe` estará listo para distribuir.

---

## 🎯 Características Implementadas

✅ Integración de Tauri
✅ Configuración de ventana
✅ Scripts de compilación
✅ Inicialización de JavaScript
✅ Estructura de carpetas

---

## 📝 Notas

- La aplicación Laravel sigue funcionando normalmente
- Tauri se ejecuta en modo desarrollo con `npm run dev`
- La compilación genera un instalador profesional
- Compatible con Windows, Mac y Linux

---

## 🔗 Recursos

- [Documentación de Tauri](https://tauri.app/)
- [Tauri API](https://tauri.app/en/api/js/)
- [Configuración de Tauri](https://tauri.app/en/api/config/)

