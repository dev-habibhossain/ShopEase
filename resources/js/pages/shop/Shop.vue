<script setup lang="ts">
import { ref, computed, watch } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { shop } from '@/routes';
import { useCart } from '@/composables/useCart';
import { useWishlist } from '@/composables/useWishlist';
import { useToast } from '@/composables/useToast';

interface Category {
    name: string;
    slug: string;
}

interface Product {
    id: number;
    name: string;
    slug: string;
    price: number;
    oldPrice?: number | null;
    img: string;
    rating: number;
    reviews: number;
    inStock: boolean;
    tag: string;
    soldCount: number;
}

interface PaginatorLink {
    url: string | null;
    label: string;
    active: boolean;
}

interface Paginator {
    data: Product[];
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
    links: PaginatorLink[];
    from: number | null;
    to: number | null;
}

interface Filters {
    search: string;
    category: string;
    price: string;
    stock: boolean;
    sort: string;
}

const props = defineProps<{
    products: Paginator;
    categories: Category[];
    filters: Filters;
}>();

// Composables
const { addToCart, buyNow } = useCart();
const { toggleWish, hasWish } = useWishlist();
const { showToast } = useToast();

// Local filter state — initialised from server-supplied filters
const searchQuery = ref(props.filters.search);
const selectedCategory = ref(props.filters.category || 'all');
const selectedPriceRange = ref(props.filters.price || 'all');
const inStockOnly = ref(props.filters.stock);
const sortBy = ref(props.filters.sort || 'newest');
const isMobileFiltersOpen = ref(false);

watch(
    () => props.filters,
    (newFilters) => {
        searchQuery.value = newFilters.search || '';
        selectedCategory.value = newFilters.category || 'all';
        selectedPriceRange.value = newFilters.price || 'all';
        inStockOnly.value = newFilters.stock || false;
        sortBy.value = newFilters.sort || 'newest';
    },
    { deep: true }
);

const openMobileFilters = () => {
    isMobileFiltersOpen.value = true;
    document.body.style.overflow = 'hidden';
};

const closeMobileFilters = () => {
    isMobileFiltersOpen.value = false;
    document.body.style.overflow = '';
};

// Fire an Inertia GET visit with the current filter state
const applyFilters = (overrides: Partial<Filters> = {}) => {
    const params: Record<string, string | boolean> = {
        search: searchQuery.value,
        category: selectedCategory.value === 'all' ? '' : selectedCategory.value,
        price: selectedPriceRange.value === 'all' ? '' : selectedPriceRange.value,
        stock: inStockOnly.value,
        sort: sortBy.value,
        ...overrides,
    };

    // Strip empty strings so the URL stays clean
    const clean: Record<string, string | boolean> = {};
    for (const [k, v] of Object.entries(params)) {
        if (v !== '' && v !== false) {
            clean[k] = v;
        }
    }

    router.get(shop.url(), clean, {
        preserveState: true,
        replace: true,
    });
};

// Debounce search so we don't hit the server on every keystroke
let searchTimer: ReturnType<typeof setTimeout> | null = null;
const handleSearchInput = () => {
    if (searchTimer) clearTimeout(searchTimer);
    searchTimer = setTimeout(() => applyFilters(), 400);
};

const handleFilterChange = () => {
    applyFilters();
};

const clearAllFilters = () => {
    searchQuery.value = '';
    selectedCategory.value = 'all';
    selectedPriceRange.value = 'all';
    inStockOnly.value = false;
    sortBy.value = 'newest';
    router.get(shop.url(), {}, { preserveState: false, replace: true });
};

// Active filter chips for display
const activeChips = computed(() => {
    const chips: { label: string; clear: () => void }[] = [];

    if (selectedCategory.value !== 'all' && selectedCategory.value !== '') {
        const cat = props.categories.find((c) => c.slug === selectedCategory.value);
        chips.push({
            label: cat?.name ?? selectedCategory.value,
            clear: () => {
                selectedCategory.value = 'all';
                applyFilters();
            },
        });
    }

    if (selectedPriceRange.value !== 'all' && selectedPriceRange.value !== '') {
        const labels: Record<string, string> = {
            '0-1000': 'Under ৳ 1,000',
            '1000-3000': '৳ 1,000 – ৳ 3,000',
            '3000-6000': '৳ 3,000 – ৳ 6,000',
            '6000+': 'Over ৳ 6,000',
        };
        chips.push({
            label: labels[selectedPriceRange.value] ?? selectedPriceRange.value,
            clear: () => {
                selectedPriceRange.value = 'all';
                applyFilters();
            },
        });
    }

    if (inStockOnly.value) {
        chips.push({
            label: 'In Stock',
            clear: () => {
                inStockOnly.value = false;
                applyFilters();
            },
        });
    }

    if (searchQuery.value.trim() !== '') {
        chips.push({
            label: `"${searchQuery.value}"`,
            clear: () => {
                searchQuery.value = '';
                applyFilters();
            },
        });
    }

    return chips;
});

