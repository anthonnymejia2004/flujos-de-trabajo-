@extends('layouts.app')

@section('title', 'Agregar Producto - Pharma-Sync')

@section('content')
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-slate-900">Agregar Nuevo Producto</h1>
        <p class="text-slate-600 mt-1">Completa el formulario para agregar un producto al inventario</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <form action="{{ route('inventario.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Código de Barras -->
                <div>
                    <label class="block text-sm font-medium text-slate-900 mb-2">Código de Barras</label>
                    <input type="text" name="barcode" value="{{ old('barcode') }}" required
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('barcode') border-red-500 @enderror">
                    @error('barcode')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <!-- Nombre -->
                <div>
                    <label class="block text-sm font-medium text-slate-900 mb-2">Nombre del Producto</label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('name') border-red-500 @enderror">
                    @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <!-- Laboratorio (Opcional) -->
                <div>
                    <label class="block text-sm font-medium text-slate-900 mb-2">
                        Laboratorio 
                        <span class="text-slate-500 text-xs font-normal">(Opcional)</span>
                    </label>
                    <input type="text" name="laboratory" value="{{ old('laboratory') }}"
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('laboratory') border-red-500 @enderror"
                        placeholder="Ej: Bayer, Pfizer, etc.">
                    @error('laboratory')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <!-- Stock Cajas -->
                <div>
                    <label class="block text-sm font-medium text-slate-900 mb-2">Stock (Cajas Completas)</label>
                    <input type="number" name="stock_boxes" id="stock_boxes" value="{{ old('stock_boxes') }}" required min="0"
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('stock_boxes') border-red-500 @enderror"
                        oninput="calculateTotals()">
                    @error('stock_boxes')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    <p class="text-xs text-slate-500 mt-1">Cantidad de cajas cerradas completas</p>
                </div>

                <!-- Unidades por Caja -->
                <div>
                    <label class="block text-sm font-medium text-slate-900 mb-2">Unidades por Caja</label>
                    <input type="number" name="units_per_box" id="units_per_box" value="{{ old('units_per_box') }}" required min="1"
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('units_per_box') border-red-500 @enderror"
                        oninput="calculateTotals()">
                    @error('units_per_box')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    <p class="text-xs text-slate-500 mt-1">Pastillas/sobres por caja</p>
                </div>

                <!-- Stock Suelto (Restos) -->
                <div>
                    <label class="block text-sm font-medium text-slate-900 mb-2">
                        Stock Suelto (Restos)
                        <span class="text-slate-500 text-xs font-normal">(Opcional)</span>
                    </label>
                    <input type="number" name="stock_loose" id="stock_loose" value="{{ old('stock_loose', 0) }}" min="0"
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('stock_loose') border-red-500 @enderror"
                        oninput="calculateTotals()">
                    @error('stock_loose')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    <p class="text-xs text-slate-500 mt-1">Unidades sueltas de una caja abierta</p>
                </div>

                <!-- Stock Total (Informativo) -->
                <div>
                    <label class="block text-sm font-medium text-slate-900 mb-2">
                        Stock Total
                        <span class="text-blue-600 text-xs font-normal">(Calculado)</span>
                    </label>
                    <input type="number" id="total_stock" name="total_stock" value="{{ old('total_stock', 0) }}" min="0"
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg bg-blue-50 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        readonly>
                    <p class="text-xs text-slate-500 mt-1">(Cajas × Unidades) + Sueltos</p>
                </div>

                <!-- Precio Costo -->
                <div>
                    <label class="block text-sm font-medium text-slate-900 mb-2">Precio de Costo (por Caja)</label>
                    <input type="number" id="cost_price" name="cost_price" value="{{ old('cost_price') }}" required min="0" step="0.01"
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('cost_price') border-red-500 @enderror"
                        oninput="calculateTotals()">
                    @error('cost_price')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    <p class="text-xs text-slate-500 mt-1">Costo total de la caja completa</p>
                </div>

                <!-- IVA -->
                <div>
                    <label class="block text-sm font-medium text-slate-900 mb-2">IVA (%)</label>
                    <input type="number" id="iva_percentage" name="iva_percentage" value="{{ old('iva_percentage', 15) }}" min="0" max="100" step="0.01"
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('iva_percentage') border-red-500 @enderror"
                        oninput="calculateTotals()">
                    @error('iva_percentage')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <!-- Ganancia Deseada -->
                <div>
                    <label class="block text-sm font-medium text-slate-900 mb-2">
                        Ganancia Deseada (por Caja)
                        <span class="text-slate-500 text-xs font-normal">(Monto en $)</span>
                    </label>
                    <input type="number" id="profit_amount" name="profit_amount" value="{{ old('profit_amount') }}" required min="0" step="0.01"
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('profit_amount') border-red-500 @enderror"
                        placeholder="Ej: 2.50"
                        oninput="calculateTotals()">
                    @error('profit_amount')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    <p class="text-xs text-slate-500 mt-1">Ganancia total por caja completa</p>
                </div>

                <!-- Precio de Venta Caja (Calculado) -->
                <div>
                    <label class="block text-sm font-medium text-slate-900 mb-2">
                        Precio Venta Caja
                        <span class="text-green-600 text-xs font-normal">(Calculado)</span>
                    </label>
                    <input type="number" id="sale_price_box" name="sale_price_box" value="{{ old('sale_price_box') }}" required min="0" step="0.01"
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg bg-green-50 focus:ring-2 focus:ring-green-500 focus:border-transparent @error('sale_price_box') border-red-500 @enderror"
                        readonly>
                    @error('sale_price_box')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    <p class="text-xs text-slate-500 mt-1">Costo + IVA + Ganancia</p>
                </div>

                <!-- Precio de Venta Unitario (Sugerido, editable) -->
                <div>
                    <label class="block text-sm font-medium text-slate-900 mb-2">
                        Precio Venta Unitario
                        <span class="text-amber-600 text-xs font-normal">(Editable)</span>
                    </label>
                    <input type="number" id="sale_price_unit" name="sale_price_unit" value="{{ old('sale_price_unit') }}" required min="0" step="0.01"
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg bg-amber-50 focus:ring-2 focus:ring-amber-500 focus:border-transparent @error('sale_price_unit') border-red-500 @enderror"
                        oninput="markUnitPriceAsManuallyEdited()">
                    @error('sale_price_unit')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    <p class="text-xs text-slate-500 mt-1">Precio por unidad individual (Caja ÷ Unidades)</p>
                </div>

                <!-- Fecha de Vencimiento -->
                <div>
                    <label class="block text-sm font-medium text-slate-900 mb-2">Fecha de Vencimiento</label>
                    <input type="date" name="expiration_date" value="{{ old('expiration_date') }}" required
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('expiration_date') border-red-500 @enderror">
                    @error('expiration_date')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <!-- Botones -->
            <div class="flex gap-3 pt-4 border-t border-slate-200">
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition-colors">
                    <i class="fas fa-save mr-2"></i> Guardar Producto
                </button>
                <a href="{{ route('inventario.index') }}" class="px-6 py-2 bg-slate-200 text-slate-900 rounded-lg font-medium hover:bg-slate-300 transition-colors">
                    <i class="fas fa-times mr-2"></i> Cancelar
                </a>
            </div>
        </form>
    </div>

    <script>
        // Variable para rastrear si el precio unitario fue editado manualmente
        let unitPriceManuallyEdited = false;

        /**
         * Marca que el precio unitario fue editado manualmente por el usuario
         */
        function markUnitPriceAsManuallyEdited() {
            unitPriceManuallyEdited = true;
        }

        /**
         * Calcula todos los totales: stock total, precio de caja y precio unitario
         * Se ejecuta en tiempo real cuando el usuario modifica los inputs
         */
        function calculateTotals() {
            // Obtener valores de los inputs
            const stockBoxes = parseInt(document.getElementById('stock_boxes').value) || 0;
            const unitsPerBox = parseInt(document.getElementById('units_per_box').value) || 1;
            const stockLoose = parseInt(document.getElementById('stock_loose').value) || 0;
            const costPrice = parseFloat(document.getElementById('cost_price').value) || 0;
            const ivaPercentage = parseFloat(document.getElementById('iva_percentage').value) || 0;
            const profitAmount = parseFloat(document.getElementById('profit_amount').value) || 0;

            // 1. Calcular Stock Total
            const totalStock = (stockBoxes * unitsPerBox) + stockLoose;
            document.getElementById('total_stock').value = totalStock;

            // 2. Calcular Precio de Venta de la Caja
            // Fórmula: Costo + (Costo × IVA%) + Ganancia
            const ivaAmount = costPrice * (ivaPercentage / 100);
            const salePriceBox = costPrice + ivaAmount + profitAmount;
            document.getElementById('sale_price_box').value = salePriceBox.toFixed(2);

            // 3. Calcular Precio Unitario Sugerido (solo si no fue editado manualmente)
            if (!unitPriceManuallyEdited && unitsPerBox > 0) {
                const suggestedUnitPrice = salePriceBox / unitsPerBox;
                document.getElementById('sale_price_unit').value = suggestedUnitPrice.toFixed(4);
            }
        }

        /**
         * Reinicia el estado de edición manual cuando cambian los costos base
         * Esto permite que el precio unitario se recalcule automáticamente
         */
        function resetManualEditFlag() {
            unitPriceManuallyEdited = false;
        }

        // Agregar event listeners a los inputs de costos base
        document.addEventListener('DOMContentLoaded', function() {
            const costPriceInput = document.getElementById('cost_price');
            const ivaInput = document.getElementById('iva_percentage');
            const profitInput = document.getElementById('profit_amount');
            const unitsPerBoxInput = document.getElementById('units_per_box');

            // Cuando cambian los costos base, reiniciar el flag de edición manual
            if (costPriceInput) {
                costPriceInput.addEventListener('change', resetManualEditFlag);
            }
            if (ivaInput) {
                ivaInput.addEventListener('change', resetManualEditFlag);
            }
            if (profitInput) {
                profitInput.addEventListener('change', resetManualEditFlag);
            }
            if (unitsPerBoxInput) {
                unitsPerBoxInput.addEventListener('change', resetManualEditFlag);
            }

            // Calcular al cargar la página si hay valores previos
            calculateTotals();
        });
    </script>
@endsection
