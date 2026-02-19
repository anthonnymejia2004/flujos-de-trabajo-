# Plan de Transformación a Aplicación de Escritorio Windows
## Pharma-Sync Desktop Edition

---

## 📋 RESUMEN EJECUTIVO

Transformar la aplicación web Laravel actual en una aplicación de escritorio nativa para Windows usando **NativePHP**. La aplicación mantendrá toda su funcionalidad actual pero con acceso directo desde el escritorio, sin necesidad de navegador web.

**Tiempo estimado:** 2-3 semanas
**Complejidad:** Media
**Requisitos:** NativePHP, PHP 8.1+, Node.js

---

## 🎯 OBJETIVOS

1. ✅ Crear ejecutable .exe para Windows
2. ✅ Mantener toda la funcionalidad actual
3. ✅ Mejorar experiencia de usuario (UX)
4. ✅ Agregar funcionalidades de escritorio
5. ✅ Distribuir fácilmente a usuarios
6. ✅ Actualización automática de versiones

---

## 📊 FASES DEL PROYECTO

### FASE 1: PREPARACIÓN (Semana 1)

#### 1.1 Verificar Requisitos
- [ ] PHP 8.1 o superior instalado
- [ ] Node.js 16+ instalado
- [ ] Composer actualizado
- [ ] Git configurado
- [ ] Visual Studio Build Tools (opcional, para compilación)

#### 1.2 Instalar NativePHP
```bash
composer require nativephp/nativephp
php artisan native:install
```

#### 1.3 Configurar NativePHP
- [ ] Revisar `config/nativephp.php`
- [ ] Configurar nombre de aplicación: "Pharma-Sync"
- [ ] Configurar versión inicial: "1.0.0"
- [ ] Configurar iconos (256x256 PNG)
- [ ] Configurar tamaño de ventana inicial

#### 1.4 Preparar Recursos
- [ ] Crear icono de aplicación (256x256, 512x512)
- [ ] Crear icono de bandeja (tray icon)
- [ ] Crear splash screen
- [ ] Preparar documentación de usuario

---

### FASE 2: CONFIGURACIÓN DE NATIVEPHP (Semana 1)

#### 2.1 Actualizar `config/nativephp.php`

```php
return [
    'name' => 'Pharma-Sync',
    'id' => 'pharma-sync',
    'version' => '1.0.0',
    'author' => 'Tu Empresa',
    'description' => 'Sistema de Gestión de Inventario Farmacéutico',
    
    'windows' => [
        'executable' => 'Pharma-Sync.exe',
        'icon' => 'resources/images/icon.png',
        'publisher' => 'Tu Empresa',
    ],
    
    'menu' => [
        // Menú de aplicación
    ],
];
```

#### 2.2 Configurar Ventana Principal
- [ ] Tamaño: 1400x900 (mínimo)
- [ ] Resizable: true
- [ ] Maximizable: true
- [ ] Minimizable: true
- [ ] Closable: true

#### 2.3 Configurar Base de Datos Local
- [ ] SQLite como base de datos por defecto
- [ ] Ubicación: `storage/database.sqlite`
- [ ] Backup automático en `storage/backups/`

---

### FASE 3: ADAPTACIÓN DE CÓDIGO (Semana 1-2)

#### 3.1 Crear Controlador de Aplicación
Archivo: `app/NativePHP/ApplicationController.php`

```php
<?php

namespace App\NativePHP;

use NativePHP\Laravel\Facades\Window;
use NativePHP\Laravel\Facades\Menu;

class ApplicationController
{
    public function boot()
    {
        // Inicializar aplicación
        $this->configureWindow();
        $this->configureMenu();
        $this->configureEvents();
    }
    
    private function configureWindow()
    {
        Window::current()
            ->setTitle('Pharma-Sync')
            ->setWidth(1400)
            ->setHeight(900)
            ->center();
    }
    
    private function configureMenu()
    {
        Menu::create()
            ->link('Inicio', '/')
            ->link('Inventario', '/inventario')
            ->link('Ventas', '/ventas')
            ->link('Reportes', '/reportes')
            ->divider()
            ->link('Configuración', '/configuracion')
            ->divider()
            ->link('Salir', 'quit');
    }
    
    private function configureEvents()
    {
        // Eventos de aplicación
    }
}
```

