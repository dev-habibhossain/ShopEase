<?php

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\HeroSlide;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatusHistory;
use App\Models\Payment;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Review;
use App\Models\User;
use App\Models\Wishlist;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

test('all new models can be instantiated and have correct relationships', function () {
    // 1. User
    $user = new User;
    expect($user->orders())->toBeInstanceOf(HasMany::class)
        ->and($user->reviews())->toBeInstanceOf(HasMany::class)
        ->and($user->wishlists())->toBeInstanceOf(HasMany::class)
        ->and($user->cart())->toBeInstanceOf(HasOne::class);

    // 2. HeroSlide
    $hero = new HeroSlide;
    expect($hero)->toBeInstanceOf(HeroSlide::class);

    // 3. Category
    $category = new Category;
    expect($category->products())->toBeInstanceOf(HasMany::class);

    // 4. Product
    $product = new Product;
    expect($product->category())->toBeInstanceOf(BelongsTo::class)
        ->and($product->images())->toBeInstanceOf(HasMany::class)
        ->and($product->reviews())->toBeInstanceOf(HasMany::class)
        ->and($product->wishlists())->toBeInstanceOf(HasMany::class)
        ->and($product->orderItems())->toBeInstanceOf(HasMany::class)
        ->and($product->cartItems())->toBeInstanceOf(HasMany::class);

    // 5. ProductImage
    $image = new ProductImage;
    expect($image->product())->toBeInstanceOf(BelongsTo::class);

    // 6. Cart
    $cart = new Cart;
    expect($cart->user())->toBeInstanceOf(BelongsTo::class)
        ->and($cart->items())->toBeInstanceOf(HasMany::class);

    // 7. CartItem
    $cartItem = new CartItem;
    expect($cartItem->cart())->toBeInstanceOf(BelongsTo::class)
        ->and($cartItem->product())->toBeInstanceOf(BelongsTo::class);

    // 8. Coupon
    $coupon = new Coupon;
    expect($coupon->orders())->toBeInstanceOf(HasMany::class);

    // 9. Order
    $order = new Order;
    expect($order->user())->toBeInstanceOf(BelongsTo::class)
        ->and($order->coupon())->toBeInstanceOf(BelongsTo::class)
        ->and($order->items())->toBeInstanceOf(HasMany::class)
        ->and($order->invoice())->toBeInstanceOf(HasOne::class)
        ->and($order->payments())->toBeInstanceOf(HasMany::class)
        ->and($order->statusHistories())->toBeInstanceOf(HasMany::class)
        ->and($order->reviews())->toBeInstanceOf(HasMany::class);

    // 10. OrderItem
    $orderItem = new OrderItem;
    expect($orderItem->order())->toBeInstanceOf(BelongsTo::class)
        ->and($orderItem->product())->toBeInstanceOf(BelongsTo::class);

    // 11. Invoice
    $invoice = new Invoice;
    expect($invoice->order())->toBeInstanceOf(BelongsTo::class);

    // 12. Payment
    $payment = new Payment;
    expect($payment->order())->toBeInstanceOf(BelongsTo::class);

    // 13. OrderStatusHistory
    $history = new OrderStatusHistory;
    expect($history->order())->toBeInstanceOf(BelongsTo::class)
        ->and($history->changedBy())->toBeInstanceOf(BelongsTo::class);

    // 14. Review
    $review = new Review;
    expect($review->product())->toBeInstanceOf(BelongsTo::class)
        ->and($review->user())->toBeInstanceOf(BelongsTo::class)
        ->and($review->order())->toBeInstanceOf(BelongsTo::class);

    // 15. Wishlist
    $wishlist = new Wishlist;
    expect($wishlist->user())->toBeInstanceOf(BelongsTo::class)
        ->and($wishlist->product())->toBeInstanceOf(BelongsTo::class);
});
