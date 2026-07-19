<?php

use Inertia\Testing\AssertableInertia as Assert;

test('renders the wishlist page with Inertia', function () {
    $response = $this->get(route('wishlist'));

    $response->assertOk();
    $response->assertInertia(fn (Assert $page) => $page->component('shop/Wishlist'));
});
