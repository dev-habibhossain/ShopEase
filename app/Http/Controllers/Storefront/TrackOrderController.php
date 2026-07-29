<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

class TrackOrderController extends Controller
{
    /**
     * Display order tracking search and details page.
     */
    public function __invoke(Request $request): InertiaResponse
    {
        $orderNumber = trim((string) $request->query('order_number', ''));
        $searchQuery = trim((string) $request->query('search', ''));

        $query = $orderNumber !== '' ? $orderNumber : $searchQuery;

        $order = null;
        $errorMessage = null;

        $userRecentOrders = collect();

        if (Auth::check()) {
            $userRecentOrders = Order::where('user_id', Auth::id())
                ->latest()
                ->take(5)
                ->get();
        }

        if ($query !== '') {
            $order = Order::with(['items.product', 'user'])
                ->where('order_number', $query)
                ->orWhere(function ($q) use ($query) {
                    $q->where('phone', $query)
                        ->orWhere('email', $query);
                })
                ->latest()
                ->first();

            if (! $order) {
                $errorMessage = "No order found matching '{$query}'. Please check your Order Number or Phone/Email.";
            }
        } elseif (Auth::check() && $userRecentOrders->isNotEmpty()) {
            // Default to latest order for authenticated users
            $latestOrder = $userRecentOrders->first();
            $order = Order::with(['items.product', 'user'])->find($latestOrder->id);
        }

        return Inertia::render('shop/TrackOrder', [
            'order' => $order ? [
                'id' => $order->id,
                'order_number' => $order->order_number,
                'customer_name' => $order->customer_name,
                'phone' => $order->phone,
                'email' => $order->email,
                'district' => $order->district,
                'area' => $order->area,
                'address' => $order->address,
                'notes' => $order->notes,
                'subtotal' => (float) $order->subtotal,
                'delivery_charge' => (float) $order->delivery_charge,
                'discount_amount' => (float) $order->discount_amount,
                'total' => (float) $order->total,
                'payment_method' => $order->payment_method === 'cod' ? 'Cash on Delivery' : 'Stripe (Paid)',
                'payment_status' => ucfirst($order->payment_status),
                'status' => ucfirst($order->status),
                'status_raw' => strtolower($order->status),
                'placed_at' => $order->placed_at ? $order->placed_at->format('M d, Y, h:i A') : $order->created_at->format('M d, Y, h:i A'),
                'items' => $order->items->map(function ($item) {
                    $product = $item->product;
                    $image = null;
                    if ($product) {
                        $image = $product->primary_image ?? ($product->images[0] ?? null);
                    }

                    return [
                        'id' => $item->id,
                        'product_name' => $item->product_name ?? ($product->name ?? 'Product'),
                        'product_slug' => $product->slug ?? null,
                        'image' => $image,
                        'unit_price' => (float) $item->unit_price,
                        'quantity' => (int) $item->quantity,
                        'line_total' => (float) $item->line_total,
                    ];
                }),
            ] : null,
            'userRecentOrders' => $userRecentOrders->map(fn ($o) => [
                'id' => $o->id,
                'order_number' => $o->order_number,
                'total' => (float) $o->total,
                'status' => ucfirst($o->status),
                'placed_at' => $o->placed_at ? $o->placed_at->format('M d, Y') : $o->created_at->format('M d, Y'),
            ]),
            'searchedQuery' => $query,
            'errorMessage' => $errorMessage,
        ]);
    }
}
