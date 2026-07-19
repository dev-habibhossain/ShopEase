<?php

use Inertia\Testing\AssertableInertia as Assert;

test('renders the checkout page with Inertia', function () {
    $response = $this->get(route('checkout'));

    $response->assertOk();
    $response->assertInertia(fn (Assert $page) => $page->component('shop/Checkout'));
});
