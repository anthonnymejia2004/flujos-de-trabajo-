<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'barcode',
        'code',
        'name',
        'laboratory',
        'package_type',
        'presentation',
        'stock_boxes',
        'units_per_box',
        'stock_loose',
        'loose_stock',
        'stock_units',
        'total_units',
        'cost_price',
        'precio_venta',
        'precio_venta_unitario',
        'sale_price_box',
        'sale_price_unit',
        'iva_percentage',
        'iva',
        'profit_margin',
        'profit_amount',
        'profit_amount_total',
        'expiration_date'
    ];

    // Tu lógica de IVA Híbrido
    protected $appends = ['precio_venta'];

    /**
     * Generar presentación automáticamente
     * Formato: "Nombre (Tipo x Cantidad)"
     * Ejemplo: "Paracetamol (Caja x 20)"
     */
    public static function generatePresentation($name, $packageType, $unitsPerBox)
    {
        return "{$name} ({$packageType} x {$unitsPerBox})";
    }

    public function getPrecioVentaAttribute()
    {
        // Si existen los nuevos campos de venta fraccionada, usarlos
        if ($this->sale_price_box && $this->sale_price_box > 0) {
            return $this->sale_price_box;
        }

        $iva = $this->iva_percentage ?? env('GLOBAL_IVA', 15);
        
        // Si existe profit_amount (ganancia fija), usarlo
        if ($this->profit_amount && $this->profit_amount > 0) {
            $ivaAmount = $this->cost_price * ($iva / 100);
            return $this->cost_price + $ivaAmount + $this->profit_amount;
        }
        
        // Si no, usar el método antiguo con profit_margin (porcentaje)
        $subtotal = $this->cost_price * (1 + ($this->profit_margin / 100));
        return $subtotal * (1 + ($iva / 100));
    }
}