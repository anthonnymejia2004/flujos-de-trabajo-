@extends('layouts.app')

@section('title')
    Dashboard - {{ \App\Models\UserSetting::get('empresa_nombre', 'Pharma-Sync') }}
@endsection

@section('content')
    <!-- Encabezado -->
    <div class="mb-8 fade-in">
        <h1 class="text-3xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">Dashboard</h1>
        <p class="text-slate-600 dark:text-slate-400 mt-1">Bienvenido a {{ \App\Models\UserSetting::get('empresa_nombre', 'Pharma-Sync') }} - {{ \App\Models\UserSetting::get('empresa_descripcion', 'Sistema de Farmacia') }}</p>
    </div>

    <!-- GRID DE TARJETAS PRINCIPALES -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Tarjeta: Valor Total de Venta -->
        <a href="{{ route('inventario.index') }}" class="card-modern fade-in" style="animation-delay: 0.1s">
            <div class="flex items-center gap-4">
                <div class="card-icon gradient-success">
                    <i class="fas fa-dollar-sign"></i>
                </div>
                <div class="flex-1">
                    <h3 class="text-sm font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wide">Valor Total Venta</h3>
                    <p class="card-value text-2xl mt-1">${{ number_format($totalVenta, 2) }}</p>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Inventario total en venta</p>
                </div>
            </div>
        </a>

        <!-- Tarjeta: Valor Total de Costo -->
        <a href="{{ route('inventario.index') }}" class="card-modern fade-in" style="animation-delay: 0.2s">
            <div class="flex items-center gap-4">
                <div class="card-icon gradient-primary">
                    <i class="fas fa-cube"></i>
                </div>
                <div class="flex-1">
                    <h3 class="text-sm font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wide">Valor Total Costo</h3>
                    <p class="card-value text-2xl mt-1">${{ number_format($products->sum(fn($p) => $p->cost_price * $p->stock_boxes), 2) }}</p>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Costo del inventario</p>
                </div>
            </div>
        </a>

        <!-- Tarjeta: Stock Bajo -->
        <a href="{{ route('inventario.index', ['filter' => 'stock_bajo']) }}" class="card-modern fade-in" style="animation-delay: 0.3s">
            <div class="flex items-center gap-4">
                <div class="card-icon gradient-warning">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div class="flex-1">
                    <h3 class="text-sm font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wide">Stock Bajo</h3>
                    <p class="card-value text-2xl mt-1">{{ $stockBajo->count() }}</p>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Productos con stock bajo</p>
                </div>
            </div>
        </a>

        <!-- Tarjeta: Próximos a Vencer -->
        <a href="{{ route('inventario.index', ['filter' => 'proximos_vencer']) }}" class="card-modern fade-in" style="animation-delay: 0.4s">
            <div class="flex items-center gap-4">
                <div class="card-icon gradient-info">
                    <i class="fas fa-calendar-times"></i>
                </div>
                <div class="flex-1">
                    <h3 class="text-sm font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wide">Próximos a Vencer</h3>
                    <p class="card-value text-2xl mt-1">{{ $proximosVencer->count() }}</p>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">En los próximos 30 días</p>
                </div>
            </div>
        </a>
    </div>

    <!-- GRID DE CONTENIDO SECUNDARIO -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Tarjeta: Comparativa Precio Costo vs Venta -->
        <div class="lg:col-span-2 card-modern fade-in" style="animation-delay: 0.5s">
            <h2 class="text-lg font-bold text-slate-900 dark:text-white mb-6 flex items-center gap-2">
                <i class="fas fa-chart-line text-blue-500"></i>
                Comparativa: Costo vs Venta
            </h2>
            <div class="space-y-4">
                @forelse($products->take(5) as $product)
                    <div class="flex items-center justify-between p-4 bg-slate-50 dark:bg-slate-700/50 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-700 transition-all transform hover:scale-[1.02]">
                        <div class="flex-1">
                            <p class="font-medium text-slate-900 dark:text-white">{{ $product->name }}</p>
                            <p class="text-xs text-slate-500 dark:text-slate-400">{{ $product->laboratory }}</p>
                        </div>
                        <div class="flex items-center gap-6">
                            <div class="text-right">
                                <p class="text-xs text-slate-500 dark:text-slate-400 uppercase">Costo</p>
                                <p class="text-sm font-semibold text-blue-600 dark:text-blue-400">${{ number_format($product->cost_price, 2) }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-xs text-slate-500 dark:text-slate-400 uppercase">Venta</p>
                                <p class="text-sm font-semibold text-green-600 dark:text-green-400">${{ number_format($product->precio_venta, 2) }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-xs text-slate-500 dark:text-slate-400 uppercase">Stock</p>
                                <span class="badge badge-info">{{ $product->stock_boxes }} cajas</span>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-center text-slate-500 dark:text-slate-400 py-8">No hay productos disponibles</p>
                @endforelse
            </div>
        </div>

        <!-- Tarjeta: Próximos a Vencer (Detalle) -->
        <div class="card-modern fade-in" style="animation-delay: 0.6s">
            <h2 class="text-lg font-bold text-slate-900 dark:text-white mb-6 flex items-center gap-2">
                <i class="fas fa-exclamation-triangle text-orange-500"></i>
                Próximos a Vencer
            </h2>
            <div class="space-y-3">
                @forelse($proximosVencer->take(6) as $product)
                    @php
                        $daysUntilExpiry = \Carbon\Carbon::parse($product->expiration_date)->diffInDays(\Carbon\Carbon::now());
                        $badgeClass = $daysUntilExpiry <= 7 ? 'badge-error' : ($daysUntilExpiry <= 15 ? 'badge-warning' : 'badge-info');
                    @endphp
                    <div class="flex items-center justify-between p-3 bg-slate-50 dark:bg-slate-700/50 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-700 transition-all">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-slate-900 dark:text-white">{{ $product->name }}</p>
                            <p class="text-xs text-slate-500 dark:text-slate-400">{{ \Carbon\Carbon::parse($product->expiration_date)->format('d/m/Y') }}</p>
                        </div>
                        <span class="badge {{ $badgeClass }}">
                            {{ $daysUntilExpiry }}d
                        </span>
                    </div>
                @empty
                    <p class="text-center text-slate-500 dark:text-slate-400 py-8">Sin productos próximos a vencer</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- TARJETA: Stock Bajo (Detalle) -->
    <div class="mt-6 card-modern fade-in" style="animation-delay: 0.7s">
        <h2 class="text-lg font-bold text-slate-900 dark:text-white mb-6 flex items-center gap-2">
            <i class="fas fa-box text-red-500"></i>
            Productos con Stock Bajo
        </h2>
        <div class="overflow-x-auto">
            <table class="table-modern">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Laboratorio</th>
                        <th class="text-center">Stock (Cajas)</th>
                        <th class="text-center">Precio Costo</th>
                        <th class="text-center">Precio Venta</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($stockBajo as $product)
                        <tr>
                            <td class="font-medium text-slate-900 dark:text-white">{{ $product->name }}</td>
                            <td class="text-slate-600 dark:text-slate-300">{{ $product->laboratory }}</td>
                            <td class="text-center">
                                <span class="badge badge-error">
                                    {{ $product->stock_boxes }}
                                </span>
                            </td>
                            <td class="text-center text-blue-600 dark:text-blue-400 font-semibold">${{ number_format($product->cost_price, 2) }}</td>
                            <td class="text-center text-green-600 dark:text-green-400 font-semibold">${{ number_format($product->precio_venta, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-slate-500 dark:text-slate-400">No hay productos con stock bajo</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection