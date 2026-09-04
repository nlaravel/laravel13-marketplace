<?php

declare(strict_types=1);

use App\Livewire\Customer\Addresses\Create;
use App\Livewire\Customer\Addresses\Edit;
use App\Livewire\Customer\Addresses\Index;
use App\Livewire\Customer\Cart;
use App\Livewire\Customer\Dashboard;
use App\Livewire\Customer\Profile;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function (): void {

    /* Dashboard */
    Route::get('/customer', Dashboard::class)->name('customer.dashboard');
    Route::get('/customer/profile', Profile::class)->name('customer.profile');

    /* Addresses */
    Route::get('/customer/addresses', Index::class)->name('customer.addresses.index');

    Route::get('/customer/addresses/create', Create::class)->name('customer.addresses.create');

    Route::get('/customer/addresses/{address}/edit', Edit::class)->name('customer.addresses.edit');

    /* Cart */
    Route::get('/customer/cart', Cart::class)->name('customer.cart');

});
