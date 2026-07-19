<?php

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;

uses(RefreshDatabase::class);

test('renders the shop product details page with Inertia', function () {
    $product = Product::factory()->create([
        'slug' => 'test-product',
        'is_active' => true,
    ]);

    $response = $this->get(route('product.details', ['slug' => $product->slug]));

    $response->assertOk();
    $response->assertInertia(fn (Assert $page) => $page
        ->component('shop/ProductDetails')
        ->has('product')
        ->where('product.slug', 'test-product')
    );
});
