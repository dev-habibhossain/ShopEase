<?php

use App\Http\Controllers\Storefront\CheckoutController;
use App\Http\Controllers\Storefront\HomeController;
use App\Http\Controllers\Storefront\ProductDetailsController;
use App\Http\Controllers\Storefront\ShopController;
use App\Http\Controllers\Storefront\WishlistController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');
Route::get('product-details/{slug}', ProductDetailsController::class)->name('product.details');
Route::get('shop', ShopController::class)->name('shop');
Route::get('checkout', CheckoutController::class)->name('checkout');
Route::get('wishlist', WishlistController::class)->name('wishlist');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'Dashboard')->name('dashboard');
});

require __DIR__.'/settings.php';
