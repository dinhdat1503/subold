<?php

namespace App\Http\Middleware;

use App\Models\Session;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MultiSession
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
        if (!Auth::check()) {
            return $next($request);
        }
        $sessionCount = Session::where('user_id', Auth::id())
            ->count();
        if ($sessionCount > 1) {
            $reason = "Tài Khoản Của Bạn Đã Khoá Vì Nghi Vấn Bị Hack";
            $ipKey = 'ban-reason-' . $request->ip();
            \Cache::put($ipKey, $reason, now()->addMinutes(30));
            $user = Auth::user();
            $user->security()->update(['banned_reason' => $reason]);
            $user->update(['status' => 0]);
            Session::where('user_id', Auth::id())
                ->delete();
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
