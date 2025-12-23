<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class CheckTenant
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // For simple MVP usage: User is logged in -> User belongs to a tenant -> Set Session.
        // In a complex app we might use subdomains.
        
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->tenant_id) {
                Session::put('tenant_id', $user->tenant_id);
            } else {
                // If user has no tenant, maybe they are super admin or it's an error.
                // For now, let's assume valid users have tenants.
                // Or maybe redirect to a "Select Tenant" page if one user has multiple tenants (not in v1 scope).
            }
        }

        return $next($request);
    }
}
