<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InventarioController;
use App\Http\Controllers\VentasController;
use App\Http\Controllers\ReportesController;
use App\Http\Controllers\ConfiguracionController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DesktopController;
use App\Http\Controllers\Auth\LoginController;

// Rutas de autenticación
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);

// Ruta raíz - redirige a dashboard o login
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
})->name('home');

// Ruta para obtener token CSRF fresco
Route::get('/csrf-token', function() {
    return response()->json(['token' => csrf_token()]);
});

// Rutas de API para Aplicación de Escritorio
Route::prefix('api/desktop')->group(function () {
    Route::get('/info', [DesktopController::class, 'getAppInfo']);
    Route::get('/config', [DesktopController::class, 'getConfig']);
    Route::get('/status', [DesktopController::class, 'getStatus']);
    Route::post('/log-event', [DesktopController::class, 'logEvent']);
    Route::post('/backup', [DesktopController::class, 'backup']);
    Route::post('/restore', [DesktopController::class, 'restore']);
    Route::get('/backups', [DesktopController::class, 'listBackups']);
});

// Rutas protegidas por autenticación
Route::middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('inventario', InventarioController::class);
    Route::get('/ventas', [VentasController::class, 'index'])->name('ventas.index');
    Route::post('/ventas/store', [VentasController::class, 'store'])->name('ventas.store');
    Route::get('/ventas/history', [VentasController::class, 'history'])->name('ventas.history');
    Route::get('/ventas/{sale}', [VentasController::class, 'show'])->name('ventas.show');
    Route::get('/reportes', [ReportesController::class, 'index'])->name('reportes.index');
    // Rutas de configuración
    Route::get('/configuracion', [ConfiguracionController::class, 'index'])->name('configuracion.index');
    Route::post('/configuracion', [ConfiguracionController::class, 'update'])->name('configuracion.update');
    Route::post('/configuracion/theme', [ConfiguracionController::class, 'updateTheme'])->name('configuracion.theme');

    // Rutas de acciones rápidas
    Route::get('/configuracion/export', [ConfiguracionController::class, 'exportData'])->name('configuracion.export');
    Route::post('/configuracion/import', [ConfiguracionController::class, 'importData'])->name('configuracion.import');
    Route::get('/configuracion/report', [ConfiguracionController::class, 'generateReport'])->name('configuracion.report');

    // API Routes para búsqueda de productos
    Route::get('/api/products/search', [VentasController::class, 'searchByBarcode']);

    // Rutas de notificaciones
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        Route::get('/unread', [NotificationController::class, 'getUnread'])->name('unread');
        Route::get('/count', [NotificationController::class, 'getUnreadCount'])->name('count');
        Route::post('/mark-read/{id}', [NotificationController::class, 'markAsRead'])->name('mark-read');
        Route::post('/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('mark-all-read');
        Route::post('/generate', [NotificationController::class, 'generateAutomaticNotifications'])->name('generate');
        Route::delete('/{id}', [NotificationController::class, 'destroy'])->name('destroy');
    });

    // Rutas de usuarios
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/create', [UserController::class, 'create'])->name('create');
        Route::post('/', [UserController::class, 'store'])->name('store');
        Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
        Route::put('/{user}', [UserController::class, 'update'])->name('update');
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
        Route::get('/profile', [UserController::class, 'profile'])->name('profile');
        Route::put('/profile', [UserController::class, 'updateProfile'])->name('profile.update');
    });

    // Ruta de logout
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});

// Ruta de prueba para eliminar producto
Route::get('/test-delete/{id}', function($id) {
    $product = App\Models\Product::find($id);
    
    if (!$product) {
        return "Producto no encontrado";
    }
    
    $name = $product->name;
    $result = $product->delete();
    
    $check = App\Models\Product::find($id);
    
    return [
        'producto' => $name,
        'delete_result' => $result,
        'aun_existe' => $check ? 'SI' : 'NO',
        'total_productos' => App\Models\Product::count()
    ];
});