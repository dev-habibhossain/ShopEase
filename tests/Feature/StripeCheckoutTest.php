<?php

use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('guest or user can initiate stripe checkout session', function () {
    $payload = [
        'cart' => [
            [
                'name' => 'Wireless Headphones',
                'price' => 2500,
                'qty' => 2,
            ],
        ],
        'fullName' => 'Jane Doe',
        'phone' => '01712345678',
        'email' => 'jane@example.com',
        'district' => 'Dhaka',
        'area' => 'Mirpur',
        'address' => 'House 12, Road 4',
        'notes' => 'Deliver in afternoon',
        'deliveryCharge' => 60,
    ];

    $response = $this->postJson('/checkout/stripe-session', $payload);

    $response->assertStatus(200);
    $response->assertJsonStructure(['url']);

    expect(Order::where('email', 'jane@example.com')->exists())->toBeTrue();
});

test('stripe checkout success page renders successfully', function () {
    $response = $this->get('/checkout/stripe/success?order_id=SE-2026-123456&total=5060');

    $response->assertStatus(200);
});
