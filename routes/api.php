<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiMapMarkerController;

/*
|--------------------------------------------------------------------------
| API Routes (REST API v1)
|--------------------------------------------------------------------------
*/
Route::prefix('v1')->group(function () {
    Route::get('/map-markers', [ApiMapMarkerController::class, 'index'])->name('api.map.markers');
});
