# 💊 Pharma-Sync

Sistema de Gestión de Farmacia Open Source desarrollado con Laravel, NativePHP y Tailwind CSS.

![Laravel](https://img.shields.io/badge/Laravel-11-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.2-777BB4?style=for-the-badge&logo=php&logoColor=white)
![NativePHP](https://img.shields.io/badge/NativePHP-1.3-4B5563?style=for-the-badge&logo=laravel&logoColor=white)
![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-3.x-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)
![License](https://img.shields.io/badge/License-MIT-green?style=for-the-badge)

## 📋 Descripción

Pharma-Sync es un sistema completo de gestión para farmacias que incluye:

- ✅ **Dashboard** con estadísticas en tiempo real
- ✅ **Gestión de Inventario** (CRUD completo)
- ✅ **Sistema de Autenticación** con roles de usuario
- ✅ **Módulo de Ventas** con historial
- ✅ **Sistema de Notificaciones**
- ✅ **Reportes y Análisis**
- ✅ **Configuración del Sistema**
- ✅ **Diseño Responsive** con sidebar retráctil
- ✅ **Cálculo automático de precios** con IVA y margen de ganancia

## 🚀 Características

### 🏠 Dashboard
- Tarjetas de estadísticas principales
- Valor total de inventario (costo y venta)
- Alertas de stock bajo y productos próximos a vencer
- Gráficos de resumen

### 📦 Inventario
- CRUD completo de productos
- Gestión de stock por cajas y unidades
- Cálculo automático de precio de venta
- IVA configurable por producto
- Control de fechas de vencimiento
- Validación de formularios

### 👥 Usuarios
- Sistema de autenticación completo
- Gestión de usuarios con roles
- Perfiles de usuario personalizables

### 💰 Ventas
- Registro de ventas
- Historial de transacciones
- Vista detallada de ventas

### 🔔 Notificaciones
- Sistema de notificaciones en tiempo real
- Alertas de stock bajo
- Notificaciones de vencimientos

### 📊 Reportes
- Valor total del inventario
- Ganancia estimada
- Análisis de productos
- Alertas de stock y vencimientos

### ⚙️ Configuración
- Configuración global del sistema
- Personalización de alertas
- Información de la empresa

## 🛠️ Tecnologías

- **Backend**: Laravel 11
- **Frontend**: Tailwind CSS 3.x + Vanilla JavaScript
- **Base de Datos**: SQLite (configurable a MySQL/PostgreSQL)
- **Íconos**: Font Awesome 6.4.0
- **Build Tool**: Vite
- **Autenticación**: Laravel Breeze

## 📦 Instalación

### ⚡ Para Usuarios Finales

**Windows:**
1. Descargar `Pharma-Sync-Setup.exe`
2. Ejecutar instalador
3. ¡Listo!

**macOS:**
1. Descargar `Pharma-Sync.dmg`
2. Ejecutar instalador
3. ¡Listo!

**Linux:**
1. Descargar `pharma-sync.AppImage`
2. Ejecutar archivo
3. ¡Listo!

### 🛠️ Para Desarrolladores

**Requisitos:**
- PHP 8.2+
- Composer
- Node.js 18+

**Instalación:**

```bash
# Clonar repositorio
git clone https://github.com/tu-usuario/pharma-sync.git
cd pharma-sync

# Instalar dependencias
composer install
npm install

# Configurar
cp .env.example .env
php artisan key:generate

# Ejecutar migraciones
php artisan migrate --seed

# Iniciar en desarrollo
npm run dev
```

### 📦 Compilar para Distribución

```bash
npm run build
```

Esto genera instaladores en la carpeta `builds/`:
- `Pharma-Sync-Setup.exe` (Windows)
- `Pharma-Sync.dmg` (macOS)
- `pharma-sync.AppImage` (Linux)

## 📖 Uso

### Gestión de Inventario
1. Navega a **Inventario** desde el sidebar
2. Haz clic en **Agregar Producto** para crear nuevos productos
3. Completa los campos requeridos (nombre, laboratorio, precios, stock)
4. El sistema calculará automáticamente el precio de venta basado en el margen configurado

### Sistema de Ventas
1. Ve a **Ventas** para registrar nuevas transacciones
2. Consulta el **Historial de Ventas** para ver transacciones pasadas

### Configuración del Sistema
1. Accede a **Configuración** para personalizar:
   - IVA global
   - Información de la empresa
   - Alertas de stock y vencimientos

## 🔧 Configuración Avanzada

### Base de Datos

Por defecto usa SQLite. Para cambiar a MySQL/PostgreSQL, edita `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=pharma_sync
DB_USERNAME=root
DB_PASSWORD=tu_password
```

### Variables de Entorno Importantes

```env
APP_NAME="Pharma-Sync"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://tu-dominio.com

DB_CONNECTION=sqlite
CACHE_STORE=database
SESSION_DRIVER=database
```

### Comandos Útiles

```bash
# Desarrollo
npm run dev

# Compilación
npm run build

# Desarrollo web (sin NativePHP)
npm run dev:web

# Compilación web (sin NativePHP)
npm run build:web

# Migraciones
php artisan migrate
php artisan migrate:refresh --seed

# Caché
php artisan cache:clear
php artisan config:clear
```

## 🤝 Contribuir

Las contribuciones son bienvenidas. Por favor:

1. Fork el proyecto
2. Crea una rama para tu feature (`git checkout -b feature/AmazingFeature`)
3. Commit tus cambios (`git commit -m 'Add some AmazingFeature'`)
4. Push a la rama (`git push origin feature/AmazingFeature`)
5. Abre un Pull Request

## 📝 Roadmap

- [x] Sistema de autenticación completo
- [x] Gestión de usuarios y roles
- [x] Sistema de notificaciones
- [x] Dashboard con estadísticas
- [x] CRUD de inventario
- [x] Módulo de ventas básico
- [ ] Carrito de compras avanzado
- [ ] Impresión de tickets/facturas
- [ ] Gráficos interactivos con Chart.js
- [ ] Exportar reportes a PDF/Excel
- [ ] API REST completa
- [ ] Modo oscuro
- [ ] Multi-idioma (i18n)
- [ ] Backup automático
- [ ] Integración con códigos de barras

## 🐛 Reportar Bugs

Si encuentras un bug, por favor abre un [issue](https://github.com/tu-usuario/pharma-sync/issues) con:

- Descripción detallada del problema
- Pasos para reproducirlo
- Comportamiento esperado vs actual
- Capturas de pantalla (si aplica)
- Información del entorno (PHP, Laravel, navegador)

## 📄 Licencia

Este proyecto está bajo la Licencia MIT. Ver el archivo [LICENSE](LICENSE) para más detalles.

## 🙏 Agradecimientos

- [Laravel Framework](https://laravel.com)
- [Tailwind CSS](https://tailwindcss.com)
- [Font Awesome](https://fontawesome.com)
- [Vite](https://vitejs.dev)
- Comunidad Open Source

---

**Pharma-Sync** - Sistema de Gestión de Farmacia Open Source  
Hecho con ❤️ usando Laravel y Tailwind CSS
