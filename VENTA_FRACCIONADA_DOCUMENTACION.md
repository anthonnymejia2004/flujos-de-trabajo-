# Documentación: Sistema de Venta Fraccionada - Pharma-Sync

## Resumen de Cambios

Se ha implementado un sistema completo de venta fraccionada que permite vender productos tanto en cajas completas como en unidades sueltas (pastillas/sobres individuales).

---

## 1. Cambios en el Formulario HTML

### Nuevos Campos de Inventario

#### Stock (Cajas Completas)
- **Campo**: `stock_boxes`
- **Tipo**: Número entero
- **Descripción**: Cantidad de cajas cerradas completas
- **Evento**: `oninput="calculateTotals()"`

#### Unidades por Caja
- **Campo**: `units_per_box`
- **Tipo**: Número entero (mínimo 1)
- **Descripción**: Cuántas pastillas/sobres trae cada caja
- **Evento**: `oninput="calculateTotals()"`

#### Stock Suelto (Restos) - NUEVO
- **Campo**: `stock_loose`
- **Tipo**: Número entero (opcional, por defecto 0)
- **Descripción**: Unidades sueltas de una caja abierta
- **Evento**: `oninput="calculateTotals()"`
- **Ejemplo**: Si tienes 5 cajas completas + 3 pastillas sueltas

#### Stock Total - NUEVO (Calculado)
- **Campo**: `total_stock`
- **Tipo**: Número (solo lectura)
- **Fórmula**: `(stock_boxes × units_per_box) + stock_loose`
- **Ejemplo**: (5 × 20) + 3 = 103 unidades totales

### Nuevos Campos de Precios

#### Precio de Costo (por Caja)
- **Campo**: `cost_price`
- **Tipo**: Decimal (2 decimales)
- **Descripción**: Costo total de la caja completa
- **Evento**: `oninput="calculateTotals()"`
- **Ejemplo**: 15.50 (costo de toda la caja)

#### Ganancia Deseada (por Caja) - ACTUALIZADO
- **Campo**: `profit_amount`
- **Tipo**: Decimal (2 decimales)
- **Descripción**: Ganancia total por caja completa (no por unidad)
- **Evento**: `oninput="calculateTotals()"`
- **Ejemplo**: 2.50 (ganancia de toda la caja)

#### Precio Venta Caja - NUEVO (Calculado)
- **Campo**: `sale_price_box`
- **Tipo**: Decimal (2 decimales, solo lectura)
- **Fórmula**: `Costo + (Costo × IVA%) + Ganancia`
- **Ejemplo**: 15.50 + 2.33 + 2.50 = 20.33
- **Color**: Verde (indica campo calculado)

#### Precio Venta Unitario - NUEVO (Editable)
- **Campo**: `sale_price_unit`
- **Tipo**: Decimal (4 decimales, EDITABLE)
- **Fórmula Sugerida**: `Precio Venta Caja ÷ Unidades por Caja`
- **Ejemplo**: 20.33 ÷ 20 = 1.0165 → puedes redondear a 1.02
- **Color**: Ámbar (indica campo editable)
- **Evento**: `oninput="markUnitPriceAsManuallyEdited()"`

---

## 2. Lógica JavaScript

### Función Principal: `calculateTotals()`

Se ejecuta automáticamente cuando el usuario modifica cualquiera de estos campos:
- Stock Cajas
- Unidades por Caja
- Stock Suelto
- Precio de Costo
- IVA (%)
- Ganancia Deseada

**Qué hace:**

1. **Calcula Stock Total**
   ```javascript
   totalStock = (stockBoxes × unitsPerBox) + stockLoose
   ```

2. **Calcula Precio de Venta de la Caja**
   ```javascript
   ivaAmount = costPrice × (ivaPercentage / 100)
   salePriceBox = costPrice + ivaAmount + profitAmount
   ```

3. **Calcula Precio Unitario Sugerido** (solo si no fue editado manualmente)
   ```javascript
   suggestedUnitPrice = salePriceBox / unitsPerBox
   ```

### Función: `markUnitPriceAsManuallyEdited()`

Se ejecuta cuando el usuario edita manualmente el campo "Precio Venta Unitario".

**Qué hace:**
- Establece la bandera `unitPriceManuallyEdited = true`
- Esto evita que el precio unitario se sobrescriba automáticamente

### Función: `resetManualEditFlag()`

Se ejecuta cuando el usuario termina de editar (evento `change`) los campos de costos base:
- Precio de Costo
- IVA (%)
- Ganancia Deseada
- Unidades por Caja

**Qué hace:**
- Reinicia la bandera `unitPriceManuallyEdited = false`
- Permite que el precio unitario se recalcule automáticamente en la siguiente ejecución de `calculateTotals()`

---

## 3. Cambios en la Base de Datos

### Nueva Migración

Archivo: `database/migrations/2026_02_03_000000_add_fractional_sales_to_products_table.php`

Nuevas columnas en la tabla `products`:

| Columna | Tipo | Descripción |
|---------|------|-------------|
| `stock_loose` | INTEGER | Unidades sueltas (por defecto 0) |
| `sale_price_box` | DECIMAL(10,2) | Precio de venta de la caja |
| `sale_price_unit` | DECIMAL(10,4) | Precio de venta unitario |

**Para ejecutar la migración:**
```bash
php artisan migrate
```

---

## 4. Cambios en el Modelo

### Archivo: `app/Models/Product.php`

**Campos agregados a `$fillable`:**
```php
'stock_loose',
'sale_price_box',
'sale_price_unit',
```

**Método `getPrecioVentaAttribute()` actualizado:**
- Ahora verifica primero si existe `sale_price_box`
- Si existe, lo usa como precio de venta
- Si no, usa la lógica anterior (compatibilidad hacia atrás)

