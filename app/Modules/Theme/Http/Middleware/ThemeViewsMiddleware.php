<?php

declare(strict_types=1);

namespace App\Modules\Theme\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ThemeViewsMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        $activeTheme = 'school-classic';
        try {
            // Retrieve active theme setting safely
            $setting = \App\Modules\System\Models\Setting::where('key', 'theme.active')->first();
            if ($setting && $setting->value) {
                $activeTheme = $setting->value;
            }
        } catch (\Exception $e) {
            // Fallback if settings table does not exist yet
        }

        $themeViewsPath = base_path('themes/' . $activeTheme . '/views');
        if (is_dir($themeViewsPath)) {
            app('view')->getFinder()->prependLocation($themeViewsPath);
        }

        return $next($request);
    }
}
