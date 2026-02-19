# 🚀 Pharma-Sync con NativePHP

## ⚡ Inicio Rápido

### Para Usuarios Finales

**Solo descargar y ejecutar:**

- **Windows**: `Pharma-Sync-Setup.exe`
- **macOS**: `Pharma-Sync.dmg`
- **Linux**: `pharma-sync.AppImage`

### Para Desarrolladores

```bash
# 1. Instalar dependencias
composer install
npm install

# 2. Configurar
cp .env.example .env
php artisan key:generate

# 3. Migraciones
php artisan migrate --seed

# 4. Iniciar
npm run dev
```

---

## 🔑 Credenciales por Defecto

- **Email**: admin@pharmasync.com
- **Contraseña**: admin123

---

## 📦 Compilar para Distribución

```bash
npm run build
```

Genera instaladores en `builds/`:
- `Pharma-Sync-Setup.exe` (Windows)
- `Pharma-Sync.dmg` (macOS)
- `pharma-sync.AppImage` (Linux)

---

## 📝 Comandos Disponibles

```bash
# Desarrollo
npm run dev

# Compilación
npm run build

# Desarrollo web (sin NativePHP)
npm run dev:web

# Compilación web (sin NativePHP)
npm run build:web
```

---

## 🎯 Características

- ✅ Dashboard con estadísticas
- ✅ Gestión de inventario (CRUD)
- ✅ Sistema de ventas
- ✅ Notificaciones
- ✅ Reportes
- ✅ Configuración del sistema
- ✅ Modo claro/oscuro
- ✅ Autenticación con roles
- ✅ Base de datos SQLite

---

## 📞 Soporte

Para problemas:

1. Revisa los logs: `storage/logs/laravel.log`
2. Limpia el caché: `php artisan cache:clear`
3. Consulta la documentación oficial de NativePHP: https://nativephp.com

---

**¡Listo para usar!** 🎉
