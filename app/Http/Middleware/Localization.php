<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class Localization
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $locale = 'en';
        if (session('locale')) {
            $locale = session('locale');
        } else if (Auth::user()) {
            if (Auth::user()->locale) {
                $locale = (Auth::user()->locale) ? Auth::user()->locale : $locale;
            }
        }
        App::setlocale($locale);
        return $next($request);
    }
}