#### 3.2 Crear Proveedor de Aplicación
Archivo: `app/Providers/NativeAppServiceProvider.php`

```php
<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\NativePHP\ApplicationController;

class NativeAppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInNative()) {
            $controller = new ApplicationController();
            $controller->boot();
        }
    }
}
```

#### 3.3 Actualizar `bootstrap/providers.php`
```php
return [
    // ... otros providers
    App\Providers\NativeAppServiceProvider::class,
];
```

#### 3.4 Optimizar para Escritorio
- [ ] Ajustar tamaños de fuente para pantalla
- [ ] Optimizar espaciado de elementos
- [ ] Mejorar contraste de colores
- [ ] Agregar atajos de teclado (Ctrl+N, Ctrl+S, etc.)

---

### FASE 4: FUNCIONALIDADES DE ESCRITORIO (Semana 2)

#### 4.1 Menú de Aplicación
```php
Menu::create()
    ->submenu('Archivo', [
        Menu::link('Nuevo Producto', 'inventario.create'),
        Menu::link('Nueva Venta', 'ventas.index'),
        Menu::divider(),
        Menu::link('Exportar Datos', 'configuracion.export'),
        Menu::link('Importar Datos', 'configuracion.import'),
        Menu::divider(),
        Menu::link('Salir', 'quit'),
    ])
    ->submenu('Editar', [
        Menu::link('Deshacer', 'undo'),
        Menu::link('Rehacer', 'redo'),
        Menu::divider(),
        Menu::link('Preferencias', 'configuracion.index'),
    ])
    ->submenu('Ver', [
        Menu::link('Pantalla Completa', 'fullscreen'),
        Menu::link('Zoom In', 'zoom-in'),
        Menu::link('Zoom Out', 'zoom-out'),
    ])
    ->submenu('Ayuda', [
        Menu::link('Documentación', 'help'),
        Menu::link('Acerca de', 'about'),
    ]);
```

#### 4.2 Notificaciones del Sistema
```php
// En InventarioController.php
use NativePHP\Laravel\Facades\Notification;

Notification::create()
    ->title('Producto Guardado')
    ->body("Producto '{$product->name}' agregado exitosamente")
    ->show();
```

#### 4.3 Bandeja del Sistema (Tray)
```php
use NativePHP\Laravel\Facades\Tray;

Tray::create()
    ->setIcon('resources/images/tray-icon.png')
    ->setMenu([
        Tray::link('Abrir', '/'),
        Tray::link('Inventario', '/inventario'),
        Tray::divider(),
        Tray::link('Salir', 'quit'),
    ]);
```

#### 4.4 Atajos de Teclado
```php
use NativePHP\Laravel\Facades\Keyboard;

Keyboard::register('ctrl+n', 'inventario.create');
Keyboard::register('ctrl+s', 'save');
Keyboard::register('ctrl+q', 'quit');
Keyboard::register('ctrl+,', 'configuracion.index');
```

#### 4.5 Acceso a Archivos del Sistema
```php
use NativePHP\Laravel\Facades\Dialog;

// Abrir diálogo de archivo
$file = Dialog::open()
    ->title('Seleccionar archivo')
    ->filters(['csv' => 'Archivos CSV', 'xlsx' => 'Excel'])
    ->browse();

// Guardar archivo
Dialog::save()
    ->title('Guardar reporte')
    ->defaultName('reporte_' . date('Y-m-d'))
    ->save();
```

#### 4.6 Sincronización de Datos
```php
// Crear comando para sincronización
php artisan make:command SyncData

// En app/Console/Commands/SyncData.php
public function handle()
{
    // Sincronizar con servidor (si existe)
    // Hacer backup automático
    // Limpiar caché
}
```

---

### FASE 5: COMPILACIÓN Y DISTRIBUCIÓN (Semana 2-3)

