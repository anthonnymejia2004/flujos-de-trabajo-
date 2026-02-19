# Guía de Instalación: Sistema de Venta Fraccionada

## 📋 Requisitos Previos

- Laravel 11+
- PHP 8.1+
- Base de datos SQLite o MySQL
- Composer instalado
- Git (opcional)

---

## 🚀 Pasos de Instalación

### Paso 1: Ejecutar la Migración

La migración crea las nuevas columnas en la tabla `products`.

```bash
php artisan migrate
```

**Qué hace:**
- Agrega columna `stock_loose` (INTEGER, default 0)
- Agrega columna `sale_price_box` (DECIMAL 10,2)
- Agrega columna `sale_price_unit` (DECIMAL 10,4)

**Si algo sale mal:**
```bash
# Revertir la migración
php artisan migrate:rollback

# Ejecutar nuevamente
php artisan migrate
```

---

### Paso 2: Actualizar el Modelo Product

**Archivo:** `app/Models/Product.php`

**Cambios necesarios:**

1. Agregar campos a `$fillable`:
```php
protected $fillable = [
    'barcode', 
    'name', 
    'laboratory', 
    'stock_boxes', 
    'units_per_box', 
    'stock_loose',           // ← NUEVO
    'stock_units', 
    'cost_price', 
    'iva_percentage', 
    'profit_margin', 
    'profit_amount',
    'sale_price_box',        // ← NUEVO
    'sale_price_unit',       // ← NUEVO
    'expiration_date'
];
```

2. Actualizar método `getPrecioVentaAttribute()`:
```php
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
```

---

### Paso 3: Actualizar el Controlador

**Archivo:** `app/Http/Controllers/InventarioController.php`

**Cambios en método `store()`:**

