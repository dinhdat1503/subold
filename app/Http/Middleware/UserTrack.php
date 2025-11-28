<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class UserTrack
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
        $user = \Auth::user();
        if ($user) {
            $cacheKey = 'user-last-seen-' . $user->id;
            if (!Cache::has($cacheKey)) {
                $user->update(['last_online' => now()]);
                Cache::put($cacheKey, true, now()->addMinutes(5));
            }

            $cacheKeyAttempt = "user-attempt-error-" . $user->id;
            $cacheKeyLastSync = "last-sync-user-attempt-error-" . $user->id;
            if (Cache::has($cacheKeyAttempt)) {
                $attempts = Cache::get($cacheKeyAttempt);
                if (!Cache::has($cacheKeyLastSync)) {
                    if ($attempts >= siteSetting("max_user_error_attempts")) {
                        $user->update(['status' => 0]);
                        $user->security()->update([
                            'attempt_error' => $attempts,
                            'banned_reason' => 'Bạn Đã Bị Khoá Tài Khoản Vì Có Hành Động Spam',
                        ]);
                    }
                    Cache::put($cacheKeyLastSync, true, now()->addMinute());
                }
            } else {
                Cache::put($cacheKeyAttempt, 0, now()->addMinutes(30));
            }
        }
        return $next($request);
    }
}
