<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckIpAccess
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
        $ip = $request->ip();
        $blockedIps = \Cache::rememberForever('blocked-ips-list', function () {
            return \App\Models\BlockedIp::where("banned", 1)
                ->pluck('reason', 'ip_address')
                ->toArray();
        });
        if (!isset($blockedIps[$ip])) {
            return $next($request);
        }
        if ($request->routeIs('ip.block')) {
            return $next($request);
        }
        if ($request->expectsJson()) {
            return \App\Helpers\BackendHelper::resApi('error', $blockedIps[$ip], [
                'redirect' => route('ip.block'),
            ]);
        }
        return redirect()->route('ip.block');
    }

}
