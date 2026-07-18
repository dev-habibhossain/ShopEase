<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\HeroSlide;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): Response
    {
        $heroSlides = HeroSlide::where('is_active', true)
            ->orderBy('sort_order')
            ->get(['image', 'link'])
            ->map(fn (HeroSlide $slide) => [
                'image' => $slide->image,
                'link' => $slide->link,
            ]);

        $categories = Category::where('is_active', true)
            ->orderBy('sort_order')
            ->get(['id', 'name', 'slug', 'image'])
            ->map(fn (Category $cat) => [
                'name' => $cat->name,
                'slug' => $cat->slug,
                'image' => $cat->image,
            ]);

        $bestSelling = Product::with(['images' => fn ($q) => $q->where('is_primary', true)])
            ->withCount('reviews')
            ->withAvg('reviews', 'rating')
            ->where('is_active', true)
            ->where('is_best_seller', true)
            ->orderByDesc('sold_count')
            ->limit(8)
            ->get()
            ->map(fn (Product $p) => $this->mapProduct($p, 'Best Seller'));

        $newCollection = Product::with(['images' => fn ($q) => $q->where('is_primary', true)])
            ->withCount('reviews')
            ->withAvg('reviews', 'rating')
            ->where('is_active', true)
            ->where('is_featured', true)
            ->orderByDesc('created_at')
            ->limit(8)
            ->get()
            ->map(fn (Product $p) => $this->mapProduct($p, 'New'));

        return Inertia::render('shop/Home', [
            'heroSlides' => $heroSlides,
            'categories' => $categories,
            'bestSelling' => $bestSelling,
            'newCollection' => $newCollection,
        ]);
    }

    /**
     * Map a Product model to the shape expected by the Vue page.
     *
     * @return array{name:string,slug:string,price:float,oldPrice:float|null,img:string,rating:float,reviews:int,inStock:bool,tag:string}
     */
    private function mapProduct(Product $product, string $tag): array
    {
        $primaryImage = $product->images->first();

        return [
            'id' => $product->id,
            'name' => $product->name,
            'slug' => $product->slug,
            'price' => (float) $product->price,
            'oldPrice' => $product->compare_at_price !== null ? (float) $product->compare_at_price : null,
            'img' => $primaryImage?->image_path ?? '',
            'rating' => round((float) ($product->reviews_avg_rating ?? 0), 1),
            'reviews' => (int) ($product->reviews_count ?? 0),
            'inStock' => $product->stock_status === 'in_stock',
            'tag' => $tag,
        ];
    }
}
