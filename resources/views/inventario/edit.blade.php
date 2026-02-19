@extends('layouts.app')

@section('title')
    Editar Producto - {{ \App\Models\UserSetting::get('empresa_nombre', 'Pharma-Sync') }}
@endsection

@section('content')
    <div class="max-w-5xl mx-auto px-4 py-8">
        <!-- Encabezado -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-slate-900 dark:text-white">Editar Producto</h1>
            <p class="text-slate-600 dark:text-slate-400 mt-2">Actualiza los datos del producto</p>
        </div>

        <!-- Formulario -->
        <form action="{{ route('inventario.update', $product) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Sección: Información Básica -->
            <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                <h2 class="text-lg font-semibold text-slate-900 dark:text-white mb-4">Información Básica</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Nombre del Producto -->
                    <div class="md:col-span-2">
                        <label for="name" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                            Nombre del Producto *
                        </label>
                        <input 
                            type="text" 
                            id="name" 
                            name="name" 
                            required
                            class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Ej: Paracetamol 500mg"
                            value="{{ old('name', $product->name) }}"
                        >
                        @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tipo de Envase -->
                    <div>
                        <label for="package_type" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                            Tipo de Envase *
                        </label>
                        <select 
                            id="package_type" 
                            name="package_type" 
                            required
                            class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        >
                            <option value="Caja" {{ old('package_type', $product->package_type ?? 'Caja') == 'Caja' ? 'selected' : '' }}>Caja</option>
                            <option value="Sobre" {{ old('package_type', $product->package_type) == 'Sobre' ? 'selected' : '' }}>Sobre</option>
                            <option value="Ampolla" {{ old('package_type', $product->package_type) == 'Ampolla' ? 'selected' : '' }}>Ampolla</option>
                            <option value="Frasco" {{ old('package_type', $product->package_type) == 'Frasco' ? 'selected' : '' }}>Frasco</option>
                            <option value="Blíster" {{ old('package_type', $product->package_type) == 'Blíster' ? 'selected' : '' }}>Blíster</option>
                            <option value="Tubo" {{ old('package_type', $product->package_type) == 'Tubo' ? 'selected' : '' }}>Tubo</option>
                            <option value="Pomo" {{ old('package_type', $product->package_type) == 'Pomo' ? 'selected' : '' }}>Pomo</option>
                            <option value="Otros" {{ old('package_type', $product->package_type) == 'Otros' ? 'selected' : '' }}>Otros</option>
                        </select>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">La presentación se genera automáticamente</p>
                        @error('package_type')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Código del Producto -->
                    <div>
                        <label for="code" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                            Código
                        </label>
                        <input 
                            type="text" 
                            id="code" 
                            name="code"
                            class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Ej: PAR-500"
                            value="{{ old('code', $product->code) }}"
                        >
                    </div>

                    <!-- Laboratorio -->
                    <div>
                        <label for="laboratory" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                            Laboratorio
                        </label>
                        <input 
                            type="text" 
                            id="laboratory" 
                            name="laboratory"
                            class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Ej: Bayer (opcional)"
                            value="{{ old('laboratory', $product->laboratory) }}"
                        >
                        @error('laboratory')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Presentación (Oculto - se genera automáticamente) -->
                    <input type="hidden" id="presentation" name="presentation" value="{{ old('presentation', $product->presentation) }}">
                    <script>
                        function updatePresentation() {
                            const name = document.getElementById('name').value;
                            const packageType = document.getElementById('package_type').value;
                            const unitsPerBox = document.getElementById('units_per_box').value;
                            
                            if (name && packageType && unitsPerBox) {
                                const presentation = `${name} - ${packageType} x${unitsPerBox}`;
                                document.getElementById('presentation').value = presentation;
                            }
                        }
                        
                        document.getElementById('name').addEventListener('change', updatePresentation);
                        document.getElementById('package_type').addEventListener('change', updatePresentation);
                        document.getElementById('units_per_box').addEventListener('change', updatePresentation);
                        
                        // Generar presentación al cargar
                        updatePresentation();
                    </script>

                    <!-- Fecha de Vencimiento -->
                    <div>
                        <label for="expiration_date" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                            Fecha de Vencimiento *
                        </label>
                        <input 
                            type="date" 
                            id="expiration_date" 
                            name="expiration_date" 
                            required
                            class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            value="{{ old('expiration_date', $product->expiration_date?->format('Y-m-d')) }}"
                        >
                        @error('expiration_date')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Sección: Inventario Fraccionado -->
            <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-800 p-6">
                <h2 class="text-lg font-semibold text-slate-900 dark:text-white mb-4 flex items-center">
                    <i class="fas fa-boxes text-blue-600 dark:text-blue-400 mr-2"></i>
                    Inventario Fraccionado
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Stock (Cajas Cerradas) -->
                    <div>
                        <label for="stock_boxes" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                            Stock (Cajas Cerradas) *
                        </label>
                        <input 
                            type="number" 
                            id="stock_boxes" 
                            name="stock_boxes" 
                            required
                            min="0"
                            step="1"
                            class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Ej: 5, 10, 20..."
                            value="{{ old('stock_boxes', $product->stock_boxes) }}"
                        >
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Cajas completas</p>
                        @error('stock_boxes')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Unidades por Caja -->
                    <div>
                        <label for="units_per_box" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                            Unidades por Caja *
                        </label>
                        <input 
                            type="number" 
                            id="units_per_box" 
                            name="units_per_box" 
                            required
                            min="1"
                            step="1"
                            class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="20"
                            value="{{ old('units_per_box', $product->units_per_box) }}"
                        >
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Comprimidos, pastillas, etc.</p>
                        @error('units_per_box')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Stock Suelto -->
                    <div>
                        <label for="loose_stock" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                            Stock Suelto (Restos)
                        </label>
                        <input 
                            type="number" 
                            id="loose_stock" 
                            name="loose_stock"
                            min="0"
                            step="1"
                            class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Ej: 0, 5, 15..."
                            value="{{ old('loose_stock', $product->stock_loose) }}"
                        >
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Pastillas/comprimidos sueltos</p>
                        @error('loose_stock')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Total de Unidades (Calculado) -->
                    <div class="md:col-span-3">
                        <div class="bg-white dark:bg-slate-700 rounded-lg p-4 border border-blue-300 dark:border-blue-600">
                            <p class="text-sm text-slate-600 dark:text-slate-400 mb-2">Total de Unidades</p>
                            <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">
                                <span id="total_units">{{ $product->total_units ?? 0 }}</span> unidades
                            </p>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-2">
                                (<span id="total_boxes_units">{{ $product->stock_boxes * $product->units_per_box }}</span> en cajas + <span id="total_loose">{{ $product->stock_loose }}</span> sueltas)
                            </p>
                            <p id="loose_warning" class="text-amber-600 dark:text-amber-400 text-xs mt-2 hidden">
                                ⚠️ Tienes más sueltas que unidades por caja. Considera convertirlas en cajas.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sección: Precios -->
            <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                <h2 class="text-lg font-semibold text-slate-900 dark:text-white mb-4 flex items-center">
                    <i class="fas fa-dollar-sign text-green-600 dark:text-green-400 mr-2"></i>
                    Precios
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Precio Costo -->
                    <div>
                        <label for="cost_price" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                            Precio Costo (por Caja) *
                        </label>
                        <input 
                            type="number" 
                            id="cost_price" 
                            name="cost_price" 
                            required
                            min="0"
                            step="0.01"
                            class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Ej: 15.50, 20.00..."
                            value="{{ old('cost_price', $product->cost_price) }}"
                        >
                        @error('cost_price')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Precio Venta Caja -->
                    <div>
                        <label for="precio_venta" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                            Precio Venta (por Caja) *
                        </label>
                        <input 
                            type="number" 
                            id="precio_venta" 
                            name="precio_venta" 
                            required
                            min="0"
                            step="0.01"
                            class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Ej: 18.00, 25.50..."
                            value="{{ old('precio_venta', $product->precio_venta) }}"
                        >
                        <p id="price_warning" class="text-red-500 text-sm mt-1 hidden">⚠️ El precio de venta es menor al costo. Tendrás pérdidas.</p>
                        @error('precio_venta')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Precio Venta Unitario -->
                    <div>
                        <label for="precio_venta_unitario" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                            Precio Unitario (Sugerido)
                        </label>
                        <input 
                            type="number" 
                            id="precio_venta_unitario" 
                            name="precio_venta_unitario"
                            min="0"
                            step="0.01"
                            class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-blue-50 dark:bg-blue-900/20 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Se calcula automáticamente"
                            value="{{ old('precio_venta_unitario', $product->precio_venta_unitario) }}"
                        >
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Se calcula automáticamente (editable)</p>
                    </div>

                    <!-- IVA -->
                    <div>
                        <label for="iva" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                            IVA (%)
                        </label>
                        <input 
                            type="number" 
                            id="iva" 
                            name="iva"
                            min="0"
                            max="100"
                            step="0.01"
                            class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="{{ \App\Models\UserSetting::get('iva_global', 15) }}"
                            value="{{ old('iva', $product->iva ?? \App\Models\UserSetting::get('iva_global', 15)) }}"
                        >
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">IVA global del sistema: {{ \App\Models\UserSetting::get('iva_global', 15) }}%</p>
                    </div>

                    <!-- Margen de Ganancia -->
                    <div>
                        <label for="profit_margin" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                            Margen de Ganancia (%)
                        </label>
                        <input 
                            type="number" 
                            id="profit_margin" 
                            name="profit_margin"
                            min="0"
                            step="0.01"
                            class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white placeholder:text-slate-400 placeholder:opacity-50 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="{{ \App\Models\UserSetting::get('margen_ganancia_global', 30) }}"
                            value="{{ old('profit_margin', $product->profit_margin) }}"
                        >
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                            Margen global: {{ \App\Models\UserSetting::get('margen_ganancia_global', 30) }}% (opcional, se usa el global si está vacío)
                        </p>
                    </div>

                    <!-- Ganancia Estimada -->
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                            Ganancia Estimada (por Caja)
                        </label>
                        <div class="bg-green-50 dark:bg-green-900/20 rounded-lg p-3 border border-green-200 dark:border-green-800">
                            <p class="text-lg font-bold text-green-600 dark:text-green-400">
                                $<span id="profit_amount">{{ number_format($product->profit_amount ?? 0, 2) }}</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Botones de Acción -->
            <div class="flex gap-4 justify-end">
                <a href="{{ route('inventario.index') }}" class="px-6 py-2 border border-slate-300 dark:border-slate-600 rounded-lg text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">
                    Cancelar
                </a>
                <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
                    Actualizar Producto
                </button>
            </div>
        </form>
    </div>

    <!-- Script para Cálculos -->
    <script>
        const stockBoxesInput = document.getElementById('stock_boxes');
        const unitsPerBoxInput = document.getElementById('units_per_box');
        const looseStockInput = document.getElementById('loose_stock');
        const precioVentaInput = document.getElementById('precio_venta');
        const precioVentaUnitarioInput = document.getElementById('precio_venta_unitario');
        const costPriceInput = document.getElementById('cost_price');
        const profitMarginInput = document.getElementById('profit_margin');
        const ivaInput = document.getElementById('iva');

        const totalUnitsSpan = document.getElementById('total_units');
        const totalBoxesUnitsSpan = document.getElementById('total_boxes_units');
        const totalLooseSpan = document.getElementById('total_loose');
        const profitAmountSpan = document.getElementById('profit_amount');
        const priceWarning = document.getElementById('price_warning');
        const looseWarning = document.getElementById('loose_warning');

        function calculateTotals() {
            const stockBoxes = parseInt(stockBoxesInput.value) || 0;
            const unitsPerBox = parseInt(unitsPerBoxInput.value) || 1;
            const looseStock = parseInt(looseStockInput.value) || 0;
            
            // Fórmula: Total = (Cajas × Unidades Por Caja) + Stock Suelto
            const unitsInBoxes = stockBoxes * unitsPerBox;
            const totalUnits = unitsInBoxes + looseStock;

            totalUnitsSpan.textContent = totalUnits;
            totalBoxesUnitsSpan.textContent = unitsInBoxes;
            totalLooseSpan.textContent = looseStock;

            // Advertencia si stock suelto >= unidades por caja
            if (looseStock >= unitsPerBox && unitsPerBox > 0) {
                looseWarning.classList.remove('hidden');
                looseStockInput.classList.add('border-amber-500');
            } else {
                looseWarning.classList.add('hidden');
                looseStockInput.classList.remove('border-amber-500');
            }
        }

        function calculatePrices() {
            const precioVenta = parseFloat(precioVentaInput.value) || 0;
            const costPrice = parseFloat(costPriceInput.value) || 0;

            // Validación: Precio de venta menor al costo
            if (precioVenta > 0 && costPrice > 0 && precioVenta < costPrice) {
                priceWarning.classList.remove('hidden');
                precioVentaInput.classList.add('border-red-500', 'bg-red-50', 'dark:bg-red-900/20');
            } else {
                priceWarning.classList.add('hidden');
                precioVentaInput.classList.remove('border-red-500', 'bg-red-50', 'dark:bg-red-900/20');
            }

            // Calcular ganancia por caja (no por stock total)
            const ganancia = precioVenta - costPrice;
            profitAmountSpan.textContent = ganancia.toFixed(2);
            
            // Cambiar color de ganancia según sea positiva o negativa
            const profitContainer = profitAmountSpan.parentElement;
            if (ganancia < 0) {
                profitAmountSpan.classList.remove('text-green-600', 'dark:text-green-400');
                profitAmountSpan.classList.add('text-red-600', 'dark:text-red-400');
                profitContainer.classList.remove('bg-green-50', 'dark:bg-green-900/20', 'border-green-200', 'dark:border-green-800');
                profitContainer.classList.add('bg-red-50', 'dark:bg-red-900/20', 'border-red-200', 'dark:border-red-800');
            } else {
                profitAmountSpan.classList.remove('text-red-600', 'dark:text-red-400');
                profitAmountSpan.classList.add('text-green-600', 'dark:text-green-400');
                profitContainer.classList.remove('bg-red-50', 'dark:bg-red-900/20', 'border-red-200', 'dark:border-red-800');
                profitContainer.classList.add('bg-green-50', 'dark:bg-green-900/20', 'border-green-200', 'dark:border-green-800');
            }
        }

        // Función para sincronizar margen y precio de venta
        function syncMarginAndPrice(changedField) {
            const costPrice = parseFloat(costPriceInput.value) || 0;
            const profitMargin = parseFloat(profitMarginInput.value) || 0;
            const precioVenta = parseFloat(precioVentaInput.value) || 0;

            if (costPrice === 0) return;

            // Si cambió el margen, recalcular precio de venta
            if (changedField === 'margin' && profitMargin > 0) {
                const newPrecioVenta = costPrice * (1 + profitMargin / 100);
                precioVentaInput.value = newPrecioVenta.toFixed(2);
                calculatePrices(); // Recalcular después del cambio
            }
            
            // Si cambió el precio de venta, recalcular margen
            if (changedField === 'price' && precioVenta > 0) {
                const newMargin = ((precioVenta - costPrice) / costPrice) * 100;
                profitMarginInput.value = newMargin.toFixed(2);
            }
        }

        // Event listeners con input y change para actualización en tiempo real
        stockBoxesInput.addEventListener('input', calculateTotals);
        stockBoxesInput.addEventListener('change', calculateTotals);
        
        unitsPerBoxInput.addEventListener('input', () => {
            calculateTotals();
            calculatePrices();
        });
        unitsPerBoxInput.addEventListener('change', () => {
            calculateTotals();
            calculatePrices();
        });
        
        looseStockInput.addEventListener('input', calculateTotals);
        looseStockInput.addEventListener('change', calculateTotals);
        
        precioVentaInput.addEventListener('input', () => {
            calculatePrices();
            syncMarginAndPrice('price');
        });
        precioVentaInput.addEventListener('change', () => {
            calculatePrices();
            syncMarginAndPrice('price');
        });
        
        costPriceInput.addEventListener('input', calculatePrices);
        costPriceInput.addEventListener('change', calculatePrices);
        
        precioVentaUnitarioInput.addEventListener('input', function() {
            // No hacer nada - el usuario puede editar libremente
        });
        precioVentaUnitarioInput.addEventListener('change', function() {
            // No hacer nada - el usuario puede editar libremente
        });
        
        profitMarginInput.addEventListener('input', () => {
            syncMarginAndPrice('margin');
        });
        profitMarginInput.addEventListener('change', () => {
            syncMarginAndPrice('margin');
        });
        
        ivaInput.addEventListener('input', calculatePrices);
        ivaInput.addEventListener('change', calculatePrices);

        // Calcular al cargar
        calculateTotals();
        calculatePrices();

        // Seleccionar todo el contenido al hacer clic en campos numéricos
        const numericInputs = [
            stockBoxesInput, unitsPerBoxInput, looseStockInput,
            costPriceInput, precioVentaInput, precioVentaUnitarioInput,
            profitMarginInput, ivaInput
        ];

        numericInputs.forEach(input => {
            input.addEventListener('focus', function() {
                this.select();
            });
            
            input.addEventListener('click', function() {
                this.select();
            });
        });
    </script>
@endsection