```php
public function store(Request $request)
{
    $validated = $request->validate([
        'barcode' => 'required|string|max:255',
        'name' => 'required|string|max:255',
        'laboratory' => 'nullable|string|max:255',
        'stock_boxes' => 'required|integer|min:0',
        'units_per_box' => 'required|integer|min:1',
        'stock_loose' => 'nullable|integer|min:0',           // ← NUEVO
        'cost_price' => 'required|numeric|min:0',
        'iva_percentage' => 'nullable|numeric|min:0|max:100',
        'profit_amount' => 'required|numeric|min:0',
        'sale_price_box' => 'required|numeric|min:0',        // ← NUEVO
        'sale_price_unit' => 'required|numeric|min:0',       // ← NUEVO
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

**Cambios en método `update()`:**

```php
public function update(Request $request, Product $product)
{
    $validated = $request->validate([
        'barcode' => 'required|string|max:255',
        'name' => 'required|string|max:255',
        'laboratory' => 'nullable|string|max:255',
        'stock_boxes' => 'required|integer|min:0',
        'units_per_box' => 'required|integer|min:1',
        'stock_loose' => 'nullable|integer|min:0',           // ← NUEVO
        'cost_price' => 'required|numeric|min:0',
        'iva_percentage' => 'nullable|numeric|min:0|max:100',
        'profit_amount' => 'required|numeric|min:0',
        'sale_price_box' => 'required|numeric|min:0',        // ← NUEVO
        'sale_price_unit' => 'required|numeric|min:0',       // ← NUEVO
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

### Paso 4: Actualizar el Formulario

**Archivo:** `resources/views/inventario/create.blade.php`

**Reemplazar la sección de inventario:**

```blade
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

**Reemplazar la sección de precios:**

```blade
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

**Reemplazar el script JavaScript:**

```blade
<script>
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
</script>
```

---

### Paso 5: Actualizar Vista de Edición (Opcional)

**Archivo:** `resources/views/inventario/edit.blade.php`

Aplica los mismos cambios que en `create.blade.php` para mantener consistencia.

---

## ✅ Verificación Post-Instalación

### 1. Verificar Base de Datos

```bash
# Conectar a la base de datos
sqlite3 database/database.sqlite

# Ver estructura de la tabla products
.schema products

# Deberías ver las nuevas columnas:
# - stock_loose
# - sale_price_box
# - sale_price_unit
```

### 2. Probar Creación de Producto

1. Abre tu navegador
2. Ve a `/inventario/create`
3. Completa el formulario con datos de prueba:
   - Stock Cajas: 5
   - Unidades por Caja: 20
   - Stock Suelto: 3
   - Precio Costo: 15.50
   - IVA: 15
   - Ganancia: 2.50

4. Verifica que:
   - Stock Total se calcula: (5 × 20) + 3 = 103
   - Precio Venta Caja se calcula: 15.50 + 2.325 + 2.50 = 20.33
   - Precio Venta Unitario se calcula: 20.33 ÷ 20 = 1.0165

### 3. Probar Edición Manual

1. Edita el Precio Venta Unitario a 1.02
2. Cambia el Precio Costo a 16.00
3. Verifica que:
   - Precio Venta Caja se recalcula
   - Precio Venta Unitario se recalcula automáticamente (se reinicia)

### 4. Probar Guardado

1. Completa el formulario
2. Haz clic en "Guardar Producto"
3. Verifica que el producto aparece en la lista
4. Edita el producto y verifica que los datos se guardaron correctamente

---

## 🔧 Troubleshooting

### Error: "Column not found"

**Causa:** La migración no se ejecutó correctamente

**Solución:**
```bash
# Verificar estado de migraciones
php artisan migrate:status

# Si la migración no aparece, ejecutar:
php artisan migrate

# Si sigue fallando, revertir y reintentar:
php artisan migrate:rollback
php artisan migrate
```

### Error: "SQLSTATE[HY000]: General error"

**Causa:** Problema con la base de datos

**Solución:**
```bash
# Limpiar caché
php artisan cache:clear
php artisan config:clear

# Reintentar migración
php artisan migrate
```

### Los cálculos no funcionan

**Causa:** JavaScript no se cargó correctamente

**Solución:**
1. Abre la consola del navegador (F12)
2. Verifica que no haya errores de JavaScript
3. Recarga la página (Ctrl+F5)
4. Verifica que los IDs de los inputs coincidan con el JavaScript

### El precio unitario no se recalcula

**Causa:** La bandera `unitPriceManuallyEdited` está en true

**Solución:**
1. Cambia uno de los costos base (Costo, IVA, Ganancia)
2. El precio unitario debería recalcularse
3. Si no, revisa la consola del navegador para errores

---

## 📊 Verificación de Datos

### Consulta SQL para verificar

```sql
-- Ver estructura de la tabla
PRAGMA table_info(products);

-- Ver productos con nuevos campos
SELECT 
    id,
    name,
    stock_boxes,
    units_per_box,
    stock_loose,
    stock_units,
    sale_price_box,
    sale_price_unit
FROM products;

-- Ver productos sin los nuevos campos (antiguos)
SELECT * FROM products WHERE sale_price_box IS NULL;
```

---

## 🎯 Próximos Pasos

Después de instalar el sistema de venta fraccionada:

1. **Actualizar Vista de Inventario**
   - Mostrar stock_boxes, stock_loose, stock_units
   - Mostrar sale_price_box y sale_price_unit

2. **Implementar Módulo de Ventas**
   - Permitir venta de cajas completas
   - Permitir venta de unidades sueltas
   - Permitir venta mixta

3. **Crear Reportes**
   - Ingresos por tipo de venta
   - Margen de ganancia real
   - Análisis de productos

4. **Capacitar al Equipo**
   - Explicar el nuevo sistema
   - Mostrar ejemplos prácticos
   - Resolver dudas

---

## 📞 Soporte

Si tienes problemas durante la instalación:

1. Revisa los archivos de documentación:
   - `VENTA_FRACCIONADA_DOCUMENTACION.md`
   - `VENTA_FRACCIONADA_CODIGO_REFERENCIA.md`
   - `VENTA_FRACCIONADA_RESUMEN_VISUAL.md`

2. Verifica que todos los cambios se hayan aplicado correctamente

3. Revisa la consola del navegador para errores de JavaScript

4. Revisa los logs de Laravel: `storage/logs/laravel.log`

---

## ✨ ¡Listo!

Si completaste todos los pasos, tu sistema de venta fraccionada está instalado y funcionando. 

**Próximo paso:** Prueba creando un nuevo producto y verifica que todos los cálculos sean correctos.

