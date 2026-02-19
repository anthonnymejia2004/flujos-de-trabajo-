<?php

namespace App\Http\Controllers;

use App\Models\Product; // Importamos tu modelo con el IVA Híbrido
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $products = Product::all();
        
        // 1. Calculamos el Valor Total de Venta usando la lógica que ya tienes en el Modelo
        $totalVenta = $products->sum(function($p) {
            return $p->precio_venta * $p->stock_boxes; 
        });

        // 2. Filtramos productos con Stock Bajo (menos de 5 cajas)
        $stockBajo = Product::where('stock_boxes', '<', 5)->get();
        
        // 3. Filtramos vencimientos para los próximos 30 días
        $proximosVencer = Product::where('expiration_date', '<=', Carbon::now()->addDays(30))
            ->orderBy('expiration_date', 'asc')
            ->get();

        return view('dashboard', compact('products', 'totalVenta', 'stockBajo', 'proximosVencer'));
    }
}