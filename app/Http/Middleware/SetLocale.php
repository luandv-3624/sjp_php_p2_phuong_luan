<?php

namespace App\Http\Middleware;

use Closure;
use App\Enums\Language;
use Illuminate\Support\Facades\App;

class SetLocale
{
    public function handle($request, Closure $next)
    {
        $locale = $request->get('lang');

        if (!$locale) {
            $locale = $request->header('Accept-Language');
        }

        if (!in_array($locale, Language::values())) {
            $locale = config('app.locale');
        }

        App::setLocale($locale);

        return $next($request);
    }
}
