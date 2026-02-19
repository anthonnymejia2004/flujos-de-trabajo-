<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Manejar error 419 (CSRF Token Mismatch / Page Expired)
        $exceptions->render(function (\Illuminate\Session\TokenMismatchException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Tu sesión ha expirado. Por favor, recarga la página.',
                    'error' => 'token_mismatch'
                ], 419);
            }
            
            return redirect()->route('login')
                ->with('error', 'Tu sesión ha expirado. Por favor, inicia sesión nuevamente.')
                ->withInput($request->except('password', '_token'));
        });
    })->create();