---

## 5. Cambios en el Controlador

### Archivo: `app/Http/Controllers/InventarioController.php`

**Método `store()` actualizado:**
- Valida los nuevos campos: `stock_loose`, `sale_price_box`, `sale_price_unit`
- Calcula `stock_units` incluyendo los sueltos: `(stock_boxes × units_per_box) + stock_loose`

**Método `update()` actualizado:**
- Mismos cambios que en `store()`

---

## 6. Flujo de Uso

### Ejemplo Práctico: Agregar Paracetamol 500mg

1. **Datos Básicos**
   - Código de Barras: `7891234567890`
   - Nombre: `Paracetamol 500mg`
   - Laboratorio: `Bayer`

2. **Inventario**
   - Stock (Cajas): `5`
   - Unidades por Caja: `20`
   - Stock Suelto: `3`
   - **Stock Total (calculado)**: `103` unidades

3. **Precios**
   - Precio de Costo (por Caja): `15.50`
   - IVA (%): `15`
   - Ganancia Deseada (por Caja): `2.50`
   - **Precio Venta Caja (calculado)**: `20.33`
   - **Precio Venta Unitario (sugerido)**: `1.0165`
   
   *Puedes editar el unitario a `1.02` para redondear*

4. **Vencimiento**
   - Fecha de Vencimiento: `2026-12-31`

---

## 7. Cálculos Matemáticos Detallados

### Ejemplo Completo

**Datos de entrada:**
- Costo por Caja: $15.50
- IVA: 15%
- Ganancia por Caja: $2.50
- Unidades por Caja: 20

**Paso 1: Calcular IVA**
```
IVA = 15.50 × (15 / 100) = 15.50 × 0.15 = 2.325
```

**Paso 2: Calcular Precio de Venta de la Caja**
```
Precio Caja = 15.50 + 2.325 + 2.50 = 20.325 ≈ 20.33
```

**Paso 3: Calcular Precio Unitario Sugerido**
```
Precio Unitario = 20.33 / 20 = 1.0165
```

**Paso 4: Redondear Manualmente (opcional)**
```
Precio Unitario Final = 1.02 (redondeado)
```

---

## 8. Validaciones

### En el Formulario (HTML)

- `stock_boxes`: Mínimo 0
- `units_per_box`: Mínimo 1 (requerido)
- `stock_loose`: Mínimo 0 (opcional)
- `cost_price`: Mínimo 0 (requerido)
- `iva_percentage`: Entre 0 y 100 (opcional)
- `profit_amount`: Mínimo 0 (requerido)
- `sale_price_box`: Mínimo 0 (requerido)
- `sale_price_unit`: Mínimo 0 (requerido)

### En el Controlador (PHP)

```php
'stock_boxes' => 'required|integer|min:0',
'units_per_box' => 'required|integer|min:1',
'stock_loose' => 'nullable|integer|min:0',
'cost_price' => 'required|numeric|min:0',
'iva_percentage' => 'nullable|numeric|min:0|max:100',
'profit_amount' => 'required|numeric|min:0',
'sale_price_box' => 'required|numeric|min:0',
'sale_price_unit' => 'required|numeric|min:0',
```

---

## 9. Notas Importantes

### Flexibilidad del Precio Unitario

El campo "Precio Venta Unitario" es **editable** porque:
- A veces el cálculo automático genera decimales complicados (ej: 1.0165)
- En farmacias es común redondear para facilitar el cambio
- Puedes establecer un precio unitario estratégico diferente al sugerido

### Comportamiento Inteligente

- Si editas el precio unitario manualmente, **no se sobrescribe** al cambiar otros campos
- Si cambias los costos base (Costo, IVA, Ganancia, Unidades), el precio unitario se **recalcula automáticamente**
- Esto te da control total sin perder la automatización

### Compatibilidad Hacia Atrás

- Los productos antiguos sin estos campos seguirán funcionando
- El modelo verifica si existen los nuevos campos antes de usarlos
- Puedes migrar gradualmente

---

## 10. Próximos Pasos

### Para Implementar en Ventas

Cuando implementes el módulo de ventas, considera:

1. **Venta de Cajas Completas**
   - Usar `sale_price_box`
   - Restar 1 de `stock_boxes`

2. **Venta de Unidades Sueltas**
   - Usar `sale_price_unit`
   - Restar de `stock_loose`

3. **Venta Mixta**
   - Permitir vender cajas + unidades sueltas en una sola transacción
   - Actualizar ambos campos de stock

4. **Reportes**
   - Mostrar ingresos por tipo de venta (cajas vs. sueltas)
   - Calcular margen de ganancia real

---

## 11. Troubleshooting

### El precio unitario no se recalcula

**Solución**: Asegúrate de que `unitPriceManuallyEdited` sea `false`. Cambia uno de los costos base (Costo, IVA, Ganancia) y el precio unitario debería recalcularse.

### El stock total no es correcto

**Solución**: Verifica que hayas ingresado correctamente:
- Stock Cajas (número de cajas)
- Unidades por Caja (cuántas unidades trae cada caja)
- Stock Suelto (unidades sueltas adicionales)

### Los decimales se ven raros

**Solución**: El precio unitario usa 4 decimales para precisión. Puedes redondear manualmente al valor que desees.

---

## Contacto y Soporte

Para preguntas o problemas con la implementación de venta fraccionada, revisa:
- El código JavaScript en `resources/views/inventario/create.blade.php`
- La migración en `database/migrations/2026_02_03_000000_add_fractional_sales_to_products_table.php`
- El modelo en `app/Models/Product.php`
- El controlador en `app/Http/Controllers/InventarioController.php`
