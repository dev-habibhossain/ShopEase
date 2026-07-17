<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import { useCart } from '@/composables/useCart';
import { useWishlist } from '@/composables/useWishlist';
import { useToast } from '@/composables/useToast';

interface Product {
    name: string;
    price: number;
    oldPrice?: number;
    img: string;
    rating: number;
    reviews: number;
    inStock: boolean;
    tag?: string;
}

// Statically defined products from design
const bestSelling: Product[] = [
    { name: 'Wireless Noise-Cancelling Headphones', price: 6499, oldPrice: 8999, img: 'photo-1505740420928-5e560c06d30e', rating: 4.8, reviews: 214, inStock: true, tag: 'Best Seller' },
    { name: 'Smart Fitness Watch Series 6', price: 4299, img: 'photo-1523275335684-37898b6baf30', rating: 4.6, reviews: 167, inStock: true, tag: 'Best Seller' },
    { name: 'Classic Leather Sneakers', price: 2999, oldPrice: 3999, img: 'photo-1542291026-7eec264c27ff', rating: 4.7, reviews: 132, inStock: true, tag: 'Best Seller' },
    { name: 'Premium Sunglasses UV400', price: 1599, img: 'photo-1572635196237-14b3f281503f', rating: 4.5, reviews: 98, inStock: false, tag: 'Best Seller' },
];

const newCollection: Product[] = [
    { name: 'Minimalist Backpack 20L', price: 2499, img: 'photo-1553062407-98eeb64c6a62', rating: 4.4, reviews: 41, inStock: true, tag: 'New' },
    { name: 'Ceramic Pour-Over Coffee Set', price: 1899, img: 'photo-1495774856032-8b90bbb32b32', rating: 4.9, reviews: 23, inStock: true, tag: 'New' },
    { name: 'Mechanical Keyboard RGB', price: 5499, oldPrice: 6299, img: 'photo-1587829741301-dc798b83add3', rating: 4.7, reviews: 36, inStock: true, tag: 'New' },
    { name: 'Cotton Oversized T-Shirt', price: 899, img: 'photo-1521572163474-6864f9cf17ab', rating: 4.3, reviews: 18, inStock: false, tag: 'New' },
];

// Composables
const { addToCart, buyNow } = useCart();
const { toggleWish, hasWish } = useWishlist();
const { showToast } = useToast();

// Carousel Logic
const slides = [
    'https://images.unsplash.com/photo-1441986300917-64674bd600d8?auto=format&fit=crop&w=1920&q=70',
    'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?auto=format&fit=crop&w=1920&q=70',
    'https://images.unsplash.com/photo-1556905055-8f358a7a47b2?auto=format&fit=crop&w=1920&q=70'
];

const currentSlide = ref(0);
let autoTimer: any = null;
const AUTO_MS = 5000;

const goToSlide = (index: number) => {
    currentSlide.value = (index + slides.length) % slides.length;
};

const nextSlide = () => goToSlide(currentSlide.value + 1);
const prevSlide = () => goToSlide(currentSlide.value - 1);

const startAutoPlay = () => {
    stopAutoPlay();
    autoTimer = setInterval(nextSlide, AUTO_MS);
};

const stopAutoPlay = () => {
    if (autoTimer) {
        clearInterval(autoTimer);
    }
};

// Touch swipe logic
let touchStartX = 0;
const handleTouchStart = (e: TouchEvent) => {
    touchStartX = e.changedTouches[0].screenX;
    stopAutoPlay();
};
const handleTouchEnd = (e: TouchEvent) => {
    const dx = e.changedTouches[0].screenX - touchStartX;
    if (Math.abs(dx) > 40) {
        if (dx < 0) {
            nextSlide();
        } else {
            prevSlide();
        }
    }
    startAutoPlay();
};

onMounted(() => {
    startAutoPlay();
});

onUnmounted(() => {
    stopAutoPlay();
});

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

// Newsletter Form
const newsletterEmail = ref('');
const handleNewsletterSubmit = () => {
    if (newsletterEmail.value) {
        showToast('Subscribed! Check your inbox 🎉');
        newsletterEmail.value = '';
    }
};

// Currency helper
const formatPrice = (price: number) => {
    return '৳ ' + Number(price).toLocaleString('en-BD');
};
</script>

