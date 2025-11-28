<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array<int, class-string|string>
     */
    protected $middleware = [
        \App\Http\Middleware\TrustHosts::class, // APP_URL
        \App\Http\Middleware\TrustProxies::class,
        \Illuminate\Http\Middleware\HandleCors::class,
        \App\Http\Middleware\PreventRequestsDuringMaintenance::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
    ];


    protected static $sharedRouteMiddlewares = [
        \App\Http\Middleware\SiteStatus::class,
        \App\Http\Middleware\CheckIpAccess::class
    ];


    /**
     * The application's route middleware groups.
     *
     * @var array<string, array<int, class-string|string>>
     */
    protected $middlewareGroups = [
        'web' => [
            'throttle:web',
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\Session\Middleware\AuthenticateSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,

            \App\Http\Middleware\MultiSession::class,
        ],
        'api' => [
            // \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class, // Chỉ sử dụng khi sử dụng Sanctum
            'throttle:api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,


        ],
    ];


    /**
     * Gộp các Middleware dùng chung vào các nhóm 'web' và 'api'.
     *
     * @return void
     */
    public function __construct(
        \Illuminate\Foundation\Application $app,
        \Illuminate\Routing\Router $router
    ) {
        parent::__construct($app, $router);

        // Gộp Middleware dùng chung vào cuối nhóm 'web'
        $this->middlewareGroups['web'] = array_merge(
            $this->middlewareGroups['web'],
            static::$sharedRouteMiddlewares
        );

        // Gộp Middleware dùng chung vào cuối nhóm 'api'
        $this->middlewareGroups['api'] = array_merge(
            $this->middlewareGroups['api'],
            static::$sharedRouteMiddlewares
        );
    }


    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array<string, class-string|string>
     */

    protected $routeMiddleware = [
        'auth' => \App\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
        'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
        'admin' => \App\Http\Middleware\AdminCheck::class,
        'internal' => \App\Http\Middleware\Internal::class,
        'user.ban' => \App\Http\Middleware\UserBan::class,
        'user.track' => \App\Http\Middleware\UserTrack::class,
    ];
}
