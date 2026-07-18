<script setup lang="ts">
import { ref, computed } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import { useCart } from '@/composables/useCart';
import { useWishlist } from '@/composables/useWishlist';
import { useToast } from '@/composables/useToast';

interface Product {
    id: number;
    name: string;
    slug: string;
    price: number;
    oldPrice?: number;
    img: string;
    rating: number;
    reviews: number;
    inStock: boolean;
    category: string;
    tag: string;
    sold: number;
}

// 14 products from design catalog with slugs
const products: Product[] = [
    {
        id: 14,
        name: 'Wireless Noise-Cancelling Headphones',
        slug: 'wireless-noise-cancelling-headphones',
        price: 6499,
        oldPrice: 8999,
        img: 'photo-1505740420928-5e560c06d30e',
        rating: 4.8,
        reviews: 214,
        inStock: true,
        category: 'Electronics',
        tag: 'Best Seller',
        sold: 980,
    },
    {
        id: 13,
        name: 'Smart Fitness Watch Series 6',
        slug: 'smart-fitness-watch-series-6',
        price: 4299,
        img: 'photo-1523275335684-37898b6baf30',
        rating: 4.6,
        reviews: 167,
        inStock: true,
        category: 'Electronics',
        tag: 'Best Seller',
        sold: 870,
    },
    {
        id: 12,
        name: 'Classic Leather Sneakers',
        slug: 'classic-leather-sneakers',
        price: 2999,
        oldPrice: 3999,
        img: 'photo-1542291026-7eec264c27ff',
        rating: 4.7,
        reviews: 132,
        inStock: true,
        category: 'Fashion',
        tag: '',
        sold: 760,
    },
    {
        id: 11,
        name: 'Premium Sunglasses UV400',
        slug: 'premium-sunglasses-uv400',
        price: 1599,
        img: 'photo-1572635196237-14b3f281503f',
        rating: 4.5,
        reviews: 98,
        inStock: false,
        category: 'Fashion',
        tag: '',
        sold: 540,
    },
    {
        id: 10,
        name: 'Minimalist Backpack 20L',
        slug: 'minimalist-backpack-20l',
        price: 2499,
        img: 'photo-1553062407-98eeb64c6a62',
        rating: 4.4,
        reviews: 41,
        inStock: true,
        category: 'Fashion',
        tag: 'New',
        sold: 320,
    },
    {
        id: 9,
        name: 'Ceramic Pour-Over Coffee Set',
        slug: 'ceramic-pour-over-coffee-set',
        price: 1899,
        img: 'photo-1495774856032-8b90bbb32b32',
        rating: 4.9,
        reviews: 23,
        inStock: true,
        category: 'Home & Living',
        tag: 'New',
        sold: 210,
    },
    {
        id: 8,
        name: 'Mechanical Keyboard RGB',
        slug: 'mechanical-keyboard-rgb',
        price: 5499,
        oldPrice: 6299,
        img: 'photo-1587829741301-dc798b83add3',
        rating: 4.7,
        reviews: 36,
        inStock: true,
        category: 'Electronics',
        tag: '',
        sold: 430,
    },
    {
        id: 7,
        name: 'Cotton Oversized T-Shirt',
        slug: 'cotton-oversized-t-shirt',
        price: 899,
        img: 'photo-1521572163474-6864f9cf17ab',
        rating: 4.3,
        reviews: 18,
        inStock: false,
        category: 'Fashion',
        tag: '',
        sold: 150,
    },
    {
        id: 6,
        name: 'Portable Bluetooth Speaker',
        slug: 'portable-bluetooth-speaker',
        price: 2199,
        oldPrice: 2799,
        img: 'photo-1608043152269-423dbba4e7e1',
        rating: 4.5,
        reviews: 89,
        inStock: true,
        category: 'Electronics',
        tag: '',
        sold: 620,
    },
    {
        id: 5,
        name: 'Wireless Earbuds Pro',
        slug: 'wireless-earbuds-pro',
        price: 3499,
        img: 'photo-1590658268037-6bf12165a8df',
        rating: 4.7,
        reviews: 142,
        inStock: true,
        category: 'Electronics',
        tag: 'Best Seller',
        sold: 810,
    },
    {
        id: 4,
        name: 'Scented Soy Candle Set',
        slug: 'scented-soy-candle-set',
        price: 749,
        img: 'photo-1602874801007-bd458bb1b8b6',
        rating: 4.6,
        reviews: 52,
        inStock: true,
        category: 'Home & Living',
        tag: '',
        sold: 280,
    },
    {
        id: 3,
        name: 'Matte Lipstick Collection',
        slug: 'matte-lipstick-collection',
        price: 1299,
        oldPrice: 1699,
        img: 'photo-1586495777744-4413f21062fa',
        rating: 4.4,
        reviews: 64,
        inStock: true,
        category: 'Beauty',
        tag: 'New',
        sold: 360,
    },
    {
        id: 2,
        name: 'Yoga Mat Non-Slip',
        slug: 'yoga-mat-non-slip',
        price: 1450,
        img: 'photo-1601925260368-ae2f83cf8b7f',
        rating: 4.5,
        reviews: 77,
        inStock: true,
        category: 'Sports',
        tag: '',
        sold: 290,
    },
    {
        id: 1,
        name: 'Stainless Steel Water Bottle',
        slug: 'stainless-steel-water-bottle',
        price: 999,
        img: 'photo-1602143407151-7111542de6e8',
        rating: 4.6,
        reviews: 110,
        inStock: false,
        category: 'Sports',
        tag: '',
        sold: 470,
    },
];

