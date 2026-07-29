<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class CheckoutController extends Controller
{
    /**
     * Handle the incoming request to render checkout page.
     */
    public function index(Request $request): Response
    {
        return Inertia::render('shop/Checkout');
    }

    /**
     * Process Cash on Delivery (COD) order.
     */
    public function process(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'cart' => 'required|array|min:1',
            'cart.*.id' => 'nullable',
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
            'payment_method' => 'cod',
            'payment_status' => 'pending',
            'status' => 'pending',
            'placed_at' => now(),
        ]);

        session(['last_placed_order' => $order->order_number]);

        foreach ($cart as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['id'] ?? null,
                'product_name' => $item['name'],
                'unit_price' => $item['price'],
                'quantity' => $item['qty'],
                'line_total' => $item['price'] * $item['qty'],
            ]);
        }

        return response()->json([
            'success' => true,
            'order_number' => $order->order_number,
            'total' => $total,
        ]);
    }
}
