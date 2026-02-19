# Referencia de Código: Sistema de Venta Fraccionada

## 1. HTML del Formulario

### Sección de Inventario

```html
<!-- Stock Cajas -->
<div>
    <label class="block text-sm font-medium text-slate-900 mb-2">Stock (Cajas Completas)</label>
    <input type="number" name="stock_boxes" id="stock_boxes" value="{{ old('stock_boxes') }}" required min="0"
        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('stock_boxes') border-red-500 @enderror"
        oninput="calculateTotals()">
    @error('stock_boxes')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
    <p class="text-xs text-slate-500 mt-1">Cantidad de cajas cerradas completas</p>
</div>

<!-- Unidades por Caja -->
<div>
    <label class="block text-sm font-medium text-slate-900 mb-2">Unidades por Caja</label>
    <input type="number" name="units_per_box" id="units_per_box" value="{{ old('units_per_box') }}" required min="1"
        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('units_per_box') border-red-500 @enderror"
        oninput="calculateTotals()">
    @error('units_per_box')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
    <p class="text-xs text-slate-500 mt-1">Pastillas/sobres por caja</p>
</div>

<!-- Stock Suelto (Restos) -->
<div>
    <label class="block text-sm font-medium text-slate-900 mb-2">
        Stock Suelto (Restos)
        <span class="text-slate-500 text-xs font-normal">(Opcional)</span>
    </label>
    <input type="number" name="stock_loose" id="stock_loose" value="{{ old('stock_loose', 0) }}" min="0"
        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('stock_loose') border-red-500 @enderror"
        oninput="calculateTotals()">
    @error('stock_loose')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
    <p class="text-xs text-slate-500 mt-1">Unidades sueltas de una caja abierta</p>
</div>

<!-- Stock Total (Informativo) -->
<div>
    <label class="block text-sm font-medium text-slate-900 mb-2">
        Stock Total
        <span class="text-blue-600 text-xs font-normal">(Calculado)</span>
    </label>
    <input type="number" id="total_stock" name="total_stock" value="{{ old('total_stock', 0) }}" min="0"
        class="w-full px-4 py-2 border border-slate-300 rounded-lg bg-blue-50 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
        readonly>
    <p class="text-xs text-slate-500 mt-1">(Cajas × Unidades) + Sueltos</p>
</div>
```

### Sección de Precios

```html
<!-- Precio Costo -->
<div>
    <label class="block text-sm font-medium text-slate-900 mb-2">Precio de Costo (por Caja)</label>
    <input type="number" id="cost_price" name="cost_price" value="{{ old('cost_price') }}" required min="0" step="0.01"
        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('cost_price') border-red-500 @enderror"
        oninput="calculateTotals()">
    @error('cost_price')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
    <p class="text-xs text-slate-500 mt-1">Costo total de la caja completa</p>
</div>

<!-- IVA -->
<div>
    <label class="block text-sm font-medium text-slate-900 mb-2">IVA (%)</label>
    <input type="number" id="iva_percentage" name="iva_percentage" value="{{ old('iva_percentage', 15) }}" min="0" max="100" step="0.01"
        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('iva_percentage') border-red-500 @enderror"
        oninput="calculateTotals()">
    @error('iva_percentage')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
</div>

<!-- Ganancia Deseada -->
<div>
    <label class="block text-sm font-medium text-slate-900 mb-2">
        Ganancia Deseada (por Caja)
        <span class="text-slate-500 text-xs font-normal">(Monto en $)</span>
    </label>
    <input type="number" id="profit_amount" name="profit_amount" value="{{ old('profit_amount') }}" required min="0" step="0.01"
        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('profit_amount') border-red-500 @enderror"
        placeholder="Ej: 2.50"
        oninput="calculateTotals()">
    @error('profit_amount')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
    <p class="text-xs text-slate-500 mt-1">Ganancia total por caja completa</p>
</div>

<!-- Precio de Venta Caja (Calculado) -->
<div>
    <label class="block text-sm font-medium text-slate-900 mb-2">
        Precio Venta Caja
        <span class="text-green-600 text-xs font-normal">(Calculado)</span>
    </label>
    <input type="number" id="sale_price_box" name="sale_price_box" value="{{ old('sale_price_box') }}" required min="0" step="0.01"
        class="w-full px-4 py-2 border border-slate-300 rounded-lg bg-green-50 focus:ring-2 focus:ring-green-500 focus:border-transparent @error('sale_price_box') border-red-500 @enderror"
        readonly>
    @error('sale_price_box')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
    <p class="text-xs text-slate-500 mt-1">Costo + IVA + Ganancia</p>
</div>

<!-- Precio de Venta Unitario (Sugerido, editable) -->
<div>
    <label class="block text-sm font-medium text-slate-900 mb-2">
        Precio Venta Unitario
        <span class="text-amber-600 text-xs font-normal">(Editable)</span>
    </label>
    <input type="number" id="sale_price_unit" name="sale_price_unit" value="{{ old('sale_price_unit') }}" required min="0" step="0.01"
        class="w-full px-4 py-2 border border-slate-300 rounded-lg bg-amber-50 focus:ring-2 focus:ring-amber-500 focus:border-transparent @error('sale_price_unit') border-red-500 @enderror"
        oninput="markUnitPriceAsManuallyEdited()">
    @error('sale_price_unit')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
    <p class="text-xs text-slate-500 mt-1">Precio por unidad individual (Caja ÷ Unidades)</p>
</div>
```

