<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserRole
{
    /**
     * Handle an incoming request.
     *
     * Mendukung pengecekan multi-role.
     * Laravel melewatkan setiap role sebagai parameter terpisah dari definisi route.
     * Contoh: 'role:admin,admin1,admin2' → $roles = ['admin', 'admin1', 'admin2']
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles  Satu atau beberapa role
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!auth()->check()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthorized Access.'], 403);
            }
            abort(403, 'Unauthorized Access.');
        }

        $allowedRoles = array_map('trim', $roles);
        $userRole     = auth()->user()->role;

        if (!in_array($userRole, $allowedRoles)) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthorized Access.'], 403);
            }
            abort(403, 'Unauthorized Access.');
        }

        return $next($request);
    }
}
