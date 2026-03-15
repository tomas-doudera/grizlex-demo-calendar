<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class ApplyLocale
{
    public function handle(Request $request, Closure $next): mixed
    {
        if ($request->hasSession()) {
            $locale = $request->session()->get('locale');

            if ($locale) {
                App::setLocale($locale);
            } elseif ($cookieLocale = $request->cookie('filament_language_switcher_locale')) {
                App::setLocale($cookieLocale);
                $request->session()->put('locale', $cookieLocale);
            }
        }

        return $next($request);
    }
}
