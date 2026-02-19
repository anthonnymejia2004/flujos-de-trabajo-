<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserSetting;

class ConfiguracionController extends Controller
{
    public function index()
    {
        // Obtener configuraciones actuales
        $configuraciones = [
            'empresa_nombre' => UserSetting::get('empresa_nombre', 'Pharma-Sync'),
            'empresa_descripcion' => UserSetting::get('empresa_descripcion', 'Sistema de Farmacia'),
            'iva_global' => UserSetting::get('iva_global', 15),
            'margen_ganancia_global' => UserSetting::get('margen_ganancia_global', 30),
            'moneda' => UserSetting::get('moneda', 'USD'),
            'stock_minimo' => UserSetting::get('stock_minimo', 5),
            'dias_alerta_vencimiento' => UserSetting::get('dias_alerta_vencimiento', 30),
            'tema' => UserSetting::get('tema', 'light')
        ];

        return view('configuracion.index', compact('configuraciones'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'empresa_nombre' => 'nullable|string|max:255',
            'empresa_descripcion' => 'nullable|string|max:255',
            'iva_global' => 'nullable|numeric|min:0|max:100',
            'margen_ganancia_global' => 'nullable|numeric|min:0',
            'moneda' => 'nullable|string|max:10',
            'stock_minimo' => 'nullable|integer|min:1',
            'dias_alerta_vencimiento' => 'nullable|integer|min:1',
        ]);

        // Guardar configuraciones
        if ($request->filled('empresa_nombre')) {
            UserSetting::set('empresa_nombre', $request->empresa_nombre, 'string', 'Nombre de la empresa');
        }
        
        if ($request->filled('empresa_descripcion')) {
            UserSetting::set('empresa_descripcion', $request->empresa_descripcion, 'string', 'Descripción de la empresa');
        }
        
        if ($request->filled('iva_global')) {
            UserSetting::set('iva_global', $request->iva_global, 'float', 'IVA global por defecto');
        }
        
        if ($request->filled('margen_ganancia_global')) {
            UserSetting::set('margen_ganancia_global', $request->margen_ganancia_global, 'float', 'Margen de ganancia global por defecto');
        }
        
        if ($request->filled('moneda')) {
            UserSetting::set('moneda', $request->moneda, 'string', 'Moneda del sistema');
        }
        
        if ($request->filled('stock_minimo')) {
            UserSetting::set('stock_minimo', $request->stock_minimo, 'integer', 'Stock mínimo de alerta');
        }
        
        if ($request->filled('dias_alerta_vencimiento')) {
            UserSetting::set('dias_alerta_vencimiento', $request->dias_alerta_vencimiento, 'integer', 'Días de alerta de vencimiento');
        }
        
        return redirect()->route('configuracion.index')->with('success', 'Configuración actualizada exitosamente');
    }

    public function updateTheme(Request $request)
    {
        $request->validate([
            'theme' => 'required|string|in:light,dark,pharma_blue,pharma_green,pharma_purple,pharma_orange'
        ]);

        // Guardar tema en configuraciones
        UserSetting::set('tema', $request->theme, 'string', 'Tema visual del sistema');
        
        // También guardar en sesión para uso inmediato
        session(['user_theme' => $request->theme]);

        return response()->json([
            'success' => true,
            'message' => 'Tema actualizado correctamente',
            'theme' => $request->theme
        ]);
    }

    /**
     * Exportar datos del inventario a Excel/CSV
     */
    public function exportData()
    {
        $products = \App\Models\Product::all();
        
        // Crear contenido CSV
        $csvContent = "Nombre,Laboratorio,Código de Barras,Precio Costo,Precio Venta,Stock Cajas,Stock Unidades,Unidades por Caja,Fecha Vencimiento,Margen Ganancia,IVA\n";
        
        foreach ($products as $product) {
            $csvContent .= sprintf(
                '"%s","%s","%s",%s,%s,%d,%d,%d,"%s",%s,%s' . "\n",
                $product->name,
                $product->laboratory,
                $product->barcode,
                $product->cost_price,
                $product->precio_venta,
                $product->stock_boxes,
                $product->stock_units,
                $product->units_per_box,
                $product->expiration_date,
                $product->profit_margin,
                $product->iva_percentage
            );
        }
        
        $fileName = 'inventario_pharma_sync_' . date('Y-m-d_H-i-s') . '.csv';
        
        return response($csvContent)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"')
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }

