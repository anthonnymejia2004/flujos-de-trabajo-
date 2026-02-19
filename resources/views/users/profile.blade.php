@extends('layouts.app')

@section('title', 'Mi Perfil - Pharma-Sync')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Mi Perfil</h1>
            <p class="text-slate-600">Gestiona tu información personal y configuración de cuenta</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Información del Perfil -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                <h2 class="text-lg font-semibold text-slate-900 mb-4">Información Personal</h2>
                
                <form method="POST" action="{{ route('users.profile.update') }}" class="space-y-4">
                    @csrf
                    @method('PUT')
                    
                    <!-- Nombre -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-slate-700 mb-2">Nombre Completo</label>
                        <input type="text" 
                               id="name" 
                               name="name" 
                               value="{{ old('name', $user->name) }}" 
                               class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror"
                               required>
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-slate-700 mb-2">Correo Electrónico</label>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               value="{{ old('email', $user->email) }}" 
                               class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('email') border-red-500 @enderror"
                               required>
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Contraseña Actual (requerida para cambios) -->
                    <div>
                        <label for="current_password" class="block text-sm font-medium text-slate-700 mb-2">Contraseña Actual</label>
                        <input type="password" 
                               id="current_password" 
                               name="current_password" 
                               class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('current_password') border-red-500 @enderror">
                        <p class="mt-1 text-xs text-slate-500">Requerida solo si quieres cambiar tu contraseña</p>
                        @error('current_password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nueva Contraseña -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-slate-700 mb-2">Nueva Contraseña</label>
                        <input type="password" 
                               id="password" 
                               name="password" 
                               class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('password') border-red-500 @enderror">
                        <p class="mt-1 text-xs text-slate-500">Deja en blanco si no quieres cambiar tu contraseña</p>
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirmar Nueva Contraseña -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-slate-700 mb-2">Confirmar Nueva Contraseña</label>
                        <input type="password" 
                               id="password_confirmation" 
                               name="password_confirmation" 
                               class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <!-- Botones -->
                    <div class="flex items-center gap-3 pt-4">
                        <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            <i class="fas fa-save mr-2"></i>
                            Guardar Cambios
                        </button>
                        <a href="{{ route('dashboard') }}" class="px-6 py-2 bg-slate-200 dark:bg-slate-600 text-slate-700 dark:text-slate-300 rounded-lg hover:bg-slate-300 dark:hover:bg-slate-500 transition-colors">
                            Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Información de la Cuenta -->
        <div class="space-y-6">
            <!-- Datos de la Cuenta -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                <h3 class="text-lg font-semibold text-slate-900 mb-4">Información de la Cuenta</h3>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Rol</label>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                            @if($user->role === 'admin') bg-purple-100 text-purple-800
                            @elseif($user->role === 'user') bg-blue-100 text-blue-800
                            @else bg-gray-100 text-gray-800 @endif">
                            @if($user->role === 'admin')
                                <i class="fas fa-crown mr-2"></i> Administrador
                            @elseif($user->role === 'user')
                                <i class="fas fa-user mr-2"></i> Usuario
                            @else
                                <i class="fas fa-eye mr-2"></i> Visualizador
                            @endif
                        </span>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Miembro desde</label>
                        <p class="text-sm text-slate-600">{{ $user->created_at->format('d/m/Y') }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Última actualización</label>
                        <p class="text-sm text-slate-600">{{ $user->updated_at->diffForHumans() }}</p>
                    </div>
                </div>
            </div>

            <!-- Estadísticas Rápidas -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                <h3 class="text-lg font-semibold text-slate-900 mb-4">Actividad Reciente</h3>
                
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-slate-600">Notificaciones no leídas</span>
                        <span class="text-sm font-medium text-slate-900">{{ \App\Models\Notification::unread()->count() }}</span>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-slate-600">Productos en inventario</span>
                        <span class="text-sm font-medium text-slate-900">{{ \App\Models\Product::count() }}</span>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-slate-600">Productos con stock bajo</span>
                        <span class="text-sm font-medium text-red-600">{{ \App\Models\Product::where('stock_boxes', '<', 5)->count() }}</span>
                    </div>
                </div>
            </div>

            <!-- Acciones Rápidas -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                <h3 class="text-lg font-semibold text-slate-900 mb-4">Acciones Rápidas</h3>
                
                <div class="space-y-3">
                    <a href="{{ route('notifications.index') }}" class="flex items-center gap-3 p-3 rounded-lg hover:bg-slate-50 transition-colors">
                        <i class="fas fa-bell text-blue-600"></i>
                        <span class="text-sm text-slate-700">Ver Notificaciones</span>
                    </a>
                    
                    @if($user->role === 'admin')
                        <a href="{{ route('users.index') }}" class="flex items-center gap-3 p-3 rounded-lg hover:bg-slate-50 transition-colors">
                            <i class="fas fa-users text-green-600"></i>
                            <span class="text-sm text-slate-700">Gestionar Usuarios</span>
                        </a>
                    @endif
                    
                    <a href="{{ route('configuracion.index') }}" class="flex items-center gap-3 p-3 rounded-lg hover:bg-slate-50 transition-colors">
                        <i class="fas fa-cog text-purple-600"></i>
                        <span class="text-sm text-slate-700">Configuración</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection