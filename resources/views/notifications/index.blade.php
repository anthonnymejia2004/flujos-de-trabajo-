@extends('layouts.app')

@section('title')
    Notificaciones - {{ \App\Models\UserSetting::get('empresa_nombre', 'Pharma-Sync') }}
@endsection

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Notificaciones</h1>
            <p class="text-slate-600">Gestiona todas las notificaciones del sistema</p>
        </div>
        
        <div class="flex items-center gap-3">
            <button onclick="markAllAsRead()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                <i class="fas fa-check-double mr-2"></i>
                Marcar todas como leídas
            </button>
            <button onclick="generateNotifications()" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                <i class="fas fa-sync mr-2"></i>
                Generar Automáticas
            </button>
        </div>
    </div>

    <!-- Filtros -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4">
        <div class="flex flex-wrap items-center gap-4">
            <div class="flex items-center gap-2">
                <label class="text-sm font-medium text-slate-700">Filtrar por tipo:</label>
                <select id="type-filter" class="px-3 py-2 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Todos</option>
                    <option value="stock_low">Stock Bajo</option>
                    <option value="expiring">Por Vencer</option>
                    <option value="expired">Vencidos</option>
                    <option value="system">Sistema</option>
                </select>
            </div>
            
            <div class="flex items-center gap-2">
                <label class="text-sm font-medium text-slate-700">Estado:</label>
                <select id="status-filter" class="px-3 py-2 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Todas</option>
                    <option value="unread">No leídas</option>
                    <option value="read">Leídas</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Lista de Notificaciones -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200">
        @if($notifications->count() > 0)
            <div class="divide-y divide-slate-200">
                @foreach($notifications as $notification)
                    <div class="p-4 hover:bg-slate-50 transition-colors {{ !$notification->is_read ? 'bg-blue-50 border-l-4 border-l-blue-500' : '' }}">
                        <div class="flex items-start gap-4">
                            <!-- Ícono -->
                            <div class="flex-shrink-0 mt-1">
                                <i class="{{ $notification->icon }}"></i>
                            </div>
                            
                            <!-- Contenido -->
                            <div class="flex-1 min-w-0">
                                <div class="flex items-start justify-between">
                                    <div>
                                        <h3 class="text-sm font-semibold text-slate-900 {{ !$notification->is_read ? 'font-bold' : '' }}">
                                            {{ $notification->title }}
                                        </h3>
                                        <p class="text-sm text-slate-600 mt-1">{{ $notification->message }}</p>
                                        
                                        <!-- Datos adicionales -->
                                        @if($notification->data)
                                            <div class="mt-2 text-xs text-slate-500">
                                                @if($notification->type === 'stock_low')
                                                    <span class="inline-flex items-center px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full">
                                                        Stock actual: {{ $notification->data['current_stock'] ?? 'N/A' }} cajas
                                                    </span>
                                                @elseif($notification->type === 'expiring')
                                                    <span class="inline-flex items-center px-2 py-1 bg-orange-100 text-orange-800 rounded-full">
                                                        Vence en: {{ $notification->data['days_to_expire'] ?? 'N/A' }} días
                                                    </span>
                                                @elseif($notification->type === 'expired')
                                                    <span class="inline-flex items-center px-2 py-1 bg-red-100 text-red-800 rounded-full">
                                                        Vencido el: {{ $notification->data['expiration_date'] ?? 'N/A' }}
                                                    </span>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <!-- Fecha y acciones -->
                                    <div class="flex items-center gap-2 ml-4">
                                        <span class="text-xs text-slate-400">
                                            {{ $notification->created_at->diffForHumans() }}
                                        </span>
                                        
                                        <div class="flex items-center gap-1">
                                            @if(!$notification->is_read)
                                                <button onclick="markAsRead({{ $notification->id }})" 
                                                        class="p-1 text-blue-600 hover:text-blue-700 transition-colors" 
                                                        title="Marcar como leída">
                                                    <i class="fas fa-check text-xs"></i>
                                                </button>
                                            @endif
                                            
                                            <button onclick="deleteNotification({{ $notification->id }})" 
                                                    class="p-1 text-red-600 hover:text-red-700 transition-colors" 
                                                    title="Eliminar">
                                                <i class="fas fa-trash text-xs"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <!-- Paginación -->
            <div class="p-4 border-t border-slate-200">
                {{ $notifications->links() }}
            </div>
        @else
            <div class="p-8 text-center">
                <i class="fas fa-bell-slash text-4xl text-slate-300 mb-4"></i>
                <h3 class="text-lg font-medium text-slate-900 mb-2">No hay notificaciones</h3>
                <p class="text-slate-600">Las notificaciones aparecerán aquí cuando se generen automáticamente o manualmente.</p>
                
                <button onclick="generateNotifications()" class="mt-4 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-sync mr-2"></i>
                    Generar Notificaciones Automáticas
                </button>
            </div>
        @endif
    </div>
</div>

<script>
    // Marcar notificación como leída
    function markAsRead(notificationId) {
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
                location.reload();
            }
        })
        .catch(error => console.error('Error:', error));
    }

    // Marcar todas como leídas
    function markAllAsRead() {
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
                location.reload();
            }
        })
        .catch(error => console.error('Error:', error));
    }

    // Generar notificaciones automáticas
    function generateNotifications() {
        fetch('/notifications/generate', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(`Se generaron ${data.generated} notificaciones automáticas`);
                location.reload();
            }
        })
        .catch(error => console.error('Error:', error));
    }

    // Eliminar notificación
    function deleteNotification(notificationId) {
        if (confirm('¿Estás seguro de que quieres eliminar esta notificación?')) {
            fetch(`/notifications/${notificationId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                }
            })
            .catch(error => console.error('Error:', error));
        }
    }

    // Filtros
    document.getElementById('type-filter').addEventListener('change', function() {
        // Implementar filtrado por tipo
        filterNotifications();
    });

    document.getElementById('status-filter').addEventListener('change', function() {
        // Implementar filtrado por estado
        filterNotifications();
    });

    function filterNotifications() {
        // Esta función se puede implementar con AJAX para filtrar sin recargar la página
        // Por ahora, simplemente recargamos con parámetros de consulta
        const typeFilter = document.getElementById('type-filter').value;
        const statusFilter = document.getElementById('status-filter').value;
        
        let url = new URL(window.location);
        
        if (typeFilter) {
            url.searchParams.set('type', typeFilter);
        } else {
            url.searchParams.delete('type');
        }
        
        if (statusFilter) {
            url.searchParams.set('status', statusFilter);
        } else {
            url.searchParams.delete('status');
        }
        
        window.location.href = url.toString();
    }
</script>
@endsection