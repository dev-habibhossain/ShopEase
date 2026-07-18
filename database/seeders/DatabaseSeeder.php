<?php

namespace Database\Seeders;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\HeroSlide;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatusHistory;
use App\Models\Payment;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Review;
use App\Models\User;
use App\Models\Wishlist;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Users
        $admin = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@shopease.com',
            'password' => Hash::make('password'),
        ]);

        $customer = User::factory()->create([
            'name' => 'Test Customer',
            'email' => 'customer@shopease.com',
            'password' => Hash::make('password'),
        ]);

        // Create 10 other random users
        $users = User::factory(10)->create();
        $allUsers = collect([$customer])->concat($users);

        // 2. Seed Hero Slides
        $slides = [
            [
                'image' => 'https://images.unsplash.com/photo-1441986300917-64674bd600d8?auto=format&fit=crop&w=1920&q=70',
                'link' => '/shop',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'image' => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?auto=format&fit=crop&w=1920&q=70',
                'link' => '/product-details/wireless-noise-cancelling-headphones',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'image' => 'https://images.unsplash.com/photo-1556905055-8f358a7a47b2?auto=format&fit=crop&w=1920&q=70',
                'link' => '/shop',
                'sort_order' => 3,
                'is_active' => true,
            ],
        ];

        foreach ($slides as $slide) {
            HeroSlide::create($slide);
        }

        // 3. Seed Categories
        $categoriesData = [
            [
                'name' => 'Electronics',
                'slug' => 'electronics',
                'image' => 'https://images.unsplash.com/photo-1498049794561-7780e7231661?auto=format&fit=crop&w=400&q=70',
                'description' => 'Find the latest and greatest in high-tech gadgets and accessories.',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Fashion',
                'slug' => 'fashion',
                'image' => 'https://images.unsplash.com/photo-1445205170230-053b83016050?auto=format&fit=crop&w=400&q=70',
                'description' => 'Stay on trend with our modern, stylish apparel and accessories.',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'Home & Living',
                'slug' => 'home-living',
                'image' => 'https://images.unsplash.com/photo-1556228453-efd6c1ff04f6?auto=format&fit=crop&w=400&q=70',
                'description' => 'Transform your space with premium furniture, decor, and tools.',
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'Beauty',
                'slug' => 'beauty',
                'image' => 'https://images.unsplash.com/photo-1596462502278-27bfdc403348?auto=format&fit=crop&w=400&q=70',
                'description' => 'Self-care and cosmetics for a fresh and glowing look.',
                'sort_order' => 4,
                'is_active' => true,
            ],
            [
                'name' => 'Sports',
                'slug' => 'sports',
                'image' => 'https://images.unsplash.com/photo-1517649763962-0c623066013b?auto=format&fit=crop&w=400&q=70',
                'description' => 'Gear and equipment for active, healthy living.',
                'sort_order' => 5,
                'is_active' => true,
            ],
            [
                'name' => 'Books',
                'slug' => 'books',
                'image' => 'https://images.unsplash.com/photo-1512820790803-83ca734da794?auto=format&fit=crop&w=400&q=70',
                'description' => 'Knowledge, stories, and educational resources.',
                'sort_order' => 6,
                'is_active' => true,
            ],
        ];

        $categories = [];
        foreach ($categoriesData as $catData) {
            $categories[$catData['name']] = Category::create($catData);
        }

        // 4. Seed Products matching Vue frontend catalogue
        $productsData = [
            [
                'id' => 14,
                'category_name' => 'Electronics',
                'name' => 'Wireless Noise-Cancelling Headphones',
                'slug' => 'wireless-noise-cancelling-headphones',
                'short_description' => 'Premium wireless noise-cancelling headphones with up to 40 hours of battery life.',
                'description' => 'The ShopEase Wireless Noise-Cancelling Headphones are engineered for listeners who refuse to compromise. Advanced hybrid active noise cancellation silences the world around you, while custom 40mm drivers deliver deep, balanced sound across every genre. Designed for all-day comfort, the breathable memory-foam ear cushions and lightweight headband let you wear them from your morning commute to late-night sessions.',
                'price' => 6499.00,
                'compare_at_price' => 8999.00,
                'stock_status' => 'in_stock',
                'is_best_seller' => true,
                'is_featured' => true,
                'is_active' => true,
                'sold_count' => 980,
                'images' => [
                    'photo-1505740420928-5e560c06d30e',
                    'photo-1484704849700-f032a568e944',
                    'photo-1583394838336-acd977736f90',
                    'photo-1546435770-a3e426bf472b',
                    'photo-1577174881658-0f30ed549adc',
                ],
            ],
            [
                'id' => 13,
                'category_name' => 'Electronics',
                'name' => 'Smart Fitness Watch Series 6',
                'slug' => 'smart-fitness-watch-series-6',
                'short_description' => 'Stay on top of your health and fitness with this premium smartwatch.',
                'description' => 'Smart Fitness Watch Series 6 tracking heart rate, steps, sleep, oxygen levels, and workouts. Sleek AMOLED display with custom watch faces and up to 7 days of battery life.',
                'price' => 4299.00,
                'compare_at_price' => null,
                'stock_status' => 'in_stock',
                'is_best_seller' => true,
                'is_featured' => false,
                'is_active' => true,
                'sold_count' => 870,
                'images' => ['photo-1523275335684-37898b6baf30'],
            ],
            [
                'id' => 12,
                'category_name' => 'Fashion',
                'name' => 'Classic Leather Sneakers',
                'slug' => 'classic-leather-sneakers',
                'short_description' => 'Minimalist leather sneakers designed for daily comfort and style.',
                'description' => 'Crafted with premium full-grain leather, these classic sneakers are perfect for casual wear. Stitched rubber cupsole offers great durability and grip.',
                'price' => 2999.00,
                'compare_at_price' => 3999.00,
                'stock_status' => 'in_stock',
                'is_best_seller' => true,
                'is_featured' => false,
                'is_active' => true,
                'sold_count' => 760,
                'images' => ['photo-1542291026-7eec264c27ff'],
            ],
            [
                'id' => 11,
                'category_name' => 'Fashion',
                'name' => 'Premium Sunglasses UV400',
                'slug' => 'premium-sunglasses-uv400',
                'short_description' => 'Modern stylish sunglasses offering 100% UV400 protection.',
                'description' => 'Protect your eyes in style with these premium sunglasses. Features a durable lightweight frame, impact-resistant lenses, and scratch-resistant coating.',
                'price' => 1599.00,
                'compare_at_price' => null,
                'stock_status' => 'stock_out',
                'is_best_seller' => true,
                'is_featured' => false,
                'is_active' => true,
                'sold_count' => 540,
                'images' => ['photo-1572635196237-14b3f281503f'],
            ],
            [
                'id' => 10,
                'category_name' => 'Fashion',
                'name' => 'Minimalist Backpack 20L',
                'slug' => 'minimalist-backpack-20l',
                'short_description' => 'Waterproof travel and school backpack with a 15.6-inch laptop compartment.',
                'description' => 'This 20L minimalist backpack is perfect for work, school, or travel. Made from durable waterproof polyester with multiple internal organization compartments.',
                'price' => 2499.00,
                'compare_at_price' => null,
                'stock_status' => 'in_stock',
                'is_best_seller' => false,
                'is_featured' => true,
                'is_active' => true,
                'sold_count' => 320,
                'images' => ['photo-1553062407-98eeb64c6a62'],
            ],
            [
                'id' => 9,
                'category_name' => 'Home & Living',
                'name' => 'Ceramic Pour-Over Coffee Set',
                'slug' => 'ceramic-pour-over-coffee-set',
                'short_description' => 'Handcrafted ceramic coffee dripper and server for manual brewing.',
                'description' => 'Elevate your morning coffee routine. Features a double-walled ceramic dripper for heat retention and a heat-resistant glass carafe server.',
                'price' => 1899.00,
                'compare_at_price' => null,
                'stock_status' => 'in_stock',
                'is_best_seller' => false,
                'is_featured' => true,
                'is_active' => true,
                'sold_count' => 210,
                'images' => ['photo-1495774856032-8b90bbb32b32'],
            ],
            [
                'id' => 8,
                'category_name' => 'Electronics',
                'name' => 'Mechanical Keyboard RGB',
                'slug' => 'mechanical-keyboard-rgb',
                'short_description' => 'Tactile, responsive mechanical keyboard with customizable RGB backlighting.',
                'description' => 'Full-sized mechanical keyboard featuring blue switches for a satisfying click, double-shot injection keycaps, and multiple dynamic RGB lighting profiles.',
                'price' => 5499.00,
                'compare_at_price' => 6299.00,
                'stock_status' => 'in_stock',
                'is_best_seller' => false,
                'is_featured' => false,
                'is_active' => true,
                'sold_count' => 430,
                'images' => ['photo-1587829741301-dc798b83add3'],
            ],
            [
                'id' => 7,
                'category_name' => 'Fashion',
                'name' => 'Cotton Oversized T-Shirt',
                'slug' => 'cotton-oversized-t-shirt',
                'short_description' => 'Super soft, 100% organic cotton oversized streetwear t-shirt.',
                'description' => 'Heavyweight organic cotton t-shirt with a relaxed boxy fit and durable ribbed collar. Designed for everyday streetwear comfort.',
                'price' => 899.00,
                'compare_at_price' => null,
                'stock_status' => 'stock_out',
                'is_best_seller' => false,
                'is_featured' => false,
                'is_active' => true,
                'sold_count' => 150,
                'images' => ['photo-1521572163474-6864f9cf17ab'],
            ],
            [
                'id' => 6,
                'category_name' => 'Electronics',
                'name' => 'Portable Bluetooth Speaker',
                'slug' => 'portable-bluetooth-speaker',
                'short_description' => 'IPX7 waterproof portable speaker with rich bass and crystal clear sound.',
                'description' => 'Take your music anywhere. With 20W dual drivers, passive bass radiators, and up to 24 hours of playback time, this speaker is built for parties and outdoors.',
                'price' => 2199.00,
                'compare_at_price' => 2799.00,
                'stock_status' => 'in_stock',
                'is_best_seller' => false,
                'is_featured' => false,
                'is_active' => true,
                'sold_count' => 620,
                'images' => ['photo-1608043152269-423dbba4e7e1'],
            ],
            [
                'id' => 5,
                'category_name' => 'Electronics',
                'name' => 'Wireless Earbuds Pro',
                'slug' => 'wireless-earbuds-pro',
                'short_description' => 'True wireless earbuds with touch control and smart ANC charging case.',
                'description' => 'Experience true audio freedom. Designed with Bluetooth 5.2, active environment noise isolation, and smart fast charging supporting up to 30 hours total battery life.',
                'price' => 3499.00,
                'compare_at_price' => null,
                'stock_status' => 'in_stock',
                'is_best_seller' => true,
                'is_featured' => false,
                'is_active' => true,
                'sold_count' => 810,
                'images' => ['photo-1590658268037-6bf12165a8df'],
            ],
            [
                'id' => 4,
                'category_name' => 'Home & Living',
                'name' => 'Scented Soy Candle Set',
                'slug' => 'scented-soy-candle-set',
                'short_description' => 'A set of 4 aromatherapy soy candles with calming scents.',
                'description' => 'Made from 100% natural eco-friendly soy wax and premium therapeutic essential oils. Scents include Lavender, Rosemary, Vanilla, and Jasmine.',
                'price' => 749.00,
                'compare_at_price' => null,
                'stock_status' => 'in_stock',
                'is_best_seller' => false,
                'is_featured' => false,
                'is_active' => true,
                'sold_count' => 280,
                'images' => ['photo-1602874801007-bd458bb1b8b6'],
            ],
            [
                'id' => 3,
                'category_name' => 'Beauty',
                'name' => 'Matte Lipstick Collection',
                'slug' => 'matte-lipstick-collection',
                'short_description' => 'Long-lasting, moisturizing matte lipsticks in 6 stunning shades.',
                'description' => 'Stunning matte formulation that keeps lips hydrated and rich with pigment all day long. Transfer-proof and smudge-free formula.',
                'price' => 1299.00,
                'compare_at_price' => 1699.00,
                'stock_status' => 'in_stock',
                'is_best_seller' => false,
                'is_featured' => true,
                'is_active' => true,
                'sold_count' => 360,
                'images' => ['photo-1586495777744-4413f21062fa'],
            ],
            [
                'id' => 2,
                'category_name' => 'Sports',
                'name' => 'Yoga Mat Non-Slip',
                'slug' => 'yoga-mat-non-slip',
                'short_description' => 'Eco-friendly TPE non-slip fitness and yoga mat with carry strap.',
                'description' => 'Extra-thick 6mm TPE mat offering optimal cushioning and joint support. Textured non-slip surface provides great grip for complex yoga poses.',
                'price' => 1450.00,
                'compare_at_price' => null,
                'stock_status' => 'in_stock',
                'is_best_seller' => false,
                'is_featured' => false,
                'is_active' => true,
                'sold_count' => 290,
                'images' => ['photo-1601925260368-ae2f83cf8b7f'],
            ],
            [
                'id' => 1,
                'category_name' => 'Sports',
                'name' => 'Stainless Steel Water Bottle',
                'slug' => 'stainless-steel-water-bottle',
                'short_description' => 'Double-walled vacuum insulated water bottle (750ml).',
                'description' => 'Keeps beverages cold for up to 24 hours or hot for up to 12 hours. Made with food-grade 18/8 stainless steel and BPA-free leak-proof lid.',
                'price' => 999.00,
                'compare_at_price' => null,
                'stock_status' => 'stock_out',
                'is_best_seller' => false,
                'is_featured' => false,
                'is_active' => true,
                'sold_count' => 470,
                'images' => ['photo-1602143407151-7111542de6e8'],
            ],
        ];

        $seededProducts = [];

        foreach ($productsData as $pData) {
            $cat = $categories[$pData['category_name']];

            $product = Product::create([
                'id' => $pData['id'],
                'category_id' => $cat->id,
                'name' => $pData['name'],
                'slug' => $pData['slug'],
                'short_description' => $pData['short_description'],
                'description' => $pData['description'],
                'price' => $pData['price'],
                'compare_at_price' => $pData['compare_at_price'],
                'stock_status' => $pData['stock_status'],
                'is_best_seller' => $pData['is_best_seller'],
                'is_featured' => $pData['is_featured'],
                'is_active' => $pData['is_active'],
                'sold_count' => $pData['sold_count'],
            ]);

            $seededProducts[] = $product;

            // Seed associated images
            foreach ($pData['images'] as $index => $imgCode) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $imgCode,
                    'alt_text' => $product->name.' - Image '.($index + 1),
                    'is_primary' => $index === 0,
                    'sort_order' => $index,
                ]);
            }
        }

        // 5. Seed Coupons
        $coupons = [
            [
                'code' => 'SAVE10',
                'type' => 'percent',
                'value' => 10,
                'min_order_amount' => 500,
                'starts_at' => now(),
                'expires_at' => now()->addDays(30),
                'usage_limit' => 100,
                'used_count' => 5,
                'is_active' => true,
            ],
            [
                'code' => 'WELCOME500',
                'type' => 'fixed',
                'value' => 500,
                'min_order_amount' => 3000,
                'starts_at' => now(),
                'expires_at' => now()->addDays(90),
                'usage_limit' => 50,
                'used_count' => 0,
                'is_active' => true,
            ],
            [
                'code' => 'FREESHIP',
                'type' => 'fixed',
                'value' => 60,
                'min_order_amount' => 1000,
                'starts_at' => now(),
                'expires_at' => now()->addDays(15),
                'usage_limit' => 200,
                'used_count' => 12,
                'is_active' => true,
            ],
        ];

        foreach ($coupons as $coupon) {
            Coupon::create($coupon);
        }

        // 6. Seed Specific Product Reviews (for Product 14)
        $prod14 = Product::find(14);
        if ($prod14) {
            $reviews14 = [
                [
                    'name' => 'Rina A.',
                    'rating' => 5,
                    'comment' => 'Sound quality is amazing and the noise cancellation actually works on the bus. Battery lasts me almost a week. Highly recommended!',
                    'date' => now()->subDays(36),
                ],
                [
                    'name' => 'Karim H.',
                    'rating' => 5,
                    'comment' => 'Delivered fast inside Dhaka, paid cash on delivery. Build quality feels premium for the price.',
                    'date' => now()->subDays(43),
                ],
                [
                    'name' => 'Sadia R.',
                    'rating' => 4,
                    'comment' => 'Very comfortable for long use. Only wish the case was a bit smaller, but overall great value.',
                    'date' => now()->subDays(51),
                ],
            ];

            foreach ($reviews14 as $revData) {
                // Find or create a user for the reviewer
                $revUser = User::factory()->create([
                    'name' => $revData['name'],
                    'email' => Str::slug($revData['name']).'@example.com',
                ]);

                Review::create([
                    'product_id' => $prod14->id,
                    'user_id' => $revUser->id,
                    'rating' => $revData['rating'],
                    'comment' => $revData['comment'],
                    'is_approved' => true,
                    'approved_at' => $revData['date'],
                    'created_at' => $revData['date'],
                    'updated_at' => $revData['date'],
                ]);
            }
        }

        // Seed random reviews for other products
        foreach ($seededProducts as $prod) {
            if ($prod->id === 14) {
                continue;
            }
            // Create 1-3 reviews
            $reviewerUsers = $users->random(rand(1, 3));
            foreach ($reviewerUsers as $revUser) {
                Review::factory()->create([
                    'product_id' => $prod->id,
                    'user_id' => $revUser->id,
                    'order_id' => null,
                ]);
            }
        }

        // 7. Seed Wishlists
        foreach ($allUsers as $usr) {
            // Add 1-3 random products to wishlist
            $wishProducts = collect($seededProducts)->random(rand(1, 3));
            foreach ($wishProducts as $p) {
                Wishlist::create([
                    'user_id' => $usr->id,
                    'product_id' => $p->id,
                ]);
            }
        }

        // 8. Seed Carts & Cart Items
        foreach ($allUsers as $usr) {
            $cart = Cart::create([
                'user_id' => $usr->id,
            ]);

            // Add 1-2 random products to cart
            $cartProducts = collect($seededProducts)->random(rand(1, 2));
            foreach ($cartProducts as $p) {
                CartItem::create([
                    'cart_id' => $cart->id,
                    'product_id' => $p->id,
                    'quantity' => rand(1, 3),
                ]);
            }
        }

        // 9. Seed Orders & Order Items
        foreach ($allUsers as $usr) {
            // Create 1-2 orders per user
            for ($o = 0; $o < rand(1, 2); $o++) {
                $orderedProducts = collect($seededProducts)->random(rand(1, 3));
                $subtotal = 0;

                foreach ($orderedProducts as $p) {
                    $subtotal += $p->price * rand(1, 2);
                }

                $discount = rand(0, 1) ? 100 : 0;
                $delivery = 60;
                $total = $subtotal - $discount + $delivery;

                $order = Order::create([
                    'order_number' => 'ORD-'.strtoupper(Str::random(10)),
                    'user_id' => $usr->id,
                    'customer_name' => $usr->name,
                    'phone' => '017'.rand(10000000, 99999999),
                    'email' => $usr->email,
                    'district' => 'Dhaka',
                    'area' => 'Mirpur',
                    'address' => 'House 12, Road 5, Block B',
                    'notes' => 'Deliver in afternoon',
                    'coupon_id' => null,
                    'discount_amount' => $discount,
                    'subtotal' => $subtotal,
                    'delivery_charge' => $delivery,
                    'total' => $total,
                    'payment_method' => 'cod',
                    'payment_status' => 'pending',
                    'status' => 'pending',
                    'placed_at' => now()->subDays(rand(1, 15)),
                ]);

                // Create order items
                foreach ($orderedProducts as $p) {
                    $qty = rand(1, 2);
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $p->id,
                        'product_name' => $p->name,
                        'unit_price' => $p->price,
                        'quantity' => $qty,
                        'line_total' => $p->price * $qty,
                    ]);
                }

                // Create payment
                Payment::create([
                    'order_id' => $order->id,
                    'gateway' => 'cod',
                    'transaction_id' => null,
                    'val_id' => null,
                    'amount' => $total,
                    'currency' => 'BDT',
                    'status' => 'initiated',
                ]);

                // Create status history
                OrderStatusHistory::create([
                    'order_id' => $order->id,
                    'status' => 'pending',
                    'note' => 'Order placed successfully.',
                    'changed_by' => $admin->id,
                ]);
            }
        }
    }
}
