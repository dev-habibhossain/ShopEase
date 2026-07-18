<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import { useCart } from '@/composables/useCart';
import { useWishlist } from '@/composables/useWishlist';
import { useToast } from '@/composables/useToast';

defineProps<{
    slug: string;
}>();

interface Product {
    name: string;
    slug: string;
    price: number;
    oldPrice?: number;
    img: string;
    rating: number;
    reviews: number;
    inStock: boolean;
    tag?: string;
}

interface Review {
    name: string;
    rating: number;
    date: string;
    verified: boolean;
    text: string;
}

// Statically defined product info
const product = {
    name: 'Wireless Noise-Cancelling Headphones',
    price: 6499,
    oldPrice: 8999,
    rating: 4.8,
    reviews: 214,
    inStock: true,
    category: 'Electronics',
    images: [
        'photo-1505740420928-5e560c06d30e',
        'photo-1484704849700-f032a568e944',
        'photo-1583394838336-acd977736f90',
        'photo-1546435770-a3e426bf472b',
        'photo-1577174881658-0f30ed549adc',
    ],
};

const relatedProducts: Product[] = [
    {
        name: 'Smart Fitness Watch Series 6',
        slug: 'smart-fitness-watch-series-6',
        price: 4299,
        img: 'photo-1523275335684-37898b6baf30',
        rating: 4.6,
        reviews: 167,
        inStock: true,
        tag: 'Best Seller',
    },
    {
        name: 'Portable Bluetooth Speaker',
        slug: 'portable-bluetooth-speaker',
        price: 2199,
        oldPrice: 2799,
        img: 'photo-1608043152269-423dbba4e7e1',
        rating: 4.5,
        reviews: 89,
        inStock: true,
    },
    {
        name: 'Wireless Earbuds Pro',
        slug: 'wireless-earbuds-pro',
        price: 3499,
        img: 'photo-1590658268037-6bf12165a8df',
        rating: 4.7,
        reviews: 142,
        inStock: false,
    },
    {
        name: 'Premium Sunglasses UV400',
        slug: 'premium-sunglasses-uv400',
        price: 1599,
        img: 'photo-1572635196237-14b3f281503f',
        rating: 4.5,
        reviews: 98,
        inStock: true,
        tag: 'New',
    },
];

const reviews = ref<Review[]>([
    {
        name: 'Rina A.',
        rating: 5,
        date: '12 Jun 2026',
        verified: true,
        text: 'Sound quality is amazing and the noise cancellation actually works on the bus. Battery lasts me almost a week. Highly recommended!',
    },
    {
        name: 'Karim H.',
        rating: 5,
        date: '5 Jun 2026',
        verified: true,
        text: 'Delivered fast inside Dhaka, paid cash on delivery. Build quality feels premium for the price.',
    },
    {
        name: 'Sadia R.',
        rating: 4,
        date: '28 May 2026',
        verified: false,
        text: 'Very comfortable for long use. Only wish the case was a bit smaller, but overall great value.',
    },
]);

const breakdown = [
    { s: 5, p: 78 },
    { s: 4, p: 15 },
    { s: 3, p: 5 },
    { s: 2, p: 1 },
    { s: 1, p: 1 },
];

// Composables
const { addToCart, buyNow } = useCart();
const { toggleWish, hasWish } = useWishlist();
const { showToast } = useToast();

// Interactive states
const selectedImageIndex = ref(0);
const qty = ref(1);
const activeTab = ref<'desc' | 'reviews'>('desc');

// Sticky Mobile/Tablet Add to Cart Bar visibility observer
const showStickyBar = ref(false);
const mainActionsRef = ref<HTMLElement | null>(null);

let observer: IntersectionObserver | null = null;

onMounted(() => {
    if (mainActionsRef.value) {
        observer = new IntersectionObserver(
            ([entry]) => {
                // Show sticky bar only when main actions are scrolled past the top of the viewport
                showStickyBar.value =
                    !entry.isIntersecting && entry.boundingClientRect.top < 0;
            },
            {
                threshold: 0,
                rootMargin: '0px',
            },
        );
        observer.observe(mainActionsRef.value);
    }
});

