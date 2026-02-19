/**
 * Inicialización de Electron para Pharma-Sync
 * Este archivo configura la integración con Electron
 */

// Configuración de Electron
export const electronConfig = {
  isDesktop: typeof window !== 'undefined' && window.electron !== undefined,
};

/**
 * Inicializar Electron
 */
export async function initElectron() {
  if (!electronConfig.isDesktop) {
    console.log('No se está ejecutando en Electron');
    return;
  }

  console.log('Inicializando Electron...');
  console.log('Plataforma:', window.electron.platform);
  console.log('Versión de Electron:', window.electron.version);
  console.log('Electron inicializado correctamente');
}

/**
 * Invocar comando (placeholder para compatibilidad)
 */
export async function invokeCommand(command, payload = {}) {
  if (!electronConfig.isDesktop) {
    console.warn(`Comando no disponible: ${command}`);
    return null;
  }

  console.log(`Comando: ${command}`, payload);
  return null;
}

/**
 * Escuchar evento (placeholder para compatibilidad)
 */
export async function onTauriEvent(event, callback) {
  if (!electronConfig.isDesktop) {
    return;
  }

  console.log(`Escuchando evento: ${event}`);
}

// Inicializar cuando el DOM esté listo
if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', initElectron);
} else {
  initElectron();
}
