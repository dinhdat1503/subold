<?php

use App\Helpers\BackendHelper;
use Illuminate\Support\Facades\Route;

Route::get('/schedule/{secret}', function () {
    try {
        Artisan::call('schedule:run');
        $output = Artisan::output();
        return BackendHelper::resApi(
            'success',
            $output
        );
    } catch (\Exception $e) {
        return BackendHelper::resApi(
            'error',
            'Lỗi khi thực thi lệnh: ' . $e->getMessage()
        );
    }
});
Route::post('/webhook/bank/{secret}', function () {
    try {
        Artisan::call('recharge:internal:bank');
        return BackendHelper::resApi(
            "",
            "",
            ['success' => true]
        );
    } catch (\Exception $e) {
        return BackendHelper::resApi(
            "error",
            'Lỗi khi thực thi lệnh: ' . $e->getMessage(),
            ['success' => false]
        );
    }
});


