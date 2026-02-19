@extends('layouts.app')

@section('title')
    Punto de Venta - {{ \App\Models\UserSetting::get('empresa_nombre', 'Pharma-Sync') }}
@endsection

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div>
        <h1 class="text-3xl font-bold text-slate-900">Punto de Venta</h1>
        <p class="text-slate-600 mt-1">Sistema de ventas con código de barras</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Panel de Búsqueda y Productos -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Búsqueda por Código de Barras -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                <h2 class="text-lg font-bold text-slate-900 mb-4">Escanear Producto</h2>
                <div class="flex gap-3">
                    <div class="flex-1">
                        <input type="text" 
                               id="barcode-input" 
                               placeholder="Escanea o ingresa el código de barras..." 
                               class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-lg"
                               autofocus>
                    </div>
                    <button onclick="searchByBarcode()" 
                            class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium">
                        <i class="fas fa-search mr-2"></i> Buscar
                    </button>
                </div>
                <p class="text-sm text-slate-500 mt-2">
                    <i class="fas fa-info-circle mr-1"></i> 
                    Presiona Enter después de escanear o escribir el código
                </p>
            </div>

            <!-- Lista de Productos en Venta -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                <h2 class="text-lg font-bold text-slate-900 mb-4">Productos en Venta</h2>
                <div id="sale-items" class="space-y-3">
                    <div class="text-center py-8 text-slate-400">
                        <i class="fas fa-shopping-cart text-4xl mb-3"></i>
                        <p>No hay productos agregados</p>
                        <p class="text-sm">Escanea un código de barras para comenzar</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Panel de Resumen y Total -->
        <div class="space-y-6">
            <!-- Resumen de Venta -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 sticky top-20">
                <h2 class="text-lg font-bold text-slate-900 mb-4">Resumen de Venta</h2>
                
                <div class="space-y-3 mb-6">
                    <div class="flex justify-between text-slate-600">
                        <span>Subtotal:</span>
                        <span id="subtotal" class="font-semibold">$0.00</span>
                    </div>
                    <div class="flex justify-between text-slate-600">
                        <span>IVA:</span>
                        <span id="iva" class="font-semibold">$0.00</span>
                    </div>
                    <div class="border-t border-slate-200 pt-3">
                        <div class="flex justify-between items-center">
                            <span class="text-lg font-bold text-slate-900">Total:</span>
                            <span id="total" class="text-2xl font-bold text-green-600">$0.00</span>
                        </div>
                    </div>
                </div>

                <div class="space-y-3">
                    <button onclick="completeSale()" 
                            id="complete-sale-btn"
                            class="w-full px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-medium disabled:bg-slate-300 disabled:cursor-not-allowed"
                            disabled>
                        <i class="fas fa-check-circle mr-2"></i> Completar Venta
                    </button>
                    <button onclick="clearSale()" 
                            class="w-full px-6 py-3 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-colors font-medium">
                        <i class="fas fa-times mr-2"></i> Cancelar Venta
                    </button>
                </div>

                <!-- Estadísticas Rápidas -->
                <div class="mt-6 pt-6 border-t border-slate-200">
                    <h3 class="text-sm font-semibold text-slate-600 mb-3">Estadísticas</h3>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-slate-600">Productos:</span>
                            <span id="item-count" class="font-semibold text-slate-900">0</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-600">Unidades:</span>
                            <span id="unit-count" class="font-semibold text-slate-900">0</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Selección de Cantidad -->
