# Ejemplos de Funcionalidades de Escritorio

## 1. NOTIFICACIONES DEL SISTEMA

### Ejemplo 1: Notificación Simple
```php
// En InventarioController.php
use NativePHP\Laravel\Facades\Notification;

public function store(Request $request)
{
    // ... código de validación y guardado ...
    
    $product = Product::create($validated);
    
    // Mostrar notificación
    Notification::create()
        ->title('✅ Producto Guardado')
        ->body("'{$product->name}' agregado exitosamente")
        ->show();
    
    return redirect()->route('inventario.index');
}
```

### Ejemplo 2: Notificación con Acciones
```php
Notification::create()
    ->title('⚠️ Stock Bajo')
    ->body('El producto X tiene stock bajo')
    ->action('Ver Producto', 'inventario.edit', ['id' => $product->id])
    ->show();
```

### Ejemplo 3: Notificación de Error
```php
Notification::create()
    ->title('❌ Error')
    ->body('No se pudo guardar el producto')
    ->urgency('critical')
    ->show();
```

---

## 2. MENÚ DE APLICACIÓN

### Menú Completo
```php
// En ApplicationController.php
private function configureMenu()
{
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
            Menu::link('Inicio', '/'),
            Menu::link('Inventario', '/inventario'),
            Menu::link('Ventas', '/ventas'),
            Menu::link('Reportes', '/reportes'),
            Menu::divider(),
            Menu::link('Pantalla Completa', 'fullscreen'),
            Menu::link('Zoom In', 'zoom-in'),
            Menu::link('Zoom Out', 'zoom-out'),
        ])
        ->submenu('Ayuda', [
            Menu::link('Documentación', 'help'),
            Menu::link('Acerca de', 'about'),
        ]);
}
```

---

## 3. BANDEJA DEL SISTEMA (TRAY)

### Configuración Básica
```php
// En ApplicationController.php
use NativePHP\Laravel\Facades\Tray;

private function configureTray()
{
    Tray::create()
        ->setIcon('resources/images/tray-icon.png')
        ->setMenu([
            Tray::link('Abrir Pharma-Sync', '/'),
            Tray::link('Inventario', '/inventario'),
            Tray::link('Ventas', '/ventas'),
            Tray::divider(),
            Tray::link('Salir', 'quit'),
        ])
        ->onDoubleClick(function () {
            Window::current()->show();
        });
}
```

---

## 4. ATAJOS DE TECLADO

### Implementar Atajos
```php
// En ApplicationController.php
use NativePHP\Laravel\Facades\Keyboard;

private function configureKeyboardShortcuts()
{
    // Crear nuevo producto
    Keyboard::register('ctrl+n', function () {
        Window::current()->navigate('inventario.create');
    });
    
    // Guardar
    Keyboard::register('ctrl+s', function () {
        // Ejecutar acción de guardado
    });
    
    // Salir
    Keyboard::register('ctrl+q', function () {
        app()->terminate();
    });
    
    // Abrir configuración
    Keyboard::register('ctrl+,', function () {
        Window::current()->navigate('configuracion.index');
    });
    
    // Ir a inventario
    Keyboard::register('ctrl+i', function () {
        Window::current()->navigate('inventario.index');
    });
    
    // Ir a ventas
    Keyboard::register('ctrl+v', function () {
        Window::current()->navigate('ventas.index');
    });
    
    // Ir a reportes
    Keyboard::register('ctrl+r', function () {
        Window::current()->navigate('reportes.index');
    });
}
```

---

## 5. DIÁLOGOS DE ARCHIVO

### Abrir Archivo
```php
// En ConfiguracionController.php
use NativePHP\Laravel\Facades\Dialog;

public function importData(Request $request)
{
    $file = Dialog::open()
        ->title('Seleccionar archivo para importar')
        ->filters([
            'csv' => 'Archivos CSV',
            'xlsx' => 'Archivos Excel',
            'json' => 'Archivos JSON',
        ])
        ->browse();
    
    if ($file) {
        // Procesar archivo
        $this->processImport($file);
    }
}
```

### Guardar Archivo
```php
public function exportData()
{
    $file = Dialog::save()
        ->title('Guardar reporte')
        ->defaultName('reporte_' . date('Y-m-d_H-i-s'))
        ->defaultExtension('csv')
        ->filters([
            'csv' => 'CSV',
            'xlsx' => 'Excel',
            'pdf' => 'PDF',
        ])
        ->save();
    
    if ($file) {
        // Generar y guardar archivo
        $this->generateReport($file);
    }
}
```

### Seleccionar Carpeta
```php
$folder = Dialog::folder()
    ->title('Seleccionar carpeta de backup')
    ->browse();

if ($folder) {
    // Hacer backup en la carpeta seleccionada
}
```

---

## 6. VENTANAS ADICIONALES

### Crear Nueva Ventana
```php
// En InventarioController.php
use NativePHP\Laravel\Facades\Window;

public function openProductDetails($id)
{
    Window::create()
        ->setTitle('Detalles del Producto')
        ->setWidth(800)
        ->setHeight(600)
        ->navigate("inventario.show", ['id' => $id])
        ->show();
}
```

### Ventana Modal
```php
Window::create()
    ->setTitle('Confirmar Eliminación')
    ->setWidth(400)
    ->setHeight(200)
    ->setModal(true)
    ->navigate('confirm-delete', ['id' => $id])
    ->show();
```

---

## 7. EVENTOS DE APLICACIÓN

