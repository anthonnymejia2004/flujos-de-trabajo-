# Resumen Visual: Sistema de Venta Fraccionada

## 📊 Comparativa: Antes vs Después

### ANTES (Sistema Antiguo)

```
┌─────────────────────────────────────┐
│     AGREGAR NUEVO PRODUCTO          │
├─────────────────────────────────────┤
│                                     │
│  Código de Barras: [___________]    │
│  Nombre: [___________________]      │
│  Laboratorio: [_______________]     │
│                                     │
│  Stock: [___]  ← Un solo campo      │
│                                     │
│  Precio Costo: [_______]            │
│  IVA (%): [___]                     │
│  Ganancia: [_______]                │
│  Precio Venta: [_______] (RO)       │
│                                     │
│  Fecha Vencimiento: [__________]    │
│                                     │
│  [Guardar] [Cancelar]               │
└─────────────────────────────────────┘

Limitaciones:
❌ No diferencia cajas de unidades sueltas
❌ No permite venta fraccionada
❌ Precio unitario no calculado
❌ Ganancia por unidad, no por caja
```

### DESPUÉS (Sistema Nuevo)

```
┌──────────────────────────────────────────┐
│     AGREGAR NUEVO PRODUCTO               │
├──────────────────────────────────────────┤
│                                          │
│  Código de Barras: [___________]         │
│  Nombre: [_____________________]         │
│  Laboratorio: [_________________]        │
│                                          │
│  ┌─ INVENTARIO FRACCIONADO ─────────┐   │
│  │ Stock (Cajas): [___]              │   │
│  │ Unidades por Caja: [___]          │   │
│  │ Stock Suelto (Restos): [___]  ✨  │   │
│  │ Stock Total: [___] (RO) 🔵        │   │
│  └──────────────────────────────────┘   │
│                                          │
│  ┌─ PRECIOS INTELIGENTES ────────────┐   │
│  │ Precio Costo (Caja): [_______]    │   │
│  │ IVA (%): [___]                    │   │
│  │ Ganancia (Caja): [_______]        │   │
│  │ Precio Venta Caja: [_______] 🟢   │   │
│  │ Precio Venta Unitario: [_____] 🟡 │   │
│  └──────────────────────────────────┘   │
│                                          │
│  Fecha Vencimiento: [__________]         │
│                                          │
│  [Guardar] [Cancelar]                    │
└──────────────────────────────────────────┘

Mejoras:
✅ Diferencia cajas de unidades sueltas
✅ Permite venta fraccionada
✅ Calcula precio unitario automáticamente
✅ Ganancia por caja (más realista)
✅ Precio unitario editable (flexible)
✅ Stock total calculado en tiempo real
```

---

## 🔄 Flujo de Cálculos

```
┌─────────────────────────────────────────────────────────────┐
│                    ENTRADA DE DATOS                         │
├─────────────────────────────────────────────────────────────┤
│                                                             │
│  Stock Cajas: 5                                             │
│  Unidades por Caja: 20                                      │
│  Stock Suelto: 3                                            │
│  Precio Costo (Caja): $15.50                                │
│  IVA: 15%                                                   │
│  Ganancia (Caja): $2.50                                     │
│                                                             │
└─────────────────────────────────────────────────────────────┘
                            ↓
┌─────────────────────────────────────────────────────────────┐
│                  CÁLCULOS AUTOMÁTICOS                       │
├─────────────────────────────────────────────────────────────┤
│                                                             │
│  1️⃣  Stock Total = (5 × 20) + 3 = 103 unidades             │
│                                                             │
│  2️⃣  IVA = $15.50 × 15% = $2.325                           │
│                                                             │
│  3️⃣  Precio Venta Caja = $15.50 + $2.325 + $2.50           │
│      = $20.325 ≈ $20.33                                    │
│                                                             │
│  4️⃣  Precio Unitario Sugerido = $20.33 ÷ 20               │
│      = $1.0165 ≈ $1.0165                                   │
│                                                             │
└─────────────────────────────────────────────────────────────┘
                            ↓
┌─────────────────────────────────────────────────────────────┐
│                    SALIDA DE DATOS                          │
├─────────────────────────────────────────────────────────────┤
│                                                             │
│  Stock Total: 103 unidades ✓                               │
│  Precio Venta Caja: $20.33 ✓                               │
│  Precio Venta Unitario: $1.0165 (editable)                 │
│                                                             │
│  Usuario puede cambiar a: $1.02 (redondeado)               │
│                                                             │
└─────────────────────────────────────────────────────────────┘
```

---

## 🎯 Casos de Uso

### Caso 1: Venta de Cajas Completas

```
Producto: Paracetamol 500mg
Stock: 5 cajas + 3 sueltos

Cliente compra: 2 cajas
Precio: 2 × $20.33 = $40.66

Nuevo Stock:
├─ Cajas: 5 - 2 = 3 cajas
├─ Sueltos: 3 (sin cambios)
└─ Total: (3 × 20) + 3 = 63 unidades
```

### Caso 2: Venta de Unidades Sueltas

```
Producto: Paracetamol 500mg
Stock: 5 cajas + 3 sueltos

Cliente compra: 5 pastillas sueltas
Precio: 5 × $1.02 = $5.10

Nuevo Stock:
├─ Cajas: 5 (sin cambios)
├─ Sueltos: 3 - 5 = -2 (necesita abrir caja)
│   → Cajas: 4, Sueltos: 18 - 2 = 16
└─ Total: (4 × 20) + 16 = 96 unidades
```

### Caso 3: Venta Mixta

```
Producto: Paracetamol 500mg
Stock: 5 cajas + 3 sueltos

Cliente compra: 1 caja + 10 sueltos
Precio: (1 × $20.33) + (10 × $1.02) = $30.53

Nuevo Stock:
├─ Cajas: 5 - 1 = 4 cajas
├─ Sueltos: 3 - 10 = -7 (necesita abrir caja)
│   → Cajas: 3, Sueltos: 20 - 7 = 13
└─ Total: (3 × 20) + 13 = 73 unidades
```

---

## 📱 Interfaz de Usuario

### Campos Visuales

```
┌─────────────────────────────────────────────────────────────┐
│ Stock (Cajas Completas)                                     │
│ [5]                                                         │
│ Cantidad de cajas cerradas completas                        │
└─────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────┐
│ Unidades por Caja                                           │
│ [20]                                                        │
│ Pastillas/sobres por caja                                   │
└─────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────┐
│ Stock Suelto (Restos) (Opcional)                            │
│ [3]                                                         │
│ Unidades sueltas de una caja abierta                        │
└─────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────┐
│ Stock Total (Calculado) 🔵                                  │
│ [103] (solo lectura)                                        │
│ (Cajas × Unidades) + Sueltos                                │
└─────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────┐
│ Precio Venta Caja 🟢                                        │
│ [20.33] (solo lectura)                                      │
│ Costo + IVA + Ganancia                                      │
└─────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────┐
│ Precio Venta Unitario 🟡 (Editable)                         │
│ [1.0165]                                                    │
│ Precio por unidad individual (Caja ÷ Unidades)              │
│ ← Puedes cambiar a 1.02 si lo deseas                        │
└─────────────────────────────────────────────────────────────┘
```

### Colores y Significados

```
🔵 AZUL = Campo calculado (Stock Total)
   └─ Solo lectura, se actualiza automáticamente

🟢 VERDE = Campo calculado (Precio Venta Caja)
   └─ Solo lectura, se actualiza automáticamente

🟡 ÁMBAR = Campo editable (Precio Venta Unitario)
   └─ Sugerencia automática, pero puedes cambiar
   └─ Útil para redondear centavos

⚪ BLANCO = Campos de entrada normales
   └─ Ingresa datos manualmente
```

---

## 🧮 Fórmulas Matemáticas

### Stock Total
```
Stock Total = (Stock Cajas × Unidades por Caja) + Stock Suelto

Ejemplo:
Stock Total = (5 × 20) + 3 = 103 unidades
```

### Precio de Venta de la Caja
```
IVA = Precio Costo × (IVA% / 100)
Precio Venta Caja = Precio Costo + IVA + Ganancia

Ejemplo:
IVA = 15.50 × (15 / 100) = 2.325
Precio Venta Caja = 15.50 + 2.325 + 2.50 = 20.325 ≈ 20.33
```

### Precio Unitario Sugerido
```
Precio Unitario = Precio Venta Caja / Unidades por Caja

Ejemplo:
Precio Unitario = 20.33 / 20 = 1.0165
```

### Ingresos Potenciales
```
Ingresos Cajas = Stock Cajas × Precio Venta Caja
Ingresos Sueltos = Stock Suelto × Precio Venta Unitario
Ingresos Total = Ingresos Cajas + Ingresos Sueltos

Ejemplo:
Ingresos Cajas = 5 × 20.33 = 101.65
Ingresos Sueltos = 3 × 1.02 = 3.06
Ingresos Total = 101.65 + 3.06 = 104.71
```

---

## 🔧 Comportamiento Inteligente

### Escenario 1: Editar Precio Unitario

```
Usuario ingresa:
├─ Precio Costo: 15.50
├─ IVA: 15%
├─ Ganancia: 2.50
├─ Unidades: 20
│
└─ Sistema calcula: Precio Unitario = 1.0165

Usuario edita manualmente: 1.0165 → 1.02
│
└─ Sistema RECUERDA que fue editado manualmente
   (bandera: unitPriceManuallyEdited = true)

Usuario cambia Precio Costo: 15.50 → 16.00
│
└─ Sistema NO sobrescribe el precio unitario
   (sigue siendo 1.02)
```

### Escenario 2: Cambiar Costos Base

