<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

class StripeCheckoutController extends Controller
{
    /**
     * Create a Stripe Checkout session and return the redirect URL.
     */
    public function createSession(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'cart' => 'required|array|min:1',
            'cart.*.name' => 'required|string',
            'cart.*.price' => 'required|numeric',
            'cart.*.qty' => 'required|integer|min:1',
            'fullName' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'district' => 'required|string|max:100',
            'area' => 'required|string|max:100',
            'address' => 'required|string|max:1000',
            'notes' => 'nullable|string|max:1000',
            'deliveryCharge' => 'nullable|numeric',
        ]);

        $cart = $validated['cart'];
        $deliveryCharge = (float) ($validated['deliveryCharge'] ?? 60);

        $secretKey = config('services.stripe.secret');

        // Check if official Stripe Secret key is configured
        if ($secretKey && (str_starts_with($secretKey, 'sk_test_') || str_starts_with($secretKey, 'sk_live_'))) {
            $lineItems = [];
            foreach ($cart as $index => $item) {
                $lineItems["line_items[{$index}][price_data][currency]"] = 'usd';
                $lineItems["line_items[{$index}][price_data][product_data][name]"] = $item['name'];
                $lineItems["line_items[{$index}][price_data][unit_amount]"] = (int) round(((float) $item['price']) * 100);
                $lineItems["line_items[{$index}][quantity]"] = (int) $item['qty'];
            }

            if ($deliveryCharge > 0) {
                $delIndex = count($cart);
                $lineItems["line_items[{$delIndex}][price_data][currency]"] = 'usd';
                $lineItems["line_items[{$delIndex}][price_data][product_data][name]"] = 'Delivery Charge';
                $lineItems["line_items[{$delIndex}][price_data][unit_amount]"] = (int) round($deliveryCharge * 100);
                $lineItems["line_items[{$delIndex}][quantity]"] = 1;
            }

            $response = Http::withToken($secretKey)
                ->asForm()
                ->post('https://api.stripe.com/v1/checkout/sessions', array_merge([
                    'mode' => 'payment',
                    'success_url' => route('checkout.stripe.success').'?session_id={CHECKOUT_SESSION_ID}',
                    'cancel_url' => route('checkout'),
                    'customer_email' => $validated['email'],
                ], $lineItems));

            if ($response->successful() && ! empty($response->json()['url'])) {
                return response()->json([
                    'url' => $response->json()['url'],
                ]);
            }
        }

        // Fallback / Sandbox mode if Stripe key is not configured or in sandbox
        $orderNumber = 'SE-'.date('Y').'-'.rand(100000, 999999);
        $subtotal = array_reduce($cart, fn ($sum, $item) => $sum + ($item['price'] * $item['qty']), 0);
        $total = $subtotal + $deliveryCharge;

        $order = Order::create([
            'order_number' => $orderNumber,
            'user_id' => Auth::id(),
            'customer_name' => $validated['fullName'],
            'phone' => $validated['phone'],
            'email' => $validated['email'],
            'district' => $validated['district'],
            'area' => $validated['area'],
            'address' => $validated['address'],
            'notes' => $validated['notes'] ?? null,
            'subtotal' => $subtotal,
            'delivery_charge' => $deliveryCharge,
            'total' => $total,
            'payment_method' => 'stripe',
            'payment_status' => 'paid',
            'status' => 'processing',
            'placed_at' => now(),
        ]);

        return response()->json([
            'url' => route('checkout.stripe.success').'?order_id='.$order->order_number.'&total='.$total,
        ]);
    }

    /**
     * Handle Stripe Checkout success redirect.
     */
    public function success(Request $request): InertiaResponse
    {
        $orderId = $request->query('order_id') ?? $request->query('session_id') ?? ('SE-'.date('Y').'-'.rand(100000, 999999));
        $total = (float) $request->query('total', 0);

        return Inertia::render('shop/Checkout', [
            'stripeSuccess' => [
                'orderId' => $orderId,
                'total' => $total,
                'paymentMethod' => 'Stripe (Paid)',
            ],
        ]);
    }
}
