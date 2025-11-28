<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserBan
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
        $user = Auth::user();
        if (!$user) {
            return $next($request);
        }
        if ($user->role === 'admin') {
            return $next($request);
        }
        if ($user->status == 0) {
            $reason = $user->security->banned_reason ?? 'Tài khoản của bạn đã bị khoá.';
            $ipKey = 'ban-reason-' . $request->ip();
            \Cache::put($ipKey, $reason, now()->addMinutes(30));
            \App\Models\Session::where('user_id', $user->id)->delete();
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            if ($request->expectsJson()) {
                return \App\Helpers\BackendHelper::resApi('error', $reason, [
                    'redirect' => route('user.ban'),
                ]);
            }
            return redirect()->route('user.ban');
        }
        return $next($request);
    }
}
