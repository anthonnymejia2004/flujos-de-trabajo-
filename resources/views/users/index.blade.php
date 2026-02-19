@extends('layouts.app')

@section('title')
    Gestión de Usuarios - {{ \App\Models\UserSetting::get('empresa_nombre', 'Pharma-Sync') }}
@endsection

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Gestión de Usuarios</h1>
            <p class="text-slate-600 mt-1">Administra los usuarios del sistema</p>
        </div>
        <a href="{{ route('users.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors flex items-center gap-2">
            <i class="fas fa-plus"></i>
            Nuevo Usuario
        </a>
    </div>

    <!-- Tabla de Usuarios -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="text-left py-3 px-4 font-semibold text-slate-900">Usuario</th>
                        <th class="text-left py-3 px-4 font-semibold text-slate-900">Email</th>
                        <th class="text-left py-3 px-4 font-semibold text-slate-900">Rol</th>
                        <th class="text-left py-3 px-4 font-semibold text-slate-900">Fecha de Registro</th>
                        <th class="text-center py-3 px-4 font-semibold text-slate-900">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($users as $user)
                    <tr class="hover:bg-slate-50">
                        <td class="py-3 px-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center">
                                    <i class="fas fa-user text-white text-sm"></i>
                                </div>
                                <div>
                                    <div class="font-medium text-slate-900">{{ $user->name }}</div>
                                    @if($user->id === auth()->id())
                                        <span class="text-xs text-blue-600 font-medium">(Tú)</span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="py-3 px-4 text-slate-600">{{ $user->email }}</td>
                        <td class="py-3 px-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if($user->role === 'admin') bg-purple-100 text-purple-800
                                @elseif($user->role === 'user') bg-blue-100 text-blue-800
                                @else bg-gray-100 text-gray-800 @endif">
                                @if($user->role === 'admin')
                                    <i class="fas fa-crown mr-1"></i>
                                @elseif($user->role === 'user')
                                    <i class="fas fa-user mr-1"></i>
                                @else
                                    <i class="fas fa-eye mr-1"></i>
                                @endif
                                {{ $user->role_display }}
                            </span>
                        </td>
                        <td class="py-3 px-4 text-slate-600">{{ $user->created_at->format('d/m/Y') }}</td>
                        <td class="py-3 px-4">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('users.edit', $user) }}" 
                                   class="text-blue-600 hover:text-blue-700 p-1 rounded transition-colors"
                                   title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @if($user->id !== auth()->id())
                                <form method="POST" action="{{ route('users.destroy', $user) }}" 
                                      onsubmit="return confirm('¿Estás seguro de eliminar este usuario?')" 
                                      class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="text-red-600 hover:text-red-700 p-1 rounded transition-colors"
                                            title="Eliminar">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-8 text-center text-slate-500">
                            <i class="fas fa-users text-3xl mb-3"></i>
                            <p>No hay usuarios registrados</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        @if($users->hasPages())
        <div class="px-4 py-3 border-t border-slate-200">
            {{ $users->links() }}
        </div>
        @endif
    </div>
</div>
@endsection