### Detectar Cambios de Ventana
```php
// En ApplicationController.php
use NativePHP\Laravel\Facades\Window;

private function configureEvents()
{
    Window::current()
        ->onFocus(function () {
            // Cuando la ventana obtiene el foco
            // Actualizar datos, sincronizar, etc.
        })
        ->onBlur(function () {
            // Cuando la ventana pierde el foco
            // Guardar estado, pausar actualizaciones, etc.
        })
        ->onClose(function () {
            // Cuando se cierra la ventana
            // Guardar datos, hacer cleanup, etc.
        });
}
```

---

## 8. SISTEMA DE BACKUP AUTOMÁTICO

### Crear Comando de Backup
```bash
php artisan make:command BackupDatabase
```

### Implementar Backup
```php
// En app/Console/Commands/BackupDatabase.php
<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class BackupDatabase extends Command
{
    protected $signature = 'backup:database';
    protected $description = 'Hacer backup de la base de datos';

    public function handle()
    {
        $source = database_path('database.sqlite');
        $timestamp = date('Y-m-d_H-i-s');
        $backup = storage_path("backups/database_$timestamp.sqlite");
        
        // Crear carpeta si no existe
        if (!is_dir(storage_path('backups'))) {
            mkdir(storage_path('backups'), 0755, true);
        }
        
        // Copiar archivo
        copy($source, $backup);
        
        $this->info("Backup creado: $backup");
        
        // Limpiar backups antiguos (mantener últimos 10)
        $this->cleanOldBackups();
    }
    
    private function cleanOldBackups()
    {
        $backups = glob(storage_path('backups/database_*.sqlite'));
        usort($backups, function ($a, $b) {
            return filemtime($b) - filemtime($a);
        });
        
        foreach (array_slice($backups, 10) as $backup) {
            unlink($backup);
        }
    }
}
```

### Programar Backup Automático
```php
// En app/Console/Kernel.php
protected function schedule(Schedule $schedule)
{
    // Backup cada hora
    $schedule->command('backup:database')->hourly();
    
    // Backup diario a las 2 AM
    $schedule->command('backup:database')->dailyAt('02:00');
}
```

---

## 9. SINCRONIZACIÓN DE DATOS

### Crear Comando de Sincronización
```bash
php artisan make:command SyncData
```

### Implementar Sincronización
```php
// En app/Console/Commands/SyncData.php
<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;

class SyncData extends Command
{
    protected $signature = 'sync:data';
    protected $description = 'Sincronizar datos con servidor';

    public function handle()
    {
        try {
            // Sincronizar productos
            $this->syncProducts();
            
            // Sincronizar ventas
            $this->syncSales();
            
            $this->info('✅ Sincronización completada');
        } catch (\Exception $e) {
            $this->error('❌ Error en sincronización: ' . $e->getMessage());
        }
    }
    
    private function syncProducts()
    {
        // Lógica de sincronización
    }
    
    private function syncSales()
    {
        // Lógica de sincronización
    }
}
```

---

## 10. NOTIFICACIONES DE ESTADO

### Mostrar Estado de Sincronización
```php
// En VentasController.php
use NativePHP\Laravel\Facades\Notification;

public function store(Request $request)
{
    Notification::create()
        ->title('⏳ Procesando venta...')
        ->body('Por favor espere')
        ->show();
    
    try {
        // Procesar venta
        $sale = Sale::create($validated);
        
        Notification::create()
            ->title('✅ Venta Registrada')
            ->body("Venta #{$sale->id} guardada exitosamente")
            ->show();
    } catch (\Exception $e) {
        Notification::create()
            ->title('❌ Error')
            ->body('No se pudo registrar la venta')
            ->urgency('critical')
            ->show();
    }
}
```

---

## 11. INTEGRACIÓN CON SISTEMA OPERATIVO

### Abrir Archivo Externo
```php
use NativePHP\Laravel\Facades\Process;

// Abrir archivo con aplicación predeterminada
Process::run('start ' . $filePath);

// En Mac
Process::run('open ' . $filePath);

// En Linux
Process::run('xdg-open ' . $filePath);
```

### Copiar al Portapapeles
```php
use NativePHP\Laravel\Facades\Clipboard;

Clipboard::copy('Texto a copiar');

// Leer del portapapeles
$text = Clipboard::read();
```

---

## 12. CONFIGURACIÓN DE VENTANA

### Personalizar Ventana
```php
// En ApplicationController.php
private function configureWindow()
{
    Window::current()
        ->setTitle('Pharma-Sync v1.0.0')
        ->setWidth(1400)
        ->setHeight(900)
        ->setMinWidth(1000)
        ->setMinHeight(600)
        ->center()
        ->setAlwaysOnTop(false)
        ->setResizable(true)
        ->setMaximizable(true)
        ->setMinimizable(true)
        ->setClosable(true)
        ->setFullscreenable(true);
}
```

---

## 📋 Resumen de Funcionalidades

| Funcionalidad | Código | Complejidad |
|---|---|---|
| Notificaciones | `Notification::create()` | ⭐ |
| Menú | `Menu::create()` | ⭐⭐ |
| Bandeja | `Tray::create()` | ⭐⭐ |
| Atajos | `Keyboard::register()` | ⭐⭐ |
| Diálogos | `Dialog::open()` | ⭐⭐ |
| Ventanas | `Window::create()` | ⭐⭐⭐ |
| Eventos | `Window::onFocus()` | ⭐⭐⭐ |
| Backup | Comando personalizado | ⭐⭐⭐ |
| Sincronización | Comando personalizado | ⭐⭐⭐⭐ |

---

## 🎯 Próximos Pasos

1. Implementar notificaciones en todos los controladores
2. Agregar atajos de teclado personalizados
3. Crear sistema de backup automático
4. Implementar sincronización de datos
5. Optimizar rendimiento

