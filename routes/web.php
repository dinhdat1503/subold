<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth;
use App\Http\Controllers\Client;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->route('guest.home');
});

// Auth routes
Route::middleware('guest')->prefix('auth')->group(function () {

    Route::controller(Auth\LoginController::class)->group(function () {
        Route::get('login', 'login')->name('login');
        Route::post('login', 'loginProcess')->name('login.process');
    });

    Route::controller(Auth\RegisterController::class)->group(function () {
        Route::get('register', 'register')->name('register');
        Route::post('register', 'registerProcess')->name('register.process');
    });

    Route::controller(Auth\ForgotPasswordController::class)->group(function () {
        Route::get('forgot-password', 'forgotPassword')->name('password.forgot');
        Route::post('forgot-password', 'forgotPasswordSend')->name('password.forgot.send');
        Route::get('reset-password/{token}', 'resetPassword')->name('password.reset');
        Route::put('reset-password', 'resetPasswordUpdate')->name('password.reset.update');
    });

});

Route::match(['get', 'post'], 'logout', [Auth\AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');


Route::middleware(['auth', 'user.ban', 'user.track'])->group(function () {
    Route::get('/home', [Client\WebsiteController::class, 'home'])->name('home');

    Route::prefix('user')->controller(Client\UserController::class)->name('user.')->group(function () {
        Route::get('/profile', 'profile')->name('profile');
        Route::put('/profile/{type}', 'profileUpdate')->name('profile.update');
        Route::get('/logs', 'logs')->name('logs');
        Route::get('/logs/data', 'logsData')->name('logs.data');
        Route::get('/level', 'level')->name('level');
    });

    Route::prefix('recharge')->controller(Client\RechargeController::class)->name('recharge.')->group(function () {
        Route::get('/bank', 'bank')->name('bank');
        Route::get('/crypto', 'crypto')->name('crypto');
        Route::post('/crypto', 'cryptoStore')->name('crypto.store');
        Route::get('/logs/data/{type}', 'logsData')->name('logs.data');
    });

    Route::prefix('service')->controller(Client\ServiceController::class)->name('service.')->group(function () {
        Route::get('/price', 'price')->name('price');
        Route::get('/orders', 'orders')->name('orders');
        Route::get('/orders/data/{type}', 'ordersData')->name('orders.data');
        Route::get('/{social}/{service}', 'view')->name('view');
        Route::post('/order', 'orderProcess')->name('order.process');
        Route::get('/order/info/{id}', 'orderInfo')->name('order.info');
        Route::put('/order/{type}', 'orderUpdate')->name('order.update');
        Route::get('/server/info/{id}', 'serverInfo')->name('server.info');
    });

    Route::prefix('tool')->controller(Client\ToolController::class)->name('tool.')->group(function () {
        Route::get('/user/poll', 'userPoll')->name('user.poll');
        Route::post('/search', 'search')->name('search');
    });
});

Route::prefix('guest')->middleware('guest')->name('guest.')->group(function () {
    Route::get('/home', [Client\WebsiteController::class, 'home'])->name('home');
    Route::get('/service/{social}/{service}', [Client\ServiceController::class, 'view'])->name('service.view');
});

Route::prefix('website')->controller(Client\WebsiteController::class)->name('web.')->group(function () {
    Route::get('/terms', 'terms')->name('terms');
    Route::get('/policy', 'policy')->name('policy');
    Route::get('/guide', 'guide')->name('guide');
});

Route::prefix('error')->controller(Client\WebsiteController::class)->group(function () {
    Route::get('/maintaince', 'maintaince')->name('maintaince');
    Route::get('/ip-block', 'ipBlock')->name('ip.block');
    Route::get('/ban', 'userBan')->name('user.ban');
});

Route::get('/blog/{name}', [Client\WebsiteController::class, 'blog'])->name('blog');

