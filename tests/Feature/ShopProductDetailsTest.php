<?php

use Inertia\Testing\AssertableInertia as Assert;

test('renders the shop product details page with Inertia', function () {
    $response = $this->get(route('product.details', ['slug' => 'test-product']));

    $response->assertOk();
    $response->assertInertia(fn (Assert $page) => $page
        ->component('shop/ProductDetails')
        ->has('slug')
        ->where('slug', 'test-product')
    );
});
