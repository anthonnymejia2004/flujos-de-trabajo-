# Tasks: Solución Permanente CSS en Electron

## 1. Configuración de Vite

- [ ] 1.1 Actualizar vite.config.js con base relativa
  - Agregar `base: './'` en la configuración
  - Configurar `outDir: 'public/build'`
  - Habilitar `manifest: true`
  - Configurar servidor de desarrollo

- [ ] 1.2 Verificar configuración de Tailwind
  - Revisar paths en `content` de tailwind.config.js
  - Asegurar que incluye todos los archivos Blade
  - Verificar que los colores personalizados están definidos

- [ ] 1.3 Limpiar y recompilar assets
  - Ejecutar `npm run clean` o eliminar `public/build`
  - Ejecutar `npm run build`
  - Verificar que se genera `public/build/manifest.json`
  - Verificar que se genera CSS en `public/build/assets/`

## 2. Configuración de Electron

- [ ] 2.1 Actualizar electron/main.js con CSP
  - Configurar Content-Security-Policy para permitir estilos
  - Permitir `style-src 'unsafe-inline'`
  - Permitir carga desde localhost y 127.0.0.1
  - Agregar logs de debugging

- [ ] 2.2 Configurar protocolo personalizado
  - Registrar protocolo `app://` para assets
  - Configurar resolución de rutas relativas
  - Agregar manejo de errores

- [ ] 2.3 Configurar variables de entorno
  - Pasar `APP_ENV=production` al servidor PHP
  - Desactivar `VITE_DEV_SERVER_URL` en producción
  - Configurar detección de modo desarrollo

## 3. Verificación de Laravel

- [ ] 3.1 Revisar layout principal
  - Verificar que `app.blade.php` usa `@vite` correctamente
  - Asegurar que incluye `resources/css/app.css`
  - Verificar que el meta CSRF está presente

- [ ] 3.2 Verificar archivo CSS principal
  - Confirmar que `resources/css/app.css` tiene directivas Tailwind
  - Verificar que tiene `@tailwind base/components/utilities`
  - Agregar estilos personalizados si faltan

- [ ] 3.3 Probar en navegador web
  - Ejecutar `php artisan serve`
  - Abrir http://127.0.0.1:8000
  - Verificar que todos los estilos cargan correctamente
  - Revisar consola del navegador (no debe haber errores)

## 4. Integración y Pruebas

- [ ] 4.1 Probar en Electron modo desarrollo
  - Ejecutar `npm run build` primero
  - Ejecutar `npm run electron:dev`
  - Verificar que la ventana abre correctamente
  - Verificar que los estilos se aplican

- [ ] 4.2 Verificar consola de Electron
  - Abrir DevTools en Electron (Ctrl+Shift+I)
  - Revisar tab Console (no debe haber errores de CSS)
  - Revisar tab Network (verificar carga de assets)
  - Revisar tab Elements (inspeccionar estilos aplicados)

- [ ] 4.3 Probar todas las páginas principales
  - Dashboard
  - Inventario (lista, crear, editar)
  - Ventas
  - Reportes
  - Configuración
  - Verificar que todas tienen estilos correctos

## 5. Build de Producción

- [ ] 5.1 Crear build de producción
  - Ejecutar `npm run build`
  - Verificar que no hay errores
  - Verificar tamaño del CSS compilado (< 500KB)

- [ ] 5.2 Crear instalador Electron
  - Ejecutar `npm run electron:build`
  - Esperar a que termine (puede tardar 5-10 min)
  - Verificar que se crea `out/Pharma-Sync-Setup-1.0.0.exe`

- [ ] 5.3 Probar instalador
  - Ejecutar el instalador .exe
  - Instalar en directorio de prueba
  - Abrir la aplicación instalada
  - Verificar que los estilos funcionan correctamente

## 6. Documentación

- [ ] 6.1 Crear guía de troubleshooting
  - Documentar errores comunes y soluciones
  - Agregar comandos de debugging
  - Incluir checklist de verificación

- [ ] 6.2 Documentar proceso de build
  - Pasos para desarrollo
  - Pasos para producción
  - Requisitos y dependencias

- [ ] 6.3 Crear scripts de verificación
  - Script para verificar manifest.json
  - Script para verificar assets compilados
  - Script para limpiar caché

## 7. Testing de Propiedades

- [ ] 7.1 Test: Consistencia Visual
  - Capturar screenshots de páginas en navegador
  - Capturar screenshots de mismas páginas en Electron
  - Comparar visualmente (deben ser idénticas)

- [ ] 7.2 Test: Carga de Assets
  - Verificar que manifest.json existe
  - Verificar que todos los assets referenciados existen
  - Verificar que los assets son accesibles

- [ ] 7.3 Test: No Errores de Consola
  - Abrir cada página en Electron
  - Revisar consola de DevTools
  - Verificar que no hay errores de CSS/assets

- [ ] 7.4 Test: Rutas Relativas
  - Inspeccionar manifest.json
  - Verificar que las rutas son relativas
  - Verificar que no hay rutas absolutas con '/'

## 8. Limpieza y Optimización

- [ ] 8.1 Limpiar archivos innecesarios
  - Eliminar carpeta `src-tauri/` si aún existe
  - Eliminar archivos de configuración de Tauri
  - Limpiar node_modules/.vite

- [ ] 8.2 Optimizar configuración
  - Revisar que no hay configuraciones duplicadas
  - Eliminar dependencias no usadas
  - Actualizar .gitignore si es necesario

- [ ] 8.3 Verificar package.json
  - Revisar que los scripts son correctos
  - Verificar que las dependencias están actualizadas
  - Eliminar referencias a Tauri

## 9. Validación Final

- [ ] 9.1 Checklist completo
  - Ejecutar checklist de pre-build
  - Ejecutar checklist de post-build
  - Verificar que todos los items pasan

- [ ] 9.2 Prueba de usuario final
  - Instalar aplicación en máquina limpia
  - Probar flujo completo de usuario
  - Verificar que todo funciona sin errores

- [ ] 9.3 Documentar resultado
  - Crear documento de resumen
  - Incluir screenshots de antes/después
  - Documentar lecciones aprendidas

## Notas de Implementación

### Orden de Ejecución
Las tareas deben ejecutarse en orden secuencial por sección (1 → 2 → 3 → etc.)

### Puntos de Verificación
Después de cada sección, verificar que todo funciona antes de continuar.

### Rollback
Si algo falla, revertir cambios de esa sección y revisar el diseño.

### Tiempo Estimado
- Sección 1-3: 30 minutos
- Sección 4-5: 30 minutos
- Sección 6-9: 30 minutos
- **Total: ~90 minutos**
