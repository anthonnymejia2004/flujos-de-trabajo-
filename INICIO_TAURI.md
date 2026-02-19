# 🚀 Inicio Rápido - Tauri Pharma-Sync

## ¡Bienvenido! Aquí está todo lo que necesitas saber

---

## 📌 Lo Más Importante

Tu aplicación Pharma-Sync está lista para ser una aplicación de escritorio.

**Dos comandos principales**:

```bash
npm run dev      # Prueba en desarrollo
npm run build    # Compila el ejecutable
```

---

## ⚡ Inicio en 3 Pasos

### 1️⃣ Probar en Desarrollo (30 segundos)
```bash
npm run dev
```
- Se abre una ventana nativa
- Puedes ver tu aplicación funcionando
- Presiona F12 para DevTools

### 2️⃣ Compilar Ejecutable (5-10 minutos)
```bash
npm run build
```
- Genera el instalador `.exe`
- Se guarda en: `src-tauri/target/release/bundle/nsis/`
- Archivo: `Pharma-Sync_1.0.0_x64-setup.exe`

### 3️⃣ Distribuir (Opcional)
- Comparte el archivo `.exe` con usuarios
- Ellos lo instalan como cualquier programa
- ¡Listo!

---

## 📋 Checklist Rápido

- [x] Tauri instalado
- [x] Rust compilado
- [x] Iconos generados
- [x] Configuración lista
- [x] Scripts NPM configurados

**Estado**: ✅ TODO LISTO

---

## 🎯 Qué Sucede Cuando Ejecutas

### `npm run dev`
```
1. Inicia Vite (frontend)
2. Compila Rust
3. Abre ventana nativa
4. Carga tu aplicación
5. Recarga automática al cambiar código
```

### `npm run build`
```
1. Compila frontend a dist/
2. Compila Rust en modo release
3. Genera instalador NSIS
4. Crea ejecutable portable
5. Listo para distribuir
```

---

## 📁 Dónde Encontrar Cosas

| Qué | Dónde |
|-----|-------|
| Configuración Tauri | `src-tauri/tauri.conf.json` |
| Código Rust | `src-tauri/src/main.rs` |
| Iconos | `src-tauri/icons/` |
| Frontend | `resources/js/` |
| Ejecutable | `src-tauri/target/release/bundle/nsis/` |

---

## 🔧 Comandos Útiles

```bash
# Desarrollo
npm run dev              # Inicia en desarrollo

# Compilación
npm run build            # Compila ejecutable

# Frontend solo
npm run dev:web          # Inicia Vite
npm run build:web        # Compila frontend

# Limpiar
cargo clean              # Limpia compilación Rust
rm -r node_modules       # Limpia dependencias npm
```

---

## ⚠️ Si Algo No Funciona

### Error: "cargo not found"
```bash
# Actualizar PATH
$env:PATH = [System.Environment]::GetEnvironmentVariable("PATH","Machine") + ";" + [System.Environment]::GetEnvironmentVariable("PATH","User")

# Verificar
cargo --version
```

### Error: "Port 5173 already in use"
```bash
# Cambiar puerto en tauri.conf.json
# O matar el proceso que usa el puerto
```

### Error: "Compilation failed"
```bash
# Limpiar y reintentar
cargo clean
npm install
npm run dev
```

---

## 📚 Documentación Disponible

1. **TAURI_SETUP_COMPLETADO.md** - Guía completa
2. **TAURI_COMANDOS_RAPIDOS.md** - Referencia rápida
3. **GUIA_VISUAL_TAURI.md** - Diagramas
4. **RESUMEN_TAURI_FINAL.md** - Resumen
5. **STATUS_TRANSFORMACION_TAURI.md** - Estado actual

---

## 🎓 Conceptos Clave

### Tauri
- Framework para crear aplicaciones de escritorio
- Usa Rust para el backend
- Usa web technologies para el frontend
- Tamaño pequeño (~50 MB)

### Flujo
```
Código → Compilación → Instalador → Distribución
```

### Resultado
- Aplicación nativa de Windows
- Instalador profesional
- Sin dependencias externas
- Funciona como cualquier programa

---

## ✨ Características

✅ Aplicación nativa
✅ Instalador profesional
✅ Acceso directo en escritorio
✅ Entrada en menú Inicio
✅ Desinstalador incluido
✅ Tamaño optimizado
✅ Rendimiento nativo

---

## 🚀 Próximo Paso

```bash
npm run dev
```

¡Abre tu aplicación en una ventana nativa! 🎉

---

## 💡 Tips

1. **Desarrollo rápido**: Usa `npm run dev` para cambios rápidos
2. **DevTools**: Presiona F12 en la ventana de desarrollo
3. **Recarga**: Los cambios se recargan automáticamente
4. **Compilación**: La primera toma más tiempo (3-4 min)
5. **Distribución**: Solo necesitas compartir el `.exe`

---

## 📞 Ayuda

- Revisar `TAURI_COMANDOS_RAPIDOS.md` para solución de problemas
- Consultar [Tauri Docs](https://tauri.app/)
- Verificar que Rust esté instalado: `rustc --version`

---

## 🎉 ¡Listo!

Tu aplicación Pharma-Sync está lista para ser una aplicación de escritorio profesional.

**Comienza ahora**:
```bash
npm run dev
```

¡Disfruta! 🚀
