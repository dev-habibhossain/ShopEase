<?php

namespace Database\Factories;

use App\Models\Invoice;
use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Invoice>
 */
class InvoiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'order_id' => Order::factory(),
            'invoice_number' => 'INV-'.strtoupper(fake()->unique()->bothify('?????#####')),
            'issued_at' => now(),
            'total' => fake()->randomFloat(2, 500, 15000),
        ];
    }
}
