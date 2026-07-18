<?php

use Inertia\Testing\AssertableInertia as Assert;

test('renders the shop page with Inertia', function () {
    $response = $this->get(route('shop'));

    $response->assertOk();
    $response->assertInertia(fn (Assert $page) => $page->component('shop/Shop'));
});
