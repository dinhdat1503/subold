<?php

use App\Http\Controllers\Admin\DataAdminController;
use App\Http\Controllers\Admin\DataServiceController;
use App\Http\Controllers\Admin\ViewAdminController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin;




Route::controller(Admin\WebsiteController::class)->group(function () {
    Route::get('/dashboard', 'dashboard')->name('dashboard');
    Route::get('/dashboard/poll', 'dashboardPoll')->name('dashboard.poll');
    Route::prefix('config')->name('config.')->group(function () {
        Route::get('/manage', 'config')->name('manage');
        Route::put('/update', 'configUpdate')->name('update');
    });
});

Route::prefix('/ip')->name('ip.')->controller(Admin\IpController::class)->group(function () {
    Route::get('/manage', 'ipBlock')->name('manage');
    Route::get('/manage/data', 'ipBlockData')->name('manage.data');
    Route::post('/store', 'ipBlockStore')->name('store');
    Route::delete('/destroy/{id}', 'ipBlockDestroy')->name('destroy');
});


Route::prefix('/notice')->name('notice.')->controller(Admin\NoticeController::class)->group(function () {
    Route::get('/notification', 'notification')->name('notification');
    Route::put('/notification', 'notificationUpdate')->name('notification.update');
    Route::get('/activity', 'activity')->name('activity');
    Route::get('/activity/data', 'activityData')->name('activity.data');
    Route::post('/activity', 'activityStore')->name('activity.store');
    Route::delete('/activity/{id}', 'activityDestroy')->name('activity.destroy');
});

Route::prefix('/blogs')->name('blogs.')->controller(Admin\BlogController::class)->group(function () {
    Route::get('/manage', 'blog')->name('manage');
    Route::get('/manage/data', 'blogData')->name('manage.data');
    Route::post('/store', 'blogStore')->name('store');
    Route::put('/update/{id}', 'blogUpdate')->name('update');
    Route::delete('/delete/{id}', 'blogDelete')->name('delete');
});


Route::prefix('/supplier')->name('supplier.')->controller(Admin\SupplierController::class)->group(function () {
    Route::get('/manage', 'supplier')->name('manage');
    Route::get('/data', 'data')->name('data');
    Route::post('/store', 'store')->name('store');
    Route::put('/update/{id}', 'update')->name('update');
    Route::get('/edit/{id}', 'edit')->name('edit');
    Route::delete('/{id}', 'destroy')->name('destroy');
});

Route::prefix('/recharge')->name('recharge.')->controller(Admin\RechargeController::class)->group(function () {
    Route::get('/manage', 'recharge')->name('manage');
    Route::put('/update', 'update')->name('update');
    Route::get('/logs', 'logs')->name('logs');
    Route::get('/logs/data', 'logsData')->name('logs.data');
});

Route::prefix('/user')->name('user.')->controller(Admin\UserController::class)->group(function () {
    Route::get('/logs', 'logs')->name('logs');
    Route::get('/logs/data/{type}', 'logsData')->name('logs.data');
    Route::get('/list', 'list')->name('list');
    Route::get('/list/data', 'listData')->name('list.data');
    Route::delete('/destroy/{id}', 'destroy')->name('destroy');
    Route::get('/edit/{id}', 'edit')->name('edit');
    Route::put('/update/{type}/{id}', 'update')->name('update');

});

Route::prefix('service')->name('service.')->group(function () {

    // SOCIAL
    Route::prefix('social')->name('social.')->controller(Admin\Services\SocialController::class)->group(function () {
        Route::get('manage', 'social')->name('manage');
        Route::get('data', 'socialData')->name('data');
        Route::post('store', 'socialStore')->name('store');
        Route::get('edit/{id}', 'socialEdit')->name('edit');
        Route::put('update/{id}', 'socialUpdate')->name('update');
        Route::delete('destroy/{id}', 'socialDestroy')->name('destroy');
    });

    // SERVER
    Route::prefix('server')->name('server.')->controller(Admin\Services\ServerController::class)->group(function () {
        Route::get('manage', 'server')->name('manage');
        Route::get('data', 'serverData')->name('data');
        Route::post('store', 'serverStore')->name('store');
        Route::get('edit-fetch', 'serverEditFetch')->name('edit.fetch');
        Route::get('edit/{id}', 'serverEdit')->name('edit');
        Route::put('update/{id}', 'serverUpdate')->name('update');
        Route::delete('destroy/{id}', 'serverDestroy')->name('destroy');
    });

    // SERVICES
    Route::prefix('services')->name('services.')->controller(Admin\Services\ServiceController::class)->group(function () {
        Route::get('manage', 'services')->name('manage');
        Route::get('data', 'servicesData')->name('data');
        Route::post('store', 'servicesStore')->name('store');
        Route::get('edit/{id}', 'servicesEdit')->name('edit');
        Route::put('update/{id}', 'servicesUpdate')->name('update');
        Route::delete('destroy/{id}', 'servicesDestroy')->name('destroy');
    });

    Route::prefix('orders')->name('orders.')->controller(Admin\Services\OrderController::class)->group(function () {
        Route::get('manage', 'orders')->name('manage');
        Route::get('info/{id}', 'orderInfo')->name('info');
        Route::put('update/{type}/{id}', 'orderUpdate')->name('update');
        Route::get('manage/data', 'ordersData')->name('manage.data');
    });

});
