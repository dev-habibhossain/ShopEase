<?php

use Inertia\Testing\AssertableInertia as Assert;

test('renders the shop home page with Inertia', function () {
    $response = $this->get(route('home'));

    $response->assertOk();
    $response->assertInertia(fn (Assert $page) => $page->component('shop/Home'));
});
