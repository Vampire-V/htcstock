<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check()) {
            if ($request->route()->getName() === 'legal.approval.verify') {
                Auth::guard()->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return \redirect()->route('legal.approval.verify', $request->route()->parameters);
            }
            if ($request->route()->getName() === 'kpi.evaluation.verify') {
                Auth::guard()->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return \redirect()->route('kpi.evaluation.verify', $request->route()->parameters);
            }
            return redirect(RouteServiceProvider::HOME);
        }

        return $next($request);
    }
}
