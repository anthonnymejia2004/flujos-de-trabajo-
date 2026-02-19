<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\NativePHP\ApplicationController;

class NativeAppServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Verificar si se está ejecutando en modo escritorio
        if ($this->isDesktopMode()) {
            $this->initializeDesktopApp();
        }
    }

    /**
     * Inicializar la aplicación de escritorio
     */
    private function initializeDesktopApp()
    {
        $controller = new ApplicationController();
        $controller->boot();
    }

    /**
     * Verificar si se está ejecutando en modo escritorio
     */
    private function isDesktopMode()
    {
        return env('NATIVE_APP', false);
    }
}
