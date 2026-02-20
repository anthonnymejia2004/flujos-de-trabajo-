# Requisitos: Solución Permanente CSS en Electron

## 1. Problema Identificado

### 1.1 Descripción del Problema
Cada vez que la aplicación Pharma-Sync se convierte a aplicación de escritorio (Electron), los estilos CSS se rompen o no cargan correctamente, resultando en una interfaz sin formato visual.

### 1.2 Síntomas Observados
- La aplicación funciona correctamente en navegador web
- Al ejecutar en Electron, los estilos CSS no se aplican
- El layout aparece sin formato (texto plano, sin colores, sin estructura)
- Los componentes de Tailwind CSS no se renderizan correctamente

### 1.3 Causa Raíz Probable
- **Rutas de assets incorrectas**: Vite genera rutas absolutas que no funcionan en Electron
- **CSP (Content Security Policy)**: Electron bloquea estilos inline o externos
- **Manifest de Vite**: No se está generando o cargando correctamente
- **Hot Module Replacement (HMR)**: Conflictos entre Vite dev server y Electron

## 2. Historias de Usuario

### 2.1 Como desarrollador
**Quiero** que los estilos CSS se carguen correctamente en Electron
**Para** que la aplicación mantenga su apariencia visual sin importar el entorno

**Criterios de Aceptación:**
- Los estilos Tailwind CSS se aplican correctamente en Electron
- Los colores, tipografías y layouts se ven idénticos al navegador
- No hay errores de carga de assets en la consola de Electron
- Los cambios en CSS se reflejan al recompilar

### 2.2 Como usuario final
**Quiero** que la aplicación de escritorio se vea profesional y bien diseñada
**Para** tener una experiencia de usuario consistente y agradable

**Criterios de Aceptación:**
- La interfaz se ve igual en navegador y en Electron
- Todos los botones, formularios y tablas tienen el estilo correcto
- Los colores del tema (azul, verde, rojo) se muestran correctamente
- Las tarjetas de estadísticas tienen sombras y bordes redondeados

### 2.3 Como administrador del sistema
**Quiero** que la configuración de CSS sea robusta y mantenible
**Para** evitar que se rompa en futuras actualizaciones

**Criterios de Aceptación:**
- La configuración de Vite está documentada
- Los archivos de configuración tienen comentarios explicativos
- Existe un proceso de verificación antes de compilar
- Hay un documento de troubleshooting para problemas comunes

## 3. Requisitos Funcionales

### 3.1 Carga de Assets
- **RF-1.1**: El sistema debe cargar correctamente el archivo CSS compilado por Vite
- **RF-1.2**: Las rutas de assets deben ser relativas o resolverse correctamente en Electron
- **RF-1.3**: El manifest de Vite debe generarse y leerse correctamente
- **RF-1.4**: Los assets deben servirse desde el directorio correcto en producción

### 3.2 Configuración de Vite
- **RF-2.1**: Vite debe compilar CSS con rutas compatibles con Electron
- **RF-2.2**: El build debe generar archivos en el directorio `public/build/`
- **RF-2.3**: El manifest.json debe incluir todas las referencias de CSS
- **RF-2.4**: Los source maps deben estar disponibles para debugging

### 3.3 Integración con Laravel
- **RF-3.1**: La directiva `@vite` debe funcionar correctamente en Blade
- **RF-3.2**: El helper `asset()` debe generar rutas correctas
- **RF-3.3**: El archivo `app.css` debe incluir todos los estilos de Tailwind
- **RF-3.4**: Los componentes Blade deben mantener sus clases CSS

### 3.4 Compatibilidad con Electron
- **RF-4.1**: Electron debe permitir la carga de estilos desde `file://`
- **RF-4.2**: La CSP de Electron debe permitir estilos inline si es necesario
- **RF-4.3**: El protocolo de carga debe ser compatible con assets locales
- **RF-4.4**: No debe haber conflictos entre HMR de Vite y Electron

## 4. Requisitos No Funcionales

