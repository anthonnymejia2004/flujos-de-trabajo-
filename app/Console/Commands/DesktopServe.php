<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

class DesktopServe extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'desktop:serve {--port=8000}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Ejecutar la aplicación en modo escritorio';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $port = $this->option('port');

        $this->info('🚀 Iniciando Pharma-Sync en modo escritorio...');
        $this->info("📍 Puerto: {$port}");
        $this->info('');

        // Establecer variable de entorno
        putenv('NATIVE_APP=true');
        putenv('DESKTOP_MODE=true');

        // Iniciar servidor Laravel
        $process = new Process(['php', 'artisan', 'serve', "--port={$port}"]);
        $process->setTty(true);
        $process->run(function ($type, $buffer) {
            echo $buffer;
        });

        if (!$process->isSuccessful()) {
            $this->error('Error al iniciar el servidor');
            return 1;
        }

        return 0;
    }
}
