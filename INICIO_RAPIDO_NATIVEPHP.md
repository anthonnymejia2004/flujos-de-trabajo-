# Inicio Rápido: Transformación a Aplicación de Escritorio

## 🚀 Paso 1: Instalar NativePHP (5 minutos)

```bash
# Instalar NativePHP
composer require nativephp/nativephp

# Inicializar
php artisan native:install
```

---

## 📝 Paso 2: Configurar `config/nativephp.php`

Reemplaza el contenido con:

```php
<?php

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
        'sign' => false, // Cambiar a true si tienes certificado
    ],
    
    'menu' => [
        // Menú de aplicación
    ],
];
```

---

## 🎨 Paso 3: Preparar Iconos

### Crear carpeta de imágenes:
```bash
mkdir -p resources/images
```

### Descargar o crear iconos:
- `icon.png` (256x256 píxeles)
- `icon-512.png` (512x512 píxeles)
- `tray-icon.png` (64x64 píxeles)

**Nota:** Puedes usar cualquier herramienta online para redimensionar imágenes.

---

## 🔧 Paso 4: Crear ApplicationController

Crear archivo: `app/NativePHP/ApplicationController.php`

```php
<?php

namespace App\NativePHP;

use NativePHP\Laravel\Facades\Window;
use NativePHP\Laravel\Facades\Menu;
use NativePHP\Laravel\Facades\Tray;

class ApplicationController
{
    public function boot()
    {
        $this->configureWindow();
        $this->configureMenu();
        $this->configureTray();
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
            ->submenu('Archivo', [
                Menu::link('Nuevo Producto', 'inventario.create'),
                Menu::link('Nueva Venta', 'ventas.index'),
                Menu::divider(),
                Menu::link('Exportar', 'configuracion.export'),
                Menu::link('Importar', 'configuracion.import'),
                Menu::divider(),
                Menu::link('Salir', 'quit'),
            ])
            ->submenu('Ver', [
                Menu::link('Inicio', '/'),
                Menu::link('Inventario', '/inventario'),
                Menu::link('Ventas', '/ventas'),
                Menu::link('Reportes', '/reportes'),
            ])
            ->submenu('Configuración', [
                Menu::link('Preferencias', 'configuracion.index'),
            ])
            ->submenu('Ayuda', [
                Menu::link('Acerca de', 'about'),
            ]);
    }
    
    private function configureTray()
    {
        Tray::create()
            ->setIcon('resources/images/tray-icon.png')
            ->setMenu([
                Tray::link('Abrir', '/'),
                Tray::link('Inventario', '/inventario'),
                Tray::divider(),
                Tray::link('Salir', 'quit'),
            ]);
    }
}
```

---

## 📦 Paso 5: Crear NativeAppServiceProvider

Crear archivo: `app/Providers/NativeAppServiceProvider.php`

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

---

## 🔌 Paso 6: Registrar Provider

Editar: `bootstrap/providers.php`

Agregar al final del array:

```php
App\Providers\NativeAppServiceProvider::class,
```

---

## ▶️ Paso 7: Ejecutar en Modo Desarrollo

```bash
php artisan native:serve
```

Esto abrirá la aplicación en una ventana nativa de Windows.

---

## 🏗️ Paso 8: Compilar para Windows

```bash
# Compilar aplicación
php artisan native:build windows

# Crear instalador
php artisan native:build windows --installer

# Crear versión portable
php artisan native:build windows --portable
```

Los archivos compilados estarán en: `dist/windows/`

---

## 📋 Checklist Rápido

- [ ] Instalar NativePHP
- [ ] Configurar `config/nativephp.php`
- [ ] Preparar iconos en `resources/images/`
- [ ] Crear `ApplicationController.php`
- [ ] Crear `NativeAppServiceProvider.php`
- [ ] Registrar provider en `bootstrap/providers.php`
- [ ] Ejecutar `php artisan native:serve`
- [ ] Compilar con `php artisan native:build windows`

---

## 🐛 Solución de Problemas

### Error: "NativePHP not found"
```bash
composer require nativephp/nativephp
php artisan native:install
```

### Error: "Icon not found"
- Verificar que los iconos existan en `resources/images/`
- Usar formato PNG
- Tamaño mínimo: 256x256

### Error: "Port already in use"
```bash
php artisan native:serve --port=8001
```

### La aplicación no inicia
- Verificar logs: `storage/logs/laravel.log`
- Ejecutar: `php artisan config:cache`
- Limpiar caché: `php artisan cache:clear`

---

## 📊 Resultado Final

Después de completar estos pasos, tendrás:

✅ Aplicación de escritorio nativa para Windows
✅ Menú de aplicación personalizado
✅ Icono en bandeja del sistema
✅ Ejecutable .exe listo para distribuir
✅ Instalador automático

---

## 🎯 Próximos Pasos

1. **Agregar notificaciones** en controladores
2. **Implementar atajos de teclado**
3. **Configurar actualizaciones automáticas**
4. **Crear sistema de backup**
5. **Optimizar rendimiento**

---

## 📞 Ayuda

Si encuentras problemas:
1. Revisar logs: `storage/logs/laravel.log`
2. Ejecutar: `php artisan config:clear`
3. Ejecutar: `php artisan cache:clear`
4. Reiniciar la aplicación

