<?php

use Inertia\Testing\AssertableInertia as Assert;

test('cart page can be rendered', function () {
    $response = $this->get(route('cart'));

    $response->assertStatus(200);
    $response->assertInertia(fn (Assert $page) => $page
        ->component('shop/Cart')
    );
});