---

## 2. JavaScript Completo

```javascript
// Variable para rastrear si el precio unitario fue editado manualmente
let unitPriceManuallyEdited = false;

/**
 * Marca que el precio unitario fue editado manualmente por el usuario
 */
function markUnitPriceAsManuallyEdited() {
    unitPriceManuallyEdited = true;
}

/**
 * Calcula todos los totales: stock total, precio de caja y precio unitario
 * Se ejecuta en tiempo real cuando el usuario modifica los inputs
 */
function calculateTotals() {
    // Obtener valores de los inputs
    const stockBoxes = parseInt(document.getElementById('stock_boxes').value) || 0;
    const unitsPerBox = parseInt(document.getElementById('units_per_box').value) || 1;
    const stockLoose = parseInt(document.getElementById('stock_loose').value) || 0;
    const costPrice = parseFloat(document.getElementById('cost_price').value) || 0;
    const ivaPercentage = parseFloat(document.getElementById('iva_percentage').value) || 0;
    const profitAmount = parseFloat(document.getElementById('profit_amount').value) || 0;

    // 1. Calcular Stock Total
    const totalStock = (stockBoxes * unitsPerBox) + stockLoose;
    document.getElementById('total_stock').value = totalStock;

    // 2. Calcular Precio de Venta de la Caja
    // Fórmula: Costo + (Costo × IVA%) + Ganancia
    const ivaAmount = costPrice * (ivaPercentage / 100);
    const salePriceBox = costPrice + ivaAmount + profitAmount;
    document.getElementById('sale_price_box').value = salePriceBox.toFixed(2);

    // 3. Calcular Precio Unitario Sugerido (solo si no fue editado manualmente)
    if (!unitPriceManuallyEdited && unitsPerBox > 0) {
        const suggestedUnitPrice = salePriceBox / unitsPerBox;
        document.getElementById('sale_price_unit').value = suggestedUnitPrice.toFixed(4);
    }
}

/**
 * Reinicia el estado de edición manual cuando cambian los costos base
 * Esto permite que el precio unitario se recalcule automáticamente
 */
function resetManualEditFlag() {
    unitPriceManuallyEdited = false;
}

// Agregar event listeners a los inputs de costos base
document.addEventListener('DOMContentLoaded', function() {
    const costPriceInput = document.getElementById('cost_price');
    const ivaInput = document.getElementById('iva_percentage');
    const profitInput = document.getElementById('profit_amount');
    const unitsPerBoxInput = document.getElementById('units_per_box');

    // Cuando cambian los costos base, reiniciar el flag de edición manual
    if (costPriceInput) {
        costPriceInput.addEventListener('change', resetManualEditFlag);
    }
    if (ivaInput) {
        ivaInput.addEventListener('change', resetManualEditFlag);
    }
    if (profitInput) {
        profitInput.addEventListener('change', resetManualEditFlag);
    }
    if (unitsPerBoxInput) {
        unitsPerBoxInput.addEventListener('change', resetManualEditFlag);
    }

    // Calcular al cargar la página si hay valores previos
    calculateTotals();
});
```

---

