<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportesController extends Controller
{
    public function index(Request $request)
    {
        // Obtener filtros de fecha si existen
        $fechaDesde = $request->get('desde');
        $fechaHasta = $request->get('hasta');
        
        $products = Product::all();
        
        // === MÉTRICAS DE INVENTARIO ===
        $totalVenta = $products->sum(fn($p) => $p->precio_venta * $p->stock_boxes);
        $totalCosto = $products->sum(fn($p) => $p->cost_price * $p->stock_boxes);
        $gananciaEstimada = $totalVenta - $totalCosto;
        $totalProductos = $products->count();
        
        // Calcular margen promedio
        $margenPromedio = $products->where('profit_margin', '>', 0)->avg('profit_margin') ?? 0;
        
        // === MÉTRICAS DE VENTAS ===
        // Ventas del día
        $ventasHoy = Sale::today()->sum('total');
        $transaccionesHoy = Sale::today()->count();
        
        // Ventas del mes actual
        $ventasMes = Sale::thisMonth()->sum('total');
        $transaccionesMes = Sale::thisMonth()->count();
        
        // Dinero total movido (todas las ventas)
        $dineroTotalMovido = Sale::sum('total');
        $totalTransacciones = Sale::count();
        
        // Promedio de venta diaria (últimos 30 días)
        $fechaInicio30Dias = Carbon::now()->subDays(30);
        $ventasUltimos30Dias = Sale::where('created_at', '>=', $fechaInicio30Dias)->sum('total');
        $promedioDiario = $ventasUltimos30Dias / 30;
        
        // Comparativa con mes anterior
        $mesAnteriorInicio = Carbon::now()->subMonth()->startOfMonth();
        $mesAnteriorFin = Carbon::now()->subMonth()->endOfMonth();
        $ventasMesAnterior = Sale::betweenDates($mesAnteriorInicio, $mesAnteriorFin)->sum('total');
        $crecimientoMensual = $ventasMesAnterior > 0 ? (($ventasMes - $ventasMesAnterior) / $ventasMesAnterior) * 100 : 0;
        
        // Top productos vendidos (por cantidad)
        $topProductosVendidos = $this->getTopSellingProducts();
        
        // Ventas por día (últimos 7 días para gráfico)
        $ventasPorDia = $this->getSalesPerDay(7);
        
        // === ALERTAS Y NOTIFICACIONES ===
        $stockBajo = Product::where('stock_boxes', '<', 5)->count();
        $proximosVencer = Product::where('expiration_date', '<=', Carbon::now()->addDays(30))
            ->where('expiration_date', '>', Carbon::now())
            ->count();
        
        // Análisis de vencimientos detallado
        $vencidos = Product::where('expiration_date', '<', Carbon::now())->count();
        $proximos7Dias = Product::where('expiration_date', '<=', Carbon::now()->addDays(7))
            ->where('expiration_date', '>', Carbon::now())
            ->count();
        $proximos30Dias = Product::where('expiration_date', '<=', Carbon::now()->addDays(30))
            ->where('expiration_date', '>', Carbon::now()->addDays(7))
            ->count();
        $mas30Dias = Product::where('expiration_date', '>', Carbon::now()->addDays(30))->count();
        
        // Distribución por laboratorio
        $laboratorios = Product::select('laboratory', DB::raw('count(*) as count'))
            ->groupBy('laboratory')
            ->orderBy('count', 'desc')
            ->limit(10)
            ->get();
        
        // Top 10 productos por valor de inventario
        $topProductos = $products->sortByDesc(function($product) {
            return $product->precio_venta * $product->stock_boxes;
        })->take(10);
        
        // Productos con mayor stock (posible baja rotación)
        $mayorStock = $products->sortByDesc('stock_boxes')->take(10);
        
        return view('reportes.index', compact(
            // Métricas de inventario
            'totalVenta',
            'totalCosto', 
            'gananciaEstimada',
            'totalProductos',
            'margenPromedio',
            
            // Métricas de ventas
            'ventasHoy',
            'transaccionesHoy',
            'ventasMes',
            'transaccionesMes',
            'dineroTotalMovido',
            'totalTransacciones',
            'promedioDiario',
            'ventasMesAnterior',
            'crecimientoMensual',
            'topProductosVendidos',
            'ventasPorDia',
            
            // Alertas
            'stockBajo',
            'proximosVencer',
            'vencidos',
            'proximos7Dias',
            'proximos30Dias',
            'mas30Dias',
            'laboratorios',
            'topProductos',
            'mayorStock',
            'products'
        ));
    }

    /**
     * Obtener productos más vendidos
     */
    private function getTopSellingProducts($limit = 10)
    {
        $sales = Sale::all();
        $productSales = [];
        
        foreach ($sales as $sale) {
            foreach ($sale->items as $item) {
                $productId = $item['product_id'];
                $unitsSold = $item['units_sold'] ?? $item['quantity'];
                
                if (!isset($productSales[$productId])) {
                    $productSales[$productId] = [
                        'product_id' => $productId,
                        'product_name' => $item['product_name'],
                        'laboratory' => $item['laboratory'] ?? '',
                        'total_units_sold' => 0,
                        'total_revenue' => 0,
                        'total_transactions' => 0
                    ];
                }
                
                $productSales[$productId]['total_units_sold'] += $unitsSold;
                $productSales[$productId]['total_revenue'] += $item['subtotal'];
                $productSales[$productId]['total_transactions']++;
            }
        }
        
        // Ordenar por unidades vendidas y tomar los top
        $topProducts = collect($productSales)
            ->sortByDesc('total_units_sold')
            ->take($limit)
            ->values();
            
        return $topProducts;
    }

    /**
     * Obtener ventas por día
     */
    private function getSalesPerDay($days = 7)
    {
        $salesPerDay = [];
        
        for ($i = $days - 1; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $total = Sale::whereDate('created_at', $date)->sum('total');
            
            $salesPerDay[] = [
                'date' => $date->format('Y-m-d'),
                'day' => $date->format('d/m'),
                'total' => $total
            ];
        }
        
        return $salesPerDay;
    }
}
