# 🚀 Cómo Ver los Cambios de UI - Pharma-Sync

## ⚠️ IMPORTANTE: Vite DEBE estar corriendo

Para que los estilos CSS se apliquen correctamente, **DEBES tener Vite corriendo en modo desarrollo**.

---

## 📋 Pasos para Ver los Cambios

### Paso 1: Iniciar Vite (OBLIGATORIO)

Abre una terminal y ejecuta:

```bash
npm run dev
```

**Debes ver algo como esto:**
```
VITE v7.3.1  ready in 933 ms
➜  Local:   http://localhost:5173/
➜  Network: http://192.168.100.131:5173/
➜  APP_URL: http://localhost:8000
```

⚠️ **NO CIERRES ESTA TERMINAL** - Vite debe seguir corriendo mientras usas la aplicación.

---

### Paso 2: Iniciar Laravel

Abre **OTRA terminal** (deja la de Vite abierta) y ejecuta:

```bash
php artisan serve
```

**Debes ver:**
```
INFO  Server running on [http://127.0.0.1:8000]
```

---

### Paso 3: Abrir en el Navegador

1. Abre tu navegador
2. Ve a: `http://localhost:8000`
3. **Presiona Ctrl + Shift + R** (o Ctrl + F5) para forzar recarga sin caché
4. Login con: `admin@pharmasync.com` / `admin123`

---

## 🔧 Si Aún No Ves los Estilos

### Solución 1: Limpiar Caché de Laravel

```bash
php artisan view:clear
php artisan cache:clear
php artisan config:clear
```

### Solución 2: Forzar Recarga del Navegador

- **Chrome/Edge**: Ctrl + Shift + R o Ctrl + F5
- **Firefox**: Ctrl + Shift + R
- **Safari**: Cmd + Shift + R

### Solución 3: Verificar que Vite esté Corriendo

En la terminal donde ejecutaste `npm run dev`, debes ver:
```
➜  Local:   http://localhost:5173/
```

Si no lo ves, Vite no está corriendo. Ejecuta `npm run dev` de nuevo.

### Solución 4: Verificar en el Navegador

1. Abre las DevTools (F12)
2. Ve a la pestaña "Network" (Red)
3. Recarga la página (Ctrl + R)
4. Busca archivos que empiecen con `app-` o `css-`
5. Si ves errores 404, Vite no está corriendo

---

## 🎯 Qué Deberías Ver

### Header:
- Logo con gradiente azul → púrpura
- Título con gradiente de texto
- Buscador moderno con icono
- Sidebar con fondo oscuro degradado

### Dashboard:
- 4 tarjetas con iconos coloridos y gradientes
- Valores con gradiente de texto
- Animaciones al cargar (fade-in)
- Efectos hover al pasar el mouse
- Tabla moderna con hover effects

### Sidebar:
- Fondo oscuro con gradiente
- Items con hover effects
- Indicador visual en página activa
- Iconos coloridos

---

## 🐛 Problemas Comunes

### Problema 1: "Se ve sin estilos / todo blanco y negro"
**Causa**: Vite no está corriendo
**Solución**: Ejecuta `npm run dev` en una terminal

### Problema 2: "Los estilos antiguos siguen apareciendo"
**Causa**: Caché del navegador
**Solución**: Ctrl + Shift + R para forzar recarga

### Problema 3: "Error 404 en archivos CSS/JS"
**Causa**: Vite no está corriendo o Laravel no está corriendo
**Solución**: 
1. Verifica que `npm run dev` esté corriendo
2. Verifica que `php artisan serve` esté corriendo

### Problema 4: "Vite dice 'Port 5173 is already in use'"
**Causa**: Vite ya está corriendo en otra terminal
**Solución**: 
1. Cierra la otra terminal con Vite
2. O usa el proceso existente

---

## 📱 Para Usar con Electron

Si quieres ver la aplicación como aplicación de escritorio:

### Terminal 1: Vite
```bash
npm run dev
```

### Terminal 2: Laravel
```bash
php artisan serve
```

### Terminal 3: Electron
```bash
npm run electron:dev
```

---

## ✅ Checklist de Verificación

Antes de reportar que "no se ve":

- [ ] ¿Está corriendo `npm run dev`?
- [ ] ¿Está corriendo `php artisan serve`?
- [ ] ¿Hice Ctrl + Shift + R en el navegador?
- [ ] ¿Limpié la caché de Laravel?
- [ ] ¿Veo "VITE v7.3.1 ready" en la terminal?
- [ ] ¿Veo "Server running on http://127.0.0.1:8000" en la otra terminal?

Si todas las respuestas son SÍ y aún no ves los estilos, entonces hay un problema real.

---

## 🎨 Comparación Visual

### ANTES (Sin Vite corriendo):
```
┌─────────────────────────┐
│ Pharma-Sync             │  ← Texto simple, sin gradiente
│ Sistema de Farmacia     │
│                         │
│ [Buscar...]             │  ← Input básico
│                         │
│ Dashboard               │  ← Sidebar blanco
│ Inventario              │
│ Ventas                  │
│                         │
│ Valor Total Venta       │  ← Tarjetas planas
│ $1,234.56               │
└─────────────────────────┘
```

### DESPUÉS (Con Vite corriendo):
```
╔═══════════════════════════╗
║ 🌈 Pharma-Sync           ║  ← Gradiente azul→púrpura
║ Sistema de Farmacia      ║
║                          ║
║ 🔍 [Buscar...]          ║  ← Input moderno con icono
║                          ║
║ ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓  ║  ← Sidebar oscuro degradado
║ ▓ 📊 Dashboard         ▓ ║
║ ▓ 📦 Inventario        ▓ ║
║ ▓ 🛒 Ventas            ▓ ║
║ ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓  ║
║                          ║
║ ┌──────────────────────┐ ║
║ │ 💰 Valor Total Venta │ ║  ← Tarjeta con gradiente
║ │ $1,234.56 ✨         │ ║  ← Valor con gradiente
║ └──────────────────────┘ ║
╚═══════════════════════════╝
```

---

## 🎯 Resumen Rápido

**Para ver los cambios:**

1. Terminal 1: `npm run dev` ← **OBLIGATORIO**
2. Terminal 2: `php artisan serve`
3. Navegador: `http://localhost:8000`
4. Presiona: **Ctrl + Shift + R**

**Si no funciona:**
```bash
php artisan view:clear
php artisan cache:clear
```

Luego Ctrl + Shift + R en el navegador.

---

**Fecha**: 18 de Febrero de 2026
**Estado**: Vite corriendo en puerto 5173 ✅
**Laravel**: Debe correr en puerto 8000

