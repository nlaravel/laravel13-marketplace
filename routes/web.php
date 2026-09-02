
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

});