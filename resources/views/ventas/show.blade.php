@extends('layouts.app')

@section('title')
    Venta {{ $sale->sale_number }} - {{ \App\Models\UserSetting::get('empresa_nombre', 'Pharma-Sync') }}
@endsection

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-slate-900">Venta {{ $sale->sale_number }}</h1>
            <p class="text-slate-600 mt-1">{{ $sale->created_at->format('d/m/Y H:i:s') }}</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('ventas.history') }}" class="px-4 py-2 bg-slate-100 text-slate-700 rounded-lg hover:bg-slate-200 transition-colors font-medium">
                <i class="fas fa-arrow-left mr-2"></i> Volver
            </a>
            <button onclick="window.print()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium">
                <i class="fas fa-print mr-2"></i> Imprimir
            </button>
        </div>
    </div>

    <!-- Información de la Venta -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Detalles Principales -->
        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <h2 class="text-lg font-bold text-slate-900 mb-6">Productos Vendidos</h2>
            
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="text-left py-3 px-4 text-xs font-semibold text-slate-600 uppercase">Producto</th>
                            <th class="text-center py-3 px-4 text-xs font-semibold text-slate-600 uppercase">Cantidad</th>
                            <th class="text-center py-3 px-4 text-xs font-semibold text-slate-600 uppercase">Precio Unit.</th>
                            <th class="text-center py-3 px-4 text-xs font-semibold text-slate-600 uppercase">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sale->items as $item)
                            <tr class="border-b border-slate-100">
                                <td class="py-4 px-4">
                                    <div>
                                        <p class="font-medium text-slate-900">{{ $item['product_name'] }}</p>
                                        <p class="text-sm text-slate-500">{{ $item['laboratory'] ?? '' }}</p>
                                        @if(isset($item['product_barcode']))
                                            <p class="text-xs text-slate-400 font-mono">{{ $item['product_barcode'] }}</p>
                                        @endif
                                    </div>
                                </td>
                                <td class="py-4 px-4 text-center">
                                    <div>
                                        <span class="font-medium">{{ $item['quantity'] }}</span>
                                        <span class="text-sm text-slate-500">{{ $item['type'] === 'box' ? 'cajas' : 'unidades' }}</span>
                                        @if(isset($item['units_sold']))
                                            <p class="text-xs text-slate-400">{{ $item['units_sold'] }} unidades total</p>
                                        @endif
                                    </div>
                                </td>
                                <td class="py-4 px-4 text-center font-medium text-slate-900">
                                    ${{ number_format($item['unit_price'], 2) }}
                                </td>
                                <td class="py-4 px-4 text-center font-bold text-green-600">
                                    ${{ number_format($item['subtotal'], 2) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Resumen de la Venta -->
        <div class="space-y-6">
            <!-- Totales -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                <h3 class="text-lg font-bold text-slate-900 mb-4">Resumen</h3>
                
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-slate-600">Subtotal:</span>
                        <span class="font-medium">${{ number_format($sale->subtotal, 2) }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-slate-600">IVA (19%):</span>
                        <span class="font-medium">${{ number_format($sale->iva, 2) }}</span>
                    </div>
                    <div class="border-t border-slate-200 pt-3">
                        <div class="flex justify-between items-center">
                            <span class="text-lg font-bold text-slate-900">Total:</span>
                            <span class="text-2xl font-bold text-green-600">${{ number_format($sale->total, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Información Adicional -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                <h3 class="text-lg font-bold text-slate-900 mb-4">Información</h3>
                
                <div class="space-y-3">
                    <div>
                        <span class="text-sm text-slate-600">Número de Venta:</span>
                        <p class="font-mono font-medium">{{ $sale->sale_number }}</p>
                    </div>
                    <div>
                        <span class="text-sm text-slate-600">Fecha y Hora:</span>
                        <p class="font-medium">{{ $sale->created_at->format('d/m/Y H:i:s') }}</p>
                    </div>
                    <div>
                        <span class="text-sm text-slate-600">Usuario:</span>
                        <p class="font-medium">{{ $sale->user ? $sale->user->name : 'Usuario eliminado' }}</p>
                    </div>
                    <div>
                        <span class="text-sm text-slate-600">Total de Items:</span>
                        <p class="font-medium">{{ $sale->total_items }} productos diferentes</p>
                    </div>
                    <div>
                        <span class="text-sm text-slate-600">Total de Unidades:</span>
                        <p class="font-medium">{{ $sale->total_units }} unidades</p>
                    </div>
                </div>
            </div>

            <!-- Estado -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                <h3 class="text-lg font-bold text-slate-900 mb-4">Estado</h3>
                
                <div class="flex items-center gap-3">
                    <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                    <span class="text-green-700 font-medium">Venta Completada</span>
                </div>
                <p class="text-sm text-slate-500 mt-2">La venta se procesó correctamente y el inventario fue actualizado.</p>
            </div>
        </div>
    </div>
</div>

<style>
@media print {
    .no-print {
        display: none !important;
    }
    
    body {
        font-size: 12px;
    }
    
    .bg-white {
        background: white !important;
    }
    
    .shadow-sm, .border {
        box-shadow: none !important;
        border: 1px solid #e2e8f0 !important;
    }
}
</style>
@endsection