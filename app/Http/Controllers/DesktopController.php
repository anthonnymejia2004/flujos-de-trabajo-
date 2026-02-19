<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DesktopController extends Controller
{
    /**
     * Obtener información de la aplicación
     */
    public function getAppInfo()
    {
        return response()->json([
            'name' => config('nativephp.name'),
            'version' => config('nativephp.version'),
            'author' => config('nativephp.author'),
            'description' => config('nativephp.description'),
        ]);
    }

    /**
     * Obtener configuración de la aplicación
     */
    public function getConfig()
    {
        return response()->json(config('nativephp'));
    }

    /**
     * Registrar evento de la aplicación
     */
    public function logEvent(Request $request)
    {
        $event = $request->input('event');
        $data = $request->input('data', []);

        \Log::info("Desktop Event: {$event}", $data);

        return response()->json(['success' => true]);
    }

    /**
     * Obtener estado de la aplicación
     */
    public function getStatus()
    {
        return response()->json([
            'status' => 'running',
            'timestamp' => now(),
            'products_count' => \App\Models\Product::count(),
            'users_count' => \App\Models\User::count(),
        ]);
    }

    /**
     * Hacer backup de la base de datos
     */
    public function backup()
    {
        try {
            $source = database_path('database.sqlite');
            $backupDir = storage_path('backups');

            // Crear directorio si no existe
            if (!is_dir($backupDir)) {
                mkdir($backupDir, 0755, true);
            }

            $timestamp = date('Y-m-d_H-i-s');
            $backup = "{$backupDir}/database_{$timestamp}.sqlite";

            copy($source, $backup);

            return response()->json([
                'success' => true,
                'message' => 'Backup creado exitosamente',
                'file' => $backup,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear backup: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Restaurar backup de la base de datos
     */
    public function restore(Request $request)
    {
        try {
            $file = $request->input('file');

            if (!file_exists($file)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Archivo de backup no encontrado',
                ], 404);
            }

            $destination = database_path('database.sqlite');
            copy($file, $destination);

            return response()->json([
                'success' => true,
                'message' => 'Backup restaurado exitosamente',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al restaurar backup: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Obtener lista de backups
     */
    public function listBackups()
    {
        try {
            $backupDir = storage_path('backups');

            if (!is_dir($backupDir)) {
                return response()->json(['backups' => []]);
            }

            $backups = array_map(function ($file) {
                return [
                    'name' => basename($file),
                    'path' => $file,
                    'size' => filesize($file),
                    'date' => filemtime($file),
                ];
            }, glob("{$backupDir}/database_*.sqlite"));

            // Ordenar por fecha descendente
            usort($backups, function ($a, $b) {
                return $b['date'] - $a['date'];
            });

            return response()->json(['backups' => $backups]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al listar backups: ' . $e->getMessage(),
            ], 500);
        }
    }
}
