@extends('layouts.app')

@section('title')
    Reportes - {{ \App\Models\UserSetting::get('empresa_nombre', 'Pharma-Sync') }}
@endsection

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div>
        <h1 class="text-3xl font-bold text-slate-900">Reportes y Análisis</h1>
        <p class="text-slate-600 mt-1">Análisis completo del inventario y estadísticas de la farmacia</p>
    </div>

    <!-- Filtros de Fecha -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <div class="flex flex-wrap items-center gap-4">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Desde</label>
                <input type="date" id="fecha-desde" class="px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Hasta</label>
                <input type="date" id="fecha-hasta" class="px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="flex items-end">
                <button onclick="filtrarReportes()" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium">
                    <i class="fas fa-filter mr-2"></i> Filtrar
                </button>
            </div>
        </div>
    </div>

    <!-- Tarjetas de Resumen de Ventas -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-semibold text-slate-600 uppercase tracking-wide">Ventas del Día</h3>
                <div class="w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-cash-register text-emerald-600 text-lg"></i>
                </div>
            </div>
            <p class="text-3xl font-bold text-slate-900">${{ number_format($ventasHoy, 2) }}</p>
            <p class="text-xs text-slate-500 mt-2">{{ $transaccionesHoy }} transacciones hoy</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-semibold text-slate-600 uppercase tracking-wide">Ventas del Mes</h3>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-calendar-check text-blue-600 text-lg"></i>
                </div>
            </div>
            <p class="text-3xl font-bold text-slate-900">${{ number_format($ventasMes, 2) }}</p>
            <div class="flex items-center mt-2">
                <p class="text-xs text-slate-500">{{ $transaccionesMes }} transacciones</p>
                @if($crecimientoMensual != 0)
                    <span class="ml-2 px-2 py-1 text-xs rounded-full {{ $crecimientoMensual > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $crecimientoMensual > 0 ? '+' : '' }}{{ number_format($crecimientoMensual, 1) }}%
                    </span>
                @endif
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-semibold text-slate-600 uppercase tracking-wide">Total Movido</h3>
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-coins text-purple-600 text-lg"></i>
                </div>
            </div>
            <p class="text-3xl font-bold text-slate-900">${{ number_format($dineroTotalMovido, 2) }}</p>
            <p class="text-xs text-slate-500 mt-2">{{ $totalTransacciones }} ventas totales</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-semibold text-slate-600 uppercase tracking-wide">Promedio Diario</h3>
                <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-chart-bar text-orange-600 text-lg"></i>
                </div>
            </div>
            <p class="text-3xl font-bold text-slate-900">${{ number_format($promedioDiario, 2) }}</p>
            <p class="text-xs text-slate-500 mt-2">Últimos 30 días</p>
        </div>
    </div>
    <!-- Tarjetas de Resumen de Inventario -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-semibold text-slate-600 uppercase tracking-wide">Valor Total Venta</h3>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-dollar-sign text-green-600 text-lg"></i>
                </div>
            </div>
            <p class="text-3xl font-bold text-slate-900">${{ number_format($totalVenta, 2) }}</p>
            <p class="text-xs text-slate-500 mt-2">Inventario total en venta</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-semibold text-slate-600 uppercase tracking-wide">Valor Total Costo</h3>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-cube text-blue-600 text-lg"></i>
                </div>
            </div>
            <p class="text-3xl font-bold text-slate-900">${{ number_format($totalCosto, 2) }}</p>
            <p class="text-xs text-slate-500 mt-2">Costo del inventario</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-semibold text-slate-600 uppercase tracking-wide">Ganancia Estimada</h3>
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-chart-line text-purple-600 text-lg"></i>
                </div>
            </div>
            <p class="text-3xl font-bold text-slate-900">${{ number_format($gananciaEstimada, 2) }}</p>
            <p class="text-xs text-slate-500 mt-2">Si se vende todo</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-semibold text-slate-600 uppercase tracking-wide">Margen Promedio</h3>
                <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-percentage text-orange-600 text-lg"></i>
                </div>
            </div>
            <p class="text-3xl font-bold text-slate-900">{{ number_format($margenPromedio, 1) }}%</p>
            <p class="text-xs text-slate-500 mt-2">Margen de ganancia</p>
        </div>
    </div>

    <!-- Alertas y Notificaciones -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-exclamation-triangle text-red-600 text-lg"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-slate-900">Stock Bajo</h3>
                    <p class="text-sm text-slate-600">Productos críticos</p>
                </div>
            </div>
            <p class="text-4xl font-bold text-red-600 mb-2">{{ $stockBajo }}</p>
            <p class="text-sm text-slate-500">Productos con menos de 5 cajas</p>
            <a href="{{ route('inventario.index', ['filter' => 'stock_bajo']) }}" 
               class="inline-flex items-center mt-3 text-sm text-red-600 hover:text-red-700 font-medium">
                Ver productos <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-calendar-times text-orange-600 text-lg"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-slate-900">Próximos a Vencer</h3>
                    <p class="text-sm text-slate-600">En 30 días</p>
                </div>
            </div>
            <p class="text-4xl font-bold text-orange-600 mb-2">{{ $proximosVencer }}</p>
            <p class="text-sm text-slate-500">Productos que vencen pronto</p>
            <a href="{{ route('inventario.index', ['filter' => 'proximos_vencer']) }}" 
               class="inline-flex items-center mt-3 text-sm text-orange-600 hover:text-orange-700 font-medium">
                Ver productos <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-boxes text-green-600 text-lg"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-slate-900">Total Productos</h3>
                    <p class="text-sm text-slate-600">En inventario</p>
                </div>
            </div>
            <p class="text-4xl font-bold text-green-600 mb-2">{{ $totalProductos }}</p>
            <p class="text-sm text-slate-500">Productos diferentes</p>
            <a href="{{ route('inventario.index') }}" 
               class="inline-flex items-center mt-3 text-sm text-green-600 hover:text-green-700 font-medium">
                Ver inventario <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>
    </div>

    <!-- Gráficos de Ventas y Análisis -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Gráfico de Ventas Últimos 7 Días -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <h2 class="text-lg font-bold text-slate-900 mb-6">Ventas Últimos 7 Días</h2>
            <div class="space-y-4">
                @foreach($ventasPorDia as $venta)
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-sm font-medium text-slate-900">{{ $venta['day'] }}</span>
                            <span class="text-sm text-green-600 font-semibold">${{ number_format($venta['total'], 2) }}</span>
                        </div>
                        <div class="w-full bg-slate-200 rounded-full h-2">
                            @php
                                $maxVenta = collect($ventasPorDia)->max('total');
                                $porcentaje = $maxVenta > 0 ? ($venta['total'] / $maxVenta) * 100 : 0;
                            @endphp
                            <div class="bg-green-600 h-2 rounded-full" style="width: {{ $porcentaje }}%"></div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Top Productos Vendidos -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <h2 class="text-lg font-bold text-slate-900 mb-6">Top Productos Vendidos</h2>
            <div class="space-y-4">
                @forelse($topProductosVendidos as $index => $producto)
                <div class="flex items-center justify-between p-3 bg-slate-50 rounded-lg">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                            <span class="text-sm font-bold text-blue-600">{{ $index + 1 }}</span>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-slate-900">{{ $producto['product_name'] }}</span>
                            <p class="text-xs text-slate-500">{{ $producto['laboratory'] }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="text-sm font-bold text-green-600">${{ number_format($producto['total_revenue'], 2) }}</span>
                        <p class="text-xs text-slate-500">{{ $producto['total_units_sold'] }} unidades</p>
                    </div>
                </div>
                @empty
                <div class="text-center py-8">
                    <i class="fas fa-chart-line text-slate-300 text-4xl mb-3"></i>
                    <p class="text-slate-500">No hay ventas registradas aún</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Gráficos y Análisis de Inventario -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Distribución por Laboratorio -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <h2 class="text-lg font-bold text-slate-900 mb-6">Distribución por Laboratorio</h2>
            <div class="space-y-4">
                @foreach($laboratorios as $lab)
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-sm font-medium text-slate-900">{{ $lab->laboratory }}</span>
                            <span class="text-sm text-slate-600">{{ $lab->count }} productos</span>
                        </div>
                        <div class="w-full bg-slate-200 rounded-full h-2">
                            <div class="bg-blue-600 h-2 rounded-full" style="width: {{ ($lab->count / $totalProductos) * 100 }}%"></div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Análisis de Vencimientos -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <h2 class="text-lg font-bold text-slate-900 mb-6">Análisis de Vencimientos</h2>
            <div class="space-y-4">
                <div class="flex items-center justify-between p-3 bg-red-50 rounded-lg">
                    <div class="flex items-center gap-3">
                        <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                        <span class="text-sm font-medium text-slate-900">Vencidos</span>
                    </div>
                    <span class="text-sm font-bold text-red-600">{{ $vencidos }}</span>
                </div>
                <div class="flex items-center justify-between p-3 bg-orange-50 rounded-lg">
                    <div class="flex items-center gap-3">
                        <div class="w-3 h-3 bg-orange-500 rounded-full"></div>
                        <span class="text-sm font-medium text-slate-900">Próximos 7 días</span>
                    </div>
                    <span class="text-sm font-bold text-orange-600">{{ $proximos7Dias }}</span>
                </div>
                <div class="flex items-center justify-between p-3 bg-yellow-50 rounded-lg">
                    <div class="flex items-center gap-3">
                        <div class="w-3 h-3 bg-yellow-500 rounded-full"></div>
                        <span class="text-sm font-medium text-slate-900">Próximos 30 días</span>
                    </div>
                    <span class="text-sm font-bold text-yellow-600">{{ $proximos30Dias }}</span>
                </div>
                <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                    <div class="flex items-center gap-3">
                        <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                        <span class="text-sm font-medium text-slate-900">Más de 30 días</span>
                    </div>
                    <span class="text-sm font-bold text-green-600">{{ $mas30Dias }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Top 10 Productos por Valor -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <h2 class="text-lg font-bold text-slate-900 mb-6">Top 10 Productos por Valor de Inventario</h2>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-slate-200 bg-slate-50">
                        <th class="text-left py-3 px-4 text-xs font-semibold text-slate-600 uppercase">#</th>
                        <th class="text-left py-3 px-4 text-xs font-semibold text-slate-600 uppercase">Producto</th>
                        <th class="text-left py-3 px-4 text-xs font-semibold text-slate-600 uppercase">Laboratorio</th>
                        <th class="text-center py-3 px-4 text-xs font-semibold text-slate-600 uppercase">Stock</th>
                        <th class="text-center py-3 px-4 text-xs font-semibold text-slate-600 uppercase">Precio Venta</th>
                        <th class="text-center py-3 px-4 text-xs font-semibold text-slate-600 uppercase">Valor Total</th>
                        <th class="text-center py-3 px-4 text-xs font-semibold text-slate-600 uppercase">Margen</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($topProductos as $index => $product)
                        <tr class="border-b border-slate-100 hover:bg-slate-50 transition-colors">
                            <td class="py-3 px-4 text-sm text-slate-600">{{ $index + 1 }}</td>
                            <td class="py-3 px-4 text-sm font-medium text-slate-900">{{ $product->name }}</td>
                            <td class="py-3 px-4 text-sm text-slate-600">{{ $product->laboratory }}</td>
                            <td class="py-3 px-4 text-sm text-center">
                                <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-medium">
                                    {{ $product->stock_boxes }} cajas
                                </span>
                            </td>
                            <td class="py-3 px-4 text-sm text-center text-green-600 font-semibold">${{ number_format($product->precio_venta, 2) }}</td>
                            <td class="py-3 px-4 text-sm text-center text-blue-600 font-bold">${{ number_format($product->precio_venta * $product->stock_boxes, 2) }}</td>
                            <td class="py-3 px-4 text-sm text-center">
                                <span class="px-2 py-1 bg-purple-100 text-purple-800 rounded-full text-xs font-medium">
                                    {{ number_format($product->profit_margin, 1) }}%
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Productos con Menor Rotación -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <h2 class="text-lg font-bold text-slate-900 mb-6">Productos con Mayor Stock (Posible Baja Rotación)</h2>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-slate-200 bg-slate-50">
                        <th class="text-left py-3 px-4 text-xs font-semibold text-slate-600 uppercase">Producto</th>
                        <th class="text-left py-3 px-4 text-xs font-semibold text-slate-600 uppercase">Laboratorio</th>
                        <th class="text-center py-3 px-4 text-xs font-semibold text-slate-600 uppercase">Stock</th>
                        <th class="text-center py-3 px-4 text-xs font-semibold text-slate-600 uppercase">Vencimiento</th>
                        <th class="text-center py-3 px-4 text-xs font-semibold text-slate-600 uppercase">Valor Inmovilizado</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($mayorStock as $product)
                        <tr class="border-b border-slate-100 hover:bg-slate-50 transition-colors">
                            <td class="py-3 px-4 text-sm font-medium text-slate-900">{{ $product->name }}</td>
                            <td class="py-3 px-4 text-sm text-slate-600">{{ $product->laboratory }}</td>
                            <td class="py-3 px-4 text-sm text-center">
                                <span class="px-2 py-1 bg-orange-100 text-orange-800 rounded-full text-xs font-medium">
                                    {{ $product->stock_boxes }} cajas
                                </span>
                            </td>
                            <td class="py-3 px-4 text-sm text-center text-slate-600">
                                {{ \Carbon\Carbon::parse($product->expiration_date)->format('d/m/Y') }}
                            </td>
                            <td class="py-3 px-4 text-sm text-center text-red-600 font-semibold">
                                ${{ number_format($product->cost_price * $product->stock_boxes, 2) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    function filtrarReportes() {
        const fechaDesde = document.getElementById('fecha-desde').value;
        const fechaHasta = document.getElementById('fecha-hasta').value;
        
        if (fechaDesde && fechaHasta) {
            // Aquí puedes implementar la lógica de filtrado
            console.log('Filtrar desde:', fechaDesde, 'hasta:', fechaHasta);
            // Recargar la página con los filtros
            window.location.href = `{{ route('reportes.index') }}?desde=${fechaDesde}&hasta=${fechaHasta}`;
        } else {
            alert('Por favor selecciona ambas fechas');
        }
    }

    // Establecer fechas por defecto (último mes)
    document.addEventListener('DOMContentLoaded', function() {
        const hoy = new Date();
        const haceUnMes = new Date();
        haceUnMes.setMonth(hoy.getMonth() - 1);
        
        document.getElementById('fecha-hasta').value = hoy.toISOString().split('T')[0];
        document.getElementById('fecha-desde').value = haceUnMes.toISOString().split('T')[0];
    });
</script>
@endsection