```
Usuario edita Precio Costo: 15.50 → 16.00
│
└─ Sistema REINICIA la bandera
   (unitPriceManuallyEdited = false)

Usuario cambia Ganancia: 2.50 → 3.00
│
└─ Sistema RECALCULA automáticamente
   Nuevo Precio Unitario = 1.0325

Usuario edita manualmente: 1.0325 → 1.05
│
└─ Sistema RECUERDA nuevamente
   (bandera: unitPriceManuallyEdited = true)
```

---

## 📊 Tabla de Comparación

| Aspecto | Antes | Después |
|---------|-------|---------|
| **Stock** | Un solo campo | Cajas + Sueltos + Total |
| **Precio Costo** | Por unidad | Por caja |
| **Ganancia** | Porcentaje | Monto fijo por caja |
| **Precio Venta** | Un solo precio | Caja + Unitario |
| **Flexibilidad** | Rígida | Editable (unitario) |
| **Cálculos** | Manuales | Automáticos |
| **Precisión** | Baja | Alta (4 decimales) |
| **Venta Fraccionada** | ❌ No | ✅ Sí |

---

## 🚀 Ventajas del Sistema Nuevo

```
✅ Gestión precisa de inventario
   └─ Diferencia cajas de sueltos

✅ Precios más realistas
   └─ Ganancia por caja, no por unidad

✅ Flexibilidad en precios
   └─ Puedes redondear centavos

✅ Cálculos automáticos
   └─ Menos errores manuales

✅ Reportes más detallados
   └─ Ingresos por tipo de venta

✅ Mejor experiencia de usuario
   └─ Interfaz clara y organizada

✅ Escalable
   └─ Fácil de extender a futuro
```

---

## 📋 Checklist de Implementación

```
□ Ejecutar migración: php artisan migrate
□ Actualizar formulario create.blade.php
□ Actualizar modelo Product.php
□ Actualizar controlador InventarioController.php
□ Probar creación de producto
□ Probar edición de producto
□ Probar cálculos automáticos
□ Probar edición manual de precio unitario
□ Verificar base de datos
□ Documentar cambios
□ Capacitar al equipo
```

---

## 🎓 Guía Rápida para Usuarios

### Para Agregar un Producto

1. **Datos Básicos**
   - Ingresa código de barras, nombre, laboratorio

2. **Inventario**
   - Cajas: ¿Cuántas cajas completas tienes?
   - Unidades/Caja: ¿Cuántas pastillas trae cada caja?
   - Sueltos: ¿Cuántas pastillas sueltas tienes?
   - **Total se calcula automáticamente**

3. **Precios**
   - Costo: ¿Cuánto cuesta la caja completa?
   - IVA: ¿Qué porcentaje de IVA aplica?
   - Ganancia: ¿Cuánto quieres ganar por caja?
   - **Precios se calculan automáticamente**

4. **Ajustes Finales**
   - Si el precio unitario no te gusta, edítalo
   - Ingresa fecha de vencimiento
   - ¡Guarda!

---

## 🔍 Ejemplos Prácticos

### Ejemplo 1: Aspirina

```
Código: 7891234567890
Nombre: Aspirina 500mg
Laboratorio: Bayer

Stock:
├─ Cajas: 10
├─ Unidades/Caja: 50
├─ Sueltos: 0
└─ Total: 500 pastillas

Precios:
├─ Costo/Caja: $25.00
├─ IVA: 15%
├─ Ganancia/Caja: $5.00
├─ Precio Caja: $34.75
└─ Precio Unitario: $0.695 → redondear a $0.70

Ingresos Potenciales:
├─ Cajas: 10 × $34.75 = $347.50
├─ Sueltos: 0 × $0.70 = $0.00
└─ Total: $347.50
```

### Ejemplo 2: Ibuprofeno

```
Código: 7891234567891
Nombre: Ibuprofeno 400mg
Laboratorio: Pfizer

Stock:
├─ Cajas: 8
├─ Unidades/Caja: 30
├─ Sueltos: 15
└─ Total: 255 pastillas

Precios:
├─ Costo/Caja: $18.50
├─ IVA: 15%
├─ Ganancia/Caja: $3.50
├─ Precio Caja: $25.28
└─ Precio Unitario: $0.8427 → redondear a $0.85

Ingresos Potenciales:
├─ Cajas: 8 × $25.28 = $202.24
├─ Sueltos: 15 × $0.85 = $12.75
└─ Total: $214.99
```

---

## 📞 Soporte

Si tienes dudas sobre:
- **Cálculos**: Revisa la sección "Fórmulas Matemáticas"
- **Interfaz**: Revisa la sección "Interfaz de Usuario"
- **Código**: Revisa "VENTA_FRACCIONADA_CODIGO_REFERENCIA.md"
- **Detalles**: Revisa "VENTA_FRACCIONADA_DOCUMENTACION.md"