<div id="quantity-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-xl shadow-xl max-w-md w-full mx-4">
        <div class="p-6">
            <h3 class="text-xl font-bold text-slate-900 mb-4">Seleccionar Cantidad</h3>
            
            <div id="product-info" class="mb-6 p-4 bg-slate-50 rounded-lg">
                <p class="font-semibold text-slate-900" id="modal-product-name"></p>
                <p class="text-sm text-slate-600" id="modal-product-lab"></p>
                <p class="text-lg font-bold text-green-600 mt-2" id="modal-product-price"></p>
            </div>

            <!-- Tipo de Venta -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-slate-700 mb-3">Tipo de Venta</label>
                <div class="grid grid-cols-2 gap-3">
                    <button onclick="setSaleType('box')" 
                            id="btn-box"
                            class="px-4 py-3 border-2 border-blue-600 bg-blue-50 text-blue-700 rounded-lg font-medium transition-colors">
                        <i class="fas fa-box mr-2"></i> Caja Completa
                    </button>
                    <button onclick="setSaleType('unit')" 
                            id="btn-unit"
                            class="px-4 py-3 border-2 border-slate-300 bg-white text-slate-700 rounded-lg font-medium hover:border-blue-600 hover:bg-blue-50 hover:text-blue-700 transition-colors">
                        <i class="fas fa-pills mr-2"></i> Por Unidad
                    </button>
                </div>
            </div>

            <!-- Cantidad -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-slate-700 mb-2">
                    Cantidad <span id="quantity-label">(Cajas)</span>
                </label>
                <div class="flex items-center gap-3">
                    <button onclick="decreaseQuantity()" 
                            class="w-12 h-12 bg-slate-100 hover:bg-slate-200 rounded-lg font-bold text-xl transition-colors">
                        -
                    </button>
                    <input type="number" 
                           id="quantity-input" 
                           value="1" 
                           min="1"
                           class="flex-1 text-center text-2xl font-bold border-2 border-slate-300 rounded-lg py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <button onclick="increaseQuantity()" 
                            class="w-12 h-12 bg-slate-100 hover:bg-slate-200 rounded-lg font-bold text-xl transition-colors">
                        +
                    </button>
                </div>
                <p class="text-sm text-slate-500 mt-2">
                    <span id="stock-info"></span>
                </p>
            </div>

            <!-- Total Parcial -->
            <div class="mb-6 p-4 bg-green-50 rounded-lg border border-green-200">
                <div class="flex justify-between items-center">
                    <span class="text-slate-700 font-medium">Total:</span>
                    <span id="modal-total" class="text-2xl font-bold text-green-600">$0.00</span>
                </div>
            </div>

            <!-- Botones -->
            <div class="flex gap-3">
                <button onclick="closeQuantityModal()" 
                        class="flex-1 px-4 py-3 bg-slate-100 text-slate-700 rounded-lg hover:bg-slate-200 transition-colors font-medium">
                    Cancelar
                </button>
                <button onclick="addToSale()" 
                        class="flex-1 px-4 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium">
                    <i class="fas fa-plus mr-2"></i> Agregar
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    let saleItems = [];
    let currentProduct = null;
    let saleType = 'box'; // 'box' o 'unit'

    // Buscar producto por código de barras
    document.getElementById('barcode-input').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            searchByBarcode();
        }
    });

    function searchByBarcode() {
        const barcode = document.getElementById('barcode-input').value.trim();
        
        if (!barcode) {
            alert('Por favor ingresa un código de barras');
            return;
        }

        // Buscar producto
        fetch(`/api/products/search?barcode=${barcode}`)
            .then(response => response.json())
            .then(data => {
                if (data.success && data.product) {
                    currentProduct = data.product;
                    openQuantityModal();
                    document.getElementById('barcode-input').value = '';
                } else {
                    alert('Producto no encontrado');
                    document.getElementById('barcode-input').value = '';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al buscar el producto');
            });
    }

    function openQuantityModal() {
        document.getElementById('modal-product-name').textContent = currentProduct.name;
        document.getElementById('modal-product-lab').textContent = currentProduct.laboratory;
        document.getElementById('modal-product-price').textContent = `$${parseFloat(currentProduct.precio_venta).toFixed(2)} por caja`;
        
        setSaleType('box');
        document.getElementById('quantity-input').value = 1;
        updateStockInfo();
        updateModalTotal();
        
        document.getElementById('quantity-modal').classList.remove('hidden');
        document.getElementById('quantity-modal').classList.add('flex');
    }

    function closeQuantityModal() {
        document.getElementById('quantity-modal').classList.add('hidden');
        document.getElementById('quantity-modal').classList.remove('flex');
        currentProduct = null;
        document.getElementById('barcode-input').focus();
    }

    function setSaleType(type) {
        saleType = type;
        
        const btnBox = document.getElementById('btn-box');
        const btnUnit = document.getElementById('btn-unit');
        const quantityLabel = document.getElementById('quantity-label');
        
        if (type === 'box') {
            btnBox.className = 'px-4 py-3 border-2 border-blue-600 bg-blue-50 text-blue-700 rounded-lg font-medium transition-colors';
            btnUnit.className = 'px-4 py-3 border-2 border-slate-300 bg-white text-slate-700 rounded-lg font-medium hover:border-blue-600 hover:bg-blue-50 hover:text-blue-700 transition-colors';
            quantityLabel.textContent = '(Cajas)';
            document.getElementById('modal-product-price').textContent = `$${parseFloat(currentProduct.precio_venta).toFixed(2)} por caja`;
        } else {
            btnUnit.className = 'px-4 py-3 border-2 border-blue-600 bg-blue-50 text-blue-700 rounded-lg font-medium transition-colors';
            btnBox.className = 'px-4 py-3 border-2 border-slate-300 bg-white text-slate-700 rounded-lg font-medium hover:border-blue-600 hover:bg-blue-50 hover:text-blue-700 transition-colors';
            quantityLabel.textContent = '(Unidades)';
            const pricePerUnit = parseFloat(currentProduct.precio_venta) / parseInt(currentProduct.units_per_box);
            document.getElementById('modal-product-price').textContent = `$${pricePerUnit.toFixed(2)} por unidad`;
        }
        
        updateStockInfo();
        updateModalTotal();
    }

    function updateStockInfo() {
        const stockBoxes = parseInt(currentProduct.stock_boxes);
        const unitsPerBox = parseInt(currentProduct.units_per_box);
        const totalUnits = stockBoxes * unitsPerBox;
        
        if (saleType === 'box') {
            document.getElementById('stock-info').textContent = `Stock disponible: ${stockBoxes} cajas`;
            document.getElementById('quantity-input').max = stockBoxes;
        } else {
            document.getElementById('stock-info').textContent = `Stock disponible: ${totalUnits} unidades (${stockBoxes} cajas)`;
            document.getElementById('quantity-input').max = totalUnits;
        }
    }

    function increaseQuantity() {
        const input = document.getElementById('quantity-input');
        const max = parseInt(input.max);
        const current = parseInt(input.value);
        
        if (current < max) {
            input.value = current + 1;
            updateModalTotal();
        }
    }

    function decreaseQuantity() {
        const input = document.getElementById('quantity-input');
        const current = parseInt(input.value);
        
        if (current > 1) {
            input.value = current - 1;
            updateModalTotal();
        }
    }

    document.getElementById('quantity-input').addEventListener('input', updateModalTotal);

    function updateModalTotal() {
        const quantity = parseInt(document.getElementById('quantity-input').value) || 0;
        const pricePerBox = parseFloat(currentProduct.precio_venta);
        const unitsPerBox = parseInt(currentProduct.units_per_box);
        
        let total = 0;
        if (saleType === 'box') {
            total = quantity * pricePerBox;
        } else {
            const pricePerUnit = pricePerBox / unitsPerBox;
            total = quantity * pricePerUnit;
        }
        
        document.getElementById('modal-total').textContent = `$${total.toFixed(2)}`;
    }

    function addToSale() {
        const quantity = parseInt(document.getElementById('quantity-input').value);
        
        if (quantity <= 0) {
            alert('La cantidad debe ser mayor a 0');
            return;
        }

        const pricePerBox = parseFloat(currentProduct.precio_venta);
        const unitsPerBox = parseInt(currentProduct.units_per_box);
        
        let unitPrice, totalUnits, subtotal;
        
        if (saleType === 'box') {
            unitPrice = pricePerBox;
            totalUnits = quantity * unitsPerBox;
            subtotal = quantity * pricePerBox;
        } else {
            unitPrice = pricePerBox / unitsPerBox;
            totalUnits = quantity;
            subtotal = quantity * unitPrice;
        }

        const item = {
            id: currentProduct.id,
            barcode: currentProduct.barcode,
            name: currentProduct.name,
            laboratory: currentProduct.laboratory,
            type: saleType,
            quantity: quantity,
            unitPrice: unitPrice,
            totalUnits: totalUnits,
            subtotal: subtotal,
            ivaPercentage: parseFloat(currentProduct.iva_percentage) || 0
        };

        saleItems.push(item);
        renderSaleItems();
        updateTotals();
        closeQuantityModal();
    }

    function renderSaleItems() {
        const container = document.getElementById('sale-items');
        
        if (saleItems.length === 0) {
            container.innerHTML = `
                <div class="text-center py-8 text-slate-400">
                    <i class="fas fa-shopping-cart text-4xl mb-3"></i>
                    <p>No hay productos agregados</p>
                    <p class="text-sm">Escanea un código de barras para comenzar</p>
                </div>
            `;
            return;
        }

        container.innerHTML = saleItems.map((item, index) => `
            <div class="flex items-center gap-4 p-4 bg-slate-50 rounded-lg hover:bg-slate-100 transition-colors">
                <div class="flex-1">
                    <p class="font-semibold text-slate-900">${item.name}</p>
                    <p class="text-xs text-slate-500">${item.laboratory}</p>
                    <p class="text-sm text-slate-600 mt-1">
                        ${item.type === 'box' ? `${item.quantity} caja(s)` : `${item.quantity} unidad(es)`}
                        × $${item.unitPrice.toFixed(2)}
                    </p>
                </div>
                <div class="text-right">
                    <p class="text-lg font-bold text-green-600">$${item.subtotal.toFixed(2)}</p>
                    <button onclick="removeItem(${index})" 
                            class="mt-2 text-red-600 hover:text-red-700 text-sm">
                        <i class="fas fa-trash mr-1"></i> Eliminar
                    </button>
                </div>
            </div>
        `).join('');
    }

    function removeItem(index) {
        saleItems.splice(index, 1);
        renderSaleItems();
        updateTotals();
    }

    function updateTotals() {
        let subtotal = 0;
        let totalIva = 0;
        let totalUnits = 0;

        saleItems.forEach(item => {
            subtotal += item.subtotal;
            const itemIva = (item.subtotal * item.ivaPercentage) / 100;
            totalIva += itemIva;
            totalUnits += item.totalUnits;
        });

        const total = subtotal + totalIva;

        document.getElementById('subtotal').textContent = `$${subtotal.toFixed(2)}`;
        document.getElementById('iva').textContent = `$${totalIva.toFixed(2)}`;
        document.getElementById('total').textContent = `$${total.toFixed(2)}`;
        document.getElementById('item-count').textContent = saleItems.length;
        document.getElementById('unit-count').textContent = totalUnits;

        // Habilitar/deshabilitar botón de completar venta
        document.getElementById('complete-sale-btn').disabled = saleItems.length === 0;
    }

    function clearSale() {
        if (saleItems.length > 0) {
            if (confirm('¿Estás seguro de cancelar esta venta?')) {
                saleItems = [];
                renderSaleItems();
                updateTotals();
                document.getElementById('barcode-input').focus();
            }
        }
    }

    function completeSale() {
        if (saleItems.length === 0) {
            alert('No hay productos en la venta');
            return;
        }

        if (confirm('¿Confirmar venta?')) {
            // Enviar venta al servidor
            fetch('/ventas/store', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    items: saleItems
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('¡Venta completada exitosamente!');
                    saleItems = [];
                    renderSaleItems();
                    updateTotals();
                    document.getElementById('barcode-input').focus();
                } else {
                    alert('Error al completar la venta: ' + (data.message || 'Error desconocido'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al procesar la venta');
            });
        }
    }

    // Focus en el input al cargar
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('barcode-input').focus();
    });
</script>
@endsection
