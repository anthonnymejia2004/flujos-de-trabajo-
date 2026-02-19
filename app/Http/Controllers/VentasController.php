<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class VentasController extends Controller
{
    public function index()
    {
        $products = Product::where('stock_boxes', '>', 0)->get();
        return view('ventas.index', compact('products'));
    }

    /**
     * Buscar producto por código de barras (API)
     */
    public function searchByBarcode(Request $request)
    {
        $barcode = $request->get('barcode');
        
        if (!$barcode) {
            return response()->json([
                'success' => false,
                'message' => 'Código de barras requerido'
            ]);
        }

        $product = Product::where('barcode', $barcode)
            ->where('stock_boxes', '>', 0)
            ->first();

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Producto no encontrado o sin stock'
            ]);
        }

        return response()->json([
            'success' => true,
            'product' => $product
        ]);
    }

    /**
     * Procesar y guardar una venta
     */
    public function store(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.type' => 'required|in:box,unit',
            'items.*.totalUnits' => 'required|integer|min:1'
        ]);

        try {
            DB::beginTransaction();

            $saleTotal = 0;
            $saleItems = [];
            $totalUnits = 0;

            // Procesar cada item de la venta
            foreach ($request->items as $item) {
                $product = Product::findOrFail($item['id']);

                // Calcular cuántas cajas se necesitan
                $boxesNeeded = 0;
                if ($item['type'] === 'box') {
                    $boxesNeeded = $item['quantity'];
                } else {
                    // Si es por unidad, calcular cuántas cajas se necesitan
                    $boxesNeeded = ceil($item['totalUnits'] / $product->units_per_box);
                }

                // Verificar stock disponible
                if ($product->stock_boxes < $boxesNeeded) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => "Stock insuficiente para {$product->name}. Disponible: {$product->stock_boxes} cajas"
                    ]);
                }

                // Actualizar stock
                $product->stock_boxes -= $boxesNeeded;
                $product->stock_units = $product->stock_boxes * $product->units_per_box;
                $product->save();

                $saleTotal += $item['subtotal'];
                $totalUnits += $item['totalUnits'];
                
                $saleItems[] = [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'product_barcode' => $product->barcode,
                    'laboratory' => $product->laboratory,
                    'quantity' => $item['quantity'],
                    'type' => $item['type'],
                    'unit_price' => $item['unitPrice'],
                    'subtotal' => $item['subtotal'],
                    'units_sold' => $item['totalUnits']
                ];
            }

            // Calcular IVA (asumiendo 19% - puedes hacer esto configurable)
            $ivaRate = 0.19;
            $subtotal = $saleTotal / (1 + $ivaRate);
            $iva = $saleTotal - $subtotal;

            // Crear la venta en la base de datos
            $sale = Sale::create([
                'sale_number' => Sale::generateSaleNumber(),
                'subtotal' => $subtotal,
                'iva' => $iva,
                'total' => $saleTotal,
                'total_items' => count($saleItems),
                'total_units' => $totalUnits,
                'user_id' => Auth::id(),
                'items' => $saleItems
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Venta completada exitosamente',
                'sale_number' => $sale->sale_number,
                'total' => $saleTotal,
                'subtotal' => $subtotal,
                'iva' => $iva,
                'items' => $saleItems,
                'sale_id' => $sale->id
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Error al procesar la venta: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener historial de ventas
     */
    public function history(Request $request)
    {
        $query = Sale::with('user')
                    ->orderBy('created_at', 'desc');

        // Filtros opcionales
        if ($request->has('fecha_desde') && $request->has('fecha_hasta')) {
            $query->betweenDates($request->fecha_desde, $request->fecha_hasta);
        }

        if ($request->has('usuario_id')) {
            $query->where('user_id', $request->usuario_id);
        }

        $sales = $query->paginate(20);

        return view('ventas.history', compact('sales'));
    }

    /**
     * Ver detalles de una venta específica
     */
    public function show(Sale $sale)
    {
        $sale->load('user');
        return view('ventas.show', compact('sale'));
    }
}
