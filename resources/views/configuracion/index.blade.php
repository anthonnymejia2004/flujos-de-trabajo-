@extends('layouts.app')

@section('title')
    Configuración - {{ \App\Models\UserSetting::get('empresa_nombre', 'Pharma-Sync') }}
@endsection

@section('content')
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-slate-900">Configuración</h1>
        <p class="text-slate-600 mt-1">Personaliza tu sistema</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Configuración General -->
        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <h2 class="text-lg font-bold text-slate-900 mb-6">Configuración General</h2>
            <form action="{{ route('configuracion.update') }}" method="POST" class="space-y-6">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-slate-900 mb-2">Nombre de la Empresa</label>
                    <input type="text" name="empresa_nombre" value="{{ $configuraciones['empresa_nombre'] }}" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-900 mb-2">Descripción de la Empresa</label>
                    <input type="text" name="empresa_descripcion" value="{{ $configuraciones['empresa_descripcion'] }}" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <p class="text-xs text-slate-500 mt-1">Aparece debajo del nombre de la empresa</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-900 mb-2">IVA Global (%)</label>
                    <input type="number" name="iva_global" value="{{ $configuraciones['iva_global'] }}" step="0.01" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <p class="text-xs text-slate-500 mt-1">Este valor se aplicará a productos sin IVA específico</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-900 mb-2">Margen de Ganancia Global (%)</label>
                    <input type="number" name="margen_ganancia_global" value="{{ $configuraciones['margen_ganancia_global'] }}" step="0.01" min="0" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <p class="text-xs text-slate-500 mt-1">Margen predeterminado para nuevos productos (aparecerá como sugerencia)</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-900 mb-2">Moneda</label>
                    <select name="moneda" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="USD" {{ $configuraciones['moneda'] == 'USD' ? 'selected' : '' }}>USD ($)</option>
                        <option value="EUR" {{ $configuraciones['moneda'] == 'EUR' ? 'selected' : '' }}>EUR (€)</option>
                        <option value="MXN" {{ $configuraciones['moneda'] == 'MXN' ? 'selected' : '' }}>MXN ($)</option>
                        <option value="COP" {{ $configuraciones['moneda'] == 'COP' ? 'selected' : '' }}>COP ($)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-900 mb-2">Stock Mínimo de Alerta</label>
                    <input type="number" name="stock_minimo" value="{{ $configuraciones['stock_minimo'] }}" min="1" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <p class="text-xs text-slate-500 mt-1">Cantidad de cajas para alertar stock bajo</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-900 mb-2">Días de Alerta de Vencimiento</label>
                    <input type="number" name="dias_alerta_vencimiento" value="{{ $configuraciones['dias_alerta_vencimiento'] }}" min="1" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <p class="text-xs text-slate-500 mt-1">Días antes del vencimiento para mostrar alerta</p>
                </div>

                <!-- Configuración de Tema -->
                <div class="pt-6 border-t border-slate-200">
                    <h3 class="text-lg font-semibold text-slate-900 mb-4">
                        <i class="fas fa-palette mr-2 text-blue-600"></i>
                        Personalización de Tema
                    </h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-900 mb-3">Seleccionar Tema</label>
                            <select id="theme-selector" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="light" {{ $configuraciones['tema'] == 'light' ? 'selected' : '' }}>🌞 Tema Claro</option>
                                <option value="dark" {{ $configuraciones['tema'] == 'dark' ? 'selected' : '' }}>🌙 Tema Oscuro</option>
                            </select>
                            <p class="text-xs text-slate-500 mt-1">Cambia la apariencia visual del sistema</p>
                        </div>

                        <!-- Vista previa de colores -->
                        <div class="bg-slate-50 rounded-lg p-4">
                            <h4 class="text-sm font-medium text-slate-900 mb-3">Vista Previa</h4>
                            <div class="flex gap-3">
                                <div class="theme-preview-light w-10 h-10 rounded-full bg-blue-600 border-2 border-white shadow-sm cursor-pointer hover:scale-110 transition-transform" data-theme="light" title="Tema Claro"></div>
                                <div class="theme-preview-dark w-10 h-10 rounded-full bg-slate-800 border-2 border-white shadow-sm cursor-pointer hover:scale-110 transition-transform" data-theme="dark" title="Tema Oscuro"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex gap-3 pt-4 border-t border-slate-200">
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition-colors">
                        <i class="fas fa-save mr-2"></i> Guardar Configuración
                    </button>
                </div>
            </form>
        </div>

        <!-- Panel de Información -->
        <div class="space-y-6">
            <!-- Información del Sistema -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                <h3 class="font-semibold text-slate-900 mb-4">Información del Sistema</h3>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-slate-600">Versión:</span>
                        <span class="font-semibold text-slate-900">1.0.0</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-600">Laravel:</span>
                        <span class="font-semibold text-slate-900">{{ app()->version() }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-600">PHP:</span>
                        <span class="font-semibold text-slate-900">{{ PHP_VERSION }}</span>
                    </div>
                </div>
            </div>

            <!-- Acciones Rápidas -->
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-blue-500/30 p-6">
                <h3 class="font-semibold text-slate-900 dark:text-white mb-4">
                    <i class="fas fa-bolt mr-2 text-yellow-500"></i>
                    Acciones Rápidas
                </h3>
                <div class="space-y-3">
                    <!-- Exportar Datos -->
                    <a href="{{ route('configuracion.export') }}" class="w-full px-4 py-3 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg hover:from-green-600 hover:to-green-700 transition-all duration-200 text-sm font-medium flex items-center justify-center group">
                        <i class="fas fa-download mr-2 group-hover:animate-bounce"></i> 
                        Exportar Inventario (CSV)
                    </a>
                    
                    <!-- Importar Datos -->
                    <div class="relative">
                        <input type="file" id="import-file" accept=".csv,.txt" class="hidden" onchange="handleImport(this)">
                        <button onclick="document.getElementById('import-file').click()" class="w-full px-4 py-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg hover:from-blue-600 hover:to-blue-700 transition-all duration-200 text-sm font-medium flex items-center justify-center group">
                            <i class="fas fa-upload mr-2 group-hover:animate-bounce"></i> 
                            Importar Productos (CSV)
                        </button>
                    </div>
                    
                    <!-- Generar Reporte -->
                    <a href="{{ route('configuracion.report') }}" class="w-full px-4 py-3 bg-gradient-to-r from-purple-500 to-purple-600 text-white rounded-lg hover:from-purple-600 hover:to-purple-700 transition-all duration-200 text-sm font-medium flex items-center justify-center group">
                        <i class="fas fa-file-pdf mr-2 group-hover:animate-bounce"></i> 
                        Generar Reporte Completo
                    </a>
                </div>
                
                <!-- Información de ayuda -->
                <div class="mt-4 p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-500/30">
                    <h4 class="text-sm font-medium text-blue-900 dark:text-blue-300 mb-2">
                        <i class="fas fa-info-circle mr-1"></i>
                        Información de Uso
                    </h4>
                    <ul class="text-xs text-blue-700 dark:text-blue-400 space-y-1">
                        <li>• <strong>Exportar:</strong> Descarga todos los productos en formato CSV</li>
                        <li>• <strong>Importar:</strong> Sube productos desde archivo CSV</li>
                        <li>• <strong>Reporte:</strong> Genera análisis completo en HTML</li>
                    </ul>
                    
                    <div class="mt-3 pt-2 border-t border-blue-200 dark:border-blue-500/30">
                        <a href="{{ asset('ejemplo_importacion.csv') }}" download class="text-xs text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-200 underline">
                            <i class="fas fa-download mr-1"></i>
                            Descargar archivo de ejemplo para importar
                        </a>
                    </div>
                </div>
            </div>

            <!-- Formulario oculto para importar -->
            <form id="import-form" action="{{ route('configuracion.import') }}" method="POST" enctype="multipart/form-data" class="hidden">
                @csrf
                <input type="file" name="import_file" id="import-file-input">
            </form>
        </div>
    </div>

    <script>
        // Funcionalidad adicional para vista previa de temas
        document.addEventListener('DOMContentLoaded', function() {
            // Manejar clics en la vista previa de colores
            document.querySelectorAll('[data-theme]').forEach(preview => {
                preview.addEventListener('click', function() {
                    const theme = this.dataset.theme;
                    const selector = document.getElementById('theme-selector');
                    selector.value = theme;
                    
                    // Disparar evento change para aplicar el tema
                    selector.dispatchEvent(new Event('change'));
                    
                    // Actualizar indicador visual
                    updatePreviewSelection(theme);
                });
            });

            // Función para actualizar la selección visual
            function updatePreviewSelection(selectedTheme) {
                document.querySelectorAll('[data-theme]').forEach(preview => {
                    preview.classList.remove('ring-2', 'ring-blue-500', 'ring-offset-2');
                    if (preview.dataset.theme === selectedTheme) {
                        preview.classList.add('ring-2', 'ring-blue-500', 'ring-offset-2');
                    }
                });
            }

            // Escuchar cambios de tema para actualizar la vista previa
            window.addEventListener('themeChanged', function(e) {
                updatePreviewSelection(e.detail.theme);
            });

            // Inicializar selección visual basada en tema actual
            if (window.themeManager) {
                updatePreviewSelection(window.themeManager.getCurrentTheme());
            }
        });

        // Función para manejar la importación de archivos
        function handleImport(input) {
            const file = input.files[0];
            if (!file) return;
            
            // Validar tipo de archivo
            const allowedTypes = ['text/csv', 'text/plain', 'application/csv'];
            if (!allowedTypes.includes(file.type) && !file.name.toLowerCase().endsWith('.csv')) {
                alert('Por favor selecciona un archivo CSV válido.');
                return;
            }
            
            // Validar tamaño (máximo 2MB)
            if (file.size > 2 * 1024 * 1024) {
                alert('El archivo es demasiado grande. Máximo 2MB permitido.');
                return;
            }
            
            // Confirmar importación
            if (confirm(`¿Estás seguro de que quieres importar "${file.name}"?\n\nEsto agregará nuevos productos al inventario.`)) {
                // Copiar archivo al formulario oculto y enviarlo
                const form = document.getElementById('import-form');
                const hiddenInput = document.getElementById('import-file-input');
                
                // Crear un nuevo input file con el archivo seleccionado
                const dt = new DataTransfer();
                dt.items.add(file);
                hiddenInput.files = dt.files;
                
                // Mostrar indicador de carga
                const button = document.querySelector('button[onclick*="import-file"]');
                const originalText = button.innerHTML;
                button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Importando...';
                button.disabled = true;
                
                // Enviar formulario
                form.submit();
            }
            
            // Limpiar input
            input.value = '';
        }

        // Función para mostrar notificaciones de éxito/error
        function showNotification(message, type = 'success') {
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 px-6 py-4 rounded-lg shadow-lg z-50 ${
                type === 'success' ? 'bg-green-500' : 'bg-red-500'
            } text-white`;
            notification.innerHTML = `
                <div class="flex items-center">
                    <i class="fas fa-${type === 'success' ? 'check-circle' : 'times-circle'} mr-2"></i>
                    <span>${message}</span>
                </div>
            `;
            
            document.body.appendChild(notification);
            
            // Auto-remove after 5 seconds
            setTimeout(() => {
                notification.style.opacity = '0';
                notification.style.transform = 'translateX(100%)';
                setTimeout(() => notification.remove(), 300);
            }, 5000);
        }

        // Mostrar notificaciones flash si existen
        @if(session('success'))
            showNotification('{{ session('success') }}', 'success');
        @endif
        
        @if(session('error'))
            showNotification('{{ session('error') }}', 'error');
        @endif
    </script>
@endsection
