@extends('layouts.app')

@section('title')
    Inventario - {{ \App\Models\UserSetting::get('empresa_nombre', 'Pharma-Sync') }}
@endsection

@section('content')
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-slate-900">Inventario</h1>
            <p class="text-slate-600 mt-1">Gestiona tu inventario de productos</p>
        </div>
        <a href="{{ route('inventario.create') }}" class="px-6 py-2 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition-colors">
            <i class="fas fa-plus mr-2"></i> Agregar Producto
        </a>
    </div>

    <!-- Tabla de Productos -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-slate-200 bg-slate-50">
                        <th class="text-left py-3 px-4 text-xs font-semibold text-slate-600 uppercase">Código</th>
                        <th class="text-left py-3 px-4 text-xs font-semibold text-slate-600 uppercase">Producto</th>
                        <th class="text-left py-3 px-4 text-xs font-semibold text-slate-600 uppercase">Laboratorio</th>
                        <th class="text-center py-3 px-4 text-xs font-semibold text-slate-600 uppercase">Stock</th>
                        <th class="text-center py-3 px-4 text-xs font-semibold text-slate-600 uppercase">Costo</th>
                        <th class="text-center py-3 px-4 text-xs font-semibold text-slate-600 uppercase">Venta</th>
                        <th class="text-center py-3 px-4 text-xs font-semibold text-slate-600 uppercase">Vencimiento</th>
                        <th class="text-center py-3 px-4 text-xs font-semibold text-slate-600 uppercase">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                        <tr class="border-b border-slate-100 hover:bg-slate-50 transition-colors">
                            <td class="py-3 px-4 text-sm text-slate-600">{{ $product->barcode }}</td>
                            <td class="py-3 px-4 text-sm font-medium text-slate-900">{{ $product->name }}</td>
                            <td class="py-3 px-4 text-sm text-slate-600">{{ $product->laboratory }}</td>
                            <td class="py-3 px-4 text-sm text-center">
                                <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $product->stock_boxes < 5 ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700' }}">
                                    {{ $product->stock_boxes }} cajas
                                </span>
                            </td>
                            <td class="py-3 px-4 text-sm text-center text-blue-600 font-semibold">${{ number_format($product->cost_price, 2) }}</td>
                            <td class="py-3 px-4 text-sm text-center text-green-600 font-semibold">${{ number_format($product->precio_venta, 2) }}</td>
                            <td class="py-3 px-4 text-sm text-center text-slate-600">{{ \Carbon\Carbon::parse($product->expiration_date)->format('d/m/Y') }}</td>
                            <td class="py-3 px-4 text-sm text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('inventario.edit', $product) }}" 
                                       class="inline-flex items-center px-3 py-1 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-colors text-xs font-medium"
                                       title="Editar producto">
                                        <i class="fas fa-edit mr-1"></i> Editar
                                    </a>
                                    
                                    <!-- Botón de eliminación con AJAX -->
                                    <button onclick="deleteProduct({{ $product->id }}, '{{ addslashes($product->name) }}')"
                                            class="inline-flex items-center px-3 py-1 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-colors text-xs font-medium"
                                            title="Eliminar producto">
                                        <i class="fas fa-trash mr-1"></i> Eliminar
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-8 text-center text-slate-500">No hay productos en el inventario</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <script>
        function deleteProduct(productId, productName) {
            if (confirm('¿Estás seguro de eliminar el producto ' + productName + '?')) {
                // Mostrar indicador de carga
                const button = event.target.closest('button');
                const originalText = button.innerHTML;
                button.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Eliminando...';
                button.disabled = true;
                
                // Realizar petición AJAX
                fetch('/inventario/' + productId, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                })
                .then(response => {
                    if (response.ok) {
                        return response.json();
                    }
                    throw new Error('Error en la respuesta del servidor');
                })
                .then(data => {
                    if (data.success) {
                        // Mostrar mensaje de éxito
                        showMessage('Producto eliminado exitosamente', 'success');
                        // Recargar la página después de un breve delay
                        setTimeout(() => {
                            window.location.reload();
                        }, 1000);
                    } else {
                        throw new Error(data.message || 'Error al eliminar el producto');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showMessage('Error al eliminar el producto: ' + error.message, 'error');
                    // Restaurar botón
                    button.innerHTML = originalText;
                    button.disabled = false;
                });
            }
        }
        
        function showMessage(message, type) {
            // Crear elemento de mensaje
            const messageDiv = document.createElement('div');
            messageDiv.className = `fixed top-4 right-4 z-50 px-6 py-3 rounded-lg shadow-lg ${
                type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
            }`;
            messageDiv.textContent = message;
            
            // Agregar al DOM
            document.body.appendChild(messageDiv);
            
            // Remover después de 3 segundos
            setTimeout(() => {
                messageDiv.remove();
            }, 3000);
        }
    </script>
@endsection
