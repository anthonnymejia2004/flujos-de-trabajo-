// Sistema de gestión de temas - Clean Medical Tech
class ThemeManager {
    constructor() {
        this.themes = {
            light: {
                name: 'Claro',
                class: '',
                colors: {
                    primary: 'pharma-primary',
                    secondary: 'slate'
                }
            },
            dark: {
                name: 'Oscuro',
                class: 'dark',
                colors: {
                    primary: 'pharma-primary',
                    secondary: 'slate'
                }
            }
        };
        
        this.currentTheme = this.getStoredTheme() || 'light';
        this.init();
    }

    init() {
        this.applyTheme(this.currentTheme);
        this.setupEventListeners();
    }

    getStoredTheme() {
        return localStorage.getItem('pharma-sync-theme');
    }

    storeTheme(theme) {
        localStorage.setItem('pharma-sync-theme', theme);
    }

    applyTheme(themeKey) {
        const theme = this.themes[themeKey];
        if (!theme) return;

        // Remover todas las clases de tema
        document.documentElement.classList.remove('dark');
        Object.values(this.themes).forEach(t => {
            if (t.class) {
                document.documentElement.classList.remove(t.class);
            }
        });

        // Aplicar nueva clase de tema
        if (theme.class) {
            document.documentElement.classList.add(theme.class);
        }

        // Forzar re-renderizado para asegurar que los cambios se apliquen
        document.body.style.display = 'none';
        document.body.offsetHeight; // Trigger reflow
        document.body.style.display = '';

        this.currentTheme = themeKey;
        this.storeTheme(themeKey);
        
        // Sincronizar con el servidor
        this.syncWithServer(themeKey);
        
        // Actualizar selector si existe
        const themeSelector = document.getElementById('theme-selector');
        if (themeSelector) {
            themeSelector.value = themeKey;
        }

        // Disparar evento personalizado
        window.dispatchEvent(new CustomEvent('themeChanged', { 
            detail: { theme: themeKey, themeData: theme } 
        }));
    }

    syncWithServer(themeKey) {
        // Enviar tema al servidor para persistencia
        fetch('/configuracion/theme', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ theme: themeKey })
        }).catch(error => {
            console.warn('No se pudo sincronizar el tema con el servidor:', error);
        });
    }

    setupEventListeners() {
        // Escuchar cambios en el selector de tema
        document.addEventListener('change', (e) => {
            if (e.target.id === 'theme-selector') {
                this.changeThemeManually(e.target.value);
            }
        });

        // Escuchar clics en vista previa de colores
        document.addEventListener('click', (e) => {
            if (e.target.dataset.theme) {
                this.changeThemeManually(e.target.dataset.theme);
            }
        });
    }

    changeThemeManually(themeKey) {
        const theme = this.themes[themeKey];
        if (!theme) return;

        // Aplicar el tema
        this.applyTheme(themeKey);
        
        // Mostrar notificación solo para cambios manuales
        this.showThemeChangeNotification(theme.name);
    }

    getCurrentTheme() {
        return this.currentTheme;
    }

    getThemes() {
        return this.themes;
    }

    showThemeChangeNotification(themeName) {
        // Crear notificación temporal
        const notification = document.createElement('div');
        notification.className = 'fixed top-20 right-4 bg-blue-600 text-white px-4 py-2 rounded-lg shadow-lg z-50 transition-all duration-300 transform translate-x-full';
        notification.innerHTML = `
            <div class="flex items-center gap-2">
                <i class="fas fa-palette"></i>
                <span>Tema cambiado a: ${themeName}</span>
            </div>
        `;
        
        document.body.appendChild(notification);
        
        // Animar entrada
        setTimeout(() => {
            notification.classList.remove('translate-x-full');
        }, 100);
        
        // Remover después de 3 segundos
        setTimeout(() => {
            notification.classList.add('translate-x-full');
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.parentNode.removeChild(notification);
                }
            }, 300);
        }, 3000);
    }
}

// Inicializar el gestor de temas cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', () => {
    window.themeManager = new ThemeManager();
});

// Exportar para uso en otros módulos
export default ThemeManager;