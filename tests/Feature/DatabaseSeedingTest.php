<?php

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\HeroSlide;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatusHistory;
use App\Models\Payment;
use App\Models\Product;
use App\Models\User;
use App\Models\Wishlist;

test('database seeds successfully and populates expected tables', function () {
    // Run the seeder
    $this->seed();

    // Assert that the categories are seeded
    expect(Category::count())->toBe(6);
    expect(Category::where('slug', 'electronics')->exists())->toBeTrue();
    expect(Category::where('slug', 'fashion')->exists())->toBeTrue();

    // Assert that the products are seeded
    expect(Product::count())->toBe(14);

    // Assert that headphones product (ID 14) is seeded with its 5 images and 3 reviews
    $headphones = Product::find(14);
    expect($headphones)->not->toBeNull();
    expect($headphones->name)->toBe('Wireless Noise-Cancelling Headphones');
    expect($headphones->images()->count())->toBe(5);
    expect($headphones->reviews()->count())->toBe(3);

    // Assert that users are seeded (1 admin, 1 test customer, 10 random users, and 3 reviewer users)
    expect(User::count())->toBeGreaterThanOrEqual(12);

    // Assert slides are seeded
    expect(HeroSlide::count())->toBe(3);

    // Assert coupons are seeded
    expect(Coupon::count())->toBe(3);

    // Assert transactional data is seeded
    expect(Order::count())->toBeGreaterThan(0);
    expect(OrderItem::count())->toBeGreaterThan(0);
    expect(Payment::count())->toBeGreaterThan(0);
    expect(OrderStatusHistory::count())->toBeGreaterThan(0);
    expect(Wishlist::count())->toBeGreaterThan(0);
    expect(Cart::count())->toBeGreaterThan(0);
    expect(CartItem::count())->toBeGreaterThan(0);
});
