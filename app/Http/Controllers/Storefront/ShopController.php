<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ShopController extends Controller
{
    private const PAGE_SIZE = 12;

    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): Response
    {
        // --- Active filter inputs ---
        $search = $request->string('search')->trim()->toString();
        $categorySlug = $request->string('category')->trim()->toString();
        $priceRange = $request->string('price', 'all')->trim()->toString();
        $stockOnly = $request->boolean('stock');
        $sortBy = $request->string('sort', 'newest')->trim()->toString();

        // --- Categories for the sidebar ---
        $categories = Category::where('is_active', true)
            ->orderBy('sort_order')
            ->get(['id', 'name', 'slug'])
            ->map(fn (Category $c) => ['name' => $c->name, 'slug' => $c->slug]);

        // --- Build product query ---
        $query = Product::with(['images' => fn ($q) => $q->where('is_primary', true)])
            ->withCount(['reviews' => fn ($q) => $q->where('is_approved', true)])
            ->withAvg(['reviews' => fn ($q) => $q->where('is_approved', true)], 'rating')
            ->where('is_active', true);

        // Search
        if ($search !== '') {
            $query->where('name', 'like', "%{$search}%");
        }

        // Category filter (supports multiple comma-separated category slugs)
        $categorySlugs = array_values(array_filter(explode(',', $categorySlug), fn ($s) => $s !== '' && $s !== 'all'));
        if (! empty($categorySlugs)) {
            $query->whereHas('category', fn ($q) => $q->whereIn('slug', $categorySlugs));
        }

        // Price range filter
        [$priceMin, $priceMax] = match ($priceRange) {
            '0-1000' => [0, 1000],
            '1000-3000' => [1000, 3000],
            '3000-6000' => [3000, 6000],
            '6000+' => [6000, null],
            default => [null, null],
        };

        if ($priceMin !== null) {
            $query->where('price', '>=', $priceMin);
        }

        if ($priceMax !== null) {
            $query->where('price', '<=', $priceMax);
        }

        // Stock filter
        if ($stockOnly) {
            $query->where('stock_status', 'in_stock');
        }

        // Sort
        match ($sortBy) {
            'price-asc' => $query->orderBy('price'),
            'price-desc' => $query->orderByDesc('price'),
            'best' => $query->orderByDesc('sold_count'),
            'rating' => $query->orderByDesc('reviews_avg_rating'),
            default => $query->orderByDesc('created_at'), // newest
        };

        $paginator = $query->paginate(self::PAGE_SIZE)->withQueryString();

        $products = $paginator->through(fn (Product $p) => [
            'id' => $p->id,
            'name' => $p->name,
            'slug' => $p->slug,
            'price' => (float) $p->price,
            'oldPrice' => $p->compare_at_price !== null ? (float) $p->compare_at_price : null,
            'img' => $p->images->first()?->image_path ?? '',
            'rating' => round((float) ($p->reviews_avg_rating ?? 0), 1),
            'reviews' => (int) ($p->reviews_count ?? 0),
            'inStock' => $p->stock_status === 'in_stock',
            'tag' => $p->is_best_seller ? 'Best Seller' : ($p->is_featured ? 'New' : ''),
            'soldCount' => $p->sold_count,
        ]);

        return Inertia::render('shop/Shop', [
            'products' => $products,
            'categories' => $categories,
            'filters' => [
                'search' => $search,
                'category' => implode(',', $categorySlugs),
                'price' => $priceRange,
                'stock' => $stockOnly,
                'sort' => $sortBy,
            ],
        ]);
    }
}
