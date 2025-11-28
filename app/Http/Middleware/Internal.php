<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Internal
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $routeSecret = $request->route('secret');
        $internalKey = env('INTERNAL_KEY');
        if (!$routeSecret || $routeSecret !== $internalKey) {
            abort(403, 'Unauthorized access: Invalid secret key');
        }
        return $next($request);
    }
}
