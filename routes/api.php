<?php

use App\Http\Controllers\Api\ProviderCatalogController;
use App\Http\Controllers\Api\ProviderProfileController;
use App\Http\Controllers\Api\UserAuthController;
use App\Http\Controllers\Api\UserBookingController;
use App\Http\Controllers\Api\ProviderController;
use Illuminate\Support\Facades\Route;



    Route::post('register', [UserAuthController::class, 'register']);
    Route::post('login', [UserAuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {

        Route::post('logout', [UserAuthController::class, 'logout']);

        Route::post('providers', [ProviderProfileController::class, 'store']);

        Route::get('providers', [ProviderController::class, 'providers']);
        Route::get('providers/{id}', [ProviderController::class, 'provider']);

        Route::middleware('is.provider')->get('providers/{id}/availabilities', [ProviderController::class, 'availabilities']);
        Route::middleware('is.provider')->post('availabilities', [ProviderController::class, 'storeAvailabilities']);
        Route::get('services/{id}/available-slots', [ProviderController::class, 'availableSlots']);

        Route::get('providers/{provider}/services', [ProviderController::class, 'providerServices']);
        Route::get('services/{id}', [ProviderController::class, 'service']);
        Route::middleware('is.provider')->post('services', [ProviderController::class, 'storeService']);


        Route::get('bookings', [UserBookingController::class, 'index']);
        Route::post('bookings', [UserBookingController::class, 'store']);
        Route::post('bookings/{booking}/cancel', [UserBookingController::class, 'cancel']);
    });




