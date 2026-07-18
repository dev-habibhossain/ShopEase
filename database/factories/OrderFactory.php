<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $subtotal = fake()->randomFloat(2, 500, 15000);
        $discountAmount = fake()->boolean(30) ? fake()->randomFloat(2, 50, 500) : 0;
        $deliveryCharge = fake()->randomElement([60, 120]);
        $total = $subtotal - $discountAmount + $deliveryCharge;

        return [
            'order_number' => 'ORD-'.strtoupper(fake()->unique()->bothify('?????#####')),
            'user_id' => User::factory(),
            'customer_name' => fake()->name(),
            'phone' => fake()->numerify('01#########'),
            'email' => fake()->safeEmail(),
            'district' => fake()->randomElement(['Dhaka', 'Chittagong', 'Sylhet', 'Rajshahi']),
            'area' => fake()->word(),
            'address' => fake()->address(),
            'notes' => fake()->boolean(40) ? fake()->sentence() : null,
            'coupon_id' => null,
            'discount_amount' => $discountAmount,
            'subtotal' => $subtotal,
            'delivery_charge' => $deliveryCharge,
            'total' => $total,
            'payment_method' => fake()->randomElement(['cod', 'stripe']),
            'payment_status' => fake()->randomElement(['pending', 'paid', 'failed', 'cancelled']),
            'status' => fake()->randomElement(['pending', 'processing', 'shipped', 'delivered', 'cancelled']),
            'placed_at' => now()->subDays(fake()->numberBetween(1, 30)),
        ];
    }
}
