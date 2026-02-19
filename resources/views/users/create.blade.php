@extends('layouts.app')

@section('title', 'Crear Usuario - Pharma-Sync')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center gap-4">
        <a href="{{ route('users.index') }}" class="text-slate-600 hover:text-slate-900 transition-colors">
            <i class="fas fa-arrow-left text-lg"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Crear Nuevo Usuario</h1>
            <p class="text-slate-600 mt-1">Completa la información del nuevo usuario</p>
        </div>
    </div>

    <!-- Formulario -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200">
        <form method="POST" action="{{ route('users.store') }}" class="p-6 space-y-6">
            @csrf

            <!-- Nombre -->
            <div>
                <label for="name" class="block text-sm font-medium text-slate-700 mb-2">
                    Nombre Completo
                </label>
                <input type="text" 
                       id="name" 
                       name="name" 
                       value="{{ old('name') }}"
                       class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror"
                       required>
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-medium text-slate-700 mb-2">
                    Correo Electrónico
                </label>
                <input type="email" 
                       id="email" 
                       name="email" 
                       value="{{ old('email') }}"
                       class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('email') border-red-500 @enderror"
                       required>
                @error('email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Rol -->
            <div>
                <label for="role" class="block text-sm font-medium text-slate-700 mb-2">
                    Rol del Usuario
                </label>
                <select id="role" 
                        name="role"
                        class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('role') border-red-500 @enderror"
                        required>
                    <option value="">Seleccionar rol</option>
                    <option value="user" {{ old('role') === 'user' ? 'selected' : '' }}>Usuario</option>
                    <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Administrador</option>
                </select>
                @error('role')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Contraseña -->
            <div>
                <label for="password" class="block text-sm font-medium text-slate-700 mb-2">
                    Contraseña
                </label>
                <input type="password" 
                       id="password" 
                       name="password"
                       class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('password') border-red-500 @enderror"
                       required>
                @error('password')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Confirmar Contraseña -->
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-slate-700 mb-2">
                    Confirmar Contraseña
                </label>
                <input type="password" 
                       id="password_confirmation" 
                       name="password_confirmation"
                       class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                       required>
            </div>

            <!-- Botones -->
            <div class="flex items-center gap-4 pt-4">
                <button type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition-colors">
                    <i class="fas fa-save mr-2"></i>
                    Crear Usuario
                </button>
                <a href="{{ route('users.index') }}" 
                   class="bg-slate-200 dark:bg-slate-600 hover:bg-slate-300 dark:hover:bg-slate-500 text-slate-700 dark:text-slate-300 px-6 py-2 rounded-lg font-medium transition-colors">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
@endsection