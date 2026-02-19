# 📖 Cómo Usar la Presentación Automática

## 🎯 ¿Qué es?

La **Presentación Automática** es una nueva funcionalidad que genera automáticamente el nombre completo de presentación de tus productos, eliminando la necesidad de escribirlo manualmente.

---

## 🚀 Inicio Rápido

### Paso 1: Ir a Agregar Producto
1. Navega a **Inventario** → **Agregar Producto**
2. Verás el nuevo formulario mejorado

### Paso 2: Llenar los Datos Básicos
```
1. Nombre del Producto: "Paracetamol 500mg"
2. Tipo de Envase: Selecciona "Caja" del menú desplegable
3. Unidades por Caja: "20"
```

### Paso 3: Ver la Vista Previa
- Automáticamente verás: **"Paracetamol 500mg (Caja x 20)"**
- Este es el nombre que se guardará en el sistema

### Paso 4: Completar y Guardar
- Llena los demás campos (precio, stock, etc.)
- Haz clic en **"Guardar Producto"**
- ¡Listo! Tu producto está guardado con la presentación correcta

---

## 📝 Tipos de Envase Disponibles

Puedes seleccionar entre estos tipos:

| Tipo | Cuándo Usar | Ejemplo |
|------|-------------|---------|
| **Caja** | Productos en cajas cerradas | Paracetamol (Caja x 20) |
| **Sobre** | Productos en sobres individuales | Suero Oral (Sobre x 12) |
| **Ampolla** | Inyectables en ampollas | Vitamina B12 (Ampolla x 5) |
| **Frasco** | Líquidos o pastillas en frasco | Jarabe (Frasco x 1) |
| **Blíster** | Pastillas en blíster | Ibuprofeno (Blíster x 10) |
| **Tubo** | Cremas o pomadas en tubo | Crema (Tubo x 1) |
| **Pomo** | Pomadas en pomo | Pomada (Pomo x 1) |

---

## 💡 Ejemplos Prácticos

### Ejemplo 1: Medicamento en Caja
```
✏️ Llenar:
- Nombre: Paracetamol 500mg
- Tipo: Caja
- Unidades: 20

✅ Resultado:
- Presentación: "Paracetamol 500mg (Caja x 20)"
```

### Ejemplo 2: Jarabe
```
✏️ Llenar:
- Nombre: Jarabe para la Tos
- Tipo: Frasco
- Unidades: 1

✅ Resultado:
- Presentación: "Jarabe para la Tos (Frasco x 1)"
```

### Ejemplo 3: Inyectable
```
✏️ Llenar:
- Nombre: Insulina Rápida
- Tipo: Ampolla
- Unidades: 5

✅ Resultado:
- Presentación: "Insulina Rápida (Ampolla x 5)"
```

---

## 🔄 Editar Productos Existentes

### ¿Qué pasa con mis productos antiguos?

Los productos que ya tenías **NO se modifican automáticamente**. Mantienen su presentación original hasta que los edites.

### Para actualizar un producto:

1. Ve a **Inventario** → Busca el producto
2. Haz clic en **"Editar"**
3. Verás el nuevo campo **"Tipo de Envase"**
4. Selecciona el tipo correcto
5. La presentación se actualizará automáticamente
6. Guarda los cambios

---

## ❓ Preguntas Frecuentes

### ¿Puedo cambiar la presentación manualmente?
**No.** La presentación se genera automáticamente para mantener consistencia. Si necesitas un formato diferente, contacta al administrador del sistema.

### ¿Qué pasa si cambio el tipo de envase?
La presentación se actualiza inmediatamente en la vista previa. Por ejemplo:
- Cambias de "Caja" a "Blíster"
- La presentación cambia de "Producto (Caja x 20)" a "Producto (Blíster x 20)"

### ¿Puedo agregar más tipos de envase?
Sí, pero debe hacerlo un desarrollador. Los tipos actuales cubren la mayoría de casos de uso en farmacias.

### ¿Qué pasa si me equivoco en las unidades?
Puedes editar el producto después y cambiar las unidades. La presentación se regenerará automáticamente.

---

## ⚠️ Consejos y Mejores Prácticas

### ✅ Hacer:
- Selecciona el tipo de envase correcto desde el inicio
- Verifica la vista previa antes de guardar
- Usa nombres descriptivos para tus productos
- Mantén consistencia en los nombres (ej: siempre "500mg", no "500 mg")

### ❌ Evitar:
- No intentes incluir el tipo de envase en el nombre del producto
  - ❌ Mal: "Paracetamol Caja"
  - ✅ Bien: "Paracetamol"
- No incluyas la cantidad en el nombre
  - ❌ Mal: "Paracetamol x 20"
  - ✅ Bien: "Paracetamol"

---

## 🎨 Vista Previa en Tiempo Real

La vista previa se actualiza automáticamente cuando:
- ✏️ Escribes el nombre del producto
- 🔽 Cambias el tipo de envase
- 🔢 Modificas las unidades por caja

**Ejemplo en vivo:**
```
Escribes: "Para"
Preview: "Para (Caja x 0)"

Escribes: "Paracetamol"
Preview: "Paracetamol (Caja x 0)"

Cambias unidades a: "20"
Preview: "Paracetamol (Caja x 20)"

Cambias tipo a: "Blíster"
Preview: "Paracetamol (Blíster x 20)"
```

---

## 🆘 Solución de Problemas

### La vista previa no se actualiza
1. Verifica que hayas llenado el nombre del producto
2. Asegúrate de que las unidades sean mayor a 0
3. Recarga la página si es necesario

### No veo el campo "Tipo de Envase"
1. Verifica que estés usando la versión actualizada del sistema
2. Limpia el caché del navegador (Ctrl + F5)
3. Contacta al administrador si el problema persiste

### La presentación se ve diferente en la lista
Esto es normal si editaste un producto antiguo. La presentación se actualiza solo cuando guardas los cambios.

---

## 📞 Soporte

Si tienes problemas o sugerencias:
1. Contacta al administrador del sistema
2. Reporta el problema con capturas de pantalla
3. Indica qué producto estabas intentando crear/editar

---

**Última actualización:** 3 de Febrero, 2026  
**Versión:** 1.0