// Image URL helper
const imageUrl = (path: string) => {
    if (!path) {
        return 'https://images.unsplash.com/photo-1441986300917-64674bd600d8?auto=format&fit=crop&w=600&q=70';
    }
    if (path.startsWith('http')) {
        return path;
    }
    return `https://images.unsplash.com/${path}?auto=format&fit=crop&w=600&q=70`;
};

// Cart/wishlist handlers
const handleWishlistToggle = (productName: string) => {
    const isAdded = toggleWish(productName);
    showToast(isAdded ? `Added to wishlist: ${productName} ❤️` : `Removed from wishlist: ${productName}`);
};

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
                            v-for="cat in categories"
                            :key="cat.slug"
                            class="flex cursor-pointer items-center gap-2.5 rounded-md px-1 py-1 hover:bg-gray-50"
                        >
                            <input
                                type="radio"
                                v-model="selectedCategory"
                                :value="cat.slug"
                                @change="handleFilterChange"
                                class="h-4 w-4 text-primary-600 focus:ring-primary-600"
                            />
                            <span class="text-gray-600">{{ cat.name }}</span>
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
                                value="6000+"
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
                                    v-for="cat in categories"
                                    :key="cat.slug"
                                    class="flex cursor-pointer items-center gap-2.5 rounded-md px-1 py-1 hover:bg-gray-50"
                                >
                                    <input
                                        type="radio"
                                        v-model="selectedCategory"
                                        :value="cat.slug"
                                        @change="handleFilterChange"
                                        class="h-4 w-4 text-primary-600 focus:ring-primary-600"
                                    />
                                    <span class="text-gray-600">{{ cat.name }}</span>
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
                                        value="6000+"
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
                <div
                    class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between"
                >
                    <p class="order-2 text-sm text-gray-600 sm:order-1">
                        Showing
                        <span v-if="products.from" class="font-semibold text-gray-900">{{ products.from }}–{{ products.to }}</span>
                        <span v-else class="font-semibold text-gray-900">0</span>
                        of
                        <span class="font-semibold text-gray-900">{{ products.total }}</span>
                        products
                    </p>
                    <div
                        class="order-1 flex w-full flex-col gap-2 sm:order-2 sm:w-auto sm:flex-row sm:items-center"
                    >
                        <!-- Local Search Bar -->
                        <div class="relative w-full sm:w-60">
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
                                @input="handleSearchInput"
                                type="search"
                                placeholder="Search products..."
                                class="w-full rounded-lg border border-gray-300 bg-white py-2 pr-3 pl-9 text-xs text-gray-900 transition placeholder:text-gray-400 focus:border-primary-600 focus:ring-1 focus:ring-primary-600 focus:outline-none sm:text-sm"
                            />
                        </div>

                        <!-- Filters & Sort Action Row (side-by-side on mobile, inline on sm) -->
                        <div class="flex w-full items-center gap-2 sm:w-auto">
                            <button
                                @click="openMobileFilters"
                                type="button"
                                class="inline-flex flex-1 items-center justify-center gap-2 rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 focus:ring-2 focus:ring-primary-600 focus:outline-none lg:hidden"
                            >
                                <svg
                                    class="h-5 w-5 text-gray-500"
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
                            <div
                                class="flex flex-1 items-center gap-2 sm:flex-initial"
                            >
                                <label
                                    for="sortSelect"
                                    class="hidden text-sm text-gray-500 md:block"
                                    >Sort:</label
                                >
                                <select
                                    id="sortSelect"
                                    v-model="sortBy"
                                    @change="handleFilterChange"
                                    class="w-full rounded-lg border border-gray-300 bg-white px-2 py-2 text-xs text-gray-900 focus:border-primary-600 focus:ring-2 focus:ring-primary-600 focus:outline-none sm:w-auto sm:px-3 sm:text-sm"
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
                    v-if="products.data.length > 0"
                    class="grid grid-cols-2 gap-4 sm:grid-cols-3 md:grid-cols-4 md:gap-6 lg:grid-cols-4"
                >
                    <article
                        v-for="p in products.data"
                        :key="p.id"
                        class="group flex h-full flex-col overflow-hidden rounded-xl border border-gray-200 bg-white transition duration-300 ease-out hover:-translate-y-1 hover:border-primary-600 hover:shadow-xl"
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
                                    :src="imageUrl(p.img)"
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
                                <div
                                    class="grid grid-cols-2 gap-1.5 min-[513px]:flex min-[513px]:flex-col min-[513px]:gap-2"
                                >
                                    <button
                                        v-if="p.inStock"
                                        @click="handleAddToCart(p)"
                                        type="button"
                                        class="inline-flex w-full items-center justify-center gap-1 rounded-lg border border-primary-600 px-2 py-2 text-xs font-medium text-primary-600 transition hover:bg-primary-50 focus:ring-2 focus:ring-primary-600 focus:outline-none min-[513px]:px-2.5 min-[513px]:py-2 md:gap-1.5 lg:text-sm"
                                    >
                                        <svg
                                            class="h-3.5 w-3.5 min-[513px]:h-4 min-[513px]:w-4"
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
                                        <span
                                            class="hidden min-[513px]:inline"
                                            >Add to Cart</span
                                        >
                                        <span
                                            class="inline min-[513px]:hidden"
                                            >Add</span
                                        >
                                    </button>
                                    <button
                                        v-else
                                        disabled
                                        type="button"
                                        class="inline-flex w-full cursor-not-allowed items-center justify-center gap-1 rounded-lg border border-gray-200 bg-gray-50 px-2 py-2 text-xs font-medium text-gray-400 min-[513px]:px-2.5 min-[513px]:py-2 md:gap-1.5 lg:text-sm"
                                    >
                                        <svg
                                            class="h-3.5 w-3.5 min-[513px]:h-4 min-[513px]:w-4"
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
                                        <span
                                            class="hidden min-[513px]:inline"
                                            >Add to Cart</span
                                        >
                                        <span
                                            class="inline min-[513px]:hidden"
                                            >Add</span
                                        >
                                    </button>
                                    <button
                                        v-if="p.inStock"
                                        @click="handleBuyNow(p)"
                                        type="button"
                                        class="w-full rounded-lg bg-primary-600 px-2 py-2 text-xs font-semibold text-white transition hover:bg-primary-700 focus:ring-2 focus:ring-primary-600 focus:outline-none min-[513px]:px-2.5 min-[513px]:py-2 lg:text-sm"
                                    >
                                        <span
                                            class="hidden min-[513px]:inline"
                                            >Buy Now</span
                                        >
                                        <span
                                            class="inline min-[513px]:hidden"
                                            >Buy</span
                                        >
                                    </button>
                                    <button
                                        v-else
                                        disabled
                                        type="button"
                                        class="w-full cursor-not-allowed rounded-lg bg-gray-200 px-2 py-2 text-xs font-semibold text-gray-400 min-[513px]:px-2.5 min-[513px]:py-2 lg:text-sm"
                                    >
                                        <span
                                            class="hidden min-[513px]:inline"
                                            >Notify Me</span
                                        >
                                        <span
                                            class="inline min-[513px]:hidden"
                                            >Notify</span
                                        >
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
    v-if="products.last_page > 1"
    class="mt-10 flex items-center justify-center gap-1.5"
    aria-label="Pagination"
>
    <template v-for="(link, index) in products.links" :key="index">
        <Link
            v-if="link.url"
            :href="link.url"
            preserve-state
            :class="[
                'inline-flex h-10 min-w-[2.5rem] items-center justify-center rounded-lg border px-3 text-sm font-medium transition',
                link.active
                    ? 'border-primary-600 bg-primary-600 text-white'
                    : 'border-gray-300 text-gray-700 hover:bg-gray-50',
            ]"
        >
            <span v-html="link.label"></span>
        </Link>
        <span
            v-else
            class="inline-flex h-10 min-w-[2.5rem] cursor-not-allowed items-center justify-center rounded-lg border border-gray-300 px-3 text-sm font-medium text-gray-400 opacity-40"
            v-html="link.label"
        />
    </template>
</nav>
            </div>
        </div>
    </div>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.3s ease;
}
.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}

.slide-right-enter-active,
.slide-right-leave-active {
    transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1);
}
.slide-right-enter-from,
.slide-right-leave-to {
    transform: translateX(100%);
}
</style>