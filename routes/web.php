<?php

use App\Http\Controllers\Storefront\CartController;
use App\Http\Controllers\Storefront\CheckoutController;
use App\Http\Controllers\Storefront\HelpSupportController;
use App\Http\Controllers\Storefront\HomeController;
use App\Http\Controllers\Storefront\ProductDetailsController;
use App\Http\Controllers\Storefront\ShopController;
use App\Http\Controllers\Storefront\WishlistController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');
Route::get('product-details/{slug}', ProductDetailsController::class)->name('product.details');
Route::get('shop', ShopController::class)->name('shop');
Route::get('cart', CartController::class)->name('cart');
Route::get('checkout', CheckoutController::class)->name('checkout');
Route::get('wishlist', WishlistController::class)->name('wishlist');
Route::get('help-support', HelpSupportController::class)->name('help.support');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'Dashboard')->name('dashboard');

    Route::prefix('dashboard')->name('dashboard.')->group(function () {
        Route::inertia('overview', 'Dashboard')->name('overview');
        Route::inertia('products', 'Dashboard/Products')->name('products');
        Route::inertia('categories', 'Dashboard/Categories')->name('categories');
        Route::inertia('orders', 'Dashboard/Orders')->name('orders');
        Route::inertia('customers', 'Dashboard/Customers')->name('customers');
        Route::inertia('coupons', 'Dashboard/Coupons')->name('coupons');
        Route::inertia('reviews', 'Dashboard/Reviews')->name('reviews');
        Route::inertia('hero-slides', 'Dashboard/HeroSlides')->name('hero-slides');
        Route::inertia('payments', 'Dashboard/Payments')->name('payments');
    });
});

require __DIR__.'/settings.php';