## 3. Modelo Product

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'barcode', 
        'name', 
        'laboratory', 
        'stock_boxes', 
        'units_per_box', 
        'stock_loose',
        'stock_units', 
        'cost_price', 
        'iva_percentage', 
        'profit_margin', 
        'profit_amount',
        'sale_price_box',
        'sale_price_unit',
        'expiration_date'
    ];

    protected $appends = ['precio_venta'];

    public function getPrecioVentaAttribute()
    {
        // Si existen los nuevos campos de venta fraccionada, usarlos
        if ($this->sale_price_box && $this->sale_price_box > 0) {
            return $this->sale_price_box;
        }

        $iva = $this->iva_percentage ?? env('GLOBAL_IVA', 15);
        
        // Si existe profit_amount (ganancia fija), usarlo
        if ($this->profit_amount && $this->profit_amount > 0) {
            $ivaAmount = $this->cost_price * ($iva / 100);
            return $this->cost_price + $ivaAmount + $this->profit_amount;
        }
        
        // Si no, usar el método antiguo con profit_margin (porcentaje)
        $subtotal = $this->cost_price * (1 + ($this->profit_margin / 100));
        return $subtotal * (1 + ($iva / 100));
    }
}
```

---

## 4. Controlador - Método Store

```php
public function store(Request $request)
{
    $validated = $request->validate([
        'barcode' => 'required|string|max:255',
        'name' => 'required|string|max:255',
        'laboratory' => 'nullable|string|max:255',
        'stock_boxes' => 'required|integer|min:0',
        'units_per_box' => 'required|integer|min:1',
        'stock_loose' => 'nullable|integer|min:0',
        'cost_price' => 'required|numeric|min:0',
        'iva_percentage' => 'nullable|numeric|min:0|max:100',
        'profit_amount' => 'required|numeric|min:0',
        'sale_price_box' => 'required|numeric|min:0',
        'sale_price_unit' => 'required|numeric|min:0',
        'expiration_date' => 'required|date|after:today',
    ]);

    // Calcular stock total: (cajas × unidades) + sueltos
    $stockLoose = $validated['stock_loose'] ?? 0;
    $validated['stock_units'] = ($validated['stock_boxes'] * $validated['units_per_box']) + $stockLoose;
    
    // Mantener profit_margin en 0 para compatibilidad
    $validated['profit_margin'] = 0;

    Product::create($validated);

    return redirect()->route('inventario.index')->with('success', 'Producto creado exitosamente');
}
```

---

## 5. Controlador - Método Update

```php
public function update(Request $request, Product $product)
{
    $validated = $request->validate([
        'barcode' => 'required|string|max:255',
        'name' => 'required|string|max:255',
        'laboratory' => 'nullable|string|max:255',
        'stock_boxes' => 'required|integer|min:0',
        'units_per_box' => 'required|integer|min:1',
        'stock_loose' => 'nullable|integer|min:0',
        'cost_price' => 'required|numeric|min:0',
        'iva_percentage' => 'nullable|numeric|min:0|max:100',
        'profit_amount' => 'required|numeric|min:0',
        'sale_price_box' => 'required|numeric|min:0',
        'sale_price_unit' => 'required|numeric|min:0',
        'expiration_date' => 'required|date',
    ]);

    // Calcular stock total: (cajas × unidades) + sueltos
    $stockLoose = $validated['stock_loose'] ?? 0;
    $validated['stock_units'] = ($validated['stock_boxes'] * $validated['units_per_box']) + $stockLoose;
    
    // Mantener profit_margin en 0 para compatibilidad
    $validated['profit_margin'] = 0;

    $product->update($validated);

    return redirect()->route('inventario.index')->with('success', 'Producto actualizado exitosamente');
}
```

---

## 6. Migración

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Stock suelto (unidades de una caja abierta)
            $table->integer('stock_loose')->default(0)->after('units_per_box');
            
            // Precio de venta de la caja completa
            $table->decimal('sale_price_box', 10, 2)->nullable()->after('profit_amount');
            
            // Precio de venta unitario (por pastilla/sobre)
            $table->decimal('sale_price_unit', 10, 4)->nullable()->after('sale_price_box');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['stock_loose', 'sale_price_box', 'sale_price_unit']);
        });
    }
};
```

---

## 7. Ejemplos de Uso en Blade

### Mostrar Producto en Tabla

```blade
<tr>
    <td>{{ $product->name }}</td>
    <td>{{ $product->stock_boxes }} cajas</td>
    <td>{{ $product->stock_loose }} sueltos</td>
    <td>{{ $product->stock_units }} total</td>
    <td>${{ number_format($product->sale_price_box, 2) }}</td>
    <td>${{ number_format($product->sale_price_unit, 4) }}</td>
</tr>
```

### Calcular Ingresos Potenciales

```blade
@php
    $ingresosCajas = $product->stock_boxes * $product->sale_price_box;
    $ingresosSueltos = $product->stock_loose * $product->sale_price_unit;
    $ingresosTotal = $ingresosCajas + $ingresosSueltos;
@endphp

<p>Ingresos por cajas: ${{ number_format($ingresosCajas, 2) }}</p>
<p>Ingresos por sueltos: ${{ number_format($ingresosSueltos, 2) }}</p>
<p>Total potencial: ${{ number_format($ingresosTotal, 2) }}</p>
```

---

## 8. Notas de Implementación

### Paso 1: Ejecutar Migración
```bash
php artisan migrate
```

### Paso 2: Actualizar Formulario
- Reemplaza el contenido de `resources/views/inventario/create.blade.php`
- Asegúrate de incluir todo el JavaScript

### Paso 3: Actualizar Modelo
- Agrega los nuevos campos a `$fillable` en `app/Models/Product.php`
- Actualiza el método `getPrecioVentaAttribute()`

### Paso 4: Actualizar Controlador
- Actualiza los métodos `store()` y `update()` en `InventarioController.php`
- Agrega validaciones para los nuevos campos

### Paso 5: Probar
- Crea un nuevo producto con valores de prueba
- Verifica que los cálculos sean correctos
- Prueba editar el precio unitario manualmente

---

## 9. Troubleshooting

### Error: "Column not found"
**Solución**: Ejecuta `php artisan migrate` para crear las nuevas columnas

### El precio unitario no se recalcula
**Solución**: Cambia uno de los costos base (Costo, IVA, Ganancia) para reiniciar el flag

### Los valores no se guardan
**Solución**: Verifica que los campos estén en `$fillable` del modelo

---

## 10. Compatibilidad

Este código es compatible con:
- Laravel 11+
- PHP 8.1+
- Tailwind CSS (estilos)
- Bootstrap (si lo prefieres, solo cambia las clases)