// Composables
const { addToCart, buyNow } = useCart();
const { toggleWish, hasWish } = useWishlist();
const { showToast } = useToast();

// State
const PAGE_SIZE = 8;
const searchQuery = ref('');
const selectedCategory = ref('all');
const selectedPriceRange = ref('all');
const inStockOnly = ref(false);
const sortBy = ref('newest');
const currentPage = ref(1);
const isMobileFiltersOpen = ref(false);

const openMobileFilters = () => {
    isMobileFiltersOpen.value = true;
    document.body.style.overflow = 'hidden';
};

const closeMobileFilters = () => {
    isMobileFiltersOpen.value = false;
    document.body.style.overflow = '';
};

// Price check helper
const inPriceRange = (price: number, range: string) => {
    if (range === 'all') return true;
    const [min, max] = range.split('-');
    if (price < Number(min)) return false;
    if (max !== '' && price > Number(max)) return false;
    return true;
};

// Filtered and sorted products list
const filteredProducts = computed(() => {
    let list = [...products];

    // Filter by Search Query
    if (searchQuery.value.trim() !== '') {
        const query = searchQuery.value.toLowerCase().trim();
        list = list.filter((p) => p.name.toLowerCase().includes(query));
    }

    // Filter by Category
    if (selectedCategory.value !== 'all') {
        list = list.filter((p) => p.category === selectedCategory.value);
    }

    // Filter by Price Range
    if (selectedPriceRange.value !== 'all') {
        list = list.filter((p) =>
            inPriceRange(p.price, selectedPriceRange.value),
        );
    }

    // Filter by Stock Availability
    if (inStockOnly.value) {
        list = list.filter((p) => p.inStock);
    }

    // Sort products
    switch (sortBy.value) {
        case 'price-asc':
            list.sort((a, b) => a.price - b.price);
            break;
        case 'price-desc':
            list.sort((a, b) => b.price - a.price);
            break;
        case 'best':
            list.sort((a, b) => b.sold - a.sold);
            break;
        default: // newest
            list.sort((a, b) => b.id - a.id);
            break;
    }

    return list;
});

// Paginated products list
const paginatedProducts = computed(() => {
    const start = (currentPage.value - 1) * PAGE_SIZE;
    return filteredProducts.value.slice(start, start + PAGE_SIZE);
});

// Total pages computation
const totalPages = computed(() => {
    return Math.max(1, Math.ceil(filteredProducts.value.length / PAGE_SIZE));
});

// Reset page on filter changes
const handleFilterChange = () => {
    currentPage.value = 1;
};

// Active chips list
const activeChips = computed(() => {
    const chips: { label: string; clear: () => void }[] = [];

    if (selectedCategory.value !== 'all') {
        chips.push({
            label: selectedCategory.value,
            clear: () => {
                selectedCategory.value = 'all';
                handleFilterChange();
            },
        });
    }

    if (selectedPriceRange.value !== 'all') {
        let label = '';
        if (selectedPriceRange.value === '0-1000') label = 'Under ৳ 1,000';
        else if (selectedPriceRange.value === '1000-3000')
            label = '৳ 1,000 – ৳ 3,000';
        else if (selectedPriceRange.value === '3000-6000')
            label = '৳ 3,000 – ৳ 6,000';
        else if (selectedPriceRange.value === '6000-') label = 'Over ৳ 6,000';

        chips.push({
            label,
            clear: () => {
                selectedPriceRange.value = 'all';
                handleFilterChange();
            },
        });
    }

    if (inStockOnly.value) {
        chips.push({
            label: 'In Stock',
            clear: () => {
                inStockOnly.value = false;
                handleFilterChange();
            },
        });
    }

    if (searchQuery.value.trim() !== '') {
        chips.push({
            label: `“${searchQuery.value}”`,
            clear: () => {
                searchQuery.value = '';
                handleFilterChange();
            },
        });
    }

    return chips;
});

// Clear all filters
const clearAllFilters = () => {
    selectedCategory.value = 'all';
    selectedPriceRange.value = 'all';
    inStockOnly.value = false;
    searchQuery.value = '';
    currentPage.value = 1;
};

