# Plan de Solución: CSS Roto en Electron

## 🎯 Objetivo

Solucionar permanentemente el problema de estilos CSS que se rompen al ejecutar Pharma-Sync en Electron.

## 📋 Problema Actual

Cada vez que la aplicación se convierte a escritorio con Electron:
- ❌ Los estilos CSS no cargan
- ❌ La interfaz aparece sin formato (texto plano)
- ❌ Los componentes de Tailwind no se renderizan
- ❌ La aplicación se ve rota visualmente

## 🔍 Causa Raíz

1. **Vite genera rutas absolutas** (`/build/assets/app.css`)
2. **Electron usa protocolo `file://`** que no resuelve rutas absolutas
3. **CSP restrictivo** bloquea estilos inline
4. **Manifest no accesible** desde Electron

## ✅ Solución Propuesta

### 1. Configurar Vite con Rutas Relativas
```javascript
// vite.config.js
export default defineConfig({
    base: './',  // ← Rutas relativas
    build: {
        outDir: 'public/build',
        manifest: true,
    },
});
```

### 2. Configurar CSP en Electron
```javascript
// electron/main.js
'Content-Security-Policy': [
  "style-src 'self' 'unsafe-inline' http://127.0.0.1:*;"
]
```

### 3. Proceso de Build Correcto
```bash
# Siempre compilar assets antes de Electron
npm run build
npm run electron:dev
```

## 📁 Archivos del Spec

He creado un spec completo en `.kiro/specs/fix-css-electron/`:

1. **requirements.md** - Requisitos detallados y casos de uso
2. **design.md** - Solución técnica completa con código
3. **tasks.md** - Lista de tareas paso a paso

## 🚀 Cómo Ejecutar la Solución

### Opción 1: Ejecución Manual

Sigue las tareas en orden:

```bash
# 1. Actualizar configuración
# Editar vite.config.js según design.md

# 2. Limpiar y recompilar
npm run clean
npm run build

# 3. Probar en navegador
php artisan serve
# Abrir http://127.0.0.1:8000

# 4. Probar en Electron
npm run electron:dev
```

### Opción 2: Ejecución con Kiro (Recomendado)

```bash
# Ejecutar todas las tareas del spec automáticamente
kiro execute spec fix-css-electron
```

## 📊 Tiempo Estimado

- **Configuración**: 30 minutos
- **Pruebas**: 30 minutos
- **Documentación**: 30 minutos
- **Total**: ~90 minutos

## ✨ Resultado Esperado

Después de implementar la solución:

- ✅ Los estilos CSS cargan correctamente en Electron
- ✅ La interfaz se ve idéntica en navegador y Electron
- ✅ No hay errores en la consola
- ✅ El problema no vuelve a ocurrir en futuros builds

## 🎓 Lecciones Aprendidas

1. **Siempre usar rutas relativas** en Electron
2. **Compilar assets antes** de ejecutar Electron
3. **Configurar CSP correctamente** para permitir estilos
4. **Probar en navegador primero** antes de Electron

## 📚 Documentación Adicional

- `ELECTRON_GUIA_RAPIDA.md` - Guía de uso de Electron
- `PLAN_MIGRACION_ELECTRON.md` - Plan de migración desde Tauri
- `.kiro/specs/fix-css-electron/` - Spec completo

## 🆘 Soporte

Si encuentras problemas:

1. Revisa `design.md` sección "Errores Comunes"
2. Ejecuta comandos de debugging
3. Verifica checklist de verificación
4. Consulta la documentación del spec

## 🎯 Próximos Pasos

1. **Revisar el spec** en `.kiro/specs/fix-css-electron/`
2. **Decidir método de ejecución** (manual o con Kiro)
3. **Ejecutar las tareas** en orden
4. **Verificar resultado** con checklist
5. **Documentar cualquier issue** encontrado

---

**¿Listo para empezar?**

Puedes ejecutar las tareas manualmente siguiendo `tasks.md` o usar Kiro para ejecutar el spec completo automáticamente.
