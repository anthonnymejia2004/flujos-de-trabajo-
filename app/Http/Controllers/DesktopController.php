<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DesktopController extends Controller
{
    /**
     * Obtener información de la aplicación de escritorio
     */
    public function getAppInfo()
    {
        return response()->json([
            'name' => 'Pharma-Sync',
            'version' => '1.0.0',
            'environment' => app()->environment(),
            'laravel_version' => app()->version(),
            'php_version' => PHP_VERSION,
            'server_time' => now()->toDateTimeString(),
            'database_type' => config('database.default'),
            'database_connected' => $this->checkDatabaseConnection(),
        ]);
    }

    /**
     * Obtener configuración de la aplicación
     */
    public function getConfig()
    {
        return response()->json([
            'app_name' => config('app.name'),
            'app_env' => config('app.env'),
            'app_debug' => config('app.debug'),
            'app_url' => config('app.url'),
            'timezone' => config('app.timezone'),
            'locale' => config('app.locale'),
            'database_connection' => config('database.default'),
            'mail_driver' => config('mail.default'),
            'cache_driver' => config('cache.default'),
            'session_driver' => config('session.driver'),
        ]);
    }

    /**
     * Obtener estado del sistema
     */
    public function getStatus()
    {
        $status = [
            'server' => [
                'status' => 'online',
                'timestamp' => now()->toDateTimeString(),
                'uptime' => $this->getServerUptime(),
            ],
            'database' => [
                'status' => $this->checkDatabaseConnection() ? 'connected' : 'disconnected',
                'tables' => $this->getDatabaseTablesCount(),
                'size' => $this->getDatabaseSize(),
            ],
            'storage' => [
                'total' => disk_total_space(storage_path()),
                'free' => disk_free_space(storage_path()),
                'used' => disk_total_space(storage_path()) - disk_free_space(storage_path()),
            ],
            'memory' => [
                'usage' => memory_get_usage(true),
                'peak' => memory_get_peak_usage(true),
            ],
            'application' => [
                'users_count' => \App\Models\User::count(),
                'products_count' => \App\Models\Product::count(),
                'sales_count' => \App\Models\Sale::count(),
                'notifications_count' => \App\Models\Notification::count(),
            ],
        ];

        return response()->json($status);
    }

    /**
     * Registrar evento de la aplicación de escritorio
     */
    public function logEvent(Request $request)
    {
        $validated = $request->validate([
            'event_type' => 'required|string',
            'message' => 'required|string',
            'data' => 'nullable|array',
        ]);

        $logEntry = [
            'timestamp' => now()->toDateTimeString(),
            'event_type' => $validated['event_type'],
            'message' => $validated['message'],
            'data' => $validated['data'] ?? [],
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ];

        // Guardar en archivo de log
        $logFile = storage_path('logs/desktop.log');
        $logContent = json_encode($logEntry, JSON_PRETTY_PRINT) . PHP_EOL;
        file_put_contents($logFile, $logContent, FILE_APPEND);

        return response()->json([
            'success' => true,
            'message' => 'Event logged successfully',
            'log_entry' => $logEntry,
        ]);
    }

    /**
     * Crear backup de la base de datos
     */
    public function backup(Request $request)
    {
        try {
            $backupName = 'backup_' . now()->format('Y-m-d_H-i-s') . '.sqlite';
            $backupPath = storage_path('backups/' . $backupName);

            // Crear directorio de backups si no existe
            if (!file_exists(storage_path('backups'))) {
                mkdir(storage_path('backups'), 0755, true);
            }

            // Copiar la base de datos SQLite
            $databasePath = database_path('database.sqlite');
            if (file_exists($databasePath)) {
                copy($databasePath, $backupPath);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Database file not found',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Backup created successfully',
                'backup_name' => $backupName,
                'backup_path' => $backupPath,
                'backup_size' => filesize($backupPath),
                'created_at' => now()->toDateTimeString(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Backup failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Restaurar backup de la base de datos
     */
    public function restore(Request $request)
    {
        $validated = $request->validate([
            'backup_name' => 'required|string',
        ]);

        try {
            $backupPath = storage_path('backups/' . $validated['backup_name']);
            $databasePath = database_path('database.sqlite');

            if (!file_exists($backupPath)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Backup file not found',
                ], 404);
            }

            // Crear backup del estado actual antes de restaurar
            $currentBackupName = 'pre_restore_' . now()->format('Y-m-d_H-i-s') . '.sqlite';
            $currentBackupPath = storage_path('backups/' . $currentBackupName);
            if (file_exists($databasePath)) {
                copy($databasePath, $currentBackupPath);
            }

            // Restaurar el backup
            copy($backupPath, $databasePath);

            return response()->json([
                'success' => true,
                'message' => 'Database restored successfully',
                'restored_from' => $validated['backup_name'],
                'current_backup' => $currentBackupName,
                'restored_at' => now()->toDateTimeString(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Restore failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Listar backups disponibles
     */
    public function listBackups()
    {
        $backupDir = storage_path('backups');
        $backups = [];

        if (file_exists($backupDir)) {
            $files = scandir($backupDir);
            foreach ($files as $file) {
                if ($file !== '.' && $file !== '..' && pathinfo($file, PATHINFO_EXTENSION) === 'sqlite') {
                    $filePath = $backupDir . '/' . $file;
                    $backups[] = [
                        'name' => $file,
                        'size' => filesize($filePath),
                        'modified' => date('Y-m-d H:i:s', filemtime($filePath)),
                        'path' => $filePath,
                    ];
                }
            }
        }

        // Ordenar por fecha de modificación (más reciente primero)
        usort($backups, function ($a, $b) {
            return strtotime($b['modified']) - strtotime($a['modified']);
        });

        return response()->json([
            'success' => true,
            'backups' => $backups,
            'total' => count($backups),
            'backup_dir' => $backupDir,
        ]);
    }

    /**
     * Verificar conexión a la base de datos
     */
    private function checkDatabaseConnection()
    {
        try {
            DB::connection()->getPdo();
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Obtener tiempo de actividad del servidor
     */
    private function getServerUptime()
    {
        if (function_exists('shell_exec')) {
            $uptime = shell_exec('uptime');
            return $uptime ? trim($uptime) : 'Unknown';
        }
        return 'Unknown';
    }

    /**
     * Obtener número de tablas en la base de datos
     */
    private function getDatabaseTablesCount()
    {
        try {
            $tables = DB::select("SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%'");
            return count($tables);
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * Obtener tamaño de la base de datos
     */
    private function getDatabaseSize()
    {
        $databasePath = database_path('database.sqlite');
        if (file_exists($databasePath)) {
            $size = filesize($databasePath);
            return $this->formatBytes($size);
        }
        return '0 B';
    }

    /**
     * Formatear bytes a formato legible
     */
    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);
        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}