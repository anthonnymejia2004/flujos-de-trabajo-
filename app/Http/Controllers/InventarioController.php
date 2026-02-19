<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InventarioController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();
        $filter = $request->get('filter');
        $filterTitle = 'Todos los Productos';

        // Aplicar filtros según el parámetro
        if ($filter === 'stock_bajo') {
            $query->where('stock_boxes', '<', 5);
            $filterTitle = 'Productos con Stock Bajo';
        } elseif ($filter === 'proximos_vencer') {
            $query->where('expiration_date', '<=', now()->addDays(30))
                  ->where('expiration_date', '>=', now())
                  ->orderBy('expiration_date', 'asc');
            $filterTitle = 'Productos Próximos a Vencer';
        } else {
            $query->orderBy('name', 'asc');
        }

        $products = $query->get();
        
        return view('inventario.index', compact('products', 'filterTitle', 'filter'));
    }

    public function create()
    {
        return view('inventario.create-responsive');
    }

    public function store(Request $request)
    {
        try {
            // Validar datos
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'code' => 'nullable|string|max:100|unique:products',
                'laboratory' => 'nullable|string|max:255',
                'package_type' => 'required|string|max:100',
                'presentation' => 'nullable|string|max:255',
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

            // Total de unidades: (Cajas × Unidades Por Caja) + Stock Suelto
            $totalUnits = ($stockBoxes * $unitsPerBox) + $looseStock;

            // Obtener IVA global del sistema si no se proporciona
            $iva = $validated['iva'] ?? \App\Models\UserSetting::get('iva_global', 15);

            // Generar presentación si no fue proporcionada
            $presentation = $validated['presentation'] ?? Product::generatePresentation(
                $validated['name'],
                $validated['package_type'],
                $unitsPerBox
            );

            // Calcular precio unitario si no fue proporcionado
            $precioVentaUnitario = $validated['precio_venta_unitario'] ?? 0;

            // Calcular ganancia
            $ganancia = $validated['precio_venta'] - $validated['cost_price'];
            $gananciaTotal = $ganancia * $stockBoxes;

            // Generar barcode único
            $barcode = 'PROD-' . time() . '-' . rand(1000, 9999);

            // Crear producto
            $product = Product::create([
                'barcode' => $barcode,
                'name' => $validated['name'],
                'code' => $validated['code'],
                'laboratory' => $validated['laboratory'],
                'package_type' => $validated['package_type'],
                'presentation' => $presentation,
                'expiration_date' => $validated['expiration_date'],
                'stock_boxes' => $stockBoxes,
                'units_per_box' => $unitsPerBox,
                'stock_loose' => $looseStock,
                'stock_units' => $totalUnits,
                'total_units' => $totalUnits,
                'cost_price' => $validated['cost_price'],
                'precio_venta' => $validated['precio_venta'],
                'precio_venta_unitario' => round($precioVentaUnitario, 4),
                'iva_percentage' => $iva,
                'profit_margin' => $validated['profit_margin'] ?? 30,
                'profit_amount' => round($ganancia, 2),
                'profit_amount_total' => round($gananciaTotal, 2),
            ]);

            return redirect()
                ->route('inventario.index')
                ->with('success', "Producto '{$product->name}' agregado exitosamente");
        } catch (\Exception $e) {
            \Log::error('Error al guardar producto: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al guardar el producto: ' . $e->getMessage());
        }
    }

    public function edit(Product $product)
    {
        return view('inventario.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'code' => 'nullable|string|max:100|unique:products,code,' . $product->id,
                'laboratory' => 'nullable|string|max:255',
                'package_type' => 'required|string|max:100',
                'presentation' => 'nullable|string|max:255',
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
            
            // Total de unidades: (Cajas × Unidades Por Caja) + Stock Suelto
            $totalUnits = ($stockBoxes * $unitsPerBox) + $looseStock;

            // Obtener IVA global del sistema si no se proporciona
            $iva = $validated['iva'] ?? \App\Models\UserSetting::get('iva_global', 15);

            // Generar presentación si no fue proporcionada
            $presentation = $validated['presentation'] ?? Product::generatePresentation(
                $validated['name'],
                $validated['package_type'],
                $unitsPerBox
            );

            // Calcular precio unitario
            $precioVentaUnitario = $validated['precio_venta_unitario'] ?? 0;

            // Calcular ganancia
            $ganancia = $validated['precio_venta'] - $validated['cost_price'];
            $gananciaTotal = $ganancia * $stockBoxes;

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
                'stock_loose' => $looseStock,
                'stock_units' => $totalUnits,
                'total_units' => $totalUnits,
                'cost_price' => $validated['cost_price'],
                'precio_venta' => $validated['precio_venta'],
                'precio_venta_unitario' => round($precioVentaUnitario, 4),
                'iva_percentage' => $iva,
                'profit_margin' => $validated['profit_margin'] ?? 30,
                'profit_amount' => round($ganancia, 2),
                'profit_amount_total' => round($gananciaTotal, 2),
            ]);

            return redirect()
                ->route('inventario.index')
                ->with('success', "Producto '{$product->name}' actualizado exitosamente");
        } catch (\Exception $e) {
            \Log::error('Error al actualizar producto: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al actualizar el producto: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        // Log de inicio
        \Log::info("=== INICIO DESTROY ===");
        \Log::info("ID recibido: " . $id);
        \Log::info("Tipo de ID: " . gettype($id));
        
        try {
            // Verificar que el ID es válido
            if (!is_numeric($id) || $id <= 0) {
                \Log::error("ID inválido: {$id}");
                return redirect()->route('inventario.index')
                    ->with('error', 'ID de producto inválido');
            }
            
            // Contar productos antes
            $countBefore = Product::count();
            \Log::info("Productos antes de eliminar: {$countBefore}");
            
            // Buscar el producto
            $product = Product::where('id', $id)->first();
            \Log::info("Producto encontrado: " . ($product ? 'SI' : 'NO'));
            
            if (!$product) {
                \Log::error("Producto con ID {$id} no encontrado en la base de datos");
                return redirect()->route('inventario.index')
                    ->with('error', "No se encontró el producto con ID {$id}");
            }
            
            $productName = $product->name;
            \Log::info("Nombre del producto: {$productName}");
            
            // Eliminar usando query builder directo
            $deleted = \DB::table('products')->where('id', $id)->delete();
            \Log::info("Filas eliminadas por query builder: {$deleted}");
            
            // Contar productos después
            $countAfter = Product::count();
            \Log::info("Productos después de eliminar: {$countAfter}");
            
            // Verificar que se eliminó
            $verification = Product::find($id);
            \Log::info("Verificación - producto aún existe: " . ($verification ? 'SI' : 'NO'));
            
            if ($deleted > 0 && !$verification && $countAfter < $countBefore) {
                \Log::info("=== ELIMINACIÓN EXITOSA ===");
                
                if (request()->expectsJson()) {
                    return response()->json([
                        'success' => true, 
                        'message' => "Producto '{$productName}' eliminado exitosamente"
                    ]);
                }
                
                return redirect()->route('inventario.index')
                    ->with('success', "Producto '{$productName}' eliminado exitosamente");
            } else {
                \Log::error("=== ELIMINACIÓN FALLÓ ===");
                
                if (request()->expectsJson()) {
                    return response()->json(['success' => false, 'message' => 'No se pudo eliminar el producto'], 500);
                }
                
                return redirect()->route('inventario.index')
                    ->with('error', 'No se pudo eliminar el producto');
            }
            
        } catch (\Exception $e) {
            \Log::error("=== ERROR EN DESTROY ===");
            \Log::error("Mensaje: " . $e->getMessage());
            \Log::error("Archivo: " . $e->getFile() . " Línea: " . $e->getLine());
            \Log::error("Stack trace: " . $e->getTraceAsString());
            
            if (request()->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Error interno: ' . $e->getMessage()], 500);
            }
            
            return redirect()->route('inventario.index')
                ->with('error', 'Error interno: ' . $e->getMessage());
        }
    }
}
