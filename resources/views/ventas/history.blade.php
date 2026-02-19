@extends('layouts.app')

@section('title')
    Historial de Ventas - {{ \App\Models\UserSetting::get('empresa_nombre', 'Pharma-Sync') }}
@endsection

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-slate-900">Historial de Ventas</h1>
            <p class="text-slate-600 mt-1">Registro completo de todas las transacciones</p>
        </div>
        <a href="{{ route('ventas.index') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium">
            <i class="fas fa-plus mr-2"></i> Nueva Venta
        </a>
    </div>

    <!-- Filtros -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <form method="GET" action="{{ route('ventas.history') }}" class="flex flex-wrap items-center gap-4">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Desde</label>
                <input type="date" name="fecha_desde" value="{{ request('fecha_desde') }}" 
                       class="px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Hasta</label>
                <input type="date" name="fecha_hasta" value="{{ request('fecha_hasta') }}" 
                       class="px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="flex items-end">
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium">
                    <i class="fas fa-filter mr-2"></i> Filtrar
                </button>
            </div>
        </form>
    </div>

    <!-- Resumen -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-dollar-sign text-green-600 text-lg"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-slate-900">Total Vendido</h3>
                    <p class="text-sm text-slate-600">En el período</p>
                </div>
            </div>
            <p class="text-3xl font-bold text-green-600">${{ number_format($sales->sum('total'), 2) }}</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-receipt text-blue-600 text-lg"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-slate-900">Transacciones</h3>
                    <p class="text-sm text-slate-600">Número de ventas</p>
                </div>
            </div>
            <p class="text-3xl font-bold text-blue-600">{{ $sales->total() }}</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-chart-line text-purple-600 text-lg"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-slate-900">Promedio</h3>
                    <p class="text-sm text-slate-600">Por transacción</p>
                </div>
            </div>
            <p class="text-3xl font-bold text-purple-600">
                ${{ $sales->count() > 0 ? number_format($sales->sum('total') / $sales->count(), 2) : '0.00' }}
            </p>
        </div>
    </div>

    <!-- Tabla de Ventas -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-200">
            <h2 class="text-lg font-bold text-slate-900">Ventas Registradas</h2>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="text-left py-3 px-6 text-xs font-semibold text-slate-600 uppercase">Número</th>
                        <th class="text-left py-3 px-6 text-xs font-semibold text-slate-600 uppercase">Fecha</th>
                        <th class="text-left py-3 px-6 text-xs font-semibold text-slate-600 uppercase">Usuario</th>
                        <th class="text-center py-3 px-6 text-xs font-semibold text-slate-600 uppercase">Items</th>
                        <th class="text-center py-3 px-6 text-xs font-semibold text-slate-600 uppercase">Unidades</th>
                        <th class="text-center py-3 px-6 text-xs font-semibold text-slate-600 uppercase">Total</th>
                        <th class="text-center py-3 px-6 text-xs font-semibold text-slate-600 uppercase">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sales as $sale)
                        <tr class="border-b border-slate-100 hover:bg-slate-50 transition-colors">
                            <td class="py-4 px-6">
                                <span class="font-mono text-sm font-medium text-slate-900">{{ $sale->sale_number }}</span>
                            </td>
                            <td class="py-4 px-6 text-sm text-slate-600">
                                {{ $sale->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="py-4 px-6 text-sm text-slate-600">
                                {{ $sale->user ? $sale->user->name : 'Usuario eliminado' }}
                            </td>
                            <td class="py-4 px-6 text-center">
                                <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-medium">
                                    {{ $sale->total_items }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-center">
                                <span class="px-2 py-1 bg-purple-100 text-purple-800 rounded-full text-xs font-medium">
                                    {{ $sale->total_units }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-center">
                                <span class="text-lg font-bold text-green-600">${{ number_format($sale->total, 2) }}</span>
                            </td>
                            <td class="py-4 px-6 text-center">
                                <a href="{{ route('ventas.show', $sale) }}" 
                                   class="inline-flex items-center px-3 py-1 bg-slate-100 text-slate-700 rounded-lg hover:bg-slate-200 transition-colors text-sm">
                                    <i class="fas fa-eye mr-1"></i> Ver
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center">
                                <i class="fas fa-receipt text-slate-300 text-4xl mb-3"></i>
                                <p class="text-slate-500">No hay ventas registradas</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        @if($sales->hasPages())
            <div class="px-6 py-4 border-t border-slate-200">
                {{ $sales->links() }}
            </div>
        @endif
    </div>
</div>
@endsection