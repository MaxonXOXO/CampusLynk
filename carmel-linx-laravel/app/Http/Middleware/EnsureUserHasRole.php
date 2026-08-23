<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $sessionRole = Session::get('userRole', Session::get('role'));
        
        if (!$sessionRole) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'status' => 'UNAUTHENTICATED',
                    'message' => 'Your session has expired. Please sign in again.'
                ], 401);
            }
            return redirect('/login')->with('error', 'Please sign in to continue.');
        }

        $normalizedSessionRole = self::normalizeRole($sessionRole);
        $normalizedAllowedRoles = array_map([self::class, 'normalizeRole'], $roles);

        if (!in_array($normalizedSessionRole, $normalizedAllowedRoles, true)) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'status' => 'FORBIDDEN',
                    'message' => 'Access denied. This administrative action requires elevated privileges.'
                ], 403);
            }

            if ($normalizedSessionRole === 'principal') {
                return redirect('/dashboard/principal')->with('error', 'Access restricted to system administrators.');
            }

            return redirect('/')->with('error', 'Access denied. You do not have permission to view this page.');
        }

        return $next($request);
    }

    /**
     * Normalize role strings to consistent comparison keys.
     *
     * @param string $role
     * @return string
     */
    public static function normalizeRole(?string $role): string
    {
        $r = strtolower(trim($role ?? ''));

        if (in_array($r, ['super_admin', 'superadmin', 'super admin', 'super-admin'])) {
            return 'super_admin';
        }
        if (in_array($r, ['admin', 'administrator', 'system_admin'])) {
            return 'admin';
        }
        if (in_array($r, ['principal', 'executive'])) {
            return 'principal';
        }
        if (in_array($r, ['hod', 'head_of_department'])) {
            return 'hod';
        }
        if (in_array($r, ['tutor'])) {
            return 'tutor';
        }
        if (in_array($r, ['chairman'])) {
            return 'chairman';
        }
        if (in_array($r, ['student'])) {
            return 'student';
        }

        return $r ?: 'guest';
    }
}
