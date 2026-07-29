<?php

use App\Models\Product;
use App\Models\User;
use App\Models\Wishlist;
use Inertia\Testing\AssertableInertia as Assert;

test('renders the wishlist page with Inertia for guests', function () {
    $response = $this->get(route('wishlist'));

    $response->assertOk();
    $response->assertInertia(fn (Assert $page) => $page
        ->component('shop/Wishlist')
        ->has('products', 0)
    );
});

test('renders wishlist items from database for authenticated users', function () {
    $user = User::factory()->create();
    $product = Product::factory()->create(['is_active' => true]);

    Wishlist::create([
        'user_id' => $user->id,
        'product_id' => $product->id,
    ]);

    $response = $this->actingAs($user)->get(route('wishlist'));

    $response->assertOk();
    $response->assertInertia(fn (Assert $page) => $page
        ->component('shop/Wishlist')
        ->has('products', 1)
        ->where('products.0.id', $product->id)
    );
});

test('authenticated user can add product to wishlist via toggle endpoint', function () {
    $user = User::factory()->create();
    $product = Product::factory()->create(['is_active' => true]);

    $response = $this->actingAs($user)->post(route('wishlist.toggle', $product));

    $response->assertRedirect();
    $this->assertDatabaseHas('wishlists', [
        'user_id' => $user->id,
        'product_id' => $product->id,
    ]);
});

test('authenticated user can remove product from wishlist via toggle endpoint', function () {
    $user = User::factory()->create();
    $product = Product::factory()->create(['is_active' => true]);

    Wishlist::create([
        'user_id' => $user->id,
        'product_id' => $product->id,
    ]);

    $response = $this->actingAs($user)->post(route('wishlist.toggle', $product));

    $response->assertRedirect();
    $this->assertDatabaseMissing('wishlists', [
        'user_id' => $user->id,
        'product_id' => $product->id,
    ]);
});