onUnmounted(() => {
    if (observer) {
        observer.disconnect();
    }
});

// Review Form states
const reviewName = ref('');
const reviewText = ref('');
const selectedRating = ref(0);
const hoverRating = ref(0);

// Quantity modifiers
const incrementQty = () => {
    qty.value++;
};

const decrementQty = () => {
    if (qty.value > 1) {
        qty.value--;
    }
};

const handleQtyInput = (e: Event) => {
    const target = e.target as HTMLInputElement;
    const val = parseInt(target.value.replace(/[^0-9]/g, '')) || 1;
    qty.value = Math.max(1, val);
};

const handleQtyBlur = () => {
    qty.value = Math.max(1, qty.value);
};

// Wishlist interaction
const handleWishlistToggle = () => {
    const isAdded = toggleWish(product.name);
    if (isAdded) {
        showToast('Added to wishlist ❤️');
    } else {
        showToast('Removed from wishlist');
    }
};

// Cart interactions
const handleAddToCart = () => {
    addToCart(product.name, product.price, product.images[0], qty.value);
    showToast(`Added ${qty.value} item(s) to cart 🛒`);
};

const handleBuyNow = () => {
    buyNow(product.name, product.price, product.images[0], qty.value);
    showToast(`Proceeding to checkout with ${qty.value} item(s) 💳`);
};

// Tabs helper
const switchTab = (tab: 'desc' | 'reviews') => {
    activeTab.value = tab;
};

// Star hover & click selection
const setHoverRating = (rating: number) => {
    hoverRating.value = rating;
};

const selectRating = (rating: number) => {
    selectedRating.value = rating;
};

// Review Submission
const handleReviewSubmit = () => {
    if (selectedRating.value === 0) {
        showToast('Please select a star rating');
        return;
    }

    const today = new Date();
    const formattedDate = today.toLocaleDateString('en-GB', {
        day: 'numeric',
        month: 'short',
        year: 'numeric',
    });

    reviews.value.unshift({
        name: reviewName.value || 'Anonymous',
        rating: selectedRating.value,
        date: formattedDate,
        verified: false,
        text: reviewText.value,
    });

    showToast('Thanks! Your review is pending approval.');

    // Reset fields
    reviewName.value = '';
    reviewText.value = '';
    selectedRating.value = 0;
    hoverRating.value = 0;
};

// Helpers
const formatPrice = (price: number) => {
    return '৳ ' + Number(price).toLocaleString('en-BD');
};
</script>

