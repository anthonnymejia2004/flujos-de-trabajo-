<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Configuración de Aplicación de Escritorio
    |--------------------------------------------------------------------------
    |
    | Esta configuración define los parámetros para la aplicación de escritorio
    | Pharma-Sync cuando se ejecuta como aplicación nativa de Windows.
    |
    */

    'name' => 'Pharma-Sync',
    'id' => 'pharma-sync',
    'version' => '1.0.0',
    'author' => 'Tu Empresa',
    'description' => 'Sistema de Gestión de Inventario Farmacéutico',

    /*
    |--------------------------------------------------------------------------
    | Configuración de Ventana
    |--------------------------------------------------------------------------
    */
    'window' => [
        'title' => 'Pharma-Sync',
        'width' => 1400,
        'height' => 900,
        'min_width' => 1000,
        'min_height' => 600,
        'resizable' => true,
        'maximizable' => true,
        'minimizable' => true,
        'closable' => true,
        'fullscreenable' => true,
        'center' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Configuración de Windows
    |--------------------------------------------------------------------------
    */
    'windows' => [
        'executable' => 'Pharma-Sync.exe',
        'icon' => 'resources/images/icon.png',
        'publisher' => 'Tu Empresa',
        'sign' => false,
    ],

    /*
    |--------------------------------------------------------------------------
    | Configuración de Base de Datos
    |--------------------------------------------------------------------------
    */
    'database' => [
        'connection' => 'sqlite',
        'path' => 'storage/database.sqlite',
        'backup_path' => 'storage/backups',
        'auto_backup' => true,
        'backup_interval' => 3600, // segundos
    ],

    /*
    |--------------------------------------------------------------------------
    | Configuración de Actualizaciones
    |--------------------------------------------------------------------------
    */
    'updates' => [
        'enabled' => true,
        'check_interval' => 86400, // 24 horas
        'auto_install' => false,
        'channel' => 'stable',
    ],

    /*
    |--------------------------------------------------------------------------
    | Configuración de Menú
    |--------------------------------------------------------------------------
    */
    'menu' => [
        'enabled' => true,
        'position' => 'top',
    ],

    /*
    |--------------------------------------------------------------------------
    | Configuración de Bandeja del Sistema
    |--------------------------------------------------------------------------
    */
    'tray' => [
        'enabled' => true,
        'icon' => 'resources/images/tray-icon.png',
        'show_on_startup' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Atajos de Teclado
    |--------------------------------------------------------------------------
    */
    'shortcuts' => [
        'new_product' => 'ctrl+n',
        'new_sale' => 'ctrl+shift+n',
        'save' => 'ctrl+s',
        'quit' => 'ctrl+q',
        'settings' => 'ctrl+,',
        'inventory' => 'ctrl+i',
        'sales' => 'ctrl+v',
        'reports' => 'ctrl+r',
    ],

    /*
    |--------------------------------------------------------------------------
    | Notificaciones
    |--------------------------------------------------------------------------
    */
    'notifications' => [
        'enabled' => true,
        'position' => 'bottom-right',
        'duration' => 5000, // milisegundos
    ],

    /*
    |--------------------------------------------------------------------------
    | Logging
    |--------------------------------------------------------------------------
    */
    'logging' => [
        'enabled' => true,
        'level' => 'debug',
        'path' => 'storage/logs/desktop.log',
    ],
];
