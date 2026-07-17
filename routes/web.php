<?php

use Illuminate\Support\Facades\Route;

Route::inertia('/', 'shop/Home')->name('home');
Route::inertia('product-details', 'shop/ProductDetails')->name('product.details');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'Dashboard')->name('dashboard');
});

require __DIR__.'/settings.php';