### 4.1 Rendimiento
- **RNF-1.1**: Los estilos deben cargar en menos de 500ms
- **RNF-1.2**: El tamaño del CSS compilado no debe exceder 500KB
- **RNF-1.3**: No debe haber bloqueo de renderizado por CSS

### 4.2 Mantenibilidad
- **RNF-2.1**: La configuración debe estar centralizada en archivos de config
- **RNF-2.2**: Los cambios en CSS deben requerir solo recompilar Vite
- **RNF-2.3**: Debe existir documentación clara del proceso de build

### 4.3 Compatibilidad
- **RNF-3.1**: Debe funcionar en Windows 10/11
- **RNF-3.2**: Debe ser compatible con Electron 40+
- **RNF-3.3**: Debe funcionar con Vite 7+
- **RNF-3.4**: Debe ser compatible con Tailwind CSS 4+

### 4.4 Debugging
- **RNF-4.1**: Los errores de carga de CSS deben ser claros en consola
- **RNF-4.2**: Debe ser posible inspeccionar estilos con DevTools
- **RNF-4.3**: Los source maps deben estar disponibles en desarrollo

## 5. Restricciones

### 5.1 Técnicas
- Debe usar Vite como bundler (no Webpack)
- Debe mantener Tailwind CSS 4.x
- Debe ser compatible con Laravel 11
- No debe requerir cambios en el código PHP

### 5.2 De Negocio
- La solución debe implementarse sin afectar la versión web
- No debe requerir dependencias adicionales costosas
- Debe ser compatible con el flujo de desarrollo actual

## 6. Casos de Prueba

### 6.1 Prueba de Carga de CSS
**Dado** que la aplicación se ejecuta en Electron
**Cuando** se carga la página principal
**Entonces** todos los estilos Tailwind deben aplicarse correctamente

### 6.2 Prueba de Consistencia Visual
**Dado** que la aplicación funciona en navegador
**Cuando** se ejecuta la misma página en Electron
**Entonces** la apariencia visual debe ser idéntica

### 6.3 Prueba de Assets
**Dado** que se compila la aplicación para producción
**Cuando** se ejecuta el instalador .exe
**Entonces** todos los assets CSS deben estar incluidos y funcionar

### 6.4 Prueba de Hot Reload
**Dado** que se ejecuta en modo desarrollo
**Cuando** se modifica un archivo CSS
**Entonces** los cambios deben reflejarse sin romper la aplicación

## 7. Dependencias

### 7.1 Archivos Críticos
- `vite.config.js` - Configuración de Vite
- `resources/css/app.css` - Archivo principal de CSS
- `resources/views/layouts/app.blade.php` - Layout principal
- `electron/main.js` - Configuración de Electron
- `tailwind.config.js` - Configuración de Tailwind

### 7.2 Paquetes NPM
- vite
- @vitejs/plugin-laravel
- tailwindcss
- autoprefixer
- postcss

## 8. Riesgos

### 8.1 Riesgo Alto
- **Riesgo**: Cambios en Vite config pueden romper la versión web
- **Mitigación**: Probar ambas versiones (web y Electron) después de cada cambio

### 8.2 Riesgo Medio
- **Riesgo**: Incompatibilidad entre versiones de Vite y Tailwind
- **Mitigación**: Documentar versiones exactas que funcionan

### 8.3 Riesgo Bajo
- **Riesgo**: Problemas de caché en desarrollo
- **Mitigación**: Documentar comandos de limpieza de caché

## 9. Criterios de Éxito

La solución será exitosa cuando:

1. ✅ La aplicación se vea idéntica en navegador y Electron
2. ✅ No haya errores de carga de CSS en la consola
3. ✅ Los cambios en CSS se reflejen correctamente al recompilar
4. ✅ El instalador .exe incluya todos los assets necesarios
5. ✅ La documentación permita reproducir la solución
6. ✅ No se requieran cambios manuales después de cada build

## 10. Fuera de Alcance

- Optimización de rendimiento de CSS (más allá de lo básico)
- Implementación de temas dinámicos
- Soporte para otros frameworks CSS
- Migración a otro bundler que no sea Vite
