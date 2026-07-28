<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ReviewManagementController extends Controller
{
    public function index(Request $request): Response
    {
        $query = Review::with(['user', 'product']);

        if ($request->filled('search')) {
            $query->whereHas('product', function ($q) use ($request) {
                $q->where('name', 'like', '%'.$request->search.'%');
            })->orWhereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%'.$request->search.'%');
            });
        }

        if ($request->filled('status')) {
            $isApproved = $request->status === 'approved';
            $query->where('is_approved', $isApproved);
        }

        $reviews = $query->latest()->paginate(10)->withQueryString();

        return Inertia::render('Dashboard/Reviews', [
            'reviews' => $reviews,
            'filters' => $request->only(['search', 'status']),
        ]);
    }

    public function update(Request $request, Review $review): RedirectResponse
    {
        $validated = $request->validate([
            'is_approved' => 'required|boolean',
        ]);

        $review->update([
            'is_approved' => $validated['is_approved'],
            'approved_at' => $validated['is_approved'] ? now() : null,
        ]);

        return redirect()->back()->with('success', 'Review approval status updated.');
    }

    public function destroy(Review $review): RedirectResponse
    {
        $review->delete();

        return redirect()->back()->with('success', 'Review deleted successfully.');
    }
}
