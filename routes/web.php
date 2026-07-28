<?php

use App\Http\Controllers\Admin\CategoryManagementController;
use App\Http\Controllers\Admin\CouponManagementController;
use App\Http\Controllers\Admin\CustomerManagementController;
use App\Http\Controllers\Admin\DashboardOverviewController;
use App\Http\Controllers\Admin\HeroSlideManagementController;
use App\Http\Controllers\Admin\OrderManagementController;
use App\Http\Controllers\Admin\PaymentManagementController;
use App\Http\Controllers\Admin\ProductManagementController;
use App\Http\Controllers\Admin\ReviewManagementController;
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
    Route::get('dashboard', DashboardOverviewController::class)->name('dashboard');

    Route::prefix('dashboard')->name('dashboard.')->group(function () {
        Route::get('overview', DashboardOverviewController::class)->name('overview');

        Route::resource('products', ProductManagementController::class)->except(['create', 'edit']);
        Route::resource('categories', CategoryManagementController::class)->except(['create', 'edit']);
        Route::resource('orders', OrderManagementController::class)->only(['index', 'show', 'update']);
        Route::resource('customers', CustomerManagementController::class)->only(['index', 'show']);
        Route::resource('coupons', CouponManagementController::class)->except(['create', 'edit']);
        Route::resource('reviews', ReviewManagementController::class)->only(['index', 'update', 'destroy']);
        Route::resource('hero-slides', HeroSlideManagementController::class)->except(['create', 'edit']);
        Route::get('payments', [PaymentManagementController::class, 'index'])->name('payments.index');
    });
});

require __DIR__.'/settings.php';
