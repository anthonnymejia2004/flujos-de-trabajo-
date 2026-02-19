<!DOCTYPE html>
<html lang="es" class="{{ \App\Models\UserSetting::get('tema', 'light') === 'dark' ? 'dark' : '' }} {{ \App\Models\UserSetting::get('tema', 'light') !== 'light' && \App\Models\UserSetting::get('tema', 'light') !== 'dark' ? 'theme-' . str_replace('_', '-', \App\Models\UserSetting::get('tema', 'light')) : '' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', \App\Models\UserSetting::get('empresa_nombre', 'Pharma-Sync'))</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Estilos para el sidebar retráctil */
        #sidebar {
            width: 256px;
            transition: width 0.3s ease-in-out;
            top: 64px; /* Altura de la barra superior */
            height: calc(100vh - 64px);
        }
        
        #sidebar.collapsed {
            width: 80px;
        }
        
        #sidebar.collapsed .sidebar-text {
            display: none;
        }
        
        #main-content {
            margin-left: 256px;
            transition: margin-left 0.3s ease-in-out;
            padding-top: 0;
        }
        
        #main-content.expanded {
            margin-left: 80px;
        }
        
        body {
            padding-top: 64px;
        }
    </style>
</head>
<body class="bg-slate-50">
    <!-- BARRA SUPERIOR GLOBAL -->
    <header class="header-modern fixed top-0 left-0 right-0 z-50">
        <div class="px-4 lg:px-8 py-3 flex items-center justify-between">
            <!-- Logo y Botón -->
            <div class="flex items-center gap-4">
                <!-- Botón para colapsar/expandir sidebar -->
                <button onclick="toggleSidebar()" class="text-slate-600 dark:text-slate-300 hover:text-blue-600 dark:hover:text-blue-400 transition-all transform hover:scale-110 focus:outline-none">
                    <i id="sidebar-icon" class="fas fa-bars text-xl"></i>
                </button>
                
                <!-- Logo y Nombre -->
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-600 to-purple-600 rounded-xl flex items-center justify-center shadow-lg transform hover:scale-110 transition-all duration-300">
                        <i class="fas fa-pills text-white text-lg pharmacy-pulse"></i>
                    </div>
                    <div class="hidden md:block">
                        <h1 class="text-lg font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">{{ \App\Models\UserSetting::get('empresa_nombre', 'Pharma-Sync') }}</h1>
                        <p class="text-xs text-slate-500 dark:text-slate-400">{{ \App\Models\UserSetting::get('empresa_descripcion', 'Sistema de Farmacia') }}</p>
                    </div>
                </div>
            </div>

            <!-- Buscador -->
            <div class="flex-1 max-w-md">
                <div class="input-group">
                    <i class="input-icon fas fa-search"></i>
                    <input 
                        type="text" 
                        placeholder="Buscar productos, reportes..." 
                        class="input-modern input-with-icon text-sm"
                    >
                </div>
            </div>

            <!-- Menú de Perfil -->
            <div class="flex items-center gap-6 ml-8">
                <!-- Notificaciones -->
                <div class="relative" id="notification-dropdown-container">
                    <button id="notification-btn" class="relative text-slate-600 dark:text-slate-300 hover:text-blue-600 dark:hover:text-blue-400 transition-colors p-2 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-700">
                        <i class="fas fa-bell text-lg"></i>
                        <span id="notification-count" class="absolute -top-1 -right-1 w-5 h-5 bg-red-500 text-white text-xs rounded-full flex items-center justify-center hidden">0</span>
                    </button>

                    <!-- Dropdown de Notificaciones -->
                    <div id="notification-dropdown" class="absolute right-0 mt-2 w-80 bg-white dark:bg-slate-800 rounded-lg shadow-lg border border-slate-200 dark:border-blue-500/30 opacity-0 invisible transform scale-95 transition-all duration-200 z-50">
                        <div class="p-4 border-b border-slate-200 dark:border-blue-500/30">
                            <div class="flex items-center justify-between">
                                <h3 class="font-semibold text-slate-900 dark:text-white">Notificaciones</h3>
                                <button id="mark-all-read" class="text-sm text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 px-2 py-1 rounded hover:bg-blue-50 dark:hover:bg-blue-500/10 transition-colors">Marcar todas como leídas</button>
                            </div>
                        </div>
                        <div id="notifications-list" class="max-h-64 overflow-y-auto">
                            <div class="p-4 text-center text-slate-500 dark:text-slate-400">
                                <i class="fas fa-bell-slash text-2xl mb-2"></i>
                                <p>No hay notificaciones</p>
                            </div>
                        </div>
                        <div class="p-3 border-t border-slate-200 dark:border-blue-500/30">
                            <a href="{{ route('notifications.index') }}" class="block text-center text-sm text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 py-2 rounded hover:bg-blue-50 dark:hover:bg-blue-500/10 transition-colors">Ver todas las notificaciones</a>
                        </div>
                    </div>
                </div>

                <!-- Perfil de Usuario -->
                <div class="relative" id="user-dropdown-container">
                    <button id="user-dropdown-btn" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors">
                        <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center">
                            <i class="fas fa-user text-white text-sm"></i>
                        </div>
                        <span class="text-sm font-medium text-slate-900 dark:text-white hidden md:block">{{ auth()->user()->name ?? 'Usuario' }}</span>
                        <i id="user-dropdown-arrow" class="fas fa-chevron-down text-xs text-slate-500 dark:text-slate-400 transition-transform duration-200"></i>
                    </button>

                    <!-- Dropdown Menu -->
                    <div id="user-dropdown-menu" class="absolute right-0 mt-2 w-48 bg-white dark:bg-slate-800 rounded-lg shadow-lg border border-slate-200 dark:border-blue-500/30 opacity-0 invisible transform scale-95 transition-all duration-200 z-50">
                        <a href="{{ route('users.profile') }}" class="block px-4 py-3 text-sm text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-blue-500/10 first:rounded-t-lg transition-colors">
                            <i class="fas fa-user-circle mr-3 text-blue-500"></i> Mi Perfil
                        </a>
                        <a href="{{ route('users.index') }}" class="block px-4 py-3 text-sm text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-blue-500/10 transition-colors">
                            <i class="fas fa-users mr-3 text-green-500"></i> Gestionar Usuarios
                        </a>
                        <hr class="my-2 border-slate-200 dark:border-blue-500/30">
                        <form method="POST" action="{{ route('logout') }}" class="block" id="logout-form">
                            @csrf
                            <button type="button" onclick="handleLogout()" class="w-full text-left px-4 py-3 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-500/10 last:rounded-b-lg transition-colors">
                                <i class="fas fa-sign-out-alt mr-3"></i> Cerrar Sesión
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="flex h-screen bg-slate-50">
        <!-- SIDEBAR LATERAL RETRÁCTIL -->
        <aside id="sidebar" class="sidebar-modern fixed overflow-y-auto z-30">
            <!-- Menú de Navegación -->
            <nav class="p-4 space-y-2">
                <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="fas fa-chart-line w-5 flex-shrink-0"></i>
                    <span class="font-medium sidebar-text whitespace-nowrap overflow-hidden">Dashboard</span>
                </a>

                <a href="{{ route('inventario.index') }}" class="nav-item {{ request()->routeIs('inventario.*') ? 'active' : '' }}">
                    <i class="fas fa-cube w-5 flex-shrink-0"></i>
                    <span class="font-medium sidebar-text whitespace-nowrap overflow-hidden">Inventario</span>
                </a>

                <div class="space-y-1">
                    <a href="{{ route('ventas.index') }}" class="nav-item {{ request()->routeIs('ventas.index') ? 'active' : '' }}">
                        <i class="fas fa-shopping-cart w-5 flex-shrink-0"></i>
                        <span class="font-medium sidebar-text whitespace-nowrap overflow-hidden">Ventas</span>
                    </a>
                    
                    <a href="{{ route('ventas.history') }}" class="nav-item text-sm {{ request()->routeIs('ventas.history') || request()->routeIs('ventas.show') ? 'active' : '' }}">
                        <i class="fas fa-history w-4 flex-shrink-0 ml-4"></i>
                        <span class="sidebar-text whitespace-nowrap overflow-hidden">Historial</span>
                    </a>
                </div>

                <a href="{{ route('reportes.index') }}" class="nav-item {{ request()->routeIs('reportes.*') ? 'active' : '' }}">
                    <i class="fas fa-chart-bar w-5 flex-shrink-0"></i>
                    <span class="font-medium sidebar-text whitespace-nowrap overflow-hidden">Reportes</span>
                </a>

                <a href="{{ route('configuracion.index') }}" class="nav-item {{ request()->routeIs('configuracion.*') ? 'active' : '' }}">
                    <i class="fas fa-cog w-5 flex-shrink-0"></i>
                    <span class="font-medium sidebar-text whitespace-nowrap overflow-hidden">Configuración</span>
                </a>
            </nav>
        </aside>

        <!-- CONTENEDOR PRINCIPAL -->
        <div id="main-content" class="flex-1 flex flex-col">
            <!-- CONTENIDO PRINCIPAL -->
            <main class="flex-1 overflow-y-auto">
                <div class="p-4 lg:p-8">
                    <!-- Mensajes Flash -->
                    @if(session('success'))
                        <div class="mb-6 bg-green-50 dark:bg-green-900/20 border-2 border-green-200 dark:border-green-500/30 rounded-xl p-4 flex items-start gap-3 fade-in">
                            <i class="fas fa-check-circle text-green-600 dark:text-green-400 text-lg mt-0.5"></i>
                            <div>
                                <h3 class="font-semibold text-green-900 dark:text-green-300">Éxito</h3>
                                <p class="text-sm text-green-700 dark:text-green-400">{{ session('success') }}</p>
                            </div>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="mb-6 bg-red-50 dark:bg-red-900/20 border-2 border-red-200 dark:border-red-500/30 rounded-xl p-4 flex items-start gap-3 fade-in">
                            <i class="fas fa-times-circle text-red-600 dark:text-red-400 text-lg mt-0.5"></i>
                            <div>
                                <h3 class="font-semibold text-red-900 dark:text-red-300">Error</h3>
                                <p class="text-sm text-red-700 dark:text-red-400">{{ session('error') }}</p>
                            </div>
                        </div>
                    @endif

                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <!-- Script para sidebar retráctil -->
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('main-content');
            const sidebarIcon = document.getElementById('sidebar-icon');
            
            // Toggle clases
            sidebar.classList.toggle('collapsed');
            mainContent.classList.toggle('expanded');
            
            // Cambiar ícono
            if (sidebar.classList.contains('collapsed')) {
                sidebarIcon.classList.remove('fa-bars');
                sidebarIcon.classList.add('fa-times');
            } else {
                sidebarIcon.classList.remove('fa-times');
                sidebarIcon.classList.add('fa-bars');
            }
        }

        // Función mejorada para manejar logout
        function handleLogout() {
            if (confirm('¿Estás seguro de que quieres cerrar sesión?')) {
                // Obtener token CSRF fresco
                fetch('/csrf-token')
                    .then(response => response.json())
                    .then(data => {
                        // Actualizar el token en el formulario
                        const form = document.getElementById('logout-form');
                        const csrfInput = form.querySelector('input[name="_token"]');
                        if (csrfInput) {
                            csrfInput.value = data.token;
                        }
                        
                        // Enviar el formulario
                        form.submit();
                    })
                    .catch(error => {
                        console.error('Error al obtener token CSRF:', error);
                        // Si falla, intentar logout directo
                        document.getElementById('logout-form').submit();
                    });
            }
        }

        // Cerrar dropdown al hacer clic fuera
        document.addEventListener('click', function(event) {
            const profileBtn = event.target.closest('.group');
            if (!profileBtn) {
                document.querySelectorAll('.group > div').forEach(el => {
                    el.classList.add('invisible', 'opacity-0');
                });
            }
        });

        // Manejo mejorado del dropdown de usuario
        let userDropdownTimeout;
        const userDropdownBtn = document.getElementById('user-dropdown-btn');
        const userDropdownMenu = document.getElementById('user-dropdown-menu');
        const userDropdownArrow = document.getElementById('user-dropdown-arrow');
        const userDropdownContainer = document.getElementById('user-dropdown-container');

        function showUserDropdown() {
            clearTimeout(userDropdownTimeout);
            userDropdownMenu.classList.remove('opacity-0', 'invisible', 'scale-95');
            userDropdownMenu.classList.add('opacity-100', 'visible', 'scale-100');
            userDropdownArrow.classList.add('rotate-180');
        }

        function hideUserDropdown() {
            userDropdownTimeout = setTimeout(() => {
                userDropdownMenu.classList.add('opacity-0', 'invisible', 'scale-95');
                userDropdownMenu.classList.remove('opacity-100', 'visible', 'scale-100');
                userDropdownArrow.classList.remove('rotate-180');
            }, 150); // Delay de 150ms para permitir mover el mouse
        }

        function cancelHideUserDropdown() {
            clearTimeout(userDropdownTimeout);
        }

        // Event listeners para el dropdown de usuario
        if (userDropdownBtn && userDropdownMenu) {
            // Click en el botón
            userDropdownBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                if (userDropdownMenu.classList.contains('opacity-0')) {
                    showUserDropdown();
                } else {
                    hideUserDropdown();
                }
            });

            // Hover en el contenedor
            userDropdownContainer.addEventListener('mouseenter', showUserDropdown);
            userDropdownContainer.addEventListener('mouseleave', hideUserDropdown);

            // Hover en el menú
            userDropdownMenu.addEventListener('mouseenter', cancelHideUserDropdown);
            userDropdownMenu.addEventListener('mouseleave', hideUserDropdown);

            // Cerrar al hacer clic fuera
            document.addEventListener('click', function(event) {
                if (!userDropdownContainer.contains(event.target)) {
                    hideUserDropdown();
                }
            });
        }

        // Sistema de Notificaciones mejorado
        document.addEventListener('DOMContentLoaded', function() {
            const notificationBtn = document.getElementById('notification-btn');
            const notificationDropdown = document.getElementById('notification-dropdown');
            const notificationDropdownContainer = document.getElementById('notification-dropdown-container');
            const notificationCount = document.getElementById('notification-count');
            const notificationsList = document.getElementById('notifications-list');
            const markAllReadBtn = document.getElementById('mark-all-read');

            let notificationTimeout;

            function showNotificationDropdown() {
                clearTimeout(notificationTimeout);
                notificationDropdown.classList.remove('opacity-0', 'invisible', 'scale-95');
                notificationDropdown.classList.add('opacity-100', 'visible', 'scale-100');
                loadNotifications();
            }

            function hideNotificationDropdown() {
                notificationTimeout = setTimeout(() => {
                    notificationDropdown.classList.add('opacity-0', 'invisible', 'scale-95');
                    notificationDropdown.classList.remove('opacity-100', 'visible', 'scale-100');
                }, 150);
            }

            function cancelHideNotificationDropdown() {
                clearTimeout(notificationTimeout);
            }

            // Cargar notificaciones al cargar la página
            loadNotifications();

            // Actualizar notificaciones cada 30 segundos
            setInterval(loadNotifications, 30000);

            // Event listeners para el dropdown de notificaciones
            if (notificationBtn && notificationDropdown) {
                // Click en el botón
                notificationBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    if (notificationDropdown.classList.contains('opacity-0')) {
                        showNotificationDropdown();
                    } else {
                        hideNotificationDropdown();
                    }
                });

                // Hover en el contenedor
                notificationDropdownContainer.addEventListener('mouseenter', showNotificationDropdown);
                notificationDropdownContainer.addEventListener('mouseleave', hideNotificationDropdown);

                // Hover en el menú
                notificationDropdown.addEventListener('mouseenter', cancelHideNotificationDropdown);
                notificationDropdown.addEventListener('mouseleave', hideNotificationDropdown);

                // Cerrar al hacer clic fuera
                document.addEventListener('click', function(event) {
                    if (!notificationDropdownContainer.contains(event.target)) {
                        hideNotificationDropdown();
                    }
                });
            }

            // Marcar todas como leídas
            if (markAllReadBtn) {
                markAllReadBtn.addEventListener('click', function() {
                    fetch('/notifications/mark-all-read', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            loadNotifications();
                        }
                    });
                });
            }

            // Función para cargar notificaciones
            function loadNotifications() {
                fetch('/notifications/unread')
                    .then(response => response.json())
                    .then(data => {
                        updateNotificationCount(data.count);
                        updateNotificationsList(data.notifications);
                    })
                    .catch(error => console.error('Error loading notifications:', error));
            }

            // Actualizar contador de notificaciones
            function updateNotificationCount(count) {
                if (count > 0) {
                    notificationCount.textContent = count > 99 ? '99+' : count;
                    notificationCount.classList.remove('hidden');
                } else {
                    notificationCount.classList.add('hidden');
                }
            }

            // Actualizar lista de notificaciones
            function updateNotificationsList(notifications) {
                if (notifications.length === 0) {
                    notificationsList.innerHTML = `
                        <div class="p-4 text-center text-slate-500 dark:text-slate-400">
                            <i class="fas fa-bell-slash text-2xl mb-2"></i>
                            <p>No hay notificaciones</p>
                        </div>
                    `;
                    return;
                }

                notificationsList.innerHTML = notifications.map(notification => `
                    <div class="p-3 border-b border-slate-100 dark:border-blue-500/20 hover:bg-slate-50 dark:hover:bg-blue-500/10 cursor-pointer" onclick="markAsRead(${notification.id})">
                        <div class="flex items-start gap-3">
                            <i class="${getNotificationIcon(notification.type)} flex-shrink-0 mt-1"></i>
                            <div class="flex-1 min-w-0">
                                <h4 class="text-sm font-medium text-slate-900 dark:text-white truncate">${notification.title}</h4>
                                <p class="text-xs text-slate-600 dark:text-slate-300 mt-1">${notification.message}</p>
                                <p class="text-xs text-slate-400 dark:text-slate-500 mt-1">${formatDate(notification.created_at)}</p>
                            </div>
                        </div>
                    </div>
                `).join('');
            }

            // Marcar notificación como leída
            window.markAsRead = function(notificationId) {
                fetch(`/notifications/mark-read/${notificationId}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        loadNotifications();
                    }
                });
            };

            // Obtener ícono según tipo de notificación
            function getNotificationIcon(type) {
                switch(type) {
                    case 'stock_low':
                        return 'fas fa-exclamation-triangle text-yellow-500';
                    case 'expiring':
                        return 'fas fa-clock text-orange-500';
                    case 'expired':
                        return 'fas fa-times-circle text-red-500';
                    case 'system':
                        return 'fas fa-info-circle text-blue-500';
                    default:
                        return 'fas fa-bell text-gray-500';
                }
            }

            // Formatear fecha
            function formatDate(dateString) {
                const date = new Date(dateString);
                const now = new Date();
                const diffInMinutes = Math.floor((now - date) / (1000 * 60));

                if (diffInMinutes < 1) return 'Ahora';
                if (diffInMinutes < 60) return `${diffInMinutes}m`;
                if (diffInMinutes < 1440) return `${Math.floor(diffInMinutes / 60)}h`;
                return `${Math.floor(diffInMinutes / 1440)}d`;
            }

            // Generar notificaciones automáticas al cargar la página
            fetch('/notifications/generate', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                }
            });
        });
    </script>
</body>
</html>
