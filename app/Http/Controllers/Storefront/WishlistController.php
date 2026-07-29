<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class WishlistController extends Controller
{
    /**
     * Display user's wishlist page with database products if authenticated.
     */
    public function index(Request $request): Response
    {
        $user = $request->user();
        $wishlistProducts = [];

        if ($user) {
            $wishlistProducts = $user->wishlists()
                ->with(['product.category', 'product.images', 'product.reviews'])
                ->get()
                ->pluck('product')
                ->filter()
                ->map(function ($product) {
                    $primaryImage = $product->images->where('is_primary', true)->first() ?? $product->images->first();
                    $imagePath = $primaryImage ? $primaryImage->image_path : '';
                    $avgRating = $product->reviews->avg('rating') ? round((float) $product->reviews->avg('rating'), 1) : 4.5;
                    $reviewsCount = $product->reviews->count();

                    return [
                        'id' => $product->id,
                        'name' => $product->name,
                        'slug' => $product->slug,
                        'price' => (float) $product->price,
                        'oldPrice' => $product->compare_at_price ? (float) $product->compare_at_price : null,
                        'img' => $imagePath,
                        'rating' => $avgRating,
                        'reviews' => $reviewsCount,
                        'inStock' => $product->stock_status === 'in_stock',
                        'category' => $product->category ? $product->category->name : '',
                        'tag' => $product->is_best_seller ? 'Best Seller' : ($product->is_featured ? 'Featured' : ''),
                    ];
                })->values()->all();
        }

        return Inertia::render('shop/Wishlist', [
            'products' => $wishlistProducts,
        ]);
    }

    /**
     * Toggle a product in the user's wishlist.
     */
    public function toggle(Request $request, Product $product): JsonResponse|RedirectResponse
    {
        $user = $request->user();

        if (! $user) {
            if ($request->wantsJson()) {
                return response()->json(['message' => 'Unauthenticated.'], 401);
            }

            return back()->with('error', 'Please login to save wishlist items.');
        }

        $existing = $user->wishlists()->where('product_id', $product->id)->first();

        if ($existing) {
            $existing->delete();
            $status = 'removed';
            $message = "Removed {$product->name} from wishlist";
        } else {
            $user->wishlists()->create(['product_id' => $product->id]);
            $status = 'added';
            $message = "Added {$product->name} to wishlist";
        }

        $userWishlistIds = $user->wishlists()->pluck('product_id')->all();

        if ($request->wantsJson()) {
            return response()->json([
                'status' => $status,
                'message' => $message,
                'wishlist_ids' => $userWishlistIds,
            ]);
        }

        return back()->with('success', $message);
    }
}
