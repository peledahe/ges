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
            if (isset($user->is_active) && !$user->is_active) {
                Auth::guard('web')->logout();
                Auth::forgetGuards();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('login')->withErrors([
                    'email' => 'Su cuenta de usuario se encuentra inactiva. Por favor, comuníquese con el administrador del taller para habilitar su acceso.',
                ]);
            }

            if ($user->tenant_id) {
                if ($user->tenant && !$user->tenant->is_active) {
                    Auth::guard('web')->logout();
                    Auth::forgetGuards();
                    $request->session()->invalidate();
                    $request->session()->regenerateToken();

                    return redirect()->route('login')->withErrors([
                        'email' => 'Su taller se encuentra deshabilitado por el momento. Por favor, comuníquese con el administrador del sistema.',
                    ]);
                }

                Session::put('tenant_id', $user->tenant_id);
            } elseif ($user->isRoot()) {
                if (!$request->routeIs('admin.*') && !$request->routeIs('profile.*') && !$request->routeIs('logout')) {
                    return redirect()->route('admin.tenants');
                }
            }
        }

        return $next($request);
    }
}
