<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
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
        // Check if locale is set in the URL parameter
        if ($request->has('lang')) {
            $locale = $request->get('lang');
            app()->setLocale($locale);
        }

        // Check if locale is set in the route parameter
        if ($request->route('lang')) {
            $locale = $request->route('lang');
            app()->setLocale($locale);
        }

        return $next($request);
    }
}
