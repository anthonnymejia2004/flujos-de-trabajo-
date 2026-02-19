<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;
use App\Models\Product;
use Carbon\Carbon;

class NotificationController extends Controller
{
    /**
     * Mostrar todas las notificaciones
     */
    public function index()
    {
        $notifications = Notification::orderBy('created_at', 'desc')->paginate(20);
        
        return view('notifications.index', compact('notifications'));
    }

    /**
     * Obtener notificaciones no leídas (API)
     */
    public function getUnread()
    {
        $notifications = Notification::unread()
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return response()->json([
            'notifications' => $notifications,
            'count' => $notifications->count()
        ]);
    }

    /**
     * Obtener contador de notificaciones no leídas
     */
    public function getUnreadCount()
    {
        $count = Notification::unread()->count();
        
        return response()->json(['count' => $count]);
    }

    /**
     * Marcar una notificación como leída
     */
    public function markAsRead($id)
    {
        $notification = Notification::find($id);
        
        if ($notification) {
            $notification->markAsRead();
            return response()->json(['success' => true]);
        }
        
        return response()->json(['success' => false], 404);
    }

    /**
     * Marcar todas las notificaciones como leídas
     */
    public function markAllAsRead()
    {
        Notification::unread()->update(['is_read' => true]);
        
        return response()->json(['success' => true]);
    }

    /**
     * Generar notificaciones automáticas basadas en el inventario
     */
    public function generateAutomaticNotifications()
    {
        $generatedCount = 0;

        // 1. Productos con stock bajo (menos de 5 cajas)
        $lowStockProducts = Product::where('stock_boxes', '<', 5)->get();
        
        foreach ($lowStockProducts as $product) {
            // Verificar si ya existe una notificación reciente para este producto
            $existingNotification = Notification::where('type', Notification::TYPE_STOCK_LOW)
                ->where('data->product_id', $product->id)
                ->where('created_at', '>=', Carbon::now()->subHours(24))
                ->first();

            if (!$existingNotification) {
                Notification::createStockLowNotification($product);
                $generatedCount++;
            }
        }

        // 2. Productos próximos a vencer (en los próximos 30 días)
        $expiringProducts = Product::where('expiration_date', '<=', Carbon::now()->addDays(30))
            ->where('expiration_date', '>', Carbon::now())
            ->get();

        foreach ($expiringProducts as $product) {
            $existingNotification = Notification::where('type', Notification::TYPE_EXPIRING)
                ->where('data->product_id', $product->id)
                ->where('created_at', '>=', Carbon::now()->subHours(24))
                ->first();

            if (!$existingNotification) {
                Notification::createExpiringNotification($product);
                $generatedCount++;
            }
        }

        // 3. Productos ya vencidos
        $expiredProducts = Product::where('expiration_date', '<', Carbon::now())->get();

        foreach ($expiredProducts as $product) {
            $existingNotification = Notification::where('type', Notification::TYPE_EXPIRED)
                ->where('data->product_id', $product->id)
                ->where('created_at', '>=', Carbon::now()->subHours(24))
                ->first();

            if (!$existingNotification) {
                Notification::createExpiredNotification($product);
                $generatedCount++;
            }
        }

        return response()->json([
            'success' => true,
            'generated' => $generatedCount,
            'message' => "Se generaron {$generatedCount} notificaciones automáticas"
        ]);
    }

    /**
     * Crear notificación manual del sistema
     */
    public function createSystemNotification(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        $notification = Notification::create([
            'title' => $request->title,
            'message' => $request->message,
            'type' => Notification::TYPE_SYSTEM,
        ]);

        return response()->json([
            'success' => true,
            'notification' => $notification
        ]);
    }

    /**
     * Eliminar notificación
     */
    public function destroy($id)
    {
        $notification = Notification::find($id);
        
        if ($notification) {
            $notification->delete();
            return response()->json(['success' => true]);
        }
        
        return response()->json(['success' => false], 404);
    }
}