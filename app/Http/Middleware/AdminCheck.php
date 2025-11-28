<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AdminCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        $currentIp = $request->ip();
        $currentAgent = $request->userAgent();

        if ($user->role === 'admin') {
            if (
                ($user->last_ip && $currentIp !== $user->last_ip) ||
                ($user->last_useragent && $currentAgent !== $user->last_useragent)
            ) {
                $this->removeSession($request, $user);
                return redirect()->route('login')->withErrors([
                    'msg' => 'Phiên đăng nhập đã bị hủy do thay đổi IP hoặc thiết bị.',
                ]);
            }

            return $next($request);
        }
        if ($user->role === 'ctv') {
            $allowedRoutes = [
                'admin.ip.*',
                'admin.service.*',
                'admin.user.*',
                'admin.recharge.logs*',
            ];
            if (($user->last_ip && $currentIp !== $user->last_ip) || ($user->last_useragent && $currentAgent !== $user->last_useragent)) {
                $this->removeSession($request, $user);
                return redirect()->route('login')->withErrors([
                    'msg' => 'Phiên đăng nhập đã bị hủy do thay đổi IP hoặc thiết bị.',
                ]);
            }
            foreach ($allowedRoutes as $pattern) {
                if (\Str::is($pattern, $request->route()->getName())) {
                    return $next($request);
                }
            }
            return redirect()->route('admin.ip.manage');
        }
        abort(403, 'Bạn không có quyền truy cập khu vực admin.');
    }
    private function removeSession($request, $user)
    {
        \App\Models\Session::where('user_id', $user->id)->delete();
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
    }
}
