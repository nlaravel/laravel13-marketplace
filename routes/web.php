<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/home', function () {
    return view('home');
})
    ->middleware('auth')
    ->name('home');

Route::middleware(['auth'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Customer Dashboard
    |--------------------------------------------------------------------------
    */

    Route::view(
        '/customer',
        'customer.dashboard'
    )->name('customer.dashboard');


    /*
    |--------------------------------------------------------------------------
    | Customer Addresses
    |--------------------------------------------------------------------------
    */

    Route::view(
        '/customer/addresses',
        'customer.addresses.index'
    )->name('customer.addresses.index');

    Route::view(
        '/customer/addresses/create',
        'customer.addresses.create'
    )->name('customer.addresses.create');

    Route::view(
        '/customer/addresses/{address}/edit',
        'customer.addresses.edit'
    )->name('customer.addresses.edit');

});