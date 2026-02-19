<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'sale_number',
        'subtotal',
        'iva',
        'total',
        'total_items',
        'total_units',
        'user_id',
        'items'
    ];

    protected $casts = [
        'items' => 'array',
        'subtotal' => 'decimal:2',
        'iva' => 'decimal:2',
        'total' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Relación con el usuario que realizó la venta
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Generar número de venta único
     */
    public static function generateSaleNumber()
    {
        $date = Carbon::now()->format('Ymd');
        $lastSale = self::whereDate('created_at', Carbon::today())
                       ->orderBy('id', 'desc')
                       ->first();
        
        $sequence = $lastSale ? (int)substr($lastSale->sale_number, -4) + 1 : 1;
        
        return $date . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Scope para ventas del día
     */
    public function scopeToday($query)
    {
        return $query->whereDate('created_at', Carbon::today());
    }

    /**
     * Scope para ventas del mes
     */
    public function scopeThisMonth($query)
    {
        return $query->whereMonth('created_at', Carbon::now()->month)
                    ->whereYear('created_at', Carbon::now()->year);
    }

    /**
     * Scope para ventas entre fechas
     */
    public function scopeBetweenDates($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    /**
     * Obtener productos vendidos con detalles
     */
    public function getProductsAttribute()
    {
        $productIds = collect($this->items)->pluck('product_id')->unique();
        return Product::whereIn('id', $productIds)->get();
    }

    /**
     * Calcular total de ventas por período
     */
    public static function getTotalSales($startDate = null, $endDate = null)
    {
        $query = self::query();
        
        if ($startDate && $endDate) {
            $query->betweenDates($startDate, $endDate);
        }
        
        return $query->sum('total');
    }

    /**
     * Obtener estadísticas de ventas
     */
    public static function getSalesStats($startDate = null, $endDate = null)
    {
        $query = self::query();
        
        if ($startDate && $endDate) {
            $query->betweenDates($startDate, $endDate);
        }
        
        return [
            'total_sales' => $query->sum('total'),
            'total_transactions' => $query->count(),
            'average_sale' => $query->avg('total') ?? 0,
            'total_items_sold' => $query->sum('total_units')
        ];
    }
}