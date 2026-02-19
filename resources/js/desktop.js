/**
 * Pharma-Sync Desktop Application
 * Módulo para manejar funcionalidades de escritorio
 */

class DesktopApp {
    constructor() {
        this.config = null;
        this.appInfo = null;
        this.isDesktopMode = this.detectDesktopMode();
    }

    /**
     * Detectar si se está ejecutando en modo escritorio
     */
    detectDesktopMode() {
        return window.location.hostname === 'localhost' && 
               window.location.port === '8000' &&
               localStorage.getItem('desktop_mode') === 'true';
    }

    /**
     * Inicializar la aplicación de escritorio
     */
    async init() {
        if (!this.isDesktopMode) return;

        try {
            await this.loadConfig();
            await this.loadAppInfo();
            this.setupEventListeners();
            this.setupKeyboardShortcuts();
            this.logEvent('app_initialized', { timestamp: new Date() });
        } catch (error) {
            console.error('Error inicializando aplicación de escritorio:', error);
        }
    }

    /**
     * Cargar configuración de la aplicación
     */
    async loadConfig() {
        try {
            const response = await fetch('/api/desktop/config');
            this.config = await response.json();
            console.log('Configuración cargada:', this.config);
        } catch (error) {
            console.error('Error cargando configuración:', error);
        }
    }

    /**
     * Cargar información de la aplicación
     */
    async loadAppInfo() {
        try {
            const response = await fetch('/api/desktop/info');
            this.appInfo = await response.json();
            console.log('Información de la aplicación:', this.appInfo);
        } catch (error) {
            console.error('Error cargando información:', error);
        }
    }

    /**
     * Configurar listeners de eventos
     */
    setupEventListeners() {
        // Evento cuando la ventana obtiene el foco
        window.addEventListener('focus', () => {
            this.logEvent('window_focused');
        });

        // Evento cuando la ventana pierde el foco
        window.addEventListener('blur', () => {
            this.logEvent('window_blurred');
        });

        // Evento antes de cerrar la ventana
        window.addEventListener('beforeunload', () => {
            this.logEvent('window_closing');
        });
    }

    /**
     * Configurar atajos de teclado
     */
    setupKeyboardShortcuts() {
        document.addEventListener('keydown', (e) => {
            // Ctrl+N: Nuevo Producto
            if (e.ctrlKey && e.key === 'n') {
                e.preventDefault();
                window.location.href = '/inventario/create';
            }

            // Ctrl+Shift+N: Nueva Venta
            if (e.ctrlKey && e.shiftKey && e.key === 'N') {
                e.preventDefault();
                window.location.href = '/ventas';
            }

            // Ctrl+S: Guardar (enviar formulario)
            if (e.ctrlKey && e.key === 's') {
                e.preventDefault();
                const form = document.querySelector('form');
                if (form) form.submit();
            }

            // Ctrl+Q: Salir
            if (e.ctrlKey && e.key === 'q') {
                e.preventDefault();
                if (confirm('¿Deseas salir de Pharma-Sync?')) {
                    window.close();
                }
            }

            // Ctrl+,: Configuración
            if (e.ctrlKey && e.key === ',') {
                e.preventDefault();
                window.location.href = '/configuracion';
            }

            // Ctrl+I: Inventario
            if (e.ctrlKey && e.key === 'i') {
                e.preventDefault();
                window.location.href = '/inventario';
            }

            // Ctrl+V: Ventas
            if (e.ctrlKey && e.key === 'v') {
                e.preventDefault();
                window.location.href = '/ventas';
            }

            // Ctrl+R: Reportes
            if (e.ctrlKey && e.key === 'r') {
                e.preventDefault();
                window.location.href = '/reportes';
            }
        });
    }

    /**
     * Registrar evento en el servidor
     */
    async logEvent(event, data = {}) {
        try {
            await fetch('/api/desktop/log-event', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                },
                body: JSON.stringify({ event, data }),
            });
        } catch (error) {
            console.error('Error registrando evento:', error);
        }
    }

    /**
     * Obtener estado de la aplicación
     */
    async getStatus() {
        try {
            const response = await fetch('/api/desktop/status');
            return await response.json();
        } catch (error) {
            console.error('Error obteniendo estado:', error);
            return null;
        }
    }

    /**
     * Hacer backup de la base de datos
     */
    async backup() {
        try {
            const response = await fetch('/api/desktop/backup', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                },
            });
            const result = await response.json();
            
            if (result.success) {
                this.showNotification('✅ Backup Creado', result.message);
                this.logEvent('backup_created', { file: result.file });
            } else {
                this.showNotification('❌ Error', result.message);
            }
            
            return result;
        } catch (error) {
            console.error('Error haciendo backup:', error);
            this.showNotification('❌ Error', 'No se pudo crear el backup');
            return null;
        }
    }

    /**
     * Restaurar backup de la base de datos
     */
    async restore(file) {
        try {
            const response = await fetch('/api/desktop/restore', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                },
                body: JSON.stringify({ file }),
            });
            const result = await response.json();
            
            if (result.success) {
                this.showNotification('✅ Backup Restaurado', result.message);
                this.logEvent('backup_restored', { file });
            } else {
                this.showNotification('❌ Error', result.message);
            }
            
            return result;
        } catch (error) {
            console.error('Error restaurando backup:', error);
            this.showNotification('❌ Error', 'No se pudo restaurar el backup');
            return null;
        }
    }

    /**
     * Obtener lista de backups
     */
    async listBackups() {
        try {
            const response = await fetch('/api/desktop/backups');
            return await response.json();
        } catch (error) {
            console.error('Error listando backups:', error);
            return { backups: [] };
        }
    }

    /**
     * Mostrar notificación
     */
    showNotification(title, message, duration = 5000) {
        // Crear elemento de notificación
        const notification = document.createElement('div');
        notification.className = 'fixed bottom-4 right-4 bg-white dark:bg-slate-800 rounded-lg shadow-lg p-4 max-w-sm z-50';
        notification.innerHTML = `
            <div class="font-semibold text-slate-900 dark:text-white">${title}</div>
            <div class="text-sm text-slate-600 dark:text-slate-400">${message}</div>
        `;

        document.body.appendChild(notification);

        // Remover después del tiempo especificado
        setTimeout(() => {
            notification.remove();
        }, duration);
    }

    /**
     * Obtener información de la aplicación
     */
    getAppInfo() {
        return this.appInfo;
    }

    /**
     * Obtener configuración
     */
    getConfig() {
        return this.config;
    }
}

// Inicializar la aplicación de escritorio cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', () => {
    window.desktopApp = new DesktopApp();
    window.desktopApp.init();
});

// Exportar para uso en otros módulos
if (typeof module !== 'undefined' && module.exports) {
    module.exports = DesktopApp;
}
