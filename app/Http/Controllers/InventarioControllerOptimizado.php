<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class InventarioControllerOptimizado extends Controller
{
    /**
     * Mostrar formulario para crear nuevo producto
     */
    public function create()
    {
        return view('inventario.create-responsive');
    }

    /**
     * Guardar nuevo producto con inventario fraccionado
     */
    public function store(Request $request)
    {
        // Validar datos
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:100|unique:products',
            'laboratory' => 'required|string|max:255',
            'package_type' => 'required|string|max:100',
            'expiration_date' => 'required|date',
            'stock_boxes' => 'required|integer|min:0',
            'units_per_box' => 'required|integer|min:1',
            'loose_stock' => 'nullable|integer|min:0',
            'cost_price' => 'required|numeric|min:0',
            'precio_venta' => 'required|numeric|min:0',
            'precio_venta_unitario' => 'nullable|numeric|min:0',
            'iva' => 'nullable|numeric|min:0|max:100',
            'profit_margin' => 'nullable|numeric|min:0',
        ]);

        // Calcular totales de inventario
        $stockBoxes = $validated['stock_boxes'];
        $unitsPerBox = $validated['units_per_box'];
        $looseStock = $validated['loose_stock'] ?? 0;

        // Total de unidades
        $totalUnits = ($stockBoxes * $unitsPerBox) + $looseStock;

        // Calcular precio unitario si no fue proporcionado
        $precioVentaUnitario = $validated['precio_venta_unitario'] ?? 
            ($unitsPerBox > 0 ? $validated['precio_venta'] / $unitsPerBox : 0);

        // Calcular ganancia
        $ganancia = $validated['precio_venta'] - $validated['cost_price'];
        $gananciaTotal = $ganancia * $stockBoxes;

        // Usar margen global si no se proporcionó uno específico
        $profitMargin = $validated['profit_margin'] ?? \App\Models\UserSetting::get('margen_ganancia_global', 30);

        // Generar presentación automáticamente
        $presentation = Product::generatePresentation(
            $validated['name'],
            $validated['package_type'],
            $unitsPerBox
        );

        // Crear producto
        $product = Product::create([
            'name' => $validated['name'],
            'code' => $validated['code'],
            'laboratory' => $validated['laboratory'],
            'package_type' => $validated['package_type'],
            'presentation' => $presentation,
            'expiration_date' => $validated['expiration_date'],
            'stock_boxes' => $stockBoxes,
            'units_per_box' => $unitsPerBox,
            'loose_stock' => $looseStock,
            'total_units' => $totalUnits,
            'cost_price' => $validated['cost_price'],
            'precio_venta' => $validated['precio_venta'],
            'precio_venta_unitario' => round($precioVentaUnitario, 2),
            'iva' => $validated['iva'] ?? 21,
            'profit_margin' => $profitMargin,
            'profit_amount' => round($ganancia, 2),
            'profit_amount_total' => round($gananciaTotal, 2),
        ]);

        return redirect()
            ->route('inventario.index')
            ->with('success', "Producto '{$product->name}' agregado exitosamente");
    }

    /**
     * Actualizar producto con inventario fraccionado
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:100|unique:products,code,' . $product->id,
            'laboratory' => 'required|string|max:255',
            'package_type' => 'required|string|max:100',
            'expiration_date' => 'required|date',
            'stock_boxes' => 'required|integer|min:0',
            'units_per_box' => 'required|integer|min:1',
            'loose_stock' => 'nullable|integer|min:0',
            'cost_price' => 'required|numeric|min:0',
            'precio_venta' => 'required|numeric|min:0',
            'precio_venta_unitario' => 'nullable|numeric|min:0',
            'iva' => 'nullable|numeric|min:0|max:100',
            'profit_margin' => 'nullable|numeric|min:0',
        ]);

        // Calcular totales
        $stockBoxes = $validated['stock_boxes'];
        $unitsPerBox = $validated['units_per_box'];
        $looseStock = $validated['loose_stock'] ?? 0;
        $totalUnits = ($stockBoxes * $unitsPerBox) + $looseStock;

        // Calcular precio unitario
        $precioVentaUnitario = $validated['precio_venta_unitario'] ?? 
            ($unitsPerBox > 0 ? $validated['precio_venta'] / $unitsPerBox : 0);

        // Calcular ganancia
        $ganancia = $validated['precio_venta'] - $validated['cost_price'];
        $gananciaTotal = $ganancia * $stockBoxes;

        // Usar margen global si no se proporcionó uno específico
        $profitMargin = $validated['profit_margin'] ?? \App\Models\UserSetting::get('margen_ganancia_global', 30);

        // Generar presentación automáticamente
        $presentation = Product::generatePresentation(
            $validated['name'],
            $validated['package_type'],
            $unitsPerBox
        );

        // Actualizar producto
        $product->update([
            'name' => $validated['name'],
            'code' => $validated['code'],
            'laboratory' => $validated['laboratory'],
            'package_type' => $validated['package_type'],
            'presentation' => $presentation,
            'expiration_date' => $validated['expiration_date'],
            'stock_boxes' => $stockBoxes,
            'units_per_box' => $unitsPerBox,
            'loose_stock' => $looseStock,
            'total_units' => $totalUnits,
            'cost_price' => $validated['cost_price'],
            'precio_venta' => $validated['precio_venta'],
            'precio_venta_unitario' => round($precioVentaUnitario, 2),
            'iva' => $validated['iva'] ?? 21,
            'profit_margin' => $profitMargin,
            'profit_amount' => round($ganancia, 2),
            'profit_amount_total' => round($gananciaTotal, 2),
        ]);

        return redirect()
            ->route('inventario.index')
            ->with('success', "Producto '{$product->name}' actualizado exitosamente");
    }

    /**
     * Obtener información de inventario fraccionado (API)
     */
    public function getInventoryInfo(Product $product)
    {
        return response()->json([
            'stock_boxes' => $product->stock_boxes,
            'units_per_box' => $product->units_per_box,
            'loose_stock' => $product->loose_stock,
            'total_units' => $product->total_units,
            'precio_venta_unitario' => $product->precio_venta_unitario,
            'profit_amount' => $product->profit_amount,
        ]);
    }
}
