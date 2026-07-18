<?php

namespace Database\Factories;

use App\Models\Coupon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Coupon>
 */
class CouponFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $type = fake()->randomElement(['fixed', 'percent']);
        $value = ($type === 'percent') ? fake()->numberBetween(5, 30) : fake()->numberBetween(50, 500);

        return [
            'code' => strtoupper(fake()->unique()->bothify('????##')),
            'type' => $type,
            'value' => $value,
            'min_order_amount' => fake()->randomElement([null, 500, 1000, 2000]),
            'starts_at' => now(),
            'expires_at' => now()->addDays(fake()->numberBetween(7, 30)),
            'usage_limit' => fake()->randomElement([null, 10, 50, 100]),
            'used_count' => 0,
            'is_active' => true,
        ];
    }
}
