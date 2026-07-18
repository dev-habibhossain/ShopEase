<script setup lang="ts">
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';
import { useCart } from '@/composables/useCart';
import { useWishlist } from '@/composables/useWishlist';
import { useToast } from '@/composables/useToast';

const {
    cart,
    isCartOpen,
    cartQty,
    cartSubtotal,
    closeCart,
    openCart,
    incrementQty,
    decrementQty,
    removeFromCart,
} = useCart();
const { wishCount } = useWishlist();
const { toastMessage, isToastVisible } = useToast();

const isMobileMenuOpen = ref(false);
const isCategoriesDropdownOpen = ref(false);
const isMobileCategoriesOpen = ref(false);

const formatPrice = (price: number) => {
    return '৳ ' + Number(price).toLocaleString('en-BD');
};

const openMobileMenu = () => {
    isMobileMenuOpen.value = true;
    document.body.style.overflow = 'hidden';
};

const closeMobileMenu = () => {
    isMobileMenuOpen.value = false;
    isMobileCategoriesOpen.value = false;
    document.body.style.overflow = '';
};

const currentYear = new Date().getFullYear();
</script>

<template>
    <div
        class="flex min-h-screen flex-col bg-white font-sans text-gray-600 antialiased"
    >
        <!-- Skip link for a11y -->
        <a
            href="#main"
            class="sr-only focus:not-sr-only focus:absolute focus:top-3 focus:left-3 focus:z-[100] focus:rounded-lg focus:bg-white focus:px-4 focus:py-2 focus:text-gray-900 focus:ring-2 focus:ring-primary-600"
        >
            Skip to content
        </a>

        <!-- ============================ HEADER / NAVBAR ============================ -->
        <header
            class="sticky top-0 z-50 border-b border-gray-200 bg-white/95 backdrop-blur"
        >
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex h-16 items-center justify-between gap-4">
                    <!-- Left: mobile menu button + logo -->
                    <div class="flex items-center gap-2">
                        <button
                            @click="openMobileMenu"
                            type="button"
                            aria-label="Open menu"
                            :aria-expanded="isMobileMenuOpen"
                            class="-ml-1 inline-flex h-10 w-10 items-center justify-center rounded-lg text-gray-700 hover:bg-gray-100 focus:ring-2 focus:ring-primary-600 focus:outline-none lg:hidden"
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
                                    d="M4 6h16M4 12h16M4 18h16"
                                />
                            </svg>
                        </button>

                        <Link
                            href="/"
                            class="flex items-center gap-2"
                            aria-label="ShopEase home"
                        >
                            <span
                                class="inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-primary-600 text-white"
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
                            </span>
                            <span
                                class="font-display text-xl font-extrabold tracking-tight text-gray-900 shrink-0"
                            >
                                Shop<span class="text-primary-600">Ease</span>
                            </span>
                        </Link>
                    </div>

                    <!-- Center: desktop nav -->
                    <nav
                        class="hidden items-center gap-1 lg:flex"
                        aria-label="Primary"
                    >
                        <Link
                            href="/"
                            class="rounded-lg px-3 py-2 text-sm font-medium text-gray-600 transition hover:bg-gray-100 hover:text-gray-900"
                            >Home</Link
                        >
                        <Link
                            href="/shop"
                            class="rounded-lg px-3 py-2 text-sm font-medium text-gray-600 transition hover:bg-gray-100 hover:text-gray-900"
                            >Shop</Link
                        >
                        
                        <!-- Categories Dropdown -->
                        <div
                            class="relative"
                            @mouseenter="isCategoriesDropdownOpen = true"
                            @mouseleave="isCategoriesDropdownOpen = false"
                        >
                            <button
                                type="button"
                                @click="isCategoriesDropdownOpen = !isCategoriesDropdownOpen"
                                class="inline-flex items-center gap-1 rounded-lg px-3 py-2 text-sm font-medium text-gray-600 transition hover:bg-gray-100 hover:text-gray-900"
                            >
                                <span>Categories</span>
                                <svg
                                    class="h-4 w-4 transition-transform duration-200"
                                    :class="{ 'rotate-180': isCategoriesDropdownOpen }"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                    stroke-width="2"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M19 9l-7 7-7-7"
                                    />
                                </svg>
                            </button>
                            <transition
                                enter-active-class="transition ease-out duration-200"
                                enter-from-class="opacity-0 translate-y-1"
                                enter-to-class="opacity-100 translate-y-0"
                                leave-active-class="transition ease-in duration-150"
                                leave-from-class="opacity-100 translate-y-0"
                                leave-to-class="opacity-0 translate-y-1"
                            >
                                <div
                                    v-show="isCategoriesDropdownOpen"
                                    class="absolute left-0 z-50 mt-1 w-56 origin-top-left rounded-xl border border-gray-200 bg-white p-2 shadow-xl focus:outline-none"
                                >
                                    <Link
                                        v-for="cat in ['Electronics', 'Fashion', 'Home & Living', 'Beauty', 'Sports', 'Books']"
                                        :key="cat"
                                        :href="`/shop?category=${encodeURIComponent(cat)}`"
                                        @click="isCategoriesDropdownOpen = false"
                                        class="block rounded-lg px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900"
                                    >
                                        {{ cat }}
                                    </Link>
                                </div>
                            </transition>
                        </div>

                        <Link
                            href="/help-support"
                            class="rounded-lg px-3 py-2 text-sm font-medium text-gray-600 transition hover:bg-gray-100 hover:text-gray-900"
                            >Help & Support</Link
                        >
                    </nav>

                    <!-- Desktop search -->
                    <div class="hidden max-w-md flex-1 md:block">
                        <label for="searchDesktop" class="sr-only"
                            >Search products</label
                        >
                        <div class="relative">
                            <span
                                class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-gray-400"
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
                                        d="M21 21l-4.35-4.35M11 18a7 7 0 100-14 7 7 0 000 14z"
                                    />
                                </svg>
                            </span>
                            <input
                                id="searchDesktop"
                                type="search"
                                placeholder="Search for products…"
                                class="w-full rounded-lg border border-gray-300 bg-gray-50 py-2.5 pr-4 pl-10 text-sm text-gray-900 transition placeholder:text-gray-400 focus:border-primary-600 focus:bg-white focus:ring-2 focus:ring-primary-600 focus:outline-none"
                            />
                        </div>
                    </div>

                    <!-- Right: icons -->
                    <div class="flex items-center gap-0.5 sm:gap-1">
                        <button
                            type="button"
                            aria-label="Search"
                            class="inline-flex h-10 w-10 items-center justify-center rounded-lg text-gray-700 hover:bg-gray-100 focus:ring-2 focus:ring-primary-600 focus:outline-none md:hidden"
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
                                    d="M21 21l-4.35-4.35M11 18a7 7 0 100-14 7 7 0 000 14z"
                                />
                            </svg>
                        </button>
                        <Link
                            href="/login"
                            aria-label="Account"
                            class="hidden h-10 w-10 items-center justify-center rounded-lg text-gray-700 hover:bg-gray-100 focus:ring-2 focus:ring-primary-600 focus:outline-none sm:inline-flex"
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
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                                />
                            </svg>
                        </Link>
                        <Link
                            href="/wishlist"
                            :aria-label="`Wishlist, ${wishCount} items`"
                            class="relative inline-flex h-10 w-10 items-center justify-center rounded-lg text-gray-700 hover:bg-gray-100 focus:ring-2 focus:ring-primary-600 focus:outline-none"
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
                                    d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"
                                />
                            </svg>
                            <span
                                class="absolute -top-0.5 -right-0.5 inline-flex h-5 min-w-[1.25rem] items-center justify-center rounded-full bg-accent-600 px-1 text-xs font-semibold text-white"
                            >
                                {{ wishCount }}
                            </span>
                        </Link>
                        <button
                            @click="openCart"
                            type="button"
                            :aria-label="`Cart, ${cartQty} items`"
                            class="relative inline-flex h-10 w-10 items-center justify-center rounded-lg text-gray-700 hover:bg-gray-100 focus:ring-2 focus:ring-primary-600 focus:outline-none"
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
                                    d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"
                                />
                            </svg>
                            <span
                                class="absolute -top-0.5 -right-0.5 inline-flex h-5 min-w-[1.25rem] items-center justify-center rounded-full bg-accent-600 px-1 text-xs font-semibold text-white"
                            >
                                {{ cartQty }}
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </header>

        <!-- ============================ MOBILE DRAWER ============================ -->
        <Transition name="fade">
            <div
                v-if="isMobileMenuOpen"
                @click="closeMobileMenu"
                class="fixed inset-0 z-50 bg-black/40 lg:hidden"
                aria-hidden="true"
            ></div>
        </Transition>

        <Transition name="slide-left">
            <aside
                v-if="isMobileMenuOpen"
                class="fixed inset-y-0 left-0 z-50 flex w-80 max-w-[85%] flex-col bg-white shadow-xl lg:hidden"
                role="dialog"
                aria-modal="true"
                aria-label="Menu"
            >
                <div
                    class="flex h-16 items-center justify-between border-b border-gray-200 px-4"
                >
                    <span class="text-lg font-bold text-gray-900">Menu</span>
                    <button
                        @click="closeMobileMenu"
                        type="button"
                        aria-label="Close menu"
                        class="inline-flex h-11 w-11 items-center justify-center rounded-lg text-gray-700 hover:bg-gray-100 focus:ring-2 focus:ring-primary-600 focus:outline-none"
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
                <div class="flex-1 overflow-y-auto p-4">
                    <label for="searchMobile" class="sr-only"
                        >Search products</label
                    >
                    <div class="relative mb-4">
                        <span
                            class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-gray-400"
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
                                    d="M21 21l-4.35-4.35M11 18a7 7 0 100-14 7 7 0 000 14z"
                                />
                            </svg>
                        </span>
                        <input
                            id="searchMobile"
                            type="search"
                            placeholder="Search for products…"
                            class="w-full rounded-lg border border-gray-300 bg-gray-50 py-2.5 pr-4 pl-10 text-sm focus:border-primary-600 focus:bg-white focus:ring-2 focus:ring-primary-600 focus:outline-none"
                        />
                    </div>
                    <nav class="flex flex-col" aria-label="Mobile">
                        <Link
                            href="/"
                            @click="closeMobileMenu"
                            class="rounded-lg px-3 py-3 text-base font-medium text-gray-900 hover:bg-gray-100"
                            >Home</Link
                        >
                        <Link
                            href="/shop"
                            @click="closeMobileMenu"
                            class="rounded-lg px-3 py-3 text-base font-medium text-gray-700 hover:bg-gray-100"
                            >Shop</Link
                        >
                        <!-- Collapsible Categories on Mobile -->
                        <div class="space-y-1">
                            <button
                                @click="isMobileCategoriesOpen = !isMobileCategoriesOpen"
                                class="flex w-full items-center justify-between rounded-lg px-3 py-3 text-base font-medium text-gray-700 hover:bg-gray-100"
                            >
                                <span>Categories</span>
                                <svg
                                    class="h-5 w-5 transition-transform duration-200"
                                    :class="{ 'rotate-180': isMobileCategoriesOpen }"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                    stroke-width="2"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M19 9l-7 7-7-7"
                                    />
                                </svg>
                            </button>
                            <div
                                v-show="isMobileCategoriesOpen"
                                class="pl-4 space-y-1"
                            >
                                <Link
                                    v-for="cat in ['Electronics', 'Fashion', 'Home & Living', 'Beauty', 'Sports', 'Books']"
                                    :key="cat"
                                    :href="`/shop?category=${encodeURIComponent(cat)}`"
                                    @click="closeMobileMenu"
                                    class="block rounded-lg px-3 py-2 text-sm text-gray-600 hover:bg-gray-100"
                                >
                                    {{ cat }}
                                </Link>
                            </div>
                        </div>

                        <Link
                            href="/help-support"
                            @click="closeMobileMenu"
                            class="rounded-lg px-3 py-3 text-base font-medium text-gray-700 hover:bg-gray-100"
                            >Help & Support</Link
                        >
                        <Link
                            href="/login"
                            @click="closeMobileMenu"
                            class="mt-2 rounded-lg px-3 py-3 text-base font-medium text-gray-700 hover:bg-gray-100"
                            >My Account</Link
                        >
                    </nav>
                </div>
            </aside>
        </Transition>

        <!-- ============================ MAIN CONTENT ============================ -->
        <main id="main" class="flex-grow">
            <slot />
        </main>

        <!-- ============================ FOOTER ============================ -->
        <footer class="bg-gray-900 text-gray-300 text-xs">
            <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 md:py-10 lg:px-8">
                <div class="grid grid-cols-1 gap-8 md:grid-cols-4">
                    <!-- About -->
                    <div>
                        <Link href="/" class="flex items-center gap-2">
                            <span
                                class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-primary-600 text-white"
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
                            </span>
                            <span
                                class="font-display text-xl font-extrabold tracking-tight text-white"
                            >
                                Shop<span class="text-primary-600">Ease</span>
                            </span>
                        </Link>
                        <p class="mt-2.5 text-xs leading-relaxed text-gray-400">
                            Quality products delivered across Bangladesh. Shop
                            with confidence — Cash on Delivery and secure online
                            payment available.
                        </p>
                        <div class="mt-2.5 flex gap-2">
                            <a
                                href="#"
                                aria-label="Facebook"
                                class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-white/10 transition hover:bg-primary-600"
                            >
                                <svg
                                    class="h-4 w-4"
                                    fill="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987H7.898v-2.89h2.54V9.797c0-2.507 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z"
                                    />
                                </svg>
                            </a>
                            <a
                                href="#"
                                aria-label="Instagram"
                                class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-white/10 transition hover:bg-primary-600"
                            >
                                <svg
                                    class="h-4 w-4"
                                    fill="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        d="M12 2.2c3.2 0 3.6 0 4.9.07 1.17.05 1.8.25 2.23.41.56.22.96.48 1.38.9.42.42.68.82.9 1.38.16.42.36 1.06.41 2.23.06 1.27.07 1.65.07 4.86s0 3.6-.07 4.86c-.05 1.17-.25 1.8-.41 2.23a3.7 3.7 0 01-.9 1.38c-.42.42-.82.68-1.38.9-.42.16-1.06.36-2.23.41-1.27.06-1.65.07-4.86.07s-3.6 0-4.86-.07c-1.17-.05-1.8-.25-2.23-.41a3.7 3.7 0 01-1.38-.9 3.7 3.7 0 01-.9-1.38c-.16-.42-.36-1.06-.41-2.23C2.21 15.6 2.2 15.2 2.2 12s0-3.6.07-4.86c.05-1.17.25-1.8.41-2.23.22-.56.48-.96.9-1.38.42-.42.82-.68 1.38-.9.42-.16 1.06-.36 2.23-.41C8.4 2.21 8.8 2.2 12 2.2zm0 1.8c-3.15 0-3.52.01-4.76.07-.9.04-1.39.2-1.71.32-.43.17-.74.37-1.06.69-.32.32-.52.63-.69 1.06-.12.32-.28.81-.32 1.71-.06 1.24-.07 1.61-.07 4.76s.01 3.52.07 4.76c.04.9.2 1.39.32 1.71.17.43.37.74.69 1.06.32.32.63.52 1.06.69.32.12.81.28 1.71.32 1.24.06 1.61.07 4.76.07s3.52-.01 4.76-.07c.9-.04 1.39-.2 1.71-.32.43-.17.74-.37 1.06-.69.32-.32.52-.63.69-1.06.12-.32.28-.81.32-1.71.06-1.24.07-1.61.07-4.76s-.01-3.52-.07-4.76c-.04-.9-.2-1.39-.32-1.71a2.86 2.86 0 00-.69-1.06 2.86 2.86 0 00-1.06-.69c-.32-.12-.81-.28-1.71-.32-1.24-.06-1.61-.07-4.76-.07zM12 7.4a4.6 4.6 0 100 9.2 4.6 4.6 0 000-9.2zm0 7.6a3 3 0 110-6 3 3 0 010 6zm5.85-7.8a1.08 1.08 0 11-2.15 0 1.08 1.08 0 012.15 0z"
                                    />
                                </svg>
                            </a>
                            <a
                                href="#"
                                aria-label="YouTube"
                                class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-white/10 transition hover:bg-primary-600"
                            >
                                <svg
                                    class="h-4 w-4"
                                    fill="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        d="M23.5 6.2a3 3 0 00-2.1-2.1C19.5 3.5 12 3.5 12 3.5s-7.5 0-9.4.6A3 3 0 00.5 6.2 31.3 31.3 0 000 12a31.3 31.3 0 00.5 5.8 3 3 0 002.1 2.1c1.9.6 9.4.6 9.4.6s7.5 0 9.4-.6a3 3 0 002.1-2.1A31.3 31.3 0 0024 12a31.3 31.3 0 00-.5-5.8zM9.5 15.5v-7l6.5 3.5-6.5 3.5z"
                                    />
                                </svg>
                            </a>
                        </div>
                    </div>

                    <!-- Quick Links -->
                    <div>
                        <h3
                            class="text-sm font-semibold tracking-wide text-white uppercase"
                        >
                            Quick Links
                        </h3>
                        <ul class="mt-2.5 space-y-1.5 text-xs">
                            <li>
                                <Link
                                    href="/"
                                    class="text-gray-400 transition hover:text-white"
                                    >Home</Link
                                >
                            </li>
                            <li>
                                <Link
                                    href="/shop"
                                    class="text-gray-400 transition hover:text-white"
                                    >Shop</Link
                                >
                            </li>
                            <li>
                                <Link
                                    href="/help-support"
                                    class="text-gray-400 transition hover:text-white"
                                    >Help & Support</Link
                                >
                            </li>
                        </ul>
                    </div>

                    <!-- Customer Service -->
                    <div>
                        <h3
                            class="text-sm font-semibold tracking-wide text-white uppercase"
                        >
                            Customer Service
                        </h3>
                        <ul class="mt-2.5 space-y-1.5 text-xs">
                            <li>
                                <a
                                    href="#"
                                    class="text-gray-400 transition hover:text-white"
                                    >Track Your Order</a
                                >
                            </li>
                            <li>
                                <a
                                    href="#"
                                    class="text-gray-400 transition hover:text-white"
                                    >Shipping &amp; Delivery</a
                                >
                            </li>
                            <li>
                                <a
                                    href="#"
                                    class="text-gray-400 transition hover:text-white"
                                    >Returns &amp; Refunds</a
                                >
                            </li>
                            <li>
                                <a
                                    href="#"
                                    class="text-gray-400 transition hover:text-white"
                                    >FAQ</a
                                >
                            </li>
                        </ul>
                    </div>

                    <!-- Contact -->
                    <div>
                        <h3
                            class="text-sm font-semibold tracking-wide text-white uppercase"
                        >
                            Contact
                        </h3>
                        <ul class="mt-2.5 space-y-2 text-xs">
                            <li class="flex items-start gap-2">
                                <svg
                                    class="mt-0.5 h-5 w-5 shrink-0 text-gray-400"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                    stroke-width="2"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M17.657 16.657L13.414 20.9a2 2 0 01-2.828 0l-4.243-4.243a8 8 0 1111.314 0z"
                                    />
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"
                                    />
                                </svg>
                                <span class="text-gray-400"
                                    >House 12, Road 5, Dhanmondi, Dhaka
                                    1205</span
                                >
                            </li>
                            <li class="flex items-center gap-2">
                                <svg
                                    class="h-5 w-5 shrink-0 text-gray-400"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                    stroke-width="2"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"
                                    />
                                </svg>
                                <a
                                    href="tel:+8801700000000"
                                    class="text-gray-400 transition hover:text-white"
                                    >+880 1700-000000</a
                                >
                            </li>
                            <li class="flex items-center gap-2">
                                <svg
                                    class="h-5 w-5 shrink-0 text-gray-400"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                    stroke-width="2"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
                                    />
                                </svg>
                                <a
                                    href="mailto:support@shopease.com.bd"
                                    class="text-gray-400 transition hover:text-white"
                                    >support@shopease.com.bd</a
                                >
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Bottom bar -->
                <div
                    class="mt-6 flex flex-col items-center gap-4 border-t border-white/10 pt-4 sm:flex-row sm:justify-between"
                >
                    <p class="text-xs text-gray-400 text-center sm:text-left">
                        © {{ currentYear }} ShopEase. All rights reserved.
                    </p>
                    <div class="flex flex-wrap items-center justify-center gap-3">
                        <span class="text-xs text-gray-400">We accept</span>
                        <span
                            class="rounded bg-white/10 px-2 py-1 text-xs font-medium text-gray-200"
                            >Cash on Delivery</span
                        >
                        <span
                            class="rounded bg-white/10 px-2 py-1 text-xs font-medium text-gray-200"
                            >SSLCommerz</span
                        >
                    </div>
                </div>
            </div>
        </footer>

        <!-- ============================ CART DRAWER ============================ -->
        <Transition name="fade">
            <div
                v-if="isCartOpen"
                @click="closeCart"
                class="fixed inset-0 z-[60] bg-black/40"
            ></div>
        </Transition>

        <Transition name="slide-right">
            <aside
                v-if="isCartOpen"
                class="fixed inset-y-0 right-0 z-[70] flex w-96 max-w-[90%] flex-col bg-white shadow-xl"
                role="dialog"
                aria-modal="true"
                aria-label="Shopping cart"
            >
                <div
                    class="flex h-16 shrink-0 items-center justify-between border-b border-gray-200 px-5"
                >
                    <h2 class="text-lg font-semibold text-gray-900">
                        Your Cart ({{ cartQty }})
                    </h2>
                    <button
                        @click="closeCart"
                        type="button"
                        aria-label="Close cart"
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

                <!-- Empty State -->
                <div
                    v-if="cart.length === 0"
                    class="flex flex-1 flex-col items-center justify-center px-6 text-center"
                >
                    <div
                        class="mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-gray-100 text-gray-400"
                    >
                        <svg
                            class="h-8 w-8"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="1.5"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"
                            />
                        </svg>
                    </div>
                    <p class="font-medium text-gray-900">Your cart is empty</p>
                    <p class="mt-1 text-sm text-gray-500">
                        Add items to get started.
                    </p>
                    <button
                        @click="closeCart"
                        type="button"
                        class="mt-5 rounded-lg bg-primary-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-primary-700"
                    >
                        Continue shopping
                    </button>
                </div>

                <!-- Cart Items -->
                <div v-else class="flex-1 overflow-y-auto px-5">
                    <div
                        v-for="(item, idx) in cart"
                        :key="item.name"
                        class="flex gap-3 border-b border-gray-100 py-4"
                    >
                        <div
                            class="h-16 w-16 shrink-0 overflow-hidden rounded-lg border border-gray-200 bg-gray-100"
                        >
                            <img
                                :src="`https://images.unsplash.com/${item.img}?auto=format&fit=crop&w=120&q=70`"
                                :alt="item.name"
                                class="h-full w-full object-cover"
                            />
                        </div>
                        <div class="min-w-0 flex-1">
                            <div class="flex items-start justify-between gap-2">
                                <p
                                    class="line-clamp-2 text-sm font-medium text-gray-900"
                                >
                                    {{ item.name }}
                                </p>
                                <button
                                    @click="removeFromCart(idx)"
                                    type="button"
                                    aria-label="Remove"
                                    class="shrink-0 text-gray-300 hover:text-red-600"
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
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                                        />
                                    </svg>
                                </button>
                            </div>
                            <div class="mt-2 flex items-center justify-between">
                                <div
                                    class="inline-flex items-center rounded-lg border border-gray-300"
                                >
                                    <button
                                        @click="decrementQty(idx)"
                                        type="button"
                                        aria-label="Decrease"
                                        :disabled="item.qty <= 1"
                                        class="inline-flex h-8 w-8 items-center justify-center rounded-l-lg text-gray-600 hover:bg-gray-100 disabled:opacity-40"
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
                                                d="M20 12H4"
                                            />
                                        </svg>
                                    </button>
                                    <span
                                        class="w-9 text-center text-sm font-medium text-gray-900"
                                        >{{ item.qty }}</span
                                    >
                                    <button
                                        @click="incrementQty(idx)"
                                        type="button"
                                        aria-label="Increase"
                                        class="inline-flex h-8 w-8 items-center justify-center rounded-r-lg text-gray-600 hover:bg-gray-100"
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
                                                d="M12 4v16m8-8H4"
                                            />
                                        </svg>
                                    </button>
                                </div>
                                <span
                                    class="text-sm font-semibold text-gray-900"
                                    >{{
                                        formatPrice(item.price * item.qty)
                                    }}</span
                                >
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div
                    v-if="cart.length > 0"
                    class="shrink-0 border-t border-gray-200 p-5"
                >
                    <div class="mb-1 flex items-center justify-between">
                        <span class="text-sm text-gray-500">Subtotal</span>
                        <span class="text-lg font-bold text-gray-900">{{
                            formatPrice(cartSubtotal)
                        }}</span>
                    </div>
                    <p class="mb-4 text-xs text-gray-400">
                        Delivery calculated at checkout.
                    </p>
                    <div class="grid grid-cols-2 gap-3">
                        <Link
                            href="/cart"
                            @click="closeCart"
                            class="rounded-lg border border-gray-300 px-4 py-2.5 text-center text-sm font-medium text-gray-700 transition hover:bg-gray-50"
                        >
                            View Cart
                        </Link>
                        <Link
                            href="/checkout"
                            @click="closeCart"
                            class="rounded-lg bg-primary-600 px-4 py-2.5 text-center text-sm font-semibold text-white transition hover:bg-primary-700"
                        >
                            Checkout
                        </Link>
                    </div>
                </div>
            </aside>
        </Transition>

        <!-- Toast Notification -->
        <Transition name="toast">
            <div
                v-if="isToastVisible"
                class="fixed top-20 right-4 z-[80] rounded-lg bg-green-600 px-4 py-3 text-sm font-medium text-white shadow-lg"
            >
                {{ toastMessage }}
            </div>
        </Transition>
    </div>
</template>

<style>
/* Vue Transition Animations */
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
    transition: transform 0.3s ease-in-out;
}
.slide-right-enter-from,
.slide-right-leave-to {
    transform: translateX(100%);
}

.slide-left-enter-active,
.slide-left-leave-active {
    transition: transform 0.3s ease-in-out;
}
.slide-left-enter-from,
.slide-left-leave-to {
    transform: translateX(-100%);
}

.toast-enter-active,
.toast-leave-active {
    transition: all 0.3s ease-out;
}
.toast-enter-from {
    opacity: 0;
    transform: translateY(10px);
}
.toast-leave-to {
    opacity: 0;
    transform: translateY(-10px);
}
</style>