<template>
    <Head title="ShopEase — Quality products, delivered across Bangladesh" />

    <!-- ============================ HERO CAROUSEL ============================ -->
    <section class="relative" aria-roledescription="carousel" aria-label="Featured promotions">
        <div
            @mouseenter="stopAutoPlay"
            @mouseleave="startAutoPlay"
            @touchstart="handleTouchStart"
            @touchend="handleTouchEnd"
            class="relative h-[40vh] sm:h-[55vh] lg:h-[70vh] overflow-hidden bg-gray-900"
        >
            <!-- Slides -->
            <div
                v-for="(slide, index) in slides"
                :key="index"
                :class="['slide absolute inset-0 transition-opacity duration-700 ease-in-out', { 'active': currentSlide === index }]"
                role="group"
                aria-roledescription="slide"
                :aria-label="`${index + 1} of ${slides.length}`"
            >
                <img :src="slide" alt="Featured promotion banner" class="h-full w-full object-cover opacity-85" />
            </div>

            <!-- Prev / Next controls (hidden on small screens) -->
            <button
                @click="prevSlide"
                type="button"
                aria-label="Previous slide"
                class="hidden md:flex absolute left-4 top-1/2 -translate-y-1/2 h-11 w-11 items-center justify-center rounded-full bg-white/80 text-gray-900 shadow hover:bg-white focus:outline-none focus:ring-2 focus:ring-primary-600 transition"
            >
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
            </button>
            <button
                @click="nextSlide"
                type="button"
                aria-label="Next slide"
                class="hidden md:flex absolute right-4 top-1/2 -translate-y-1/2 h-11 w-11 items-center justify-center rounded-full bg-white/80 text-gray-900 shadow hover:bg-white focus:outline-none focus:ring-2 focus:ring-primary-600 transition"
            >
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                </svg>
            </button>

            <!-- Subtle brand-tinted vignette for dot legibility -->
            <div class="pointer-events-none absolute inset-x-0 bottom-0 h-24 bg-gradient-to-t from-primary-700/40 to-transparent"></div>

            <!-- Dot indicators -->
            <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex items-center gap-2">
                <button
                    v-for="(_, index) in slides"
                    :key="index"
                    @click="goToSlide(index)"
                    type="button"
                    :aria-label="`Go to slide ${index + 1}`"
                    :aria-current="currentSlide === index ? 'true' : 'false'"
                    :class="['h-2.5 w-2.5 rounded-full transition-colors', currentSlide === index ? 'bg-white' : 'bg-white/50']"
                ></button>
            </div>
        </div>
    </section>

    <!-- ============================ TRUST STRIP ============================ -->
    <section class="border-b border-gray-200 bg-white" aria-label="Why shop with us">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 gap-4 py-6 md:grid-cols-4 md:gap-6">
                <div class="flex items-center gap-3">
                    <span class="inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-primary-50 text-primary-600">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zm10 0a2 2 0 11-4 0 2 2 0 014 0zM13 16V6a1 1 0 00-1-1H3m10 11h2.5a1 1 0 001-.7l1.4-4.2a1 1 0 00-.95-1.3H13" />
                        </svg>
                    </span>
                    <div>
                        <p class="text-sm font-semibold text-gray-900">Nationwide Delivery</p>
                        <p class="text-xs text-gray-500">Inside &amp; outside Dhaka</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <span class="inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-primary-50 text-primary-600">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8V6m0 12v-2m0-6c1.11 0 2.08.402 2.599 1M12 18c-1.11 0-2.08-.402-2.599-1" />
                        </svg>
                    </span>
                    <div>
                        <p class="text-sm font-semibold text-gray-900">Cash on Delivery</p>
                        <p class="text-xs text-gray-500">Pay when you receive</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <span class="inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-primary-50 text-primary-600">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </span>
                    <div>
                        <p class="text-sm font-semibold text-gray-900">Secure Payment</p>
                        <p class="text-xs text-gray-500">SSLCommerz protected</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <span class="inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-primary-50 text-primary-600">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 5.636a9 9 0 010 12.728m-3.536-3.536a4 4 0 010-5.656M12 12h.01" />
                        </svg>
                    </span>
                    <div>
                        <p class="text-sm font-semibold text-gray-900">Genuine Products</p>
                        <p class="text-xs text-gray-500">Quality guaranteed</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ============================ STATS ============================ -->
    <section class="border-b border-gray-200 bg-white py-10 md:py-14">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 gap-6 text-center sm:grid-cols-4">
                <div>
                    <p class="font-display text-3xl font-extrabold tracking-tight text-primary-600 md:text-4xl">50K+</p>
                    <p class="mt-1 text-sm text-gray-500">Happy customers</p>
                </div>
                <div>
                    <p class="font-display text-3xl font-extrabold tracking-tight text-primary-600 md:text-4xl">4.8<span class="text-lg text-gray-400">/5</span></p>
                    <p class="mt-1 text-sm text-gray-500">Average rating</p>
                </div>
                <div>
                    <p class="font-display text-3xl font-extrabold tracking-tight text-primary-600 md:text-4xl">64</p>
                    <p class="mt-1 text-sm text-gray-500">Districts covered</p>
                </div>
                <div>
                    <p class="font-display text-3xl font-extrabold tracking-tight text-primary-600 md:text-4xl">24/7</p>
                    <p class="mt-1 text-sm text-gray-500">Customer support</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ============================ CATEGORIES ============================ -->
    <section id="categories" class="bg-white py-12 md:py-16 lg:py-24">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mb-8 flex items-end justify-between">
                <div>
                    <h2 class="text-2xl font-display font-extrabold tracking-tight text-gray-900 md:text-3xl">Shop by category</h2>
                    <p class="mt-2 text-sm text-gray-600 md:text-base">Find exactly what you're looking for.</p>
                </div>
                <Link href="/shop" class="hidden sm:inline-flex items-center gap-1 text-sm font-medium text-primary-600 hover:text-primary-700">
                    All categories
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                </Link>
            </div>

            <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 md:gap-6 lg:grid-cols-6">
                <!-- Category tiles -->
                <Link href="/shop" class="group overflow-hidden rounded-xl border border-gray-200 bg-white transition duration-300 ease-out hover:-translate-y-1 hover:border-primary-600 hover:shadow-xl">
                    <div class="relative aspect-square overflow-hidden bg-gray-100">
                        <img src="https://images.unsplash.com/photo-1498049794561-7780e7231661?auto=format&fit=crop&w=400&q=70" alt="Electronics" loading="lazy" class="h-full w-full object-cover transition duration-500 ease-out group-hover:scale-110" />
                    </div>
                    <span class="block px-2 py-3 text-center text-sm font-medium text-gray-900 group-hover:text-primary-600 transition">Electronics</span>
                </Link>
                <Link href="/shop" class="group overflow-hidden rounded-xl border border-gray-200 bg-white transition duration-300 ease-out hover:-translate-y-1 hover:border-primary-600 hover:shadow-xl">
                    <div class="relative aspect-square overflow-hidden bg-gray-100">
                        <img src="https://images.unsplash.com/photo-1445205170230-053b83016050?auto=format&fit=crop&w=400&q=70" alt="Fashion" loading="lazy" class="h-full w-full object-cover transition duration-500 ease-out group-hover:scale-110" />
                    </div>
                    <span class="block px-2 py-3 text-center text-sm font-medium text-gray-900 group-hover:text-primary-600 transition">Fashion</span>
                </Link>
                <Link href="/shop" class="group overflow-hidden rounded-xl border border-gray-200 bg-white transition duration-300 ease-out hover:-translate-y-1 hover:border-primary-600 hover:shadow-xl">
                    <div class="relative aspect-square overflow-hidden bg-gray-100">
                        <img src="https://images.unsplash.com/photo-1556228453-efd6c1ff04f6?auto=format&fit=crop&w=400&q=70" alt="Home & Living" loading="lazy" class="h-full w-full object-cover transition duration-500 ease-out group-hover:scale-110" />
                    </div>
                    <span class="block px-2 py-3 text-center text-sm font-medium text-gray-900 group-hover:text-primary-600 transition">Home &amp; Living</span>
                </Link>
                <Link href="/shop" class="group overflow-hidden rounded-xl border border-gray-200 bg-white transition duration-300 ease-out hover:-translate-y-1 hover:border-primary-600 hover:shadow-xl">
                    <div class="relative aspect-square overflow-hidden bg-gray-100">
                        <img src="https://images.unsplash.com/photo-1596462502278-27bfdc403348?auto=format&fit=crop&w=400&q=70" alt="Beauty" loading="lazy" class="h-full w-full object-cover transition duration-500 ease-out group-hover:scale-110" />
                    </div>
                    <span class="block px-2 py-3 text-center text-sm font-medium text-gray-900 group-hover:text-primary-600 transition">Beauty</span>
                </Link>
                <Link href="/shop" class="group overflow-hidden rounded-xl border border-gray-200 bg-white transition duration-300 ease-out hover:-translate-y-1 hover:border-primary-600 hover:shadow-xl">
                    <div class="relative aspect-square overflow-hidden bg-gray-100">
                        <img src="https://images.unsplash.com/photo-1517649763962-0c623066013b?auto=format&fit=crop&w=400&q=70" alt="Sports" loading="lazy" class="h-full w-full object-cover transition duration-500 ease-out group-hover:scale-110" />
                    </div>
                    <span class="block px-2 py-3 text-center text-sm font-medium text-gray-900 group-hover:text-primary-600 transition">Sports</span>
                </Link>
                <Link href="/shop" class="group overflow-hidden rounded-xl border border-gray-200 bg-white transition duration-300 ease-out hover:-translate-y-1 hover:border-primary-600 hover:shadow-xl">
                    <div class="relative aspect-square overflow-hidden bg-gray-100">
                        <img src="https://images.unsplash.com/photo-1512820790803-83ca734da794?auto=format&fit=crop&w=400&q=70" alt="Books" loading="lazy" class="h-full w-full object-cover transition duration-500 ease-out group-hover:scale-110" />
                    </div>
                    <span class="block px-2 py-3 text-center text-sm font-medium text-gray-900 group-hover:text-primary-600 transition">Books</span>
                </Link>
            </div>
        </div>
    </section>

    <!-- ============================ BEST SELLING ============================ -->
    <section id="bestselling" class="bg-gray-100 py-12 md:py-16 lg:py-24">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mb-8 flex items-end justify-between">
                <div>
                    <h2 class="text-2xl font-display font-extrabold tracking-tight text-gray-900 md:text-3xl">Best selling</h2>
                    <p class="mt-2 text-sm text-gray-600 md:text-base">Loved most by our customers this month.</p>
                </div>
                <Link href="/shop" class="hidden sm:inline-flex items-center gap-1 text-sm font-medium text-primary-600 hover:text-primary-700">
                    View all
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                </Link>
            </div>
            <div class="grid grid-cols-2 gap-4 md:grid-cols-3 md:gap-6 lg:grid-cols-4">
                <article
                    v-for="p in bestSelling"
                    :key="p.name"
                    class="group flex flex-col overflow-hidden rounded-xl border border-gray-200 bg-white transition duration-300 ease-out hover:-translate-y-1 hover:border-primary-600 hover:shadow-xl"
                >
                    <div class="relative aspect-square overflow-hidden bg-gray-100">
                        <Link href="/product-details" :aria-label="`View ${p.name}`" class="block h-full w-full">
                            <img
                                :src="`https://images.unsplash.com/${p.img}?auto=format&fit=crop&w=600&q=70`"
                                :alt="p.name"
                                loading="lazy"
                                :class="['h-full w-full object-cover transition duration-500 ease-out group-hover:scale-110', { 'grayscale': !p.inStock }]"
                            />
                            <div class="pointer-events-none absolute inset-0 bg-gradient-to-t from-black/15 via-transparent to-transparent opacity-0 transition duration-300 group-hover:opacity-100"></div>
                        </Link>
                        <div v-if="!p.inStock" class="pointer-events-none absolute inset-0 flex items-center justify-center bg-white/30">
                            <span class="rounded-md bg-gray-900/85 px-4 py-1.5 text-xs font-semibold uppercase tracking-wide text-white shadow-md">Stock Out</span>
                        </div>
                        <div class="absolute left-2 top-2 flex flex-col items-start gap-1.5">
                            <span v-if="p.inStock" class="bg-green-50 text-green-700 text-xs font-medium px-2.5 py-1 rounded-full shadow-sm">In Stock</span>
                            <span v-if="p.tag" class="bg-accent-600 text-white text-xs font-semibold px-2.5 py-1 rounded-full shadow-sm">{{ p.tag }}</span>
                        </div>
                        <button
                            @click="handleWishlistToggle(p.name)"
                            type="button"
                            aria-label="Add to wishlist"
                            :aria-pressed="hasWish(p.name)"
                            :class="['wish-btn absolute right-2 top-2 inline-flex h-9 w-9 items-center justify-center rounded-full bg-white/90 shadow-sm transition duration-200 hover:bg-white hover:scale-110 active:scale-95 focus:outline-none focus:ring-2 focus:ring-primary-600 sm:opacity-0 sm:translate-y-1 sm:group-hover:opacity-100 sm:group-hover:translate-y-0', hasWish(p.name) ? 'text-red-600' : 'text-gray-700 hover:text-red-600']"
                        >
                            <svg class="h-5 w-5" :fill="hasWish(p.name) ? 'currentColor' : 'none'" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                            </svg>
                        </button>
                    </div>
                    <div class="flex flex-1 flex-col p-3 md:p-4">
                        <Link href="/product-details" class="block">
                            <h3 class="line-clamp-2 text-sm font-medium text-gray-900 transition-colors duration-200 group-hover:text-primary-600 md:text-base">{{ p.name }}</h3>
                        </Link>
                        <div class="mt-1.5 flex items-center gap-1">
                            <div class="flex">
                                <svg
                                    v-for="starIndex in 5"
                                    :key="starIndex"
                                    :class="['h-3.5 w-3.5', starIndex <= Math.floor(p.rating) ? 'text-accent-500' : 'text-gray-300']"
                                    fill="currentColor"
                                    viewBox="0 0 20 20"
                                >
                                    <path d="M9.05 2.927c.3-.921 1.6-.921 1.9 0l1.286 3.957a1 1 0 00.95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.368 2.447a1 1 0 00-.364 1.118l1.287 3.957c.3.922-.755 1.688-1.539 1.118l-3.366-2.447a1 1 0 00-1.176 0l-3.366 2.447c-.783.57-1.838-.196-1.539-1.118l1.287-3.957a1 1 0 00-.364-1.118L2.354 9.384c-.783-.57-.38-1.81.588-1.81h4.162a1 1 0 00.951-.69l1.286-3.957z" />
                                </svg>
                            </div>
                            <span class="text-xs text-gray-400">({{ p.reviews }})</span>
                        </div>
                        <div class="mt-2 flex items-center gap-2">
                            <span class="text-base font-semibold text-primary-600 md:text-lg">{{ formatPrice(p.price) }}</span>
                            <span v-if="p.oldPrice" class="text-xs text-gray-400 line-through">{{ formatPrice(p.oldPrice) }}</span>
                        </div>
                        <div class="mt-auto pt-3">
                            <div class="flex flex-col gap-2">
                                <button
                                    v-if="p.inStock"
                                    @click="handleAddToCart(p)"
                                    type="button"
                                    class="inline-flex w-full items-center justify-center gap-1.5 rounded-lg border border-primary-600 px-3 py-2.5 text-sm font-medium text-primary-600 hover:bg-primary-50 focus:outline-none focus:ring-2 focus:ring-primary-600 transition"
                                >
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                    Add to Cart
                                </button>
                                <button
                                    v-else
                                    disabled
                                    type="button"
                                    class="inline-flex w-full cursor-not-allowed items-center justify-center gap-1.5 rounded-lg border border-gray-200 bg-gray-50 px-3 py-2.5 text-sm font-medium text-gray-400"
                                >
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                    Add to Cart
                                </button>

                                <button
                                    v-if="p.inStock"
                                    @click="handleBuyNow(p)"
                                    type="button"
                                    class="w-full rounded-lg bg-primary-600 px-3 py-2.5 text-sm font-semibold text-white hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-600 transition"
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

    <!-- ============================ NEW COLLECTION ============================ -->
    <section id="newcollection" class="bg-white py-12 md:py-16 lg:py-24">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mb-8 flex items-end justify-between">
                <div>
                    <h2 class="text-2xl font-display font-extrabold tracking-tight text-gray-900 md:text-3xl">New collection</h2>
                    <p class="mt-2 text-sm text-gray-600 md:text-base">Just landed — be the first to grab them.</p>
                </div>
                <Link href="/shop" class="hidden sm:inline-flex items-center gap-1 text-sm font-medium text-primary-600 hover:text-primary-700">
                    View all
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                </Link>
            </div>
            <div class="grid grid-cols-2 gap-4 md:grid-cols-3 md:gap-6 lg:grid-cols-4">
                <article
                    v-for="p in newCollection"
                    :key="p.name"
                    class="group flex flex-col overflow-hidden rounded-xl border border-gray-200 bg-white transition duration-300 ease-out hover:-translate-y-1 hover:border-primary-600 hover:shadow-xl"
                >
                    <div class="relative aspect-square overflow-hidden bg-gray-100">
                        <Link href="/product-details" :aria-label="`View ${p.name}`" class="block h-full w-full">
                            <img
                                :src="`https://images.unsplash.com/${p.img}?auto=format&fit=crop&w=600&q=70`"
                                :alt="p.name"
                                loading="lazy"
                                :class="['h-full w-full object-cover transition duration-500 ease-out group-hover:scale-110', { 'grayscale': !p.inStock }]"
                            />
                            <div class="pointer-events-none absolute inset-0 bg-gradient-to-t from-black/15 via-transparent to-transparent opacity-0 transition duration-300 group-hover:opacity-100"></div>
                        </Link>
                        <div v-if="!p.inStock" class="pointer-events-none absolute inset-0 flex items-center justify-center bg-white/30">
                            <span class="rounded-md bg-gray-900/85 px-4 py-1.5 text-xs font-semibold uppercase tracking-wide text-white shadow-md">Stock Out</span>
                        </div>
                        <div class="absolute left-2 top-2 flex flex-col items-start gap-1.5">
                            <span v-if="p.inStock" class="bg-green-50 text-green-700 text-xs font-medium px-2.5 py-1 rounded-full shadow-sm">In Stock</span>
                            <span v-if="p.tag" class="bg-accent-600 text-white text-xs font-semibold px-2.5 py-1 rounded-full shadow-sm">{{ p.tag }}</span>
                        </div>
                        <button
                            @click="handleWishlistToggle(p.name)"
                            type="button"
                            aria-label="Add to wishlist"
                            :aria-pressed="hasWish(p.name)"
                            :class="['wish-btn absolute right-2 top-2 inline-flex h-9 w-9 items-center justify-center rounded-full bg-white/90 shadow-sm transition duration-200 hover:bg-white hover:scale-110 active:scale-95 focus:outline-none focus:ring-2 focus:ring-primary-600 sm:opacity-0 sm:translate-y-1 sm:group-hover:opacity-100 sm:group-hover:translate-y-0', hasWish(p.name) ? 'text-red-600' : 'text-gray-700 hover:text-red-600']"
                        >
                            <svg class="h-5 w-5" :fill="hasWish(p.name) ? 'currentColor' : 'none'" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                            </svg>
                        </button>
                    </div>
                    <div class="flex flex-1 flex-col p-3 md:p-4">
                        <Link href="/product-details" class="block">
                            <h3 class="line-clamp-2 text-sm font-medium text-gray-900 transition-colors duration-200 group-hover:text-primary-600 md:text-base">{{ p.name }}</h3>
                        </Link>
                        <div class="mt-1.5 flex items-center gap-1">
                            <div class="flex">
                                <svg
                                    v-for="starIndex in 5"
                                    :key="starIndex"
                                    :class="['h-3.5 w-3.5', starIndex <= Math.floor(p.rating) ? 'text-accent-500' : 'text-gray-300']"
                                    fill="currentColor"
                                    viewBox="0 0 20 20"
                                >
                                    <path d="M9.05 2.927c.3-.921 1.6-.921 1.9 0l1.286 3.957a1 1 0 00.95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.368 2.447a1 1 0 00-.364 1.118l1.287 3.957c.3.922-.755 1.688-1.539 1.118l-3.366-2.447a1 1 0 00-1.176 0l-3.366 2.447c-.783.57-1.838-.196-1.539-1.118l1.287-3.957a1 1 0 00-.364-1.118L2.354 9.384c-.783-.57-.38-1.81.588-1.81h4.162a1 1 0 00.951-.69l1.286-3.957z" />
                                </svg>
                            </div>
                            <span class="text-xs text-gray-400">({{ p.reviews }})</span>
                        </div>
                        <div class="mt-2 flex items-center gap-2">
                            <span class="text-base font-semibold text-primary-600 md:text-lg">{{ formatPrice(p.price) }}</span>
                            <span v-if="p.oldPrice" class="text-xs text-gray-400 line-through">{{ formatPrice(p.oldPrice) }}</span>
                        </div>
                        <div class="mt-auto pt-3">
                            <div class="flex flex-col gap-2">
                                <button
                                    v-if="p.inStock"
                                    @click="handleAddToCart(p)"
                                    type="button"
                                    class="inline-flex w-full items-center justify-center gap-1.5 rounded-lg border border-primary-600 px-3 py-2.5 text-sm font-medium text-primary-600 hover:bg-primary-50 focus:outline-none focus:ring-2 focus:ring-primary-600 transition"
                                >
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                    Add to Cart
                                </button>
                                <button
                                    v-else
                                    disabled
                                    type="button"
                                    class="inline-flex w-full cursor-not-allowed items-center justify-center gap-1.5 rounded-lg border border-gray-200 bg-gray-50 px-3 py-2.5 text-sm font-medium text-gray-400"
                                >
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                    Add to Cart
                                </button>

                                <button
                                    v-if="p.inStock"
                                    @click="handleBuyNow(p)"
                                    type="button"
                                    class="w-full rounded-lg bg-primary-600 px-3 py-2.5 text-sm font-semibold text-white hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-600 transition"
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

    <!-- ============================ TESTIMONIALS ============================ -->
    <section class="bg-gray-100 py-12 md:py-16 lg:py-24" aria-label="Customer testimonials">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mb-8 text-center">
                <h2 class="text-2xl font-display font-extrabold tracking-tight text-gray-900 md:text-3xl">What our customers say</h2>
                <p class="mt-2 text-sm text-gray-600 md:text-base">Real reviews from real ShopEase shoppers across Bangladesh.</p>
            </div>
            <div class="grid grid-cols-1 gap-4 md:grid-cols-3 md:gap-6">
                <div class="rounded-xl border border-gray-200 bg-white p-5 md:p-6 shadow-sm">
                    <div class="flex text-accent-500">
                        <svg v-for="star in 5" :key="star" class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.05 2.927c.3-.921 1.6-.921 1.9 0l1.286 3.957a1 1 0 00.95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.368 2.447a1 1 0 00-.364 1.118l1.287 3.957c.3.922-.755 1.688-1.539 1.118l-3.366-2.447a1 1 0 00-1.176 0l-3.366 2.447c-.783.57-1.838-.196-1.539-1.118l1.287-3.957a1 1 0 00-.364-1.118L2.354 9.384c-.783-.57-.38-1.81.588-1.81h4.162a1 1 0 00.951-.69l1.286-3.957z" />
                        </svg>
                    </div>
                    <p class="mt-3 text-sm text-gray-600 leading-relaxed">
                        Ordered a phone case and it arrived in two days, well before I expected. Cash on delivery made it an easy first purchase.
                    </p>
                    <div class="mt-4 flex items-center gap-3">
                        <img src="https://placehold.co/80x80/F5F3FF/7C3AED?text=RA" alt="Rina Akter" class="h-10 w-10 rounded-full object-cover border border-primary-50" />
                        <div>
                            <p class="text-sm font-semibold text-gray-900">Rina Akter</p>
                            <p class="text-xs text-gray-500">Dhaka</p>
                        </div>
                    </div>
                </div>
                <div class="rounded-xl border border-gray-200 bg-white p-5 md:p-6 shadow-sm">
                    <div class="flex text-accent-500">
                        <svg v-for="star in 5" :key="star" class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.05 2.927c.3-.921 1.6-.921 1.9 0l1.286 3.957a1 1 0 00.95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.368 2.447a1 1 0 00-.364 1.118l1.287 3.957c.3.922-.755 1.688-1.539 1.118l-3.366-2.447a1 1 0 00-1.176 0l-3.366 2.447c-.783.57-1.838-.196-1.539-1.118l1.287-3.957a1 1 0 00-.364-1.118L2.354 9.384c-.783-.57-.38-1.81.588-1.81h4.162a1 1 0 00.951-.69l1.286-3.957z" />
                        </svg>
                    </div>
                    <p class="mt-3 text-sm text-gray-600 leading-relaxed">
                        Great prices on electronics and the size guide on the clothing pages actually matches what shows up. Will keep shopping here.
                    </p>
                    <div class="mt-4 flex items-center gap-3">
                        <img src="https://placehold.co/80x80/F5F3FF/7C3AED?text=TH" alt="Tanvir Hasan" class="h-10 w-10 rounded-full object-cover border border-primary-50" />
                        <div>
                            <p class="text-sm font-semibold text-gray-900">Tanvir Hasan</p>
                            <p class="text-xs text-gray-500">Chattogram</p>
                        </div>
                    </div>
                </div>
                <div class="rounded-xl border border-gray-200 bg-white p-5 md:p-6 shadow-sm">
                    <div class="flex text-accent-500">
                        <svg v-for="star in 4" :key="star" class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.05 2.927c.3-.921 1.6-.921 1.9 0l1.286 3.957a1 1 0 00.95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.368 2.447a1 1 0 00-.364 1.118l1.287 3.957c.3.922-.755 1.688-1.539 1.118l-3.366-2.447a1 1 0 00-1.176 0l-3.366 2.447c-.783.57-1.838-.196-1.539-1.118l1.287-3.957a1 1 0 00-.364-1.118L2.354 9.384c-.783-.57-.38-1.81.588-1.81h4.162a1 1 0 00.951-.69l1.286-3.957z" />
                        </svg>
                        <svg class="h-4 w-4 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.05 2.927c.3-.921 1.6-.921 1.9 0l1.286 3.957a1 1 0 00.95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.368 2.447a1 1 0 00-.364 1.118l1.287 3.957c.3.922-.755 1.688-1.539 1.118l-3.366-2.447a1 1 0 00-1.176 0l-3.366 2.447c-.783.57-1.838-.196-1.539-1.118l1.287-3.957a1 1 0 00-.364-1.118L2.354 9.384c-.783-.57-.38-1.81.588-1.81h4.162a1 1 0 00.951-.69l1.286-3.957z" />
                        </svg>
                    </div>
                    <p class="mt-3 text-sm text-gray-600 leading-relaxed">
                        Customer support helped me change my delivery address after the order was placed. Small thing, but it made me trust the shop more.
                    </p>
                    <div class="mt-4 flex items-center gap-3">
                        <img src="https://placehold.co/80x80/F5F3FF/7C3AED?text=NJ" alt="Nusrat Jahan" class="h-10 w-10 rounded-full object-cover border border-primary-50" />
                        <div>
                            <p class="text-sm font-semibold text-gray-900">Nusrat Jahan</p>
                            <p class="text-xs text-gray-500">Sylhet</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ============================ NEWSLETTER ============================ -->
    <section class="bg-primary-600">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-12 md:py-16">
            <div class="flex flex-col items-center gap-6 text-center md:flex-row md:justify-between md:text-left">
                <div class="max-w-lg">
                    <h2 class="text-2xl font-display font-extrabold tracking-tight text-white md:text-3xl">Get 10% off your first order</h2>
                    <p class="mt-2 text-sm text-primary-50 md:text-base">Subscribe for new arrivals, exclusive deals, and offers delivered to your inbox.</p>
                </div>
                <form @submit.prevent="handleNewsletterSubmit" class="flex w-full max-w-md flex-col gap-3 sm:flex-row">
                    <label for="newsletterEmail" class="sr-only">Email address</label>
                    <input
                        id="newsletterEmail"
                        v-model="newsletterEmail"
                        type="email"
                        required
                        placeholder="Enter your email"
                        class="w-full rounded-lg border border-transparent px-4 py-2.5 text-sm text-gray-900 placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-white bg-white"
                    />
                    <button type="submit" class="shrink-0 rounded-lg bg-accent-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-accent-700 focus:outline-none focus:ring-2 focus:ring-white transition">
                        Subscribe
                    </button>
                </form>
            </div>
        </div>
    </section>
</template>

<script lang="ts">
import ShopLayout from '@/layouts/ShopLayout.vue';

export default {
    layout: ShopLayout,
};
</script>