// Page change handler
const changePage = (page: number) => {
    if (page < 1 || page > totalPages.value) return;
    currentPage.value = page;
    window.scrollTo({ top: 0, behavior: 'smooth' });
};

// Wishlist interaction
const handleWishlistToggle = (productName: string) => {
    const isAdded = toggleWish(productName);
    if (isAdded) {
        showToast(`Added to wishlist: ${productName} ❤️`);
    } else {
        showToast(`Removed from wishlist: ${productName}`);
    }
};

// Cart interactions
const handleAddToCart = (product: Product) => {
    addToCart(product.name, product.price, product.img);
    showToast(`Added to cart: ${product.name} 🛒`);
};

const handleBuyNow = (product: Product) => {
    buyNow(product.name, product.price, product.img);
    showToast(`Added to cart: ${product.name}. Proceeding to checkout... 💳`);
};

// Currency helper
const formatPrice = (price: number) => {
    return '৳ ' + Number(price).toLocaleString('en-BD');
};
</script>

<template>
    <Head title="Shop All Products — ShopEase" />

    <!-- Breadcrumb + page title -->
    <div class="border-b border-gray-200 bg-gray-50">
        <div
            class="mx-auto flex max-w-7xl flex-wrap items-center gap-x-3 gap-y-1 px-4 py-3 sm:px-6 lg:px-8"
        >
            <h1
                class="font-display text-lg font-extrabold tracking-tight text-gray-900 md:text-xl"
            >
                Shop All Products
            </h1>
            <nav aria-label="Breadcrumb">
                <ol
                    class="flex flex-wrap items-center gap-1.5 text-sm text-gray-500"
                >
                    <li>
                        <Link href="/" class="hover:text-primary-600"
                            >Home</Link
                        >
                    </li>
                    <li aria-hidden="true" class="text-gray-300">/</li>
                    <li aria-current="page" class="font-medium text-gray-700">
                        Shop
                    </li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="lg:grid lg:grid-cols-[260px_1fr] lg:gap-8">
            <!-- ============================ FILTERS (Sidebar Desktop) ============================ -->
            <aside
                class="hidden space-y-6 lg:block"
                aria-label="Product filters"
            >
                <!-- Category -->
                <div class="rounded-xl border border-gray-200 p-4">
                    <h3 class="mb-3 text-sm font-semibold text-gray-900">
                        Category
                    </h3>
                    <div class="space-y-1.5 text-sm">
                        <label
                            class="flex cursor-pointer items-center gap-2.5 rounded-md px-1 py-1 hover:bg-gray-50"
                        >
                            <input
                                type="radio"
                                v-model="selectedCategory"
                                value="all"
                                @change="handleFilterChange"
                                class="h-4 w-4 text-primary-600 focus:ring-primary-600"
                            />
                            <span class="text-gray-600">All Categories</span>
                        </label>
                        <label
                            class="flex cursor-pointer items-center gap-2.5 rounded-md px-1 py-1 hover:bg-gray-50"
                        >
                            <input
                                type="radio"
                                v-model="selectedCategory"
                                value="Electronics"
                                @change="handleFilterChange"
                                class="h-4 w-4 text-primary-600 focus:ring-primary-600"
                            />
                            <span class="text-gray-600">Electronics</span>
                        </label>
                        <label
                            class="flex cursor-pointer items-center gap-2.5 rounded-md px-1 py-1 hover:bg-gray-50"
                        >
                            <input
                                type="radio"
                                v-model="selectedCategory"
                                value="Fashion"
                                @change="handleFilterChange"
                                class="h-4 w-4 text-primary-600 focus:ring-primary-600"
                            />
                            <span class="text-gray-600">Fashion</span>
                        </label>
                        <label
                            class="flex cursor-pointer items-center gap-2.5 rounded-md px-1 py-1 hover:bg-gray-50"
                        >
                            <input
                                type="radio"
                                v-model="selectedCategory"
                                value="Home & Living"
                                @change="handleFilterChange"
                                class="h-4 w-4 text-primary-600 focus:ring-primary-600"
                            />
                            <span class="text-gray-600">Home &amp; Living</span>
                        </label>
                        <label
                            class="flex cursor-pointer items-center gap-2.5 rounded-md px-1 py-1 hover:bg-gray-50"
                        >
                            <input
                                type="radio"
                                v-model="selectedCategory"
                                value="Beauty"
                                @change="handleFilterChange"
                                class="h-4 w-4 text-primary-600 focus:ring-primary-600"
                            />
                            <span class="text-gray-600">Beauty</span>
                        </label>
                        <label
                            class="flex cursor-pointer items-center gap-2.5 rounded-md px-1 py-1 hover:bg-gray-50"
                        >
                            <input
                                type="radio"
                                v-model="selectedCategory"
                                value="Sports"
                                @change="handleFilterChange"
                                class="h-4 w-4 text-primary-600 focus:ring-primary-600"
                            />
                            <span class="text-gray-600">Sports</span>
                        </label>
                    </div>
                </div>

                <!-- Price -->
                <div class="rounded-xl border border-gray-200 p-4">
                    <h3 class="mb-3 text-sm font-semibold text-gray-900">
                        Price Range
                    </h3>
                    <div class="space-y-1.5 text-sm">
                        <label
                            class="flex cursor-pointer items-center gap-2.5 rounded-md px-1 py-1 hover:bg-gray-50"
                        >
                            <input
                                type="radio"
                                v-model="selectedPriceRange"
                                value="all"
                                @change="handleFilterChange"
                                class="h-4 w-4 text-primary-600 focus:ring-primary-600"
                            />
                            <span class="text-gray-600">All Prices</span>
                        </label>
                        <label
                            class="flex cursor-pointer items-center gap-2.5 rounded-md px-1 py-1 hover:bg-gray-50"
                        >
                            <input
                                type="radio"
                                v-model="selectedPriceRange"
                                value="0-1000"
                                @change="handleFilterChange"
                                class="h-4 w-4 text-primary-600 focus:ring-primary-600"
                            />
                            <span class="text-gray-600">Under ৳ 1,000</span>
                        </label>
                        <label
                            class="flex cursor-pointer items-center gap-2.5 rounded-md px-1 py-1 hover:bg-gray-50"
                        >
                            <input
                                type="radio"
                                v-model="selectedPriceRange"
                                value="1000-3000"
                                @change="handleFilterChange"
                                class="h-4 w-4 text-primary-600 focus:ring-primary-600"
                            />
                            <span class="text-gray-600">৳ 1,000 – ৳ 3,000</span>
                        </label>
                        <label
                            class="flex cursor-pointer items-center gap-2.5 rounded-md px-1 py-1 hover:bg-gray-50"
                        >
                            <input
                                type="radio"
                                v-model="selectedPriceRange"
                                value="3000-6000"
                                @change="handleFilterChange"
                                class="h-4 w-4 text-primary-600 focus:ring-primary-600"
                            />
                            <span class="text-gray-600">৳ 3,000 – ৳ 6,000</span>
                        </label>
                        <label
                            class="flex cursor-pointer items-center gap-2.5 rounded-md px-1 py-1 hover:bg-gray-50"
                        >
                            <input
                                type="radio"
                                v-model="selectedPriceRange"
                                value="6000-"
                                @change="handleFilterChange"
                                class="h-4 w-4 text-primary-600 focus:ring-primary-600"
                            />
                            <span class="text-gray-600">Over ৳ 6,000</span>
                        </label>
                    </div>
                </div>

                <!-- Availability -->
                <div class="rounded-xl border border-gray-200 p-4">
                    <h3 class="mb-3 text-sm font-semibold text-gray-900">
                        Availability
                    </h3>
                    <label
                        class="flex cursor-pointer items-center gap-2.5 rounded-md px-1 py-1 text-sm hover:bg-gray-50"
                    >
                        <input
                            type="checkbox"
                            v-model="inStockOnly"
                            @change="handleFilterChange"
                            class="h-4 w-4 rounded text-primary-600 focus:ring-primary-600"
                        />
                        <span class="text-gray-600">In Stock only</span>
                    </label>
                </div>

                <!-- Clear Filters button -->
                <button
                    v-if="activeChips.length > 0"
                    @click="clearAllFilters"
                    type="button"
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50 focus:ring-2 focus:ring-primary-600 focus:outline-none"
                >
                    Clear all filters
                </button>
            </aside>

            <!-- ============================ FILTERS DRAWER (Mobile) ============================ -->
            <Transition name="fade">
                <div
                    v-if="isMobileFiltersOpen"
                    @click="closeMobileFilters"
                    class="fixed inset-0 z-50 bg-black/40 lg:hidden"
                    aria-hidden="true"
                ></div>
            </Transition>

            <Transition name="slide-right">
                <aside
                    v-if="isMobileFiltersOpen"
                    class="fixed inset-y-0 right-0 z-50 w-80 max-w-[85%] overflow-y-auto bg-white p-5 shadow-xl lg:hidden"
                    aria-label="Product filters"
                >
                    <!-- Mobile header -->
                    <div class="mb-4 flex items-center justify-between">
                        <span class="text-lg font-bold text-gray-900"
                            >Filters</span
                        >
                        <button
                            @click="closeMobileFilters"
                            type="button"
                            aria-label="Close filters"
                            class="inline-flex h-10 w-10 items-center justify-center rounded-lg text-gray-700 hover:bg-gray-100 focus:ring-2 focus:ring-primary-600 focus:outline-none"
                        >
                            <svg
                                class="h-6 w-6"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="2"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M6 18L18 6M6 6l12 12"
                                />
                            </svg>
                        </button>
                    </div>

                    <div class="space-y-6">
                        <!-- Category -->
                        <div class="rounded-xl border border-gray-200 p-4">
                            <h3
                                class="mb-3 text-sm font-semibold text-gray-900"
                            >
                                Category
                            </h3>
                            <div class="space-y-1.5 text-sm">
                                <label
                                    class="flex cursor-pointer items-center gap-2.5 rounded-md px-1 py-1 hover:bg-gray-50"
                                >
                                    <input
                                        type="radio"
                                        v-model="selectedCategory"
                                        value="all"
                                        @change="handleFilterChange"
                                        class="h-4 w-4 text-primary-600 focus:ring-primary-600"
                                    />
                                    <span class="text-gray-600"
                                        >All Categories</span
                                    >
                                </label>
                                <label
                                    class="flex cursor-pointer items-center gap-2.5 rounded-md px-1 py-1 hover:bg-gray-50"
                                >
                                    <input
                                        type="radio"
                                        v-model="selectedCategory"
                                        value="Electronics"
                                        @change="handleFilterChange"
                                        class="h-4 w-4 text-primary-600 focus:ring-primary-600"
                                    />
                                    <span class="text-gray-600"
                                        >Electronics</span
                                    >
                                </label>
                                <label
                                    class="flex cursor-pointer items-center gap-2.5 rounded-md px-1 py-1 hover:bg-gray-50"
                                >
                                    <input
                                        type="radio"
                                        v-model="selectedCategory"
                                        value="Fashion"
                                        @change="handleFilterChange"
                                        class="h-4 w-4 text-primary-600 focus:ring-primary-600"
                                    />
                                    <span class="text-gray-600">Fashion</span>
                                </label>
                                <label
                                    class="flex cursor-pointer items-center gap-2.5 rounded-md px-1 py-1 hover:bg-gray-50"
                                >
                                    <input
                                        type="radio"
                                        v-model="selectedCategory"
                                        value="Home & Living"
                                        @change="handleFilterChange"
                                        class="h-4 w-4 text-primary-600 focus:ring-primary-600"
                                    />
                                    <span class="text-gray-600"
                                        >Home &amp; Living</span
                                    >
                                </label>
                                <label
                                    class="flex cursor-pointer items-center gap-2.5 rounded-md px-1 py-1 hover:bg-gray-50"
                                >
                                    <input
                                        type="radio"
                                        v-model="selectedCategory"
                                        value="Beauty"
                                        @change="handleFilterChange"
                                        class="h-4 w-4 text-primary-600 focus:ring-primary-600"
                                    />
                                    <span class="text-gray-600">Beauty</span>
                                </label>
                                <label
                                    class="flex cursor-pointer items-center gap-2.5 rounded-md px-1 py-1 hover:bg-gray-50"
                                >
                                    <input
                                        type="radio"
                                        v-model="selectedCategory"
                                        value="Sports"
                                        @change="handleFilterChange"
                                        class="h-4 w-4 text-primary-600 focus:ring-primary-600"
                                    />
                                    <span class="text-gray-600">Sports</span>
                                </label>
                            </div>
                        </div>

                        <!-- Price -->
                        <div class="rounded-xl border border-gray-200 p-4">
                            <h3
                                class="mb-3 text-sm font-semibold text-gray-900"
                            >
                                Price Range
                            </h3>
                            <div class="space-y-1.5 text-sm">
                                <label
                                    class="flex cursor-pointer items-center gap-2.5 rounded-md px-1 py-1 hover:bg-gray-50"
                                >
                                    <input
                                        type="radio"
                                        v-model="selectedPriceRange"
                                        value="all"
                                        @change="handleFilterChange"
                                        class="h-4 w-4 text-primary-600 focus:ring-primary-600"
                                    />
                                    <span class="text-gray-600"
                                        >All Prices</span
                                    >
                                </label>
                                <label
                                    class="flex cursor-pointer items-center gap-2.5 rounded-md px-1 py-1 hover:bg-gray-50"
                                >
                                    <input
                                        type="radio"
                                        v-model="selectedPriceRange"
                                        value="0-1000"
                                        @change="handleFilterChange"
                                        class="h-4 w-4 text-primary-600 focus:ring-primary-600"
                                    />
                                    <span class="text-gray-600"
                                        >Under ৳ 1,000</span
                                    >
                                </label>
                                <label
                                    class="flex cursor-pointer items-center gap-2.5 rounded-md px-1 py-1 hover:bg-gray-50"
                                >
                                    <input
                                        type="radio"
                                        v-model="selectedPriceRange"
                                        value="1000-3000"
                                        @change="handleFilterChange"
                                        class="h-4 w-4 text-primary-600 focus:ring-primary-600"
                                    />
                                    <span class="text-gray-600"
                                        >৳ 1,000 – ৳ 3,000</span
                                    >
                                </label>
                                <label
                                    class="flex cursor-pointer items-center gap-2.5 rounded-md px-1 py-1 hover:bg-gray-50"
                                >
                                    <input
                                        type="radio"
                                        v-model="selectedPriceRange"
                                        value="3000-6000"
                                        @change="handleFilterChange"
                                        class="h-4 w-4 text-primary-600 focus:ring-primary-600"
                                    />
                                    <span class="text-gray-600"
                                        >৳ 3,000 – ৳ 6,000</span
                                    >
                                </label>
                                <label
                                    class="flex cursor-pointer items-center gap-2.5 rounded-md px-1 py-1 hover:bg-gray-50"
                                >
                                    <input
                                        type="radio"
                                        v-model="selectedPriceRange"
                                        value="6000-"
                                        @change="handleFilterChange"
                                        class="h-4 w-4 text-primary-600 focus:ring-primary-600"
                                    />
                                    <span class="text-gray-600"
                                        >Over ৳ 6,000</span
                                    >
                                </label>
                            </div>
                        </div>

                        <!-- Availability -->
                        <div class="rounded-xl border border-gray-200 p-4">
                            <h3
                                class="mb-3 text-sm font-semibold text-gray-900"
                            >
                                Availability
                            </h3>
                            <label
                                class="flex cursor-pointer items-center gap-2.5 rounded-md px-1 py-1 text-sm hover:bg-gray-50"
                            >
                                <input
                                    type="checkbox"
                                    v-model="inStockOnly"
                                    @change="handleFilterChange"
                                    class="h-4 w-4 rounded text-primary-600 focus:ring-primary-600"
                                />
                                <span class="text-gray-600">In Stock only</span>
                            </label>
                        </div>

                        <button
                            @click="
                                clearAllFilters();
                                closeMobileFilters();
                            "
                            type="button"
                            class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50 focus:ring-2 focus:ring-primary-600 focus:outline-none"
                        >
                            Clear all filters
                        </button>

                        <button
                            @click="closeMobileFilters"
                            type="button"
                            class="w-full rounded-lg bg-primary-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-primary-700"
                        >
                            Show results
                        </button>
                    </div>
                </aside>
            </Transition>

            <!-- ============================ RESULTS ============================ -->
            <div>
                <!-- Toolbar -->
                <div class="mb-5 flex items-center justify-between gap-3">
                    <p class="text-sm text-gray-600">
                        <span class="font-semibold text-gray-900">{{
                            filteredProducts.length
                        }}</span>
                        products
                    </p>
                    <div class="flex items-center gap-2">
                        <!-- Local Search Bar -->
                        <div class="relative w-40 sm:w-60">
                            <span
                                class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-gray-400"
                            >
                                <svg
                                    class="h-4 w-4"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                    stroke-width="2"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M21 21l-4.35-4.35M11 18a7 7 0 100-14 7 7 0 000 14z"
                                    />
                                </svg>
                            </span>
                            <input
                                v-model="searchQuery"
                                @input="handleFilterChange"
                                type="search"
                                placeholder="Search products..."
                                class="w-full rounded-lg border border-gray-300 bg-white py-2 pr-3 pl-9 text-xs text-gray-900 transition placeholder:text-gray-400 focus:border-primary-600 focus:ring-1 focus:ring-primary-600 focus:outline-none sm:text-sm"
                            />
                        </div>

                        <button
                            @click="openMobileFilters"
                            type="button"
                            class="inline-flex items-center gap-2 rounded-lg border border-gray-300 px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 lg:hidden"
                        >
                            <svg
                                class="h-5 w-5"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="2"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L14 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 018 21v-7.586L3.293 6.707A1 1 0 013 6V4z"
                                />
                            </svg>
                            Filters
                        </button>
                        <div class="flex items-center gap-2">
                            <label
                                for="sortSelect"
                                class="hidden text-sm text-gray-500 sm:block"
                                >Sort:</label
                            >
                            <select
                                id="sortSelect"
                                v-model="sortBy"
                                @change="handleFilterChange"
                                class="rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 focus:border-primary-600 focus:ring-2 focus:ring-primary-600 focus:outline-none"
                            >
                                <option value="newest">Newest</option>
                                <option value="best">Best Selling</option>
                                <option value="price-asc">
                                    Price: Low to High
                                </option>
                                <option value="price-desc">
                                    Price: High to Low
                                </option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Active filter chips -->
                <div
                    v-if="activeChips.length > 0"
                    class="mb-5 flex flex-wrap gap-2"
                >
                    <button
                        v-for="(chip, index) in activeChips"
                        :key="index"
                        @click="chip.clear()"
                        type="button"
                        class="hover:bg-primary-100 inline-flex items-center gap-1.5 rounded-full bg-primary-50 px-3 py-1 text-xs font-medium text-primary-700 transition"
                    >
                        {{ chip.label }}
                        <svg
                            class="h-3.5 w-3.5"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="2.5"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M6 18L18 6M6 6l12 12"
                            />
                        </svg>
                    </button>
                </div>

                <!-- Product grid -->
                <div
                    v-if="paginatedProducts.length > 0"
                    class="grid grid-cols-2 gap-4 sm:grid-cols-3 md:gap-6 lg:grid-cols-4"
                >
                    <article
                        v-for="p in paginatedProducts"
                        :key="p.id"
                        class="group flex flex-col overflow-hidden rounded-xl border border-gray-200 bg-white transition duration-300 ease-out hover:-translate-y-1 hover:border-primary-600 hover:shadow-xl"
                    >
                        <div
                            class="relative aspect-square overflow-hidden bg-gray-100"
                        >
                            <Link
                                :href="`/product-details/${p.slug}`"
                                :aria-label="`View ${p.name}`"
                                class="block h-full w-full"
                            >
                                <img
                                    :src="`https://images.unsplash.com/${p.img}?auto=format&fit=crop&w=600&q=70`"
                                    :alt="p.name"
                                    loading="lazy"
                                    :class="[
                                        'h-full w-full object-cover transition duration-500 ease-out group-hover:scale-110',
                                        { grayscale: !p.inStock },
                                    ]"
                                />
                                <div
                                    class="pointer-events-none absolute inset-0 bg-gradient-to-t from-black/15 via-transparent to-transparent opacity-0 transition duration-300 group-hover:opacity-100"
                                ></div>
                            </Link>
                            <div
                                v-if="!p.inStock"
                                class="pointer-events-none absolute inset-0 flex items-center justify-center bg-white/30"
                            >
                                <span
                                    class="rounded-md bg-gray-900/85 px-4 py-1.5 text-xs font-semibold tracking-wide text-white uppercase shadow-md"
                                    >Stock Out</span
                                >
                            </div>
                            <div
                                class="absolute top-2 left-2 flex flex-col items-start gap-1.5"
                            >
                                <span
                                    v-if="p.inStock"
                                    class="rounded-full bg-green-50 px-2.5 py-1 text-xs font-medium text-green-700 shadow-sm"
                                    >In Stock</span
                                >
                                <span
                                    v-if="p.tag"
                                    class="rounded-full bg-accent-600 px-2.5 py-1 text-xs font-semibold text-white shadow-sm"
                                    >{{ p.tag }}</span
                                >
                            </div>
                            <button
                                @click="handleWishlistToggle(p.name)"
                                type="button"
                                aria-label="Add to wishlist"
                                :aria-pressed="hasWish(p.name)"
                                :class="[
                                    'wish-btn absolute top-2 right-2 inline-flex h-9 w-9 items-center justify-center rounded-full bg-white/90 shadow-sm transition duration-200 hover:scale-110 hover:bg-white focus:ring-2 focus:ring-primary-600 focus:outline-none active:scale-95 sm:translate-y-1 sm:opacity-0 sm:group-hover:translate-y-0 sm:group-hover:opacity-100',
                                    hasWish(p.name)
                                        ? 'text-red-600'
                                        : 'text-gray-700 hover:text-red-600',
                                ]"
                            >
                                <svg
                                    class="h-5 w-5"
                                    :fill="
                                        hasWish(p.name)
                                            ? 'currentColor'
                                            : 'none'
                                    "
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                    stroke-width="2"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"
                                    />
                                </svg>
                            </button>
                        </div>
                        <div class="flex flex-1 flex-col p-3 md:p-4">
                            <Link
                                :href="`/product-details/${p.slug}`"
                                class="block"
                            >
                                <h3
                                    class="line-clamp-2 text-sm font-medium text-gray-900 transition-colors duration-200 group-hover:text-primary-600 md:text-base"
                                >
                                    {{ p.name }}
                                </h3>
                            </Link>
                            <div class="mt-1.5 flex items-center gap-1">
                                <div class="flex">
                                    <svg
                                        v-for="starIndex in 5"
                                        :key="starIndex"
                                        :class="[
                                            'h-3.5 w-3.5',
                                            starIndex <= Math.floor(p.rating)
                                                ? 'text-accent-500'
                                                : 'text-gray-300',
                                        ]"
                                        fill="currentColor"
                                        viewBox="0 0 20 20"
                                    >
                                        <path
                                            d="M9.05 2.927c.3-.921 1.6-.921 1.9 0l1.286 3.957a1 1 0 00.95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.368 2.447a1 1 0 00-.364 1.118l1.287 3.957c.3.922-.755 1.688-1.539 1.118l-3.366-2.447a1 1 0 00-1.176 0l-3.366 2.447c-.783.57-1.838-.196-1.539-1.118l1.287-3.957a1 1 0 00-.364-1.118L2.354 9.384c-.783-.57-.38-1.81.588-1.81h4.162a1 1 0 00.951-.69l1.286-3.957z"
                                        />
                                    </svg>
                                </div>
                                <span class="text-xs text-gray-400"
                                    >({{ p.reviews }})</span
                                >
                            </div>
                            <div class="mt-2 flex items-center gap-2">
                                <span
                                    class="text-base font-semibold text-primary-600 md:text-lg"
                                    >{{ formatPrice(p.price) }}</span
                                >
                                <span
                                    v-if="p.oldPrice"
                                    class="text-xs text-gray-400 line-through"
                                    >{{ formatPrice(p.oldPrice) }}</span
                                >
                            </div>
                            <div class="mt-auto pt-3">
                                <div class="flex flex-col gap-2">
                                    <button
                                        v-if="p.inStock"
                                        @click="handleAddToCart(p)"
                                        type="button"
                                        class="inline-flex w-full items-center justify-center gap-1.5 rounded-lg border border-primary-600 px-3 py-2.5 text-sm font-medium text-primary-600 transition hover:bg-primary-50 focus:ring-2 focus:ring-primary-600 focus:outline-none"
                                    >
                                        <svg
                                            class="h-4 w-4"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                            stroke-width="2"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"
                                            />
                                        </svg>
                                        Add to Cart
                                    </button>
                                    <button
                                        v-else
                                        disabled
                                        type="button"
                                        class="inline-flex w-full cursor-not-allowed items-center justify-center gap-1.5 rounded-lg border border-gray-200 bg-gray-50 px-3 py-2.5 text-sm font-medium text-gray-400"
                                    >
                                        <svg
                                            class="h-4 w-4"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                            stroke-width="2"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"
                                            />
                                        </svg>
                                        Add to Cart
                                    </button>
                                    <button
                                        v-if="p.inStock"
                                        @click="handleBuyNow(p)"
                                        type="button"
                                        class="w-full rounded-lg bg-primary-600 px-3 py-2.5 text-sm font-semibold text-white transition hover:bg-primary-700 focus:ring-2 focus:ring-primary-600 focus:outline-none"
                                    >
                                        Buy Now
                                    </button>
                                    <button
                                        v-else
                                        disabled
                                        type="button"
                                        class="w-full cursor-not-allowed rounded-lg bg-gray-200 px-3 py-2.5 text-sm font-semibold text-gray-400"
                                    >
                                        Notify Me
                                    </button>
                                </div>
                            </div>
                        </div>
                    </article>
                </div>

                <!-- Empty state -->
                <div v-else class="py-16 text-center">
                    <div
                        class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-gray-100 text-gray-400"
                    >
                        <svg
                            class="h-8 w-8"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="1.8"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M21 21l-4.35-4.35M11 18a7 7 0 100-14 7 7 0 000 14z"
                            />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900">
                        No products found
                    </h3>
                    <p class="mt-1 text-sm text-gray-500">
                        Try adjusting your filters or search.
                    </p>
                    <button
                        @click="clearAllFilters"
                        type="button"
                        class="mt-4 rounded-lg bg-primary-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-primary-700"
                    >
                        Clear filters
                    </button>
                </div>

                <!-- Pagination -->
                <nav
                    v-if="totalPages > 1"
                    class="mt-10 flex items-center justify-center gap-1.5"
                    aria-label="Pagination"
                >
                    <button
                        type="button"
                        :disabled="currentPage === 1"
                        @click="changePage(currentPage - 1)"
                        aria-label="Previous page"
                        class="inline-flex h-10 min-w-[2.5rem] items-center justify-center rounded-lg border border-gray-300 px-3 text-sm font-medium text-gray-700 transition hover:bg-gray-50 disabled:cursor-not-allowed disabled:opacity-40"
                    >
                        ‹
                    </button>
                    <button
                        v-for="page in totalPages"
                        :key="page"
                        type="button"
                        @click="changePage(page)"
                        :class="[
                            'inline-flex h-10 min-w-[2.5rem] items-center justify-center rounded-lg border px-3 text-sm font-medium transition',
                            page === currentPage
                                ? 'border-primary-600 bg-primary-600 text-white'
                                : 'border-gray-300 text-gray-700 hover:bg-gray-50',
                        ]"
                    >
                        {{ page }}
                    </button>
                    <button
                        type="button"
                        :disabled="currentPage === totalPages"
                        @click="changePage(currentPage + 1)"
                        aria-label="Next page"
                        class="inline-flex h-10 min-w-[2.5rem] items-center justify-center rounded-lg border border-gray-300 px-3 text-sm font-medium text-gray-700 transition hover:bg-gray-50 disabled:cursor-not-allowed disabled:opacity-40"
                    >
                        ›
                    </button>
                </nav>
            </div>
        </div>
    </div>
</template>
