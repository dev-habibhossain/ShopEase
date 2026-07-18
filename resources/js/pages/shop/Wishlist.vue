<script setup lang="ts">
import { ref } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import { useCart } from '@/composables/useCart';
import { useToast } from '@/composables/useToast';

const { addToCart } = useCart();
const { showToast } = useToast();

const wishlistItems = ref([
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
    },
]);

const formatPrice = (price: number) => {
    return '৳ ' + Number(price).toLocaleString('en-BD');
};

const handleAddToCart = (product: any) => {
    addToCart(product.name, product.price, product.img, 1);
    showToast('Added to cart successfully!');
};

const handleRemove = (name: string) => {
    wishlistItems.value = wishlistItems.value.filter((p) => p.name !== name);
    showToast('Removed from wishlist');
};
</script>

<template>
    <Head title="My Wishlist | ShopEase" />

    <main id="main" class="flex-1 bg-gray-50 py-8 sm:py-12">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <!-- Breadcrumbs -->
            <nav class="mb-6 flex" aria-label="Breadcrumb">
                <ol class="flex flex-wrap items-center gap-1 sm:gap-2">
                    <li class="inline-flex items-center">
                        <Link
                            href="/"
                            class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-primary-600"
                        >
                            <svg
                                class="mr-2 h-4 w-4"
                                fill="currentColor"
                                viewBox="0 0 20 20"
                            >
                                <path
                                    d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"
                                />
                            </svg>
                            Home
                        </Link>
                    </li>
                    <li>
                        <div class="flex items-center gap-1 sm:gap-2">
                            <svg
                                class="h-5 w-5 text-gray-400"
                                fill="currentColor"
                                viewBox="0 0 20 20"
                            >
                                <path
                                    fill-rule="evenodd"
                                    d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                    clip-rule="evenodd"
                                />
                            </svg>
                            <Link
                                href="/shop"
                                class="text-sm font-medium text-gray-500 hover:text-primary-600"
                                >Shop</Link
                            >
                        </div>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center gap-1 sm:gap-2">
                            <svg
                                class="h-5 w-5 text-gray-400"
                                fill="currentColor"
                                viewBox="0 0 20 20"
                            >
                                <path
                                    fill-rule="evenodd"
                                    d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                    clip-rule="evenodd"
                                />
                            </svg>
                            <span
                                class="text-sm font-medium text-gray-800"
                                >Wishlist</span
                            >
                        </div>
                    </li>
                </ol>
            </nav>

            <!-- Page Title -->
            <div class="mb-8 flex flex-col sm:flex-row sm:items-baseline sm:justify-between gap-4 border-b border-gray-200 pb-5">
                <div>
                    <h1 class="text-3xl font-extrabold tracking-tight text-gray-900">
                        My Wishlist
                    </h1>
                    <p class="mt-2 text-sm text-gray-500">
                        Manage your favorite items and add them to your cart.
                    </p>
                </div>
                <span class="self-start sm:self-auto rounded-full bg-primary-50 px-3 py-1 text-xs font-semibold text-primary-600">
                    {{ wishlistItems.length }} {{ wishlistItems.length === 1 ? 'item' : 'items' }}
                </span>
            </div>

            <!-- Wishlist Grid -->
            <div v-if="wishlistItems.length > 0">
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
                    <article
                        v-for="p in wishlistItems"
                        :key="p.id"
                        class="group relative flex flex-col overflow-hidden rounded-xl border border-gray-200 bg-white transition hover:shadow-lg"
                    >
                        <!-- Image section -->
                        <div class="relative aspect-square overflow-hidden bg-gray-100">
                            <img
                                :src="`https://images.unsplash.com/${p.img}?w=500&auto=format&fit=crop&q=60`"
                                :alt="p.name"
                                class="h-full w-full object-cover object-center transition duration-500 group-hover:scale-105"
                                loading="lazy"
                            />
                            
                            <!-- Badges -->
                            <div v-if="p.tag" class="absolute top-3 left-3 z-10">
                                <span
                                    class="rounded-full bg-primary-600 px-2.5 py-1 text-[10px] font-bold tracking-wider text-white uppercase shadow-sm"
                                >
                                    {{ p.tag }}
                                </span>
                            </div>

                            <!-- Remove from wishlist -->
                            <button
                                @click="handleRemove(p.name)"
                                type="button"
                                aria-label="Remove from wishlist"
                                class="absolute top-3 right-3 z-10 flex h-8 w-8 items-center justify-center rounded-full bg-white text-gray-400 shadow-sm transition hover:bg-red-50 hover:text-red-500 focus:outline-none"
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
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                                    />
                                </svg>
                            </button>
                        </div>

                        <!-- Info details -->
                        <div class="flex flex-1 flex-col p-4">
                            <span class="text-xs font-semibold tracking-wider text-gray-400 uppercase">
                                {{ p.category }}
                            </span>
                            <h3 class="mt-1 text-sm font-semibold text-gray-900 line-clamp-2 min-h-[40px]">
                                <Link :href="`/product-details/${p.slug}`" class="hover:text-primary-600">
                                    {{ p.name }}
                                </Link>
                            </h3>

                            <!-- Rating -->
                            <div class="mt-2 flex items-center gap-1">
                                <div class="flex items-center text-amber-400">
                                    <svg
                                        v-for="star in 5"
                                        :key="star"
                                        class="h-3.5 w-3.5"
                                        :class="star <= Math.round(p.rating) ? 'fill-current' : 'stroke-current fill-none'"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                        stroke-width="2"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.908c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.908a1 1 0 00.95-.69l1.519-4.674z"
                                        />
                                    </svg>
                                </div>
                                <span class="text-xs text-gray-500 font-medium">({{ p.reviews }})</span>
                            </div>

                            <div class="mt-3 flex items-center gap-2">
                                <span class="text-base font-semibold text-primary-600">
                                    {{ formatPrice(p.price) }}
                                </span>
                                <span v-if="p.oldPrice" class="text-xs text-gray-400 line-through">
                                    {{ formatPrice(p.oldPrice) }}
                                </span>
                            </div>

                            <!-- Cart add action -->
                            <div class="mt-4 pt-3 border-t border-gray-100">
                                <button
                                    v-if="p.inStock"
                                    @click="handleAddToCart(p)"
                                    type="button"
                                    class="inline-flex w-full items-center justify-center gap-1.5 rounded-lg border border-primary-600 px-3 py-2 text-xs font-semibold text-primary-600 transition hover:bg-primary-50 focus:ring-2 focus:ring-primary-600 focus:outline-none"
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
                                    class="w-full cursor-not-allowed rounded-lg border border-gray-200 bg-gray-50 px-3 py-2 text-xs font-semibold text-gray-400"
                                >
                                    Out of Stock
                                </button>
                            </div>
                        </div>
                    </article>
                </div>
            </div>

            <!-- Empty State -->
            <div v-else class="rounded-2xl border border-dashed border-gray-300 bg-white py-16 px-4 text-center">
                <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-red-50 text-red-500 animate-pulse">
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
                            d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"
                        />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900">Your wishlist is empty</h3>
                <p class="mx-auto mt-2 max-w-sm text-sm text-gray-500">
                    Explore our collection and add products to your wishlist to buy them later.
                </p>
                <div class="mt-6">
                    <Link
                        href="/shop"
                        class="inline-flex items-center gap-2 rounded-lg bg-primary-600 px-5 py-3 text-sm font-bold text-white shadow transition hover:bg-primary-700 focus:ring-2 focus:ring-primary-600 focus:outline-none"
                    >
                        Explore Catalog
                    </Link>
                </div>
            </div>
        </div>
    </main>
</template>

<script lang="ts">
import ShopLayout from '@/layouts/ShopLayout.vue';

export default {
    layout: ShopLayout,
};
</script>
