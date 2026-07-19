<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class ProductDetailsController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, string $slug): Response
    {
        /** @var Product|null $product */
        $product = Product::with([
            'images' => fn ($q) => $q->orderBy('sort_order'),
            'reviews' => fn ($q) => $q->where('is_approved', true)->with('user:id,name')->latest(),
            'category:id,name,slug',
        ])
            ->withCount(['reviews' => fn ($q) => $q->where('is_approved', true)])
            ->withAvg(['reviews' => fn ($q) => $q->where('is_approved', true)], 'rating')
            ->where('is_active', true)
            ->where('slug', $slug)
            ->first();

        if (! $product) {
            abort(HttpResponse::HTTP_NOT_FOUND);
        }

        // Build rating breakdown (percentage per star 1–5)
        $totalReviews = $product->reviews_count ?: 0;
        $breakdown = collect(range(5, 1))->map(function (int $star) use ($product, $totalReviews) {
            $count = $product->reviews->where('rating', $star)->count();

            return [
                's' => $star,
                'p' => $totalReviews > 0 ? (int) round(($count / $totalReviews) * 100) : 0,
            ];
        })->values();

        // Shape reviews for the frontend
        $reviews = $product->reviews->map(fn ($r) => [
            'name' => $r->user?->name ?? 'Anonymous',
            'rating' => $r->rating,
            'date' => $r->created_at->format('j M Y'),
            'verified' => $r->order_id !== null,
            'text' => $r->comment ?? '',
        ]);

        // Shape images
        $images = $product->images->map(fn ($img) => [
            'path' => $img->image_path,
            'alt' => $img->alt_text ?? $product->name,
            'isPrimary' => $img->is_primary,
        ])->values();

        // Related products from same category (excluding current)
        $relatedProducts = Product::with(['images' => fn ($q) => $q->where('is_primary', true)])
            ->withCount(['reviews' => fn ($q) => $q->where('is_approved', true)])
            ->withAvg(['reviews' => fn ($q) => $q->where('is_approved', true)], 'rating')
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->inRandomOrder()
            ->limit(4)
            ->get()
            ->map(fn (Product $p) => [
                'id' => $p->id,
                'name' => $p->name,
                'slug' => $p->slug,
                'price' => (float) $p->price,
                'oldPrice' => $p->compare_at_price !== null ? (float) $p->compare_at_price : null,
                'img' => $p->images->first()?->image_path ?? '',
                'rating' => round((float) ($p->reviews_avg_rating ?? 0), 1),
                'reviews' => (int) ($p->reviews_count ?? 0),
                'inStock' => $p->stock_status === 'in_stock',
                'tag' => $p->is_best_seller ? 'Best Seller' : ($p->is_featured ? 'Featured' : null),
            ]);

        return Inertia::render('shop/ProductDetails', [
            'product' => [
                'id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'shortDescription' => $product->short_description,
                'description' => $product->description,
                'price' => (float) $product->price,
                'oldPrice' => $product->compare_at_price !== null ? (float) $product->compare_at_price : null,
                'rating' => round((float) ($product->reviews_avg_rating ?? 0), 1),
                'reviewCount' => $totalReviews,
                'inStock' => $product->stock_status === 'in_stock',
                'category' => $product->category?->name ?? '',
                'categorySlug' => $product->category?->slug ?? '',
                'images' => $images,
            ],
            'reviews' => $reviews,
            'breakdown' => $breakdown,
            'relatedProducts' => $relatedProducts,
        ]);
    }
}
