<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiMapMarkerController;
use App\Http\Controllers\Api\ApiV1Controller;

/*
|--------------------------------------------------------------------------
| API Routes (REST API v1)
|--------------------------------------------------------------------------
*/
Route::prefix('v1')->group(function () {
    Route::get('/destinations', [ApiV1Controller::class, 'destinations'])->name('api.destinations');
    Route::get('/map-markers', [ApiMapMarkerController::class, 'index'])->name('api.map.markers');
    Route::get('/products', [ApiV1Controller::class, 'products'])->name('api.products');
    Route::get('/products/{id}', [ApiV1Controller::class, 'productShow'])->name('api.products.show');

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/orders/simulate', [ApiV1Controller::class, 'simulateOrder'])->name('api.orders.simulate');
        Route::post('/payments/simulate-pay', [ApiV1Controller::class, 'simulatePayment'])->name('api.payments.simulate');
        Route::get('/umkm/my-finances', [ApiV1Controller::class, 'myFinances'])->name('api.umkm.finances');
    });
});
