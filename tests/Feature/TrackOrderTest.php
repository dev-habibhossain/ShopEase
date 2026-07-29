<?php

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('guest can access track order page without params', function () {
    $response = $this->get('/track-order');

    $response->assertStatus(200);
});

test('guest can track an order by order number', function () {
    $order = Order::create([
        'order_number' => 'SE-2026-999888',
        'customer_name' => 'John Doe',
        'phone' => '01800000000',
        'email' => 'john@example.com',
        'district' => 'Dhaka',
        'area' => 'Dhanmondi',
        'address' => 'House 1',
        'subtotal' => 1000,
        'delivery_charge' => 60,
        'total' => 1060,
        'payment_method' => 'cod',
        'payment_status' => 'pending',
        'status' => 'pending',
        'placed_at' => now(),
    ]);

    OrderItem::create([
        'order_id' => $order->id,
        'product_name' => 'Sample Item',
        'unit_price' => 1000,
        'quantity' => 1,
        'line_total' => 1000,
    ]);

    $response = $this->get('/track-order?order_number=SE-2026-999888');

    $response->assertStatus(200);
    $response->assertSee('SE-2026-999888');
    $response->assertSee('John Doe');
});
