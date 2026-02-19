<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Session\TokenMismatchException;
use Symfony\Component\HttpFoundation\Response;

class HandleCsrfTokenMismatch
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            return $next($request);
        } catch (TokenMismatchException $e) {
            // Si es una petición AJAX
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Tu sesión ha expirado. Por favor, recarga la página.',
                    'error' => 'csrf_token_mismatch'
                ], 419);
            }
            
            // Si es una petición normal, redirigir al login con mensaje
            return redirect()->route('login')
                ->with('error', 'Tu sesión ha expirado. Por favor, inicia sesión nuevamente.')
                ->withInput($request->except('password', '_token'));
        }
    }
}
