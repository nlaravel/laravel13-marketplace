<?php

use App\Http\Controllers\Api\V1\Auth\AuthController;
use App\Http\Controllers\Api\V1\Customer\AddressController;
use App\Http\Controllers\Api\V1\Customer\CartController;
use App\Http\Controllers\Api\V1\Customer\ProfileController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('register', [AuthController::class, 'register']);
        Route::post('login', [AuthController::class, 'login']);

        Route::post('forgot-password', [AuthController::class, 'forgotPassword']);
        Route::post('reset-password', [AuthController::class, 'resetPassword']);

        Route::middleware('auth:sanctum')->group(function () {
            Route::get('me', [AuthController::class, 'me']);
            Route::post('logout', [AuthController::class, 'logout']);
        });
    });

});
Route::prefix('v1/customer')->middleware('auth:sanctum')->group(function () {

    Route::get('/profile', [ProfileController::class, 'show']);
    Route::put('/profile', [ProfileController::class, 'update']);

    Route::get('/addresses', [
        AddressController::class,
        'index',
    ]);

    Route::post('/addresses', [
        AddressController::class,
        'store',
    ]);

    Route::get('/addresses/{address}', [
        AddressController::class,
        'show',
    ]);

    Route::put('/addresses/{address}', [
        AddressController::class,
        'update',
    ]);

    Route::delete('/addresses/{address}', [
        AddressController::class,
        'destroy',
    ]);

    Route::patch('/addresses/{address}/default', [
        AddressController::class,
        'setDefault',
    ]);

    // Cart
    Route::get('/cart', [CartController::class, 'show'])->name('api.v1.customer.cart.show');

    Route::post('/cart/items', [CartController::class, 'store'])->name('api.v1.customer.cart.items.store');

    Route::put('/cart/items/{item}', [CartController::class, 'update'])->name('api.v1.customer.cart.items.update');

    Route::delete('/cart/items/{item}', [CartController::class, 'destroyItem'])->name('api.v1.customer.cart.items.destroy');

    Route::delete('/cart', [CartController::class, 'clear'])->name('api.v1.customer.cart.clear');

    Route::post('/cart/items/bulk', [CartController::class, 'addItems'])->name('api.v1.customer.cart.addItems');

});
