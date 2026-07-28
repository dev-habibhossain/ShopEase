<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PaymentManagementController extends Controller
{
    public function index(Request $request): Response
    {
        $query = Payment::with(['order.user']);

        if ($request->filled('search')) {
            $query->where('transaction_id', 'like', '%'.$request->search.'%');
        }

        $payments = $query->latest()->paginate(10)->withQueryString();

        return Inertia::render('Dashboard/Payments', [
            'payments' => $payments,
            'filters' => $request->only(['search']),
        ]);
    }
}