<template>
    <Head :title="`${product.name} — ShopEase`" />

    <!-- ============================ BREADCRUMB ============================ -->
    <nav aria-label="Breadcrumb" class="border-b border-gray-200 bg-gray-50">
        <div class="mx-auto max-w-7xl px-4 py-3 sm:px-6 lg:px-8">
            <ol
                class="flex flex-wrap items-center gap-1.5 text-sm text-gray-500"
            >
                <li>
                    <Link href="/" class="hover:text-primary-600">Home</Link>
                </li>
                <li aria-hidden="true" class="text-gray-300">/</li>
                <li>
                    <Link href="/shop" class="hover:text-primary-600">{{
                        product.category
                    }}</Link>
                </li>
                <li aria-hidden="true" class="text-gray-300">/</li>
                <li
                    aria-current="page"
                    class="max-w-[200px] truncate font-medium text-gray-900 sm:max-w-none"
                >
                    {{ product.name }}
                </li>
            </ol>
        </div>
    </nav>

    <!-- ============================ PRODUCT OVERVIEW ============================ -->
    <section class="mx-auto max-w-7xl px-4 py-8 sm:px-6 md:py-12 lg:px-8">
        <div
            class="grid grid-cols-1 gap-8 lg:grid-cols-2 lg:items-start lg:gap-12"
        >
            <!-- Left: Gallery component -->
            <div>
                <!-- Main image -->
                <div
                    class="aspect-square overflow-hidden rounded-xl border border-gray-200 bg-gray-100"
                >
                    <img
                        :src="`https://images.unsplash.com/${product.images[selectedImageIndex]}?auto=format&fit=crop&w=900&q=75`"
                        :alt="product.name"
                        class="h-full w-full object-cover transition duration-300"
                    />
                </div>
                <!-- Thumbnail row -->
                <div class="no-scrollbar mt-4 flex gap-3 overflow-x-auto">
                    <button
                        v-for="(img, idx) in product.images"
                        :key="idx"
                        @click="selectedImageIndex = idx"
                        type="button"
                        :class="[
                            'h-20 w-20 shrink-0 overflow-hidden rounded-lg border-2 transition',
                            selectedImageIndex === idx
                                ? 'border-primary-600 shadow'
                                : 'border-transparent hover:border-gray-300',
                        ]"
                        :aria-label="`View image ${idx + 1}`"
                    >
                        <img
                            :src="`https://images.unsplash.com/${img}?auto=format&fit=crop&w=200&q=70`"
                            alt=""
                            class="h-full w-full object-cover"
                        />
                    </button>
                </div>
            </div>

            <!-- Right: Product Info panel -->
            <div>
                <Link
                    href="/shop"
                    class="text-sm font-medium text-primary-600 hover:text-primary-700"
                >
                    {{ product.category }}
                </Link>
                <h1
                    class="mt-2 font-display text-2xl font-extrabold tracking-tight text-gray-900 md:text-3xl"
                >
                    {{ product.name }}
                </h1>

                <!-- Rating and Stock -->
                <div class="mt-3 flex flex-wrap items-center gap-3">
                    <div class="flex items-center gap-0.5">
                        <svg
                            v-for="star in 5"
                            :key="star"
                            :class="[
                                'h-4 w-4',
                                star <= Math.floor(product.rating)
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
                    <span class="text-sm font-medium text-gray-900">{{
                        product.rating
                    }}</span>
                    <button
                        @click="switchTab('reviews')"
                        class="text-sm text-gray-500 transition hover:text-primary-600"
                    >
                        {{ product.reviews }} reviews
                    </button>
                    <span class="text-gray-300">|</span>
                    <span
                        class="rounded-full bg-green-50 px-2.5 py-1 text-xs font-medium text-green-700 shadow-sm"
                    >
                        In Stock
                    </span>
                </div>

                <!-- Price segment -->
                <div class="mt-5 flex flex-wrap items-end gap-3">
                    <span class="text-3xl font-bold text-primary-600">{{
                        formatPrice(product.price)
                    }}</span>
                    <span
                        v-if="product.oldPrice"
                        class="text-lg text-gray-400 line-through"
                        >{{ formatPrice(product.oldPrice) }}</span
                    >
                    <span
                        class="rounded-full bg-accent-600 px-2.5 py-1 text-xs font-semibold text-white shadow-sm"
                    >
                        Save
                        {{
                            Math.round(
                                (1 - product.price / product.oldPrice) * 100,
                            )
                        }}%
                    </span>
                </div>

                <p
                    class="mt-5 max-w-prose text-sm leading-relaxed text-gray-600 md:text-base"
                >
                    Premium wireless noise-cancelling headphones with up to 40
                    hours of battery life and all-day comfort. Full details in
                    the description below.
                </p>

                <hr class="my-6 border-gray-200" />

                <!-- Quantity input and Actions -->
                <div>
                    <span class="mb-1.5 block text-sm font-medium text-gray-700"
                        >Quantity</span
                    >
                    <div
                        class="inline-flex items-center rounded-lg border border-gray-300 bg-white"
                    >
                        <button
                            @click="decrementQty"
                            type="button"
                            aria-label="Decrease quantity"
                            :disabled="qty <= 1"
                            class="inline-flex h-11 w-11 items-center justify-center rounded-l-lg text-gray-600 hover:bg-gray-100 focus:outline-none disabled:opacity-40"
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
                                    d="M20 12H4"
                                />
                            </svg>
                        </button>
                        <input
                            id="qtyInput"
                            type="text"
                            inputmode="numeric"
                            :value="qty"
                            @input="handleQtyInput"
                            @blur="handleQtyBlur"
                            aria-label="Quantity"
                            class="h-11 w-12 border-x border-gray-300 text-center text-sm font-medium text-gray-900 focus:outline-none"
                        />
                        <button
                            @click="incrementQty"
                            type="button"
                            aria-label="Increase quantity"
                            class="inline-flex h-11 w-11 items-center justify-center rounded-r-lg text-gray-600 hover:bg-gray-100 focus:outline-none"
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
                                    d="M12 4v16m8-8H4"
                                />
                            </svg>
                        </button>
                    </div>
                </div>

                <div
                    ref="mainActionsRef"
                    class="mt-5 flex flex-col gap-3 sm:flex-row"
                >
                    <button
                        @click="handleAddToCart"
                        type="button"
                        class="inline-flex flex-1 items-center justify-center gap-2 rounded-lg border border-primary-600 px-5 py-3 text-sm font-semibold text-primary-600 transition hover:bg-primary-50 focus:ring-2 focus:ring-primary-600 focus:outline-none"
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
                                d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"
                            />
                        </svg>
                        Add to Cart
                    </button>
                    <button
                        @click="handleBuyNow"
                        type="button"
                        class="inline-flex flex-1 items-center justify-center rounded-lg bg-gradient-to-r from-primary-600 to-primary-700 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:from-primary-700 hover:to-primary-700 hover:shadow-md focus:ring-2 focus:ring-primary-600 focus:outline-none"
                    >
                        Buy Now
                    </button>
                    <button
                        @click="handleWishlistToggle"
                        type="button"
                        aria-label="Add to wishlist"
                        :aria-pressed="hasWish(product.name)"
                        :class="[
                            'inline-flex h-12 w-12 shrink-0 items-center justify-center rounded-lg border transition focus:ring-2 focus:ring-primary-600 focus:outline-none',
                            hasWish(product.name)
                                ? 'border-red-600 text-red-600'
                                : 'border-gray-300 text-gray-600 hover:border-red-300 hover:text-red-600',
                        ]"
                    >
                        <svg
                            class="h-5 w-5"
                            :fill="
                                hasWish(product.name) ? 'currentColor' : 'none'
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

                <!-- Shipping and Safety info -->
                <div
                    class="mt-6 space-y-3 rounded-xl border border-gray-200 bg-gray-50 p-4"
                >
                    <div class="flex items-start gap-3">
                        <svg
                            class="mt-0.5 h-5 w-5 shrink-0 text-primary-600"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="2"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M9 17a2 2 0 11-4 0 2 2 0 014 0zm10 0a2 2 0 11-4 0 2 2 0 014 0zM13 16V6a1 1 0 00-1-1H3m10 11h2.5a1 1 0 001-.7l1.4-4.2a1 1 0 00-.95-1.3H13"
                            />
                        </svg>
                        <p class="text-sm text-gray-600">
                            <span class="font-medium text-gray-900"
                                >Delivery:</span
                            >
                            ৳ 60 inside Dhaka, ৳ 120 outside Dhaka. Delivered in
                            2–4 days.
                        </p>
                    </div>
                    <div class="flex items-start gap-3">
                        <svg
                            class="mt-0.5 h-5 w-5 shrink-0 text-primary-600"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="2"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8V6m0 12v-2"
                            />
                        </svg>
                        <p class="text-sm text-gray-600">
                            <span class="font-medium text-gray-900"
                                >Cash on Delivery</span
                            >
                            available — pay when you receive.
                        </p>
                    </div>
                    <div class="flex items-start gap-3">
                        <svg
                            class="mt-0.5 h-5 w-5 shrink-0 text-primary-600"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="2"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"
                            />
                        </svg>
                        <p class="text-sm text-gray-600">
                            Secure online payment via
                            <span class="font-medium text-gray-900"
                                >Stripe</span
                            >.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ============================ DETAILS TABS ============================ -->
    <section class="border-t border-gray-200 bg-gray-50">
        <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 md:py-14 lg:px-8">
            <!-- Tab switches -->
            <div class="border-b border-gray-200">
                <div
                    class="flex gap-6"
                    role="tablist"
                    aria-label="Product details"
                >
                    <button
                        @click="switchTab('desc')"
                        role="tab"
                        :aria-selected="activeTab === 'desc'"
                        class="tab-btn -mb-px border-b-2 px-1 pb-3 text-sm font-semibold transition"
                        :class="[
                            activeTab === 'desc'
                                ? 'border-primary-600 text-primary-600'
                                : 'border-transparent text-gray-500 hover:text-gray-900',
                        ]"
                    >
                        Description
                    </button>
                    <button
                        @click="switchTab('reviews')"
                        role="tab"
                        :aria-selected="activeTab === 'reviews'"
                        class="tab-btn -mb-px border-b-2 px-1 pb-3 text-sm font-semibold transition"
                        :class="[
                            activeTab === 'reviews'
                                ? 'border-primary-600 text-primary-600'
                                : 'border-transparent text-gray-500 hover:text-gray-900',
                        ]"
                    >
                        Reviews
                        <span class="text-gray-400"
                            >({{ reviews.length }})</span
                        >
                    </button>
                </div>
            </div>

            <!-- Description panel -->
            <div v-if="activeTab === 'desc'" role="tabpanel" class="pt-6">
                <div
                    class="max-w-prose space-y-4 text-sm leading-relaxed text-gray-600 md:text-base"
                >
                    <p>
                        The ShopEase Wireless Noise-Cancelling Headphones are
                        engineered for listeners who refuse to compromise.
                        Advanced hybrid active noise cancellation silences the
                        world around you, while custom 40mm drivers deliver
                        deep, balanced sound across every genre.
                    </p>
                    <p>
                        Designed for all-day comfort, the breathable memory-foam
                        ear cushions and lightweight headband let you wear them
                        from your morning commute to late-night sessions. With
                        up to 40 hours of playback and fast charging — 10
                        minutes gives you 5 hours — you'll rarely reach for the
                        cable.
                    </p>
                    <h3 class="pt-2 text-base font-semibold text-gray-900">
                        Key Features
                    </h3>
                    <ul class="list-disc space-y-1.5 pl-5">
                        <li>
                            Hybrid Active Noise Cancellation (ANC) with
                            transparency mode
                        </li>
                        <li>
                            Up to 40 hours battery life; USB-C fast charging
                        </li>
                        <li>Bluetooth 5.3 with multipoint pairing</li>
                        <li>
                            Built-in microphone with environmental noise
                            reduction for calls
                        </li>
                        <li>Foldable design with travel case included</li>
                    </ul>
                </div>
            </div>

            <!-- Reviews panel -->
            <div v-if="activeTab === 'reviews'" role="tabpanel" class="pt-6">
                <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
                    <!-- Rating summary card -->
                    <div class="lg:col-span-1">
                        <div
                            class="rounded-xl border border-gray-200 bg-white p-6 text-center shadow-sm"
                        >
                            <p class="text-5xl font-bold text-gray-900">4.8</p>
                            <div class="mt-2 flex justify-center gap-0.5">
                                <svg
                                    v-for="star in 5"
                                    :key="star"
                                    class="h-5 w-5 text-accent-500"
                                    fill="currentColor"
                                    viewBox="0 0 20 20"
                                >
                                    <path
                                        d="M9.05 2.927c.3-.921 1.6-.921 1.9 0l1.286 3.957a1 1 0 00.95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.368 2.447a1 1 0 00-.364 1.118l1.287 3.957c.3.922-.755 1.688-1.539 1.118l-3.366-2.447a1 1 0 00-1.176 0l-3.366 2.447c-.783.57-1.838-.196-1.539-1.118l1.287-3.957a1 1 0 00-.364-1.118L2.354 9.384c-.783-.57-.38-1.81.588-1.81h4.162a1 1 0 00.951-.69l1.286-3.957z"
                                    />
                                </svg>
                            </div>
                            <p class="mt-2 text-sm text-gray-500">
                                Based on {{ reviews.length }} reviews
                            </p>
                        </div>

                        <!-- Bar breakdown -->
                        <div class="mt-4 space-y-2">
                            <div
                                v-for="b in breakdown"
                                :key="b.s"
                                class="flex items-center gap-2 text-sm"
                            >
                                <span class="w-8 shrink-0 text-gray-600"
                                    >{{ b.s }} ★</span
                                >
                                <div
                                    class="h-2 flex-1 overflow-hidden rounded-full bg-gray-200"
                                >
                                    <div
                                        class="h-full rounded-full bg-accent-500"
                                        :style="{ width: `${b.p}%` }"
                                    ></div>
                                </div>
                                <span
                                    class="w-9 shrink-0 text-right text-gray-400"
                                    >{{ b.p }}%</span
                                >
                            </div>
                        </div>
                    </div>

                    <!-- Reviews List and Form -->
                    <div class="space-y-8 lg:col-span-2">
                        <!-- Reviews List -->
                        <div class="space-y-6">
                            <div
                                v-for="(r, idx) in reviews"
                                :key="idx"
                                class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm"
                            >
                                <div
                                    class="flex items-center justify-between gap-3"
                                >
                                    <div class="flex items-center gap-3">
                                        <span
                                            class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-primary-50 text-sm font-semibold text-primary-600"
                                        >
                                            {{ r.name.charAt(0) }}
                                        </span>
                                        <div>
                                            <p
                                                class="text-sm font-semibold text-gray-900"
                                            >
                                                {{ r.name }}
                                                <span
                                                    v-if="r.verified"
                                                    class="ml-1 rounded bg-green-50 px-1.5 py-0.5 align-middle text-[10px] font-medium text-green-700 shadow-sm"
                                                >
                                                    Verified
                                                </span>
                                            </p>
                                            <div class="mt-0.5 flex gap-0.5">
                                                <svg
                                                    v-for="star in 5"
                                                    :key="star"
                                                    :class="[
                                                        'h-3.5 w-3.5',
                                                        star <= r.rating
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
                                        </div>
                                    </div>
                                    <span class="text-xs text-gray-400">{{
                                        r.date
                                    }}</span>
                                </div>
                                <p
                                    class="mt-3 text-sm leading-relaxed text-gray-600"
                                >
                                    {{ r.text }}
                                </p>
                            </div>
                        </div>

                        <!-- Write a review form -->
                        <div
                            class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm"
                        >
                            <h3 class="text-lg font-semibold text-gray-900">
                                Write a review
                            </h3>
                            <p class="mt-1 text-sm text-gray-500">
                                Your review will appear after approval by our
                                team.
                            </p>
                            <form
                                @submit.prevent="handleReviewSubmit"
                                class="mt-4 space-y-4"
                            >
                                <!-- Star Rating selector -->
                                <div>
                                    <span
                                        class="mb-1.5 block text-sm font-medium text-gray-700"
                                        >Your rating</span
                                    >
                                    <div
                                        class="flex gap-1"
                                        role="radiogroup"
                                        aria-label="Your rating"
                                    >
                                        <button
                                            v-for="star in 5"
                                            :key="star"
                                            @click="selectRating(star)"
                                            @mouseover="setHoverRating(star)"
                                            @mouseleave="setHoverRating(0)"
                                            type="button"
                                            role="radio"
                                            :aria-checked="
                                                star === selectedRating
                                            "
                                            :aria-label="`${star} star`"
                                            class="rounded p-0.5 focus:ring-2 focus:ring-primary-600 focus:outline-none"
                                        >
                                            <svg
                                                :class="[
                                                    'h-7 w-7 transition',
                                                    star <=
                                                    (hoverRating ||
                                                        selectedRating)
                                                        ? 'scale-110 text-accent-500'
                                                        : 'text-gray-300',
                                                ]"
                                                fill="currentColor"
                                                viewBox="0 0 20 20"
                                            >
                                                <path
                                                    d="M9.05 2.927c.3-.921 1.6-.921 1.9 0l1.286 3.957a1 1 0 00.95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.368 2.447a1 1 0 00-.364 1.118l1.287 3.957c.3.922-.755 1.688-1.539 1.118l-3.366-2.447a1 1 0 00-1.176 0l-3.366 2.447c-.783.57-1.838-.196-1.539-1.118l1.287-3.957a1 1 0 00-.364-1.118L2.354 9.384c-.783-.57-.38-1.81.588-1.81h4.162a1 1 0 00.951-.69l1.286-3.957z"
                                                />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                                <div>
                                    <label
                                        for="reviewName"
                                        class="mb-1.5 block text-sm font-medium text-gray-700"
                                        >Name</label
                                    >
                                    <input
                                        id="reviewName"
                                        v-model="reviewName"
                                        type="text"
                                        required
                                        class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm transition focus:border-primary-600 focus:ring-2 focus:ring-primary-600 focus:outline-none"
                                        placeholder="Your name"
                                    />
                                </div>
                                <div>
                                    <label
                                        for="reviewText"
                                        class="mb-1.5 block text-sm font-medium text-gray-700"
                                        >Review</label
                                    >
                                    <textarea
                                        id="reviewText"
                                        v-model="reviewText"
                                        rows="4"
                                        required
                                        class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm transition focus:border-primary-600 focus:ring-2 focus:ring-primary-600 focus:outline-none"
                                        placeholder="Share your experience with this product…"
                                    ></textarea>
                                </div>
                                <button
                                    type="submit"
                                    class="rounded-lg bg-primary-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-primary-700 focus:ring-2 focus:ring-primary-600 focus:outline-none"
                                >
                                    Submit Review
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ============================ RELATED PRODUCTS ============================ -->
    <section class="bg-white py-12 md:py-16">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <h2
                class="mb-8 font-display text-2xl font-extrabold tracking-tight text-gray-900 md:text-3xl"
            >
                You may also like
            </h2>
            <div
                class="grid grid-cols-2 gap-4 md:grid-cols-3 md:gap-6 lg:grid-cols-4"
            >
                <article
                    v-for="p in relatedProducts"
                    :key="p.name"
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
                            @click="
                                toggleWish(p.name);
                                showToast(
                                    hasWish(p.name)
                                        ? 'Added to wishlist ❤️'
                                        : 'Removed from wishlist',
                                );
                            "
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
                                    hasWish(p.name) ? 'currentColor' : 'none'
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
                                    @click="
                                        addToCart(p.name, p.price, p.img);
                                        showToast(
                                            `Added to cart: ${p.name} 🛒`,
                                        );
                                    "
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
                                    @click="
                                        buyNow(p.name, p.price, p.img);
                                        showToast(
                                            `Added to cart: ${p.name} 🛒`,
                                        );
                                    "
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
        </div>
    </section>

    <!-- ============================ STICKY MOBILE ADD-TO-CART BAR ============================ -->
    <Transition name="fade-slide">
        <div
            v-show="showStickyBar"
            class="fixed inset-x-0 bottom-0 z-40 border-t border-gray-200 bg-white/95 shadow-[0_-2px_10px_rgba(0,0,0,0.05)] backdrop-blur lg:hidden"
        >
            <div class="flex items-center gap-3 px-4 py-3">
                <div class="shrink-0 leading-tight">
                    <p class="text-lg font-bold text-primary-600">
                        {{ formatPrice(product.price) }}
                    </p>
                    <p
                        v-if="product.oldPrice"
                        class="text-xs text-gray-400 line-through"
                    >
                        {{ formatPrice(product.oldPrice) }}
                    </p>
                </div>
                <button
                    @click="handleAddToCart"
                    type="button"
                    class="flex-1 rounded-lg border border-primary-600 px-4 py-3 text-sm font-semibold text-primary-600 transition hover:bg-primary-50"
                >
                    Add to Cart
                </button>
                <button
                    @click="handleBuyNow"
                    type="button"
                    class="flex-1 rounded-lg bg-gradient-to-r from-primary-600 to-primary-700 px-4 py-3 text-sm font-semibold text-white shadow-sm transition hover:from-primary-700 hover:to-primary-700 hover:shadow-md"
                >
                    Buy Now
                </button>
            </div>
        </div>
    </Transition>
</template>

<style scoped>
.fade-slide-enter-active,
.fade-slide-leave-active {
    transition:
        transform 0.3s cubic-bezier(0.16, 1, 0.3, 1),
        opacity 0.3s ease;
}
.fade-slide-enter-from,
.fade-slide-leave-to {
    transform: translateY(100%);
    opacity: 0;
}
</style>
