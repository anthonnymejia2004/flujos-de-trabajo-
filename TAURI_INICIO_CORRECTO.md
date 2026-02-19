# 🚀 Cómo Iniciar Tauri Correctamente

## El Problema Que Tuviste

El frontend se veía horrible sin estilos porque Vite no estaba sirviendo los archivos CSS/JS.

## La Solución

**Usa uno de estos scripts** (elige uno):

### ✅ Opción 1: PowerShell (RECOMENDADO)
```powershell
.\start-dev.ps1
```

### ✅ Opción 2: CMD
```cmd
start-dev.bat
```

### ✅ Opción 3: Manual (Si prefieres)

**Abre DOS terminales**:

Terminal 1:
```bash
npm run dev:web
```

Terminal 2 (después de que Terminal 1 esté lista):
```bash
npm run dev
```

---

## 📊 Qué Sucede

```
1. Vite inicia en puerto 5173
   ↓
2. Espera 5 segundos
   ↓
3. Tauri se conecta a Vite
   ↓
4. Se abre ventana nativa con tu aplicación
   ↓
5. ¡Frontend con estilos correctos!
```

---

## ✨ Resultado

- ✅ Frontend con todos los estilos CSS
- ✅ Aplicación funcionando correctamente
- ✅ Recarga automática al cambiar código
- ✅ DevTools disponibles (F12)

---

## 🎯 Próximas Veces

Simplemente ejecuta:
```powershell
.\start-dev.ps1
```

¡Eso es todo! 🎉
