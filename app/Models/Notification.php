<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Notification extends Model
{
    protected $fillable = [
        'title',
        'message',
        'type',
        'is_read',
        'data'
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'data' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Tipos de notificaciones
    const TYPE_STOCK_LOW = 'stock_low';
    const TYPE_EXPIRING = 'expiring';
    const TYPE_EXPIRED = 'expired';
    const TYPE_SYSTEM = 'system';

    // Scopes
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    // Métodos estáticos para crear notificaciones
    public static function createStockLowNotification($product)
    {
        return self::create([
            'title' => 'Stock Bajo',
            'message' => "El producto '{$product->name}' tiene stock bajo ({$product->stock_boxes} cajas)",
            'type' => self::TYPE_STOCK_LOW,
            'data' => [
                'product_id' => $product->id,
                'product_name' => $product->name,
                'current_stock' => $product->stock_boxes
            ]
        ]);
    }

    public static function createExpiringNotification($product)
    {
        $daysToExpire = Carbon::now()->diffInDays($product->expiration_date, false);
        
        return self::create([
            'title' => 'Producto por Vencer',
            'message' => "El producto '{$product->name}' vence en {$daysToExpire} días",
            'type' => self::TYPE_EXPIRING,
            'data' => [
                'product_id' => $product->id,
                'product_name' => $product->name,
                'expiration_date' => $product->expiration_date,
                'days_to_expire' => $daysToExpire
            ]
        ]);
    }

    public static function createExpiredNotification($product)
    {
        return self::create([
            'title' => 'Producto Vencido',
            'message' => "El producto '{$product->name}' ya está vencido",
            'type' => self::TYPE_EXPIRED,
            'data' => [
                'product_id' => $product->id,
                'product_name' => $product->name,
                'expiration_date' => $product->expiration_date
            ]
        ]);
    }

    // Método para marcar como leída
    public function markAsRead()
    {
        $this->update(['is_read' => true]);
    }

    // Método para obtener el ícono según el tipo
    public function getIconAttribute()
    {
        return match($this->type) {
            self::TYPE_STOCK_LOW => 'fas fa-exclamation-triangle text-yellow-500',
            self::TYPE_EXPIRING => 'fas fa-clock text-orange-500',
            self::TYPE_EXPIRED => 'fas fa-times-circle text-red-500',
            self::TYPE_SYSTEM => 'fas fa-info-circle text-blue-500',
            default => 'fas fa-bell text-gray-500'
        };
    }
}
