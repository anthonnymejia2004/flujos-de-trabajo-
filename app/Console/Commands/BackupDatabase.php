<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class BackupDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup:database {--clean}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Hacer backup de la base de datos SQLite';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            $source = database_path('database.sqlite');
            $backupDir = storage_path('backups');

            // Crear directorio si no existe
            if (!is_dir($backupDir)) {
                mkdir($backupDir, 0755, true);
                $this->info("📁 Directorio de backups creado: {$backupDir}");
            }

            // Crear backup
            $timestamp = date('Y-m-d_H-i-s');
            $backup = "{$backupDir}/database_{$timestamp}.sqlite";

            if (!file_exists($source)) {
                $this->error("❌ Base de datos no encontrada: {$source}");
                return 1;
            }

            copy($source, $backup);
            $size = filesize($backup);
            $sizeKB = round($size / 1024, 2);

            $this->info("✅ Backup creado exitosamente");
            $this->info("📄 Archivo: {$backup}");
            $this->info("📊 Tamaño: {$sizeKB} KB");

            // Limpiar backups antiguos si se especifica
            if ($this->option('clean')) {
                $this->cleanOldBackups($backupDir);
            }

            return 0;
        } catch (\Exception $e) {
            $this->error("❌ Error al crear backup: " . $e->getMessage());
            return 1;
        }
    }

    /**
     * Limpiar backups antiguos (mantener últimos 10)
     */
    private function cleanOldBackups($backupDir)
    {
        $backups = glob("{$backupDir}/database_*.sqlite");
        
        if (count($backups) <= 10) {
            return;
        }

        // Ordenar por fecha de modificación
        usort($backups, function ($a, $b) {
            return filemtime($b) - filemtime($a);
        });

        // Eliminar backups antiguos
        foreach (array_slice($backups, 10) as $backup) {
            unlink($backup);
            $this->info("🗑️  Backup antiguo eliminado: " . basename($backup));
        }
    }
}
