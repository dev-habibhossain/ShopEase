<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CustomerManagementController extends Controller
{
    public function index(Request $request): Response
    {
        $query = User::withCount('orders')
            ->withSum(['orders' => function ($q) {
                $q->where('payment_status', 'paid');
            }], 'total');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%'.$request->search.'%')
                    ->orWhere('email', 'like', '%'.$request->search.'%');
            });
        }

        $customers = $query->latest()->paginate(10)->withQueryString();

        return Inertia::render('Dashboard/Customers', [
            'customers' => $customers,
            'filters' => $request->only(['search']),
        ]);
    }
}
