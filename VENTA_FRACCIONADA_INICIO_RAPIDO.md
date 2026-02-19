# Inicio Rápido: Sistema de Venta Fraccionada

## ⚡ En 5 Minutos

### ¿Qué es?
Sistema que permite vender productos en **cajas completas** y **unidades sueltas** (pastillas individuales).

### ¿Qué cambió?

**Antes:**
```
Stock: [100]  ← Un solo número
Precio: [20]  ← Un solo precio
```

**Después:**
```
Stock Cajas: [5]
Unidades/Caja: [20]
Stock Suelto: [0]
Stock Total: [100] ← Calculado automáticamente

Precio Caja: [20.33] ← Calculado automáticamente
Precio Unitario: [1.02] ← Editable
```

---

## 🚀 Instalar (5 pasos)

### 1. Ejecutar Migración
```bash
php artisan migrate
```

### 2. Actualizar Modelo
Abre `app/Models/Product.php` y copia el código de:
[VENTA_FRACCIONADA_CODIGO_REFERENCIA.md](VENTA_FRACCIONADA_CODIGO_REFERENCIA.md) Sección 3

### 3. Actualizar Controlador
Abre `app/Http/Controllers/InventarioController.php` y copia los métodos `store()` y `update()` de:
[VENTA_FRACCIONADA_CODIGO_REFERENCIA.md](VENTA_FRACCIONADA_CODIGO_REFERENCIA.md) Secciones 4-5

### 4. Actualizar Formulario
Abre `resources/views/inventario/create.blade.php` y reemplaza todo el contenido con:
[VENTA_FRACCIONADA_CODIGO_REFERENCIA.md](VENTA_FRACCIONADA_CODIGO_REFERENCIA.md) Secciones 1-2

### 5. Probar
- Ve a `/inventario/create`
- Completa el formulario
- Verifica que los cálculos funcionen
- ¡Listo!

---

## 📊 Ejemplo Práctico

```
Ingreso:
├─ Stock Cajas: 5
├─ Unidades/Caja: 20
├─ Stock Suelto: 3
├─ Precio Costo: $15.50
├─ IVA: 15%
└─ Ganancia: $2.50

Sistema calcula:
├─ Stock Total: (5 × 20) + 3 = 103 unidades
├─ IVA: $15.50 × 15% = $2.325
├─ Precio Caja: $15.50 + $2.325 + $2.50 = $20.33
└─ Precio Unitario: $20.33 ÷ 20 = $1.0165 → $1.02
```

---

## 🎯 Características Clave

✅ **Stock Fraccionado**
- Cajas completas + unidades sueltas
- Total calculado automáticamente

✅ **Precios Inteligentes**
- Precio de caja calculado
- Precio unitario sugerido
- Precio unitario editable (para redondear)

✅ **Cálculos en Tiempo Real**
- Mientras escribes, se actualizan
- Sin necesidad de guardar

✅ **Flexible**
- Si editas precio unitario, no se sobrescribe
- Si cambias costos, precio unitario se recalcula

---

## 📚 Documentación Completa

| Documento | Para Qué |
|-----------|----------|
| [VENTA_FRACCIONADA_INDICE.md](VENTA_FRACCIONADA_INDICE.md) | Índice de toda la documentación |
| [VENTA_FRACCIONADA_RESUMEN_VISUAL.md](VENTA_FRACCIONADA_RESUMEN_VISUAL.md) | Entender visualmente |
| [VENTA_FRACCIONADA_INSTALACION.md](VENTA_FRACCIONADA_INSTALACION.md) | Instalar paso a paso |
| [VENTA_FRACCIONADA_DOCUMENTACION.md](VENTA_FRACCIONADA_DOCUMENTACION.md) | Documentación completa |
| [VENTA_FRACCIONADA_CODIGO_REFERENCIA.md](VENTA_FRACCIONADA_CODIGO_REFERENCIA.md) | Código para copiar/pegar |
| [VENTA_FRACCIONADA_RESUMEN_IMPLEMENTACION.md](VENTA_FRACCIONADA_RESUMEN_IMPLEMENTACION.md) | Resumen ejecutivo |

---

## ❓ Preguntas Frecuentes

**P: ¿Dónde está el código?**  
R: En [VENTA_FRACCIONADA_CODIGO_REFERENCIA.md](VENTA_FRACCIONADA_CODIGO_REFERENCIA.md)

**P: ¿Cómo instalo?**  
R: Sigue los 5 pasos arriba o lee [VENTA_FRACCIONADA_INSTALACION.md](VENTA_FRACCIONADA_INSTALACION.md)

**P: ¿Qué hago si algo falla?**  
R: Revisa [VENTA_FRACCIONADA_INSTALACION.md](VENTA_FRACCIONADA_INSTALACION.md) Sección 8

**P: ¿Cómo funciona?**  
R: Lee [VENTA_FRACCIONADA_RESUMEN_VISUAL.md](VENTA_FRACCIONADA_RESUMEN_VISUAL.md)

---

## ✅ Checklist

```
□ Ejecuté: php artisan migrate
□ Actualicé: app/Models/Product.php
□ Actualicé: app/Http/Controllers/InventarioController.php
□ Actualicé: resources/views/inventario/create.blade.php
□ Probé: /inventario/create
□ Funcionan los cálculos
□ Puedo editar precio unitario
□ ¡Listo para usar!
```

---

## 🎉 ¡Listo!

Tu sistema de venta fraccionada está instalado.

**Próximo paso:** Crea un producto de prueba y verifica que todo funcione.

---

**Versión:** 1.0  
**Fecha:** 3 de Febrero de 2026  
**Estado:** ✅ Completado

