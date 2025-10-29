<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SuperAdminBypass
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated
        if (!auth()->check()) {
            return redirect()->route('admin.login')->with('error', 'Please login to access this page.');
        }

        // If user has any admin-level role, bypass all permission checks
        $adminRoles = ['Super Admin', 'admin', 'Admin'];
        if (auth()->user()->hasAnyRole($adminRoles)) {
            return $next($request);
        }

        // For other users, continue with normal permission checks
        return $next($request);
    }
}