    /**
     * Importar datos desde archivo CSV
     */
    public function importData(Request $request)
    {
        $request->validate([
            'import_file' => 'required|file|mimes:csv,txt|max:2048'
        ]);

        try {
            $file = $request->file('import_file');
            $csvData = file_get_contents($file->getRealPath());
            $lines = explode("\n", $csvData);
            
            // Saltar la primera línea (headers)
            array_shift($lines);
            
            $imported = 0;
            $errors = [];
            
            foreach ($lines as $lineNumber => $line) {
                if (empty(trim($line))) continue;
                
                $data = str_getcsv($line);
                
                if (count($data) < 11) {
                    $errors[] = "Línea " . ($lineNumber + 2) . ": Datos insuficientes";
                    continue;
                }
                
                try {
                    \App\Models\Product::create([
                        'name' => $data[0],
                        'laboratory' => $data[1],
                        'barcode' => $data[2],
                        'cost_price' => floatval($data[3]),
                        'stock_boxes' => intval($data[5]),
                        'stock_units' => intval($data[6]),
                        'units_per_box' => intval($data[7]),
                        'expiration_date' => $data[8],
                        'profit_margin' => floatval($data[9]),
                        'iva_percentage' => floatval($data[10])
                    ]);
                    $imported++;
                } catch (\Exception $e) {
                    $errors[] = "Línea " . ($lineNumber + 2) . ": " . $e->getMessage();
                }
            }
            
            $message = "Importación completada. {$imported} productos importados.";
            if (!empty($errors)) {
                $message .= " Errores: " . implode(', ', array_slice($errors, 0, 3));
                if (count($errors) > 3) {
                    $message .= " y " . (count($errors) - 3) . " más.";
                }
            }
            
            return redirect()->route('configuracion.index')->with('success', $message);
            
        } catch (\Exception $e) {
            return redirect()->route('configuracion.index')->with('error', 'Error al importar: ' . $e->getMessage());
        }
    }

    /**
     * Generar reporte PDF del sistema
     */
    public function generateReport()
    {
        $products = \App\Models\Product::all();
        $totalProducts = $products->count();
        $totalValue = $products->sum(function($p) { return $p->precio_venta * $p->stock_boxes; });
        $totalCost = $products->sum(function($p) { return $p->cost_price * $p->stock_boxes; });
        $lowStock = $products->where('stock_boxes', '<', 5)->count();
        $expiringSoon = $products->where('expiration_date', '<=', now()->addDays(30))->count();
        
        // Crear contenido HTML para el reporte
        $html = '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Reporte Pharma-Sync</title>
            <style>
                body { font-family: Arial, sans-serif; margin: 20px; }
                .header { text-align: center; margin-bottom: 30px; }
                .stats { display: flex; justify-content: space-around; margin: 20px 0; }
                .stat-box { text-align: center; padding: 15px; border: 1px solid #ddd; border-radius: 8px; }
                .stat-value { font-size: 24px; font-weight: bold; color: #3b82f6; }
                .stat-label { font-size: 12px; color: #666; }
                table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                th { background-color: #f8f9fa; }
                .low-stock { color: #dc3545; font-weight: bold; }
                .expiring { color: #fd7e14; font-weight: bold; }
            </style>
        </head>
        <body>
            <div class="header">
                <h1>📊 Reporte de Inventario</h1>
                <h2>Pharma-Sync</h2>
                <p>Generado el: ' . date('d/m/Y H:i:s') . '</p>
            </div>
            
            <div class="stats">
                <div class="stat-box">
                    <div class="stat-value">' . $totalProducts . '</div>
                    <div class="stat-label">Total Productos</div>
                </div>
                <div class="stat-box">
                    <div class="stat-value">$' . number_format($totalValue, 2) . '</div>
                    <div class="stat-label">Valor Total Venta</div>
                </div>
                <div class="stat-box">
                    <div class="stat-value">$' . number_format($totalCost, 2) . '</div>
                    <div class="stat-label">Valor Total Costo</div>
                </div>
                <div class="stat-box">
                    <div class="stat-value">' . $lowStock . '</div>
                    <div class="stat-label">Stock Bajo</div>
                </div>
            </div>
            
            <h3>📦 Productos con Stock Bajo</h3>
            <table>
                <tr>
                    <th>Producto</th>
                    <th>Laboratorio</th>
                    <th>Stock</th>
                    <th>Vencimiento</th>
                </tr>';
        
        foreach ($products->where('stock_boxes', '<', 5) as $product) {
            $html .= '<tr>
                <td>' . htmlspecialchars($product->name) . '</td>
                <td>' . htmlspecialchars($product->laboratory) . '</td>
                <td class="low-stock">' . $product->stock_boxes . ' cajas</td>
                <td>' . date('d/m/Y', strtotime($product->expiration_date)) . '</td>
            </tr>';
        }
        
        $html .= '</table>
            
            <h3>⏰ Productos Próximos a Vencer (30 días)</h3>
            <table>
                <tr>
                    <th>Producto</th>
                    <th>Laboratorio</th>
                    <th>Stock</th>
                    <th>Vencimiento</th>
                    <th>Días Restantes</th>
                </tr>';
        
        foreach ($products->where('expiration_date', '<=', now()->addDays(30)) as $product) {
            $daysLeft = now()->diffInDays($product->expiration_date, false);
            $html .= '<tr>
                <td>' . htmlspecialchars($product->name) . '</td>
                <td>' . htmlspecialchars($product->laboratory) . '</td>
                <td>' . $product->stock_boxes . ' cajas</td>
                <td class="expiring">' . date('d/m/Y', strtotime($product->expiration_date)) . '</td>
                <td>' . ($daysLeft >= 0 ? $daysLeft : 'VENCIDO') . '</td>
            </tr>';
        }
        
        $html .= '</table>
        </body>
        </html>';
        
        $fileName = 'reporte_pharma_sync_' . date('Y-m-d_H-i-s') . '.html';
        
        return response($html)
            ->header('Content-Type', 'text/html')
            ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"')
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate');
    }
}
