<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Payment>
 */
class PaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $status = fake()->randomElement(['initiated', 'success', 'failed', 'cancelled']);
        $paidAt = ($status === 'success') ? now() : null;

        return [
            'order_id' => Order::factory(),
            'gateway' => 'stripe',
            'transaction_id' => 'ch_'.fake()->unique()->bothify('????????????????????'),
            'val_id' => null,
            'amount' => fake()->randomFloat(2, 500, 15000),
            'currency' => 'BDT',
            'status' => $status,
            'card_type' => fake()->randomElement(['Visa', 'MasterCard', 'Bkash', 'Nagad', null]),
            'bank_tran_id' => fake()->bothify('BANK-TRANS-#####'),
            'store_amount' => null,
            'raw_response' => null,
            'paid_at' => $paidAt,
        ];
    }
}
