<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware para verificar roles de usuario
 * 
 * Uso en rutas:
 * Route::middleware(['auth', 'role:admin'])->group(...)
 * Route::middleware(['auth', 'role:admin,jefe_red'])->group(...)
 */
class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Verificar que el usuario esté autenticado
        if (!auth()->check()) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No autenticado'
                ], 401);
            }
            return redirect()->route('login');
        }

        $user = auth()->user();
        $userRole = strtolower($user->role ?? '');

        // Normalizar roles permitidos
        $allowedRoles = array_map('strtolower', $roles);

        // Verificar si el usuario tiene alguno de los roles permitidos
        if (!in_array($userRole, $allowedRoles)) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No tiene permisos para acceder a esta sección'
                ], 403);
            }

            abort(403, 'No tiene permisos para acceder a esta sección');
        }

        return $next($request);
    }
}