#### 5.1 Compilar para Windows
```bash
# Compilar aplicación
php artisan native:build windows

# Esto generará:
# - Pharma-Sync.exe (instalador)
# - Pharma-Sync-portable.exe (versión portable)
```

#### 5.2 Crear Instalador
- [ ] Configurar instalador NSIS
- [ ] Agregar licencia
- [ ] Configurar carpeta de instalación
- [ ] Crear acceso directo en escritorio
- [ ] Crear acceso directo en menú inicio

#### 5.3 Configurar Actualizaciones Automáticas
```php
// En config/nativephp.php
'updates' => [
    'enabled' => true,
    'url' => 'https://tu-servidor.com/updates',
    'channel' => 'stable',
],
```

#### 5.4 Crear Sistema de Actualizaciones
- [ ] Servidor de actualizaciones
- [ ] Versionamiento semántico
- [ ] Notas de cambios
- [ ] Descarga automática
- [ ] Instalación en segundo plano

---

### FASE 6: OPTIMIZACIÓN Y PRUEBAS (Semana 3)

#### 6.1 Optimización de Rendimiento
- [ ] Minimizar tamaño de ejecutable
- [ ] Optimizar tiempo de inicio
- [ ] Caché de recursos
- [ ] Lazy loading de componentes

#### 6.2 Pruebas Funcionales
- [ ] Crear producto
- [ ] Editar producto
- [ ] Eliminar producto
- [ ] Registrar venta
- [ ] Generar reportes
- [ ] Exportar datos
- [ ] Importar datos

#### 6.3 Pruebas de Compatibilidad
- [ ] Windows 10
- [ ] Windows 11
- [ ] Diferentes resoluciones de pantalla
- [ ] Diferentes idiomas del sistema

#### 6.4 Pruebas de Seguridad
- [ ] Validación de entrada
- [ ] Protección de datos
- [ ] Encriptación de base de datos
- [ ] Permisos de archivo

---

## 🛠️ CAMBIOS TÉCNICOS REQUERIDOS

### 1. Estructura de Carpetas
```
pharma-sync/
├── app/
│   ├── NativePHP/
│   │   ├── ApplicationController.php
│   │   └── Application.php
│   └── Providers/
│       └── NativeAppServiceProvider.php
├── config/
│   └── nativephp.php
├── resources/
│   ├── images/
│   │   ├── icon.png (256x256)
│   │   ├── icon-512.png (512x512)
│   │   └── tray-icon.png
│   └── views/
├── storage/
│   ├── database.sqlite
│   └── backups/
└── dist/
    └── windows/
        ├── Pharma-Sync.exe
        └── Pharma-Sync-portable.exe
```

### 2. Dependencias a Agregar
```json
{
    "require": {
        "nativephp/nativephp": "^0.1",
        "laravel/framework": "^11.0"
    },
    "require-dev": {
        "laravel/pint": "^1.0",
        "phpunit/phpunit": "^10.0"
    }
}
```

### 3. Configuración de Entorno
```env
APP_ENV=production
APP_DEBUG=false
DB_CONNECTION=sqlite
DB_DATABASE=storage/database.sqlite
NATIVE_APP=true
```

---

## 📋 CHECKLIST DE IMPLEMENTACIÓN

### Preparación
- [ ] Verificar requisitos del sistema
- [ ] Instalar NativePHP
- [ ] Crear estructura de carpetas
- [ ] Preparar recursos (iconos, imágenes)

### Configuración
- [ ] Configurar `config/nativephp.php`
- [ ] Crear ApplicationController
- [ ] Crear NativeAppServiceProvider
- [ ] Actualizar `bootstrap/providers.php`

### Desarrollo
- [ ] Implementar menú de aplicación
- [ ] Agregar notificaciones
- [ ] Configurar bandeja del sistema
- [ ] Implementar atajos de teclado
- [ ] Agregar diálogos de archivo

### Compilación
- [ ] Compilar para Windows
- [ ] Crear instalador
- [ ] Configurar actualizaciones
- [ ] Crear servidor de actualizaciones

