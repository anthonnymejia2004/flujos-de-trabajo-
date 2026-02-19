# PASO 2: Agregar Notificaciones a Controladores

## 📋 Objetivo

Integrar notificaciones del sistema en los controladores principales para mejorar la experiencia del usuario en modo escritorio.

---

## 🎯 Controladores a Modificar

### 1. InventarioController.php
Agregar notificaciones en:
- `store()` - Cuando se crea un producto
- `update()` - Cuando se actualiza un producto
- `destroy()` - Cuando se elimina un producto

### 2. VentasController.php
Agregar notificaciones en:
- `store()` - Cuando se registra una venta
- `show()` - Cuando se visualiza una venta

### 3. ConfiguracionController.php
Agregar notificaciones en:
- `update()` - Cuando se actualiza configuración
- `exportData()` - Cuando se exportan datos
- `importData()` - Cuando se importan datos

---

## 📝 Código a Agregar

### Paso 1: Crear Trait para Notificaciones

Crear archivo: `app/Traits/SendsNotifications.php`

```php
<?php

namespace App\Traits;

trait SendsNotifications
{
    /**
     * Enviar notificación de éxito
     */
    protected function notifySuccess($title, $message)
    {
        return response()->json([
            'notification' => [
                'type' => 'success',
                'title' => $title,
                'message' => $message,
            ]
        ]);
    }

    /**
     * Enviar notificación de error
     */
    protected function notifyError($title, $message)
    {
        return response()->json([
            'notification' => [
                'type' => 'error',
                'title' => $title,
                'message' => $message,
            ]
        ]);
    }

    /**
     * Enviar notificación de información
     */
    protected function notifyInfo($title, $message)
    {
        return response()->json([
            'notification' => [
                'type' => 'info',
                'title' => $title,
                'message' => $message,
            ]
        ]);
    }

    /**
     * Enviar notificación de advertencia
     */
    protected function notifyWarning($title, $message)
    {
        return response()->json([
            'notification' => [
                'type' => 'warning',
                'title' => $title,
                'message' => $message,
            ]
        ]);
    }
}
```

### Paso 2: Actualizar InventarioController

En `app/Http/Controllers/InventarioController.php`, agregar al método `store()`:

```php
public function store(Request $request)
{
    try {
        // ... código de validación ...
        
        $product = Product::create([
            // ... campos ...
        ]);

        // Registrar evento en escritorio
        \Log::info('Producto creado', [
            'product_id' => $product->id,
            'product_name' => $product->name,
        ]);

        return redirect()
            ->route('inventario.index')
            ->with('success', "✅ Producto '{$product->name}' agregado exitosamente");
    } catch (\Exception $e) {
        \Log::error('Error al guardar producto: ' . $e->getMessage());
        return redirect()->back()
            ->withInput()
            ->with('error', '❌ Error al guardar el producto: ' . $e->getMessage());
    }
}
```

### Paso 3: Actualizar VentasController

En `app/Http/Controllers/VentasController.php`, agregar al método `store()`:

```php
public function store(Request $request)
{
    try {
        // ... código de validación ...
        
        $sale = Sale::create([
            // ... campos ...
        ]);

        // Registrar evento
        \Log::info('Venta registrada', [
            'sale_id' => $sale->id,
            'total' => $sale->total,
        ]);

        return redirect()
            ->route('ventas.history')
            ->with('success', "✅ Venta #{$sale->id} registrada exitosamente");
    } catch (\Exception $e) {
        \Log::error('Error al registrar venta: ' . $e->getMessage());
        return redirect()->back()
            ->withInput()
            ->with('error', '❌ Error al registrar la venta');
    }
}
```

---

## 🔧 Implementación Paso a Paso

### 1. Crear el Trait
```bash
# Crear archivo app/Traits/SendsNotifications.php
# Copiar el código del Trait anterior
```

### 2. Usar el Trait en Controladores
```php
// En InventarioController.php
use App\Traits\SendsNotifications;

class InventarioController extends Controller
{
    use SendsNotifications;
    
    // ... resto del código ...
}
```

### 3. Agregar Notificaciones en Métodos
```php
// En el método store()
try {
    $product = Product::create($validated);
    
    // Notificación de éxito
    session()->flash('notification', [
        'type' => 'success',
        'title' => '✅ Éxito',
        'message' => "Producto '{$product->name}' guardado",
    ]);
    
    return redirect()->route('inventario.index');
} catch (\Exception $e) {
    // Notificación de error
    session()->flash('notification', [
        'type' => 'error',
        'title' => '❌ Error',
        'message' => $e->getMessage(),
    ]);
    
    return redirect()->back();
}
```

---

## 📱 Mostrar Notificaciones en Vistas

Agregar a `resources/views/layouts/app.blade.php`:

```blade
@if (session('notification'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const notification = @json(session('notification'));
            if (window.desktopApp) {
                window.desktopApp.showNotification(
                    notification.title,
                    notification.message
                );
            }
        });
    </script>
@endif
```

---

## 🎨 Estilos para Notificaciones

Agregar a `resources/css/app.css`:

```css
.notification {
    @apply fixed bottom-4 right-4 rounded-lg shadow-lg p-4 max-w-sm z-50 animate-fade-in;
}

.notification.success {
    @apply bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800;
}

.notification.error {
    @apply bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800;
}

.notification.info {
    @apply bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800;
}

.notification.warning {
    @apply bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800;
}

@keyframes fade-in {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in {
    animation: fade-in 0.3s ease-in-out;
}
```

---

## ✅ Checklist

- [ ] Crear `app/Traits/SendsNotifications.php`
- [ ] Actualizar `InventarioController.php`
- [ ] Actualizar `VentasController.php`
- [ ] Actualizar `ConfiguracionController.php`
- [ ] Agregar notificaciones a layout
- [ ] Agregar estilos CSS
- [ ] Probar notificaciones
- [ ] Verificar en modo escritorio

---

## 🚀 Comandos para Probar

```bash
# Ejecutar en modo escritorio
php artisan desktop:serve

# En otra terminal, hacer una solicitud
curl -X POST http://localhost:8000/inventario \
  -H "Content-Type: application/json" \
  -d '{"name":"Test","code":"TEST","laboratory":"Lab","package_type":"Caja","expiration_date":"2026-12-31","stock_boxes":5,"units_per_box":20,"loose_stock":0,"cost_price":10,"precio_venta":15}'
```

---

## 📊 Resultado Esperado

Cuando se complete el PASO 2:

✅ Notificaciones en creación de productos
✅ Notificaciones en actualización de productos
✅ Notificaciones en eliminación de productos
✅ Notificaciones en registro de ventas
✅ Notificaciones en cambios de configuración
✅ Notificaciones en exportación/importación de datos

---

## 📝 Próximo Paso

Después de completar el PASO 2, proceder con:

**PASO 3: Crear Menú de Aplicación**
- Implementar menú nativo
- Agregar opciones de archivo
- Agregar opciones de edición
- Agregar opciones de vista

