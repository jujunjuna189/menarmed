<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = $request->user();

        Log::info('Cek role middleware', [
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'auth_check' => Auth::check(),
            'auth_id' => Auth::id(),
            'auth_email' => $user ? $user->email : null,
            'auth_role' => $user ? $user->role : null,
            'allowed_roles' => $roles,
        ]);

        if ($user && in_array($user->role, $roles)) {
            return $next($request);
        }

        Log::warning('Role ditolak middleware', [
            'auth_id' => Auth::id(),
            'auth_email' => $user ? $user->email : null,
            'auth_role' => $user ? $user->role : null,
            'allowed_roles' => $roles,
        ]);

        Auth::logout();
        return redirect('/login');
    }
}