### Pruebas
- [ ] Pruebas funcionales
- [ ] Pruebas de compatibilidad
- [ ] Pruebas de seguridad
- [ ] Pruebas de rendimiento

### Distribución
- [ ] Crear página de descargas
- [ ] Documentación de instalación
- [ ] Manual de usuario
- [ ] Soporte técnico

---

## 📦 ARCHIVOS A CREAR/MODIFICAR

### Crear:
1. `app/NativePHP/ApplicationController.php`
2. `app/Providers/NativeAppServiceProvider.php`
3. `resources/images/icon.png`
4. `resources/images/icon-512.png`
5. `resources/images/tray-icon.png`
6. `config/nativephp.php` (actualizar)

### Modificar:
1. `bootstrap/providers.php`
2. `app/Http/Controllers/InventarioController.php` (agregar notificaciones)
3. `app/Http/Controllers/VentasController.php` (agregar notificaciones)
4. `.env` (configurar para escritorio)

---

## 🚀 COMANDOS PRINCIPALES

```bash
# Instalar NativePHP
composer require nativephp/nativephp

# Inicializar NativePHP
php artisan native:install

# Ejecutar en modo desarrollo
php artisan native:serve

# Compilar para Windows
php artisan native:build windows

# Crear instalador
php artisan native:build windows --installer

# Crear versión portable
php artisan native:build windows --portable
```

---

## 💾 CONSIDERACIONES DE BASE DE DATOS

### SQLite (Recomendado para Escritorio)
- ✅ No requiere servidor
- ✅ Archivo único
- ✅ Fácil de hacer backup
- ✅ Portátil

### Configuración:
```env
DB_CONNECTION=sqlite
DB_DATABASE=storage/database.sqlite
```

### Backup Automático:
```php
// En app/Console/Commands/BackupDatabase.php
public function handle()
{
    $source = database_path('database.sqlite');
    $backup = storage_path('backups/database_' . date('Y-m-d_H-i-s') . '.sqlite');
    copy($source, $backup);
}
```

---

## 🔒 SEGURIDAD

### Consideraciones:
1. **Encriptación de Base de Datos**
   - Usar SQLCipher para encriptar SQLite
   
2. **Protección de Datos**
   - Encriptar datos sensibles
   - Usar HTTPS para cualquier comunicación
   
3. **Actualizaciones Seguras**
   - Verificar firma digital
   - Usar HTTPS para descargas
   
4. **Permisos de Archivo**
   - Restringir acceso a carpeta de datos
   - Proteger archivos de configuración

---

## 📊 ESTIMACIÓN DE TAMAÑO

- **Ejecutable base:** ~50-100 MB
- **Con dependencias:** ~150-200 MB
- **Instalador:** ~80-120 MB
- **Base de datos (vacía):** ~1 MB
- **Tamaño total instalado:** ~200-300 MB

---

## 🎯 PRÓXIMOS PASOS

1. **Semana 1:** Preparación e instalación de NativePHP
2. **Semana 1-2:** Configuración y adaptación de código
3. **Semana 2:** Implementación de funcionalidades de escritorio
4. **Semana 2-3:** Compilación, pruebas y distribución
5. **Semana 3:** Optimización final y lanzamiento

---

## 📞 SOPORTE Y RECURSOS

### Documentación Oficial:
- NativePHP: https://nativephp.com
- Laravel: https://laravel.com
- PHP: https://php.net

### Comunidades:
- Discord de NativePHP
- Foro de Laravel
- Stack Overflow

---

## ✅ CONCLUSIÓN

Este plan proporciona una hoja de ruta completa para transformar Pharma-Sync en una aplicación de escritorio profesional para Windows. Siguiendo este plan, la aplicación estará lista para distribución en 2-3 semanas.

**Beneficios:**
- ✅ Experiencia de usuario mejorada
- ✅ Acceso directo desde escritorio
- ✅ Funcionalidades de escritorio nativas
- ✅ Distribución fácil
- ✅ Actualizaciones automáticas
- ✅ Mantenimiento centralizado

