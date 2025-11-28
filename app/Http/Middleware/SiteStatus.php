<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class SiteStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next): \Symfony\Component\HttpFoundation\Response
    {
        $excludedRoutes = [
            'login',
            'login.process',
        ];
        if ($request->routeIs($excludedRoutes)) {
            return $next($request);
        }
        $status = \Cache::rememberForever('site-status', function () {
            return \App\Models\SiteSetting::getValue('status');
        });
        if ($status == 1) {
            return $next($request);
        }
        $user = Auth::user();
        if ($user && $user->role == 'admin') {
            return $next($request);
        } else {
            if ($request->routeIs("maintaince")) {
                return $next($request);
            }
            if ($request->expectsJson()) {
                return \App\Helpers\BackendHelper::resApi('error', "Website Bảo Trì", [
                    'redirect' => route('maintaince'),
                ]);
            }
            return redirect()->route('maintaince');
        }
    }
}
