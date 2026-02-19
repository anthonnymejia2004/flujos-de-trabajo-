# ⚙️ Configuración de NativePHP para Pharma-Sync

## 📋 Archivos de Configuración

### 1. `config/nativephp.php`
Configuración principal de la aplicación NativePHP:
- Nombre y versión de la aplicación
- Dimensiones de ventanas
- Menú de la aplicación
- Iconos

### 2. `app/NativePHP/Application.php`
Punto de entrada de la aplicación:
- Define la ventana principal
- Ruta inicial (dashboard)
- Dimensiones y propiedades

### 3. `package.json`
Scripts de desarrollo y compilación:
- `npm run dev` - Inicia en desarrollo
- `npm run build` - Compila para distribución
- `npm run dev:web` - Desarrollo web sin NativePHP
- `npm run build:web` - Compilación web sin NativePHP

---

## 🔧 Configuración Recomendada

### Base de Datos
```env
DB_CONNECTION=sqlite
```

### Caché
```env
CACHE_STORE=database
```

### Sesión
```env
SESSION_DRIVER=database
```

### Aplicación
```env
APP_ENV=production
APP_DEBUG=false
```

---

## 🚀 Comandos de Desarrollo

### Iniciar en Desarrollo
```bash
npm run dev
```

Esto:
- Inicia el servidor Laravel
- Abre la aplicación NativePHP
- Recarga automática en cambios

### Compilar para Distribución
```bash
npm run build
```

Genera instaladores en `builds/`:
- Windows: `Pharma-Sync-Setup.exe`
- macOS: `Pharma-Sync.dmg`
- Linux: `pharma-sync.AppImage`

---

## 📦 Estructura de Carpetas

```
pharma-sync/
├── app/
│   ├── Http/
│   ├── Models/
│   ├── NativePHP/
│   │   └── Application.php
│   └── ...
├── config/
│   ├── nativephp.php
│   └── ...
├── database/
│   ├── database.sqlite
│   ├── migrations/
│   └── seeders/
├── resources/
│   ├── views/
│   ├── css/
│   ├── js/
│   └── images/
│       ├── icon.png (512x512)
│       └── tray-icon.png (256x256)
├── builds/
│   ├── Pharma-Sync-Setup.exe
│   ├── Pharma-Sync.dmg
│   └── pharma-sync.AppImage
├── native/
│   └── (generado automáticamente)
└── ...
```

---

## 🎯 Iconos Necesarios

### `resources/images/icon.png`
- Tamaño: 512x512 píxeles
- Formato: PNG con fondo transparente
- Uso: Icono principal de la aplicación

### `resources/images/tray-icon.png`
- Tamaño: 256x256 píxeles
- Formato: PNG con fondo transparente
- Uso: Icono de bandeja del sistema

---

## 🔐 Seguridad

### Producción
```env
APP_ENV=production
APP_DEBUG=false
```

### Base de Datos
- SQLite: Archivo local (seguro)
- MySQL/PostgreSQL: Configurar credenciales seguras

### Actualizaciones
NativePHP maneja actualizaciones automáticas y seguras.

---

## 📊 Requisitos del Sistema

### Para Desarrollo
- PHP 8.2+
- Composer
- Node.js 18+

### Para Usuarios Finales
- Windows 7+
- macOS 10.13+
- Linux (cualquier distribución moderna)

---

## 🆘 Solución de Problemas

### La aplicación no inicia
```bash
php artisan cache:clear
php artisan config:clear
npm run dev
```

### Iconos no aparecen
Verifica que existan:
- `resources/images/icon.png`
- `resources/images/tray-icon.png`

### Compilación falla
```bash
npm install
composer install
npm run build
```

---

## 📝 Notas Importantes

1. **Iconos**: Deben estar en `resources/images/`
2. **Base de Datos**: SQLite es portátil y recomendado
3. **Actualizaciones**: NativePHP maneja automáticamente
4. **Distribución**: Los instaladores se generan en `builds/`

---

## 🎓 Recursos

- [NativePHP Docs](https://nativephp.com/docs)
- [Laravel Docs](https://laravel.com/docs)
- [GitHub NativePHP](https://github.com/nativephp)

---

**¡Pharma-Sync está listo para usar con NativePHP!** 🚀
