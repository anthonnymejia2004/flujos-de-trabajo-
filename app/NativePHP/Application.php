<?php

namespace App\NativePHP;

use NativePHP\Electron\Facades\Window;

class Application
{
    public function boot()
    {
        // Abrir ventana principal
        Window::open()
            ->route('dashboard')
            ->title('Pharma-Sync - Sistema de Gestión de Farmacia')
            ->width(1400)
            ->height(900)
            ->minWidth(1024)
            ->minHeight(768);
    }
}
