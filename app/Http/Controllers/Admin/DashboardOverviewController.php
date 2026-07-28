<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class DashboardOverviewController extends Controller
{
    public function __invoke(Request $request): Response
    {
        $totalRevenue = Order::where('payment_status', 'paid')->sum('total');
        $totalOrders = Order::count();
        $activeProductsCount = Product::where('is_active', true)->count();
        $totalCustomers = User::count();

        $recentOrders = Order::latest()
            ->take(5)
            ->get(['id', 'order_number', 'customer_name', 'district', 'total', 'payment_method', 'payment_status', 'status', 'created_at']);

        $topProducts = Product::with('category')
            ->where('is_active', true)
            ->orderByDesc('sold_count')
            ->take(4)
            ->get(['id', 'name', 'slug', 'price', 'sold_count', 'category_id']);

        $lowStockItems = Product::where('stock_status', 'stock_out')
            ->take(4)
            ->get(['id', 'name', 'stock_status']);

        $driver = DB::connection()->getDriverName();

        if ($driver === 'sqlite') {
            $monthlySales = DB::table('orders')
                ->where('payment_status', 'paid')
                ->selectRaw("strftime('%m', created_at) as month_num, strftime('%m', created_at) as month, SUM(total) as revenue")
                ->groupBy('month_num', 'month')
                ->orderBy('month_num')
                ->get();
        } else {
            $monthlySales = DB::table('orders')
                ->where('payment_status', 'paid')
                ->selectRaw('MONTH(created_at) as month_num, DATE_FORMAT(created_at, "%b") as month, SUM(total) as revenue')
                ->groupBy('month_num', 'month')
                ->orderBy('month_num')
                ->get();
        }

        return Inertia::render('Dashboard/Overview', [
            'stats' => [
                'total_revenue' => number_format($totalRevenue, 2),
                'total_orders' => number_format($totalOrders),
                'active_products' => number_format($activeProductsCount),
                'total_customers' => number_format($totalCustomers),
            ],
            'recentOrders' => $recentOrders,
            'topProducts' => $topProducts,
            'lowStockItems' => $lowStockItems,
            'monthlySales' => $monthlySales,
        ]);
    }
}
