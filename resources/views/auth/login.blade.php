<!DOCTYPE html>
<html lang="es" class="{{ \App\Models\UserSetting::get('tema', 'light') === 'dark' ? 'dark' : '' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Iniciar Sesión - {{ \App\Models\UserSetting::get('empresa_nombre', 'Pharma-Sync') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Animaciones y efectos para el login */
        .pharmacy-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            position: relative;
            overflow: hidden;
        }
        
        .pharmacy-bg-dark {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
            position: relative;
            overflow: hidden;
        }
        
        .pharmacy-bg::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: 
                radial-gradient(circle at 20% 80%, rgba(120, 119, 198, 0.3) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(255, 119, 198, 0.3) 0%, transparent 50%),
                radial-gradient(circle at 40% 40%, rgba(120, 219, 255, 0.2) 0%, transparent 50%);
            animation: float 6s ease-in-out infinite;
        }
        
        .pharmacy-bg-dark::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: 
                radial-gradient(circle at 20% 80%, rgba(34, 197, 94, 0.2) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(59, 130, 246, 0.2) 0%, transparent 50%),
                radial-gradient(circle at 40% 40%, rgba(168, 85, 247, 0.1) 0%, transparent 50%);
            animation: float 8s ease-in-out infinite;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(2deg); }
        }
        
        .pill-float {
            position: absolute;
            opacity: 0.1;
            animation: pillFloat 10s linear infinite;
        }
        
        @keyframes pillFloat {
            0% { transform: translateY(100vh) rotate(0deg); }
            100% { transform: translateY(-100px) rotate(360deg); }
        }
        
        .glass-effect {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .glass-effect-dark {
            background: rgba(15, 23, 42, 0.8);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(59, 130, 246, 0.3);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
        }
        
        .input-glow:focus {
            box-shadow: 0 0 20px rgba(59, 130, 246, 0.4);
        }
        
        .btn-pharmacy {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            transition: all 0.3s ease;
        }
        
        .btn-pharmacy:hover {
            background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(59, 130, 246, 0.4);
        }
        
        .logo-glow {
            box-shadow: 0 0 30px rgba(59, 130, 246, 0.5);
        }
        
        /* Barra superior mejorada */
        .header-bar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
            border-radius: 50px;
            padding: 1rem 2rem;
            box-shadow: 0 10px 40px rgba(102, 126, 234, 0.4);
            border: 2px solid rgba(255, 255, 255, 0.3);
            position: relative;
            overflow: hidden;
            animation: headerPulse 3s ease-in-out infinite;
        }
        
        .header-bar-dark {
            background: linear-gradient(135deg, #1e3a8a 0%, #3730a3 50%, #4c1d95 100%);
            border: 2px solid rgba(59, 130, 246, 0.4);
            box-shadow: 0 10px 40px rgba(59, 130, 246, 0.3);
        }
        
        .header-bar::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(
                45deg,
                transparent,
                rgba(255, 255, 255, 0.1),
                transparent
            );
            animation: shine 3s infinite;
        }
        
        @keyframes shine {
            0% { transform: translateX(-100%) translateY(-100%) rotate(45deg); }
            100% { transform: translateX(100%) translateY(100%) rotate(45deg); }
        }
        
        @keyframes headerPulse {
            0%, 100% { 
                box-shadow: 0 10px 40px rgba(102, 126, 234, 0.4);
                transform: scale(1);
            }
            50% { 
                box-shadow: 0 15px 50px rgba(102, 126, 234, 0.6);
                transform: scale(1.02);
            }
        }
        
        .logo-icon-wrapper {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            border: 2px solid rgba(255, 255, 255, 0.4);
            animation: logoFloat 3s ease-in-out infinite;
        }
        
        @keyframes logoFloat {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-5px) rotate(5deg); }
        }
        
        .title-glow {
            text-shadow: 0 0 20px rgba(255, 255, 255, 0.5),
                         0 0 40px rgba(255, 255, 255, 0.3);
            animation: titleGlow 2s ease-in-out infinite;
        }
        
        @keyframes titleGlow {
            0%, 100% { 
                text-shadow: 0 0 20px rgba(255, 255, 255, 0.5),
                             0 0 40px rgba(255, 255, 255, 0.3);
            }
            50% { 
                text-shadow: 0 0 30px rgba(255, 255, 255, 0.8),
                             0 0 60px rgba(255, 255, 255, 0.5);
            }
        }
        
        .decorative-dots {
            display: flex;
            gap: 0.5rem;
            justify-content: center;
        }
        
        .dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            animation: dotPulse 1.5s ease-in-out infinite;
        }
        
        .dot:nth-child(1) { animation-delay: 0s; }
        .dot:nth-child(2) { animation-delay: 0.3s; }
        .dot:nth-child(3) { animation-delay: 0.6s; }
        
        @keyframes dotPulse {
            0%, 100% { 
                transform: scale(1);
                opacity: 0.6;
            }
            50% { 
                transform: scale(1.5);
                opacity: 1;
            }
        }
        
        /* Mejoras para pantallas muy grandes */
        @media (min-width: 1536px) {
            .glass-effect, .glass-effect-dark {
                max-height: 90vh;
                overflow-y: auto;
            }
        }
        
        /* Scroll suave para contenido que exceda la altura */
        .scrollable-content {
            scrollbar-width: thin;
            scrollbar-color: rgba(59, 130, 246, 0.5) transparent;
        }
        
        .scrollable-content::-webkit-scrollbar {
            width: 6px;
        }
        
        .scrollable-content::-webkit-scrollbar-track {
            background: transparent;
        }
        
        .scrollable-content::-webkit-scrollbar-thumb {
            background: rgba(59, 130, 246, 0.5);
            border-radius: 3px;
        }
        
        .scrollable-content::-webkit-scrollbar-thumb:hover {
            background: rgba(59, 130, 246, 0.7);
        }
        
        /* Asegurar que el contenido no se salga de la pantalla */
        @media (max-height: 700px) {
            .glass-effect, .glass-effect-dark {
                max-height: 85vh;
                overflow-y: auto;
            }
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center relative pharmacy-bg dark:pharmacy-bg-dark transition-all duration-500 p-4">
    <!-- Elementos flotantes de farmacia -->
    <div class="pill-float" style="left: 10%; animation-delay: 0s;">
        <i class="fas fa-pills text-6xl text-white"></i>
    </div>
    <div class="pill-float" style="left: 20%; animation-delay: 2s;">
        <i class="fas fa-capsules text-4xl text-blue-200"></i>
    </div>
    <div class="pill-float" style="left: 80%; animation-delay: 4s;">
        <i class="fas fa-prescription-bottle text-5xl text-green-200"></i>
    </div>
    <div class="pill-float" style="left: 70%; animation-delay: 6s;">
        <i class="fas fa-syringe text-4xl text-purple-200"></i>
    </div>
    <div class="pill-float" style="left: 90%; animation-delay: 8s;">
        <i class="fas fa-heartbeat text-3xl text-red-200"></i>
    </div>

    <!-- Container principal con máximo ancho y centrado -->
    <div class="w-full max-w-md lg:max-w-lg xl:max-w-xl 2xl:max-w-4xl relative z-10 mx-auto">
        
        <!-- Layout responsive: una columna en móvil, dos columnas en pantallas muy grandes -->
        <div class="2xl:grid 2xl:grid-cols-2 2xl:gap-12 2xl:items-center">
            
            <!-- Columna izquierda: Logo y título (solo en pantallas grandes) -->
            <div class="hidden 2xl:block">
                <div class="text-left">
                    <div class="w-32 h-32 bg-gradient-to-br from-blue-500 to-purple-600 rounded-3xl flex items-center justify-center mb-8 logo-glow transform hover:scale-110 transition-all duration-300">
                        <i class="fas fa-pills text-white text-6xl"></i>
                    </div>
                    <h1 class="text-6xl font-bold text-white mb-4 drop-shadow-lg title-glow">{{ \App\Models\UserSetting::get('empresa_nombre', 'Pharma-Sync') }}</h1>
                    <p class="text-blue-100 dark:text-blue-200 text-xl mb-6">{{ \App\Models\UserSetting::get('empresa_descripcion', 'Sistema de Farmacia') }}</p>
                    <div class="flex space-x-6">
                        <div class="w-4 h-4 bg-blue-400 rounded-full animate-pulse"></div>
                        <div class="w-4 h-4 bg-green-400 rounded-full animate-pulse" style="animation-delay: 0.5s;"></div>
                        <div class="w-4 h-4 bg-purple-400 rounded-full animate-pulse" style="animation-delay: 1s;"></div>
                    </div>
                    <div class="mt-8 p-6 bg-white/5 dark:bg-slate-800/30 rounded-xl border border-white/10 dark:border-blue-500/20">
                        <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
                            <i class="fas fa-shield-alt mr-3 text-green-300"></i>Sistema Seguro
                        </h3>
                        <ul class="text-blue-100 dark:text-blue-200 space-y-2">
                            <li class="flex items-center"><i class="fas fa-check mr-2 text-green-400"></i>Autenticación segura</li>
                            <li class="flex items-center"><i class="fas fa-check mr-2 text-green-400"></i>Datos encriptados</li>
                            <li class="flex items-center"><i class="fas fa-check mr-2 text-green-400"></i>Acceso controlado</li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <!-- Columna derecha: Formulario (siempre visible) -->
            <div class="w-full">
                
                <!-- Logo y Título original para pantallas pequeñas y medianas -->
                <div class="text-center mb-6 lg:mb-8 xl:mb-10 2xl:hidden">
                    <div class="w-16 h-16 lg:w-20 lg:h-20 xl:w-24 xl:h-24 bg-gradient-to-br from-blue-500 to-purple-600 rounded-2xl flex items-center justify-center mx-auto mb-4 lg:mb-6 logo-glow transform hover:scale-110 transition-all duration-300">
                        <i class="fas fa-pills text-white text-2xl lg:text-3xl xl:text-4xl"></i>
                    </div>
                    <h1 class="text-2xl lg:text-3xl xl:text-4xl font-bold text-white mb-2 drop-shadow-lg title-glow">{{ \App\Models\UserSetting::get('empresa_nombre', 'Pharma-Sync') }}</h1>
                    <p class="text-blue-100 dark:text-blue-200 text-sm lg:text-base xl:text-lg">{{ \App\Models\UserSetting::get('empresa_descripcion', 'Sistema de Farmacia') }}</p>
                    <div class="mt-3 lg:mt-4 flex justify-center space-x-3 lg:space-x-4">
                        <div class="w-2 h-2 lg:w-3 lg:h-3 bg-blue-400 rounded-full animate-pulse"></div>
                        <div class="w-2 h-2 lg:w-3 lg:h-3 bg-green-400 rounded-full animate-pulse" style="animation-delay: 0.5s;"></div>
                        <div class="w-2 h-2 lg:w-3 lg:h-3 bg-purple-400 rounded-full animate-pulse" style="animation-delay: 1s;"></div>
                    </div>
                </div>

        <!-- Formulario de Login con efecto glass - Responsive -->
        <div class="glass-effect dark:glass-effect-dark rounded-2xl shadow-2xl p-6 lg:p-8 xl:p-10 transform hover:scale-105 transition-all duration-300 scrollable-content">
            <div class="mb-4 lg:mb-6">
                <h2 class="text-xl lg:text-2xl xl:text-3xl font-semibold text-white mb-2">Bienvenido de Vuelta</h2>
                <p class="text-blue-100 dark:text-blue-200 text-xs lg:text-sm xl:text-base">Ingresa tus credenciales para acceder al sistema</p>
            </div>

            <form method="POST" action="{{ route('login') }}" class="space-y-4 lg:space-y-6">
                @csrf

                <!-- Email -->
                <div>
                    <label for="email" class="block text-xs lg:text-sm font-medium text-white mb-2 lg:mb-3">
                        <i class="fas fa-envelope mr-2"></i>Correo Electrónico
                    </label>
                    <div class="relative">
                        <input type="email" 
                               id="email" 
                               name="email" 
                               value="{{ old('email') }}"
                               class="w-full px-3 lg:px-4 py-3 lg:py-4 pl-10 lg:pl-12 bg-white/10 dark:bg-slate-800/50 border border-white/20 dark:border-blue-500/30 rounded-xl text-white placeholder-blue-100 dark:placeholder-blue-300 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent input-glow transition-all duration-300 text-sm lg:text-base @error('email') border-red-400 @enderror"
                               placeholder="tu@email.com"
                               required 
                               autofocus>
                        <i class="fas fa-user absolute left-3 lg:left-4 top-1/2 -translate-y-1/2 text-blue-200 text-sm lg:text-base"></i>
                    </div>
                    @error('email')
                        <p class="mt-2 text-xs lg:text-sm text-red-300 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Contraseña -->
                <div>
                    <label for="password" class="block text-xs lg:text-sm font-medium text-white mb-2 lg:mb-3">
                        <i class="fas fa-lock mr-2"></i>Contraseña
                    </label>
                    <div class="relative">
                        <input type="password" 
                               id="password" 
                               name="password"
                               class="w-full px-3 lg:px-4 py-3 lg:py-4 pl-10 lg:pl-12 bg-white/10 dark:bg-slate-800/50 border border-white/20 dark:border-blue-500/30 rounded-xl text-white placeholder-blue-100 dark:placeholder-blue-300 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent input-glow transition-all duration-300 text-sm lg:text-base @error('password') border-red-400 @enderror"
                               placeholder="••••••••"
                               required>
                        <i class="fas fa-key absolute left-3 lg:left-4 top-1/2 -translate-y-1/2 text-blue-200 text-sm lg:text-base"></i>
                    </div>
                    @error('password')
                        <p class="mt-2 text-xs lg:text-sm text-red-300 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Recordar sesión -->
                <div class="flex items-center">
                    <input type="checkbox" 
                           id="remember" 
                           name="remember" 
                           class="w-4 h-4 text-blue-500 bg-white/20 border-white/30 rounded focus:ring-blue-400 focus:ring-2"
                           {{ old('remember') ? 'checked' : '' }}>
                    <label for="remember" class="ml-3 text-xs lg:text-sm text-blue-100">
                        Recordar mi sesión
                    </label>
                </div>

                <!-- Botón de Login -->
                <button type="submit" 
                        class="w-full btn-pharmacy text-white font-semibold py-3 lg:py-4 px-4 lg:px-6 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2 focus:ring-offset-transparent text-sm lg:text-base">
                    <i class="fas fa-sign-in-alt mr-2"></i>
                    Iniciar Sesión
                </button>
            </form>

            <!-- Información de usuarios demo - Responsive -->
            <div class="mt-6 lg:mt-8 p-4 lg:p-6 bg-white/5 dark:bg-slate-800/30 rounded-xl border border-white/10 dark:border-blue-500/20">
                <h3 class="text-xs lg:text-sm font-semibold text-white mb-2 lg:mb-3 flex items-center">
                    <i class="fas fa-users mr-2 text-blue-300"></i>Usuarios de Prueba:
                </h3>
                <div class="text-xs lg:text-sm text-blue-100 dark:text-blue-200 space-y-2">
                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between p-2 bg-white/5 rounded-lg space-y-1 lg:space-y-0">
                        <span><strong>Admin:</strong> admin@pharmasync.com</span>
                        <span class="text-green-300 text-xs lg:text-sm">admin123</span>
                    </div>
                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between p-2 bg-white/5 rounded-lg space-y-1 lg:space-y-0">
                        <span><strong>Usuario:</strong> usuario@pharmasync.com</span>
                        <span class="text-green-300 text-xs lg:text-sm">usuario123</span>
                    </div>
                </div>
            </div>

            <!-- Botón de cambio de tema -->
            <div class="mt-4 lg:mt-6 text-center">
                <button onclick="toggleTheme()" class="text-blue-200 hover:text-white transition-colors text-xs lg:text-sm flex items-center justify-center mx-auto">
                    <i id="theme-icon" class="fas fa-moon mr-2"></i>
                    <span id="theme-text">Cambiar a modo claro</span>
                </button>
            </div>
        </div>

                <!-- Footer - Responsive -->
                <div class="text-center mt-6 lg:mt-8 text-xs lg:text-sm text-blue-100 dark:text-blue-200 2xl:hidden">
                    <p class="flex flex-col lg:flex-row items-center justify-center space-y-1 lg:space-y-0">
                        <i class="fas fa-heart text-red-400 mx-2 animate-pulse"></i>
                        <span>&copy; {{ date('Y') }} {{ \App\Models\UserSetting::get('empresa_nombre', 'Pharma-Sync') }}. Sistema Open Source.</span>
                        <i class="fas fa-heart text-red-400 mx-2 animate-pulse"></i>
                    </p>
                </div>
            </div>
        </div>
        
        <!-- Footer para pantallas grandes -->
        <div class="hidden 2xl:block text-center mt-8 text-sm text-blue-100 dark:text-blue-200">
            <p class="flex items-center justify-center">
                <i class="fas fa-heart text-red-400 mx-2 animate-pulse"></i>
                &copy; {{ date('Y') }} {{ \App\Models\UserSetting::get('empresa_nombre', 'Pharma-Sync') }}. Sistema Open Source.
                <i class="fas fa-heart text-red-400 mx-2 animate-pulse"></i>
            </p>
        </div>
    </div>

    <!-- Mostrar mensajes flash -->
    @if(session('success'))
        <div class="fixed top-6 right-6 bg-green-500/90 backdrop-blur-sm text-white px-6 py-4 rounded-xl shadow-2xl z-50 border border-green-400/30">
            <div class="flex items-center">
                <i class="fas fa-check-circle mr-3 text-lg"></i>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="fixed top-6 right-6 bg-red-500/90 backdrop-blur-sm text-white px-6 py-4 rounded-xl shadow-2xl z-50 border border-red-400/30">
            <div class="flex items-center">
                <i class="fas fa-times-circle mr-3 text-lg"></i>
                <span>{{ session('error') }}</span>
            </div>
        </div>
    @endif

    <script>
        // Auto-hide flash messages
        setTimeout(function() {
            const alerts = document.querySelectorAll('.fixed.top-6.right-6');
            alerts.forEach(alert => {
                alert.style.opacity = '0';
                alert.style.transform = 'translateX(100%)';
                setTimeout(() => alert.remove(), 300);
            });
        }, 5000);
        
        // Auto-refresh CSRF token cada 5 minutos para evitar expiración
        function refreshCsrfToken() {
            fetch('/csrf-token', {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.token) {
                    // Actualizar el token CSRF en el formulario
                    const csrfInput = document.querySelector('input[name="_token"]');
                    if (csrfInput) {
                        csrfInput.value = data.token;
                    }
                    // Actualizar el meta tag
                    const csrfMeta = document.querySelector('meta[name="csrf-token"]');
                    if (csrfMeta) {
                        csrfMeta.setAttribute('content', data.token);
                    }
                    console.log('CSRF token actualizado:', data.token.substring(0, 10) + '...');
                }
            })
            .catch(error => {
                console.error('Error al actualizar el token CSRF:', error);
            });
        }
        
        // Actualizar token cada 5 minutos
        setInterval(refreshCsrfToken, 5 * 60 * 1000);
        
        // Actualizar token antes de enviar el formulario
        document.querySelector('form').addEventListener('submit', function(e) {
            e.preventDefault();
            const form = this;
            
            // Obtener token fresco antes de enviar
            fetch('/csrf-token', {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.token) {
                    const csrfInput = form.querySelector('input[name="_token"]');
                    if (csrfInput) {
                        csrfInput.value = data.token;
                    }
                    console.log('Token actualizado antes de enviar');
                }
                // Enviar el formulario
                form.submit();
            })
            .catch(error => {
                console.error('Error al obtener token:', error);
                // Intentar enviar de todas formas
                form.submit();
            });
        });

        // Theme toggle functionality
        function toggleTheme() {
            const html = document.documentElement;
            const themeIcon = document.getElementById('theme-icon');
            const themeText = document.getElementById('theme-text');
            
            if (html.classList.contains('dark')) {
                html.classList.remove('dark');
                themeIcon.className = 'fas fa-moon mr-2';
                themeText.textContent = 'Cambiar a modo oscuro';
                document.body.className = 'min-h-screen flex items-center justify-center relative pharmacy-bg transition-all duration-500 p-4';
            } else {
                html.classList.add('dark');
                themeIcon.className = 'fas fa-sun mr-2';
                themeText.textContent = 'Cambiar a modo claro';
                document.body.className = 'min-h-screen flex items-center justify-center relative pharmacy-bg-dark transition-all duration-500 p-4';
            }
        }

        // Initialize theme
        document.addEventListener('DOMContentLoaded', function() {
            const html = document.documentElement;
            const themeIcon = document.getElementById('theme-icon');
            const themeText = document.getElementById('theme-text');
            
            if (html.classList.contains('dark')) {
                themeIcon.className = 'fas fa-sun mr-2';
                themeText.textContent = 'Cambiar a modo claro';
                document.body.className = 'min-h-screen flex items-center justify-center relative pharmacy-bg-dark transition-all duration-500 p-4';
            }
        });
    </script>
</body>
</html>