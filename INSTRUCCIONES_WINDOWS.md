# 🪟 Instrucciones para Windows - Pharma-Sync

## Requisitos Previos

### 1. Instalar Node.js

1. Descarga desde: https://nodejs.org/ (versión LTS recomendada)
2. Ejecuta el instalador
3. Marca la opción "Add to PATH"
4. Completa la instalación

**Verificar instalación:**
```cmd
node --version
npm --version
```

### 2. Instalar PHP

1. Descarga desde: https://www.php.net/downloads
2. Elige la versión "Windows (x64) Non Thread Safe"
3. Extrae en una carpeta (ej: `C:\php`)
4. Agrega PHP al PATH:
   - Abre "Variables de entorno"
   - Edita la variable PATH
   - Agrega: `C:\php`

**Verificar instalación:**
```cmd
php --version
```

### 3. Instalar Composer

1. Descarga desde: https://getcomposer.org/download/
2. Ejecuta el instalador
3. Selecciona la ruta de PHP cuando se pida

**Verificar instalación:**
```cmd
composer --version
```

---

## Inicio Rápido

### Opción 1: Usar el Script (Más Fácil)

1. Abre la carpeta del proyecto en el Explorador
2. Haz doble clic en `start-app.bat`
3. Espera a que se abra la ventana de Electron
4. ¡Listo! La aplicación está corriendo

### Opción 2: Usar la Terminal

1. Abre PowerShell o CMD
2. Navega a la carpeta del proyecto:
   ```cmd
   cd C:\ruta\a\pharma-sync
   ```
3. Ejecuta:
   ```cmd
   npm run dev
   ```

---

## Solución de Problemas

### Error: "Node.js no está instalado"

**Solución:**
1. Descarga Node.js desde https://nodejs.org/
2. Ejecuta el instalador
3. Marca "Add to PATH"
4. Reinicia PowerShell/CMD
5. Verifica: `node --version`

### Error: "PHP no está instalado"

**Solución:**
1. Descarga PHP desde https://www.php.net/downloads
2. Extrae en `C:\php`
3. Agrega a PATH:
   - Presiona `Win + X` → "Sistema"
   - "Configuración avanzada del sistema"
   - "Variables de entorno"
   - Edita PATH y agrega `C:\php`
4. Reinicia PowerShell/CMD
5. Verifica: `php --version`

### Error: "Puerto 8000 en uso"

**Solución:**
1. Abre PowerShell como administrador
2. Ejecuta:
   ```powershell
   netstat -ano | findstr :8000
   ```
3. Anota el PID (número al final)
4. Mata el proceso:
   ```powershell
   taskkill /PID <numero> /F
   ```

### La ventana de Electron no se abre

**Solución:**
1. Abre PowerShell en la carpeta del proyecto
2. Ejecuta:
   ```cmd
   npm run dev
   ```
3. Revisa los errores en la consola
4. Abre `storage/logs/laravel.log` para más detalles

### Base de datos no se inicializa

**Solución:**
1. Abre PowerShell en la carpeta del proyecto
2. Ejecuta:
   ```cmd
   php artisan migrate --force
   php artisan db:seed --force
   ```

---

## Usuarios de Prueba

Una vez que la aplicación esté corriendo:

| Rol | Email | Contraseña |
|-----|-------|-----------|
| Admin | admin@pharmasync.com | admin123 |
| Usuario | usuario@pharmasync.com | usuario123 |

---

## Comandos Útiles

```cmd
# Instalar dependencias
npm install
composer install

# Iniciar desarrollo
npm run dev

# Compilar para producción
npm run build

# Ejecutar migraciones
php artisan migrate

# Cargar datos de prueba
php artisan db:seed

# Limpiar caché
php artisan cache:clear

# Ver logs
type storage\logs\laravel.log
```

---

## Estructura de Carpetas

```
pharma-sync/
├── app/                    # Código de Laravel
├── database/              # Base de datos y migraciones
├── resources/             # Vistas y estilos
├── electron/              # Configuración de Electron
├── public/                # Archivos públicos
├── storage/               # Logs y caché
├── package.json           # Dependencias de Node.js
├── composer.json          # Dependencias de PHP
├── .env                   # Configuración
└── start-app.bat          # Script de inicio
```

---

## Características Principales

✅ **Gestión de Inventario**
- Agregar, editar y eliminar productos
- Venta fraccionada (cajas + sueltos)
- Control de vencimientos

✅ **Sistema de Ventas**
- Búsqueda rápida por código
- Procesamiento de ventas
- Historial completo

✅ **Reportes**
- Métricas de inventario
- Análisis de ventas
- Gráficos de tendencias

✅ **Configuración**
- Tema claro/oscuro
- Exportación de datos
- Gestión de usuarios

---

## Soporte

Si tienes problemas:

1. **Revisa los logs:**
   ```cmd
   type storage\logs\laravel.log
   ```

2. **Abre la consola de desarrollador:**
   - Presiona F12 en la ventana de Electron
   - Revisa la pestaña "Console"

3. **Limpia el caché:**
   ```cmd
   php artisan cache:clear
   ```

4. **Reinicia la base de datos:**
   ```cmd
   del database\database.sqlite
   php artisan migrate --force
   php artisan db:seed --force
   ```

---

## Próximos Pasos

1. ✅ Instala los requisitos
2. ✅ Ejecuta `start-app.bat`
3. ✅ Inicia sesión con admin@pharmasync.com / admin123
4. ✅ ¡Comienza a usar Pharma-Sync!

---

**¡Pharma-Sync está listo para Windows!** 🎉
