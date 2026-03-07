<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locale = session('locale');

        if (!$locale) {
            $browserLocale = substr($request->server('HTTP_ACCEPT_LANGUAGE'), 0, 2);

            if (in_array($browserLocale, ['en', 'ja'])) {
                $locale = $browserLocale;
            } else {
                $locale = config('app.locale');
            }

            session(['locale' => $locale]);
        }

        App::setLocale($locale);

        return $next($request);
    }
}
