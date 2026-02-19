# 🚀 Inicio Rápido - Pharma-Sync

## Requisitos Previos

Antes de iniciar, asegúrate de tener instalado:

1. **Node.js** (v18 o superior)
   - Descarga desde: https://nodejs.org/
   - Verifica: `node --version`

2. **PHP** (v8.2 o superior)
   - Descarga desde: https://www.php.net/downloads
   - Verifica: `php --version`

3. **Composer** (gestor de dependencias de PHP)
   - Descarga desde: https://getcomposer.org/
   - Verifica: `composer --version`

---

## Instalación Rápida

### Opción 1: Usar el Script de Inicio (Recomendado)

**En Windows:**
```bash
start-app.bat
```

**En macOS/Linux:**
```bash
chmod +x start-app.sh
./start-app.sh
```

### Opción 2: Instalación Manual

1. **Instalar dependencias de Node.js:**
   ```bash
   npm install
   ```

2. **Instalar dependencias de PHP:**
   ```bash
   composer install
   ```

3. **Configurar la aplicación:**
   ```bash
   php artisan key:generate
   php artisan migrate --force
   php artisan db:seed --force
   ```

4. **Iniciar la aplicación:**
   ```bash
   npm run dev
   ```

---

## Usuarios de Prueba

Una vez que la aplicación esté ejecutándose, puedes iniciar sesión con:

| Rol | Email | Contraseña |
|-----|-------|-----------|
| Admin | admin@pharmasync.com | admin123 |
| Usuario | usuario@pharmasync.com | usuario123 |

---

## Solución de Problemas

### Error: "Node.js no encontrado"
- Instala Node.js desde https://nodejs.org/
- Reinicia tu terminal después de instalar

### Error: "PHP no encontrado"
- Instala PHP desde https://www.php.net/downloads
- Asegúrate de que PHP esté en el PATH del sistema

### Error: "Puerto 8000 en uso"
- Cambia el puerto en `electron/main.js` (línea: `const PHP_PORT = 8000;`)
- O cierra la aplicación que está usando el puerto

### La interfaz gráfica no se muestra
- Verifica que el servidor Laravel esté iniciando correctamente
- Abre la consola de desarrollador (F12) para ver errores
- Revisa los logs en `storage/logs/laravel.log`

### Base de datos no se inicializa
- Elimina el archivo `database/database.sqlite`
- Ejecuta: `php artisan migrate --force && php artisan db:seed --force`

---

## Comandos Útiles

```bash
# Desarrollo
npm run dev

# Compilar para producción
npm run build

# Ejecutar migraciones
php artisan migrate

# Cargar datos de prueba
php artisan db:seed

# Ver logs
tail -f storage/logs/laravel.log

# Limpiar caché
php artisan cache:clear
php artisan config:clear
```

---

## Estructura del Proyecto

```
pharma-sync/
├── app/                    # Código de Laravel
├── database/              # Migraciones y seeders
├── resources/             # Vistas y assets
├── electron/              # Configuración de Electron
├── public/                # Archivos públicos
├── storage/               # Logs y caché
├── package.json           # Dependencias de Node.js
├── composer.json          # Dependencias de PHP
└── .env                   # Variables de entorno
```

---

## Características Principales

✅ **Gestión de Inventario**
- CRUD completo de productos
- Venta fraccionada (cajas + sueltos)
- Control de vencimientos

✅ **Sistema de Ventas**
- Búsqueda por código de barras
- Procesamiento de ventas
- Historial de transacciones

✅ **Reportes y Análisis**
- Métricas de inventario
- Análisis de ventas
- Gráficos de tendencias

✅ **Configuración del Sistema**
- Tema claro/oscuro
- Exportación/Importación de datos
- Gestión de usuarios

---

## Soporte

Si encuentras problemas:

1. Revisa los logs: `storage/logs/laravel.log`
2. Abre la consola de desarrollador (F12)
3. Verifica que todos los requisitos estén instalados
4. Intenta limpiar el caché: `php artisan cache:clear`

---

## Licencia

Este proyecto es de código abierto. Consulta el archivo LICENSE para más detalles.

---

**¡Pharma-Sync está listo para usar!** 🎉
