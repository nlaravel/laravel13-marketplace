
<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/home', function () {
    return view('home');
})->middleware('auth')->name('home');

Route::middleware(['auth'])->group(function () {
    Route::view('/customer', 'customer.dashboard')->name('customer.dashboard');
    Route::get('/customer/addresses', \App\Livewire\Customer\Addresses\Index::class) ->name('customer.addresses.index');
   // Route::get('/customer/addresses', \App\Livewire\Customer\Addresses\Index::class) ->name('customer.addresses.index');
//    Route::get('/customer/addresses/create', \App\Livewire\Customer\Addresses\Create::class)->name('customer.addresses.create');
//    Route::get('/customer/addresses/{address}/edit', \App\Livewire\Customer\Addresses\Edit::class) ->name('customer.addresses.edit');
//
//
});
