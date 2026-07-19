<script setup lang="ts">
import { ref, computed } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import { useCart } from '@/composables/useCart';

const {
    cart,
    cartQty,
    cartSubtotal,
    appliedCoupon,
    couponDiscount,
    cartTotal,
    incrementQty,
    decrementQty,
    removeFromCart,
    clearCart,
    applyCoupon,
    removeCoupon,
} = useCart();

const couponInput = ref('');
const couponFeedback = ref<{ type: 'success' | 'error'; text: string } | null>(null);
const deliveryZone = ref<'inside' | 'outside'>('inside');

const shippingCost = computed(() => {
    if (cart.value.length === 0) return 0;
    if (cartSubtotal.value >= 3000 || appliedCoupon.value?.code === 'FREESHIP') {
        return 0;
    }
    return deliveryZone.value === 'inside' ? 60 : 120;
});

const grandTotal = computed(() => {
    return Math.max(0, cartTotal.value + shippingCost.value);
});

const formatPrice = (price: number) => {
    return '৳ ' + Number(price).toLocaleString('en-BD');
};

const handleApplyCoupon = (codeToApply?: string) => {
    const code = codeToApply || couponInput.value;
    const res = applyCoupon(code);
    couponFeedback.value = {
        type: res.success ? 'success' : 'error',
        text: res.message,
    };
    if (res.success) {
        couponInput.value = '';
    }
};

const handleRemoveCoupon = () => {
    removeCoupon();
    couponFeedback.value = null;
};
</script>

<template>
    <Head title="Shopping Cart | ShopEase" />

    <main id="main" class="flex-1 bg-gray-50/70 py-8 sm:py-12">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <!-- Breadcrumbs -->
            <nav class="mb-6 flex" aria-label="Breadcrumb">
                <ol class="flex flex-wrap items-center gap-1 sm:gap-2 text-sm">
                    <li>
                        <Link
                            href="/"
                            class="inline-flex items-center font-medium text-gray-500 hover:text-primary-600 transition"
                        >
                            <svg class="mr-1.5 h-4 w-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                            </svg>
                            Home
                        </Link>
                    </li>
                    <li>
                        <div class="flex items-center gap-1 sm:gap-2">
                            <svg class="h-4 w-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                            <Link href="/shop" class="font-medium text-gray-500 hover:text-primary-600 transition">Shop</Link>
                        </div>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center gap-1 sm:gap-2">
                            <svg class="h-4 w-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                            <span class="font-semibold text-gray-900">Shopping Cart</span>
                        </div>
                    </li>
                </ol>
            </nav>

            <!-- Page Title -->
            <div class="mb-8 flex flex-col sm:flex-row sm:items-baseline sm:justify-between gap-4 border-b border-gray-200 pb-5">
                <div>
                    <h1 class="text-3xl font-extrabold tracking-tight text-gray-900">
                        Shopping Cart
                    </h1>
                    <p class="mt-2 text-sm text-gray-500">
                        Review your items, apply promo coupons, and proceed to checkout.
                    </p>
                </div>
                <div class="flex items-center gap-3">
                    <span class="rounded-full bg-primary-50 px-3.5 py-1 text-xs font-semibold text-primary-600 border border-primary-100">
                        {{ cartQty }} {{ cartQty === 1 ? 'item' : 'items' }}
                    </span>
                    <Link
                        href="/shop"
                        class="hidden sm:inline-flex items-center gap-1.5 text-xs font-bold text-primary-600 hover:text-primary-700 hover:underline transition"
                    >
                        <span>Continue Shopping</span>
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </Link>
                </div>
            </div>

            <!-- Empty Cart State -->
            <div
                v-if="cart.length === 0"
                class="rounded-2xl border border-dashed border-gray-300 bg-white py-16 px-4 text-center shadow-xs"
            >
                <div class="mx-auto mb-4 flex h-20 w-20 items-center justify-center rounded-full bg-primary-50 text-primary-600 border border-primary-100">
                    <svg class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <h2 class="text-2xl font-extrabold text-gray-900">Your cart is empty</h2>
                <p class="mx-auto mt-2 max-w-md text-sm text-gray-500">
                    Looks like you haven't added anything to your shopping cart yet. Browse our storefront to discover trending items!
                </p>
                <div class="mt-6">
                    <Link
                        href="/shop"
                        class="inline-flex items-center gap-2 rounded-xl bg-primary-600 px-6 py-3 text-sm font-bold text-white shadow-md shadow-primary-600/20 transition hover:bg-primary-700 focus:ring-2 focus:ring-primary-600 focus:outline-none"
                    >
                        Explore Products
                    </Link>
                </div>
            </div>

            <!-- Cart Content Grid -->
            <div v-else class="grid grid-cols-1 gap-8 lg:grid-cols-12 lg:items-start">
                <!-- Left Column: Cart Items -->
                <div class="lg:col-span-8 space-y-6">
                    <div class="rounded-2xl border border-gray-200 bg-white shadow-sm overflow-hidden">
                        <div class="border-b border-gray-200 bg-gray-50/70 px-6 py-4 flex items-center justify-between">
                            <span class="text-sm font-bold text-gray-900">Product List</span>
                            <button
                                @click="clearCart"
                                type="button"
                                class="text-xs font-semibold text-red-600 hover:text-red-700 hover:underline transition"
                            >
                                Clear All Items
                            </button>
                        </div>

                        <div class="divide-y divide-gray-100">
                            <div
                                v-for="(item, idx) in cart"
                                :key="item.name"
                                class="p-4 sm:p-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 transition hover:bg-gray-50/40"
                            >
                                <!-- Product Info -->
                                <div class="flex items-center gap-4 min-w-0 flex-1">
                                    <div class="h-20 w-20 shrink-0 overflow-hidden rounded-xl border border-gray-200 bg-gray-100">
                                        <img
                                            :src="item.img.startsWith('http') ? item.img : `https://images.unsplash.com/${item.img}?auto=format&fit=crop&w=160&q=70`"
                                            :alt="item.name"
                                            class="h-full w-full object-cover"
                                        />
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <h3 class="text-sm sm:text-base font-semibold text-gray-900 line-clamp-2">
                                            {{ item.name }}
                                        </h3>
                                        <div class="mt-1 flex items-center gap-2">
                                            <span class="text-xs font-medium text-gray-500">Unit Price:</span>
                                            <span class="text-sm font-semibold text-gray-900">{{ formatPrice(item.price) }}</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Quantity Stepper & Price Total -->
                                <div class="flex items-center justify-between w-full sm:w-auto sm:gap-6 pt-3 sm:pt-0 border-t sm:border-0 border-gray-100">
                                    <!-- Stepper -->
                                    <div class="inline-flex items-center rounded-lg border border-gray-300 bg-white shadow-2xs">
                                        <button
                                            @click="decrementQty(idx)"
                                            type="button"
                                            aria-label="Decrease quantity"
                                            :disabled="item.qty <= 1"
                                            class="inline-flex h-8 w-8 items-center justify-center rounded-l-lg text-gray-600 hover:bg-gray-100 disabled:opacity-40 transition"
                                        >
                                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M20 12H4" />
                                            </svg>
                                        </button>
                                        <span class="w-9 text-center text-sm font-semibold text-gray-900">
                                            {{ item.qty }}
                                        </span>
                                        <button
                                            @click="incrementQty(idx)"
                                            type="button"
                                            aria-label="Increase quantity"
                                            class="inline-flex h-8 w-8 items-center justify-center rounded-r-lg text-gray-600 hover:bg-gray-100 transition"
                                        >
                                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                                            </svg>
                                        </button>
                                    </div>

                                    <!-- Item Total -->
                                    <div class="text-right min-w-[5.5rem]">
                                        <span class="text-base sm:text-lg font-bold text-primary-600">
                                            {{ formatPrice(item.price * item.qty) }}
                                        </span>
                                    </div>

                                    <!-- Delete Item -->
                                    <button
                                        @click="removeFromCart(idx)"
                                        type="button"
                                        aria-label="Remove item"
                                        class="rounded-lg p-2 text-gray-400 hover:bg-red-50 hover:text-red-600 transition"
                                    >
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Available Promo Coupons Banner -->
                    <div class="rounded-2xl border border-dashed border-primary-200 bg-primary-50/50 p-4 sm:p-5">
                        <div class="flex items-start gap-3">
                            <div class="inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-primary-600 text-white shadow-xs">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                </svg>
                            </div>
                            <div>
                                <h4 class="text-xs font-bold text-gray-900 uppercase tracking-wider">Available Promo Coupons</h4>
                                <p class="mt-0.5 text-xs text-gray-500">Click any code below to automatically apply it to your order:</p>
                                <div class="mt-2.5 flex flex-wrap gap-2 text-xs">
                                    <button
                                        @click="handleApplyCoupon('WELCOME10')"
                                        type="button"
                                        class="inline-flex items-center gap-1.5 rounded-lg border border-primary-300 bg-white px-2.5 py-1 font-mono font-bold text-primary-700 hover:bg-primary-600 hover:text-white transition shadow-2xs"
                                    >
                                        <span>WELCOME10</span>
                                        <span class="text-[10px] opacity-80">(10% OFF)</span>
                                    </button>
                                    <button
                                        @click="handleApplyCoupon('SAVE20')"
                                        type="button"
                                        class="inline-flex items-center gap-1.5 rounded-lg border border-primary-300 bg-white px-2.5 py-1 font-mono font-bold text-primary-700 hover:bg-primary-600 hover:text-white transition shadow-2xs"
                                    >
                                        <span>SAVE20</span>
                                        <span class="text-[10px] opacity-80">(20% OFF)</span>
                                    </button>
                                    <button
                                        @click="handleApplyCoupon('SHOPEASE')"
                                        type="button"
                                        class="inline-flex items-center gap-1.5 rounded-lg border border-primary-300 bg-white px-2.5 py-1 font-mono font-bold text-primary-700 hover:bg-primary-600 hover:text-white transition shadow-2xs"
                                    >
                                        <span>SHOPEASE</span>
                                        <span class="text-[10px] opacity-80">(৳150 OFF)</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Order Summary Sidebar -->
                <div class="lg:col-span-4">
                    <div class="sticky top-24 rounded-2xl border border-gray-200 bg-white p-6 shadow-sm space-y-6">
                        <h2 class="text-lg font-bold text-gray-900 border-b border-gray-100 pb-4">
                            Order Summary
                        </h2>

                        <!-- Delivery Destination Selection -->
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-2">
                                Delivery Zone
                            </label>
                            <div class="grid grid-cols-2 gap-2 text-xs">
                                <button
                                    @click="deliveryZone = 'inside'"
                                    type="button"
                                    :class="deliveryZone === 'inside' ? 'border-primary-600 bg-primary-50 text-primary-900 font-bold' : 'border-gray-200 bg-white text-gray-600 hover:border-gray-300'"
                                    class="rounded-xl border p-2.5 text-center transition"
                                >
                                    Inside Dhaka
                                    <div class="text-[11px] font-normal text-gray-500 mt-0.5">৳ 60</div>
                                </button>
                                <button
                                    @click="deliveryZone = 'outside'"
                                    type="button"
                                    :class="deliveryZone === 'outside' ? 'border-primary-600 bg-primary-50 text-primary-900 font-bold' : 'border-gray-200 bg-white text-gray-600 hover:border-gray-300'"
                                    class="rounded-xl border p-2.5 text-center transition"
                                >
                                    Outside Dhaka
                                    <div class="text-[11px] font-normal text-gray-500 mt-0.5">৳ 120</div>
                                </button>
                            </div>
                        </div>

                        <!-- Coupon Code Form -->
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-2">
                                Apply Promo Code
                            </label>
                            <div v-if="appliedCoupon" class="rounded-xl border border-green-200 bg-green-50 p-3 flex items-center justify-between text-xs">
                                <div>
                                    <div class="font-bold text-green-800">{{ appliedCoupon.code }}</div>
                                    <div class="text-[11px] text-green-700 font-medium">{{ appliedCoupon.label }}</div>
                                </div>
                                <button @click="handleRemoveCoupon" type="button" class="font-semibold text-red-600 hover:underline">
                                    Remove
                                </button>
                            </div>
                            <form v-else @submit.prevent="() => handleApplyCoupon()" class="flex gap-2">
                                <input
                                    v-model="couponInput"
                                    type="text"
                                    placeholder="Enter code"
                                    class="w-full rounded-xl border border-gray-300 bg-white px-3.5 py-2.5 text-xs font-medium uppercase text-gray-900 placeholder:normal-case placeholder:text-gray-400 focus:border-primary-600 focus:ring-2 focus:ring-primary-600 focus:outline-none"
                                />
                                <button
                                    type="submit"
                                    class="rounded-xl bg-primary-600 px-4 py-2.5 text-xs font-bold text-white transition hover:bg-primary-700 shrink-0 shadow-xs"
                                >
                                    Apply
                                </button>
                            </form>
                            <p v-if="couponFeedback" :class="couponFeedback.type === 'success' ? 'text-green-600' : 'text-red-600'" class="mt-1.5 text-xs font-medium">
                                {{ couponFeedback.text }}
                            </p>
                        </div>

                        <!-- Price Breakdown -->
                        <div class="space-y-3 text-sm border-t border-gray-100 pt-4">
                            <div class="flex justify-between text-gray-600">
                                <span>Subtotal</span>
                                <span class="font-semibold text-gray-900">{{ formatPrice(cartSubtotal) }}</span>
                            </div>
                            <div v-if="couponDiscount > 0" class="flex justify-between text-green-600 font-semibold">
                                <span>Promo Discount</span>
                                <span>- {{ formatPrice(couponDiscount) }}</span>
                            </div>
                            <div class="flex justify-between text-gray-600">
                                <span>Estimated Shipping</span>
                                <span v-if="shippingCost === 0" class="font-bold text-green-600 uppercase text-xs">Free Shipping</span>
                                <span v-else class="font-semibold text-gray-900">{{ formatPrice(shippingCost) }}</span>
                            </div>
                            <div class="flex justify-between text-base font-black text-gray-900 border-t border-gray-200 pt-3">
                                <span>Total Amount</span>
                                <span class="text-xl text-primary-600">{{ formatPrice(grandTotal) }}</span>
                            </div>
                        </div>

                        <!-- Proceed to Checkout Action -->
                        <Link
                            href="/checkout"
                            class="w-full inline-flex items-center justify-center gap-2 rounded-xl bg-primary-600 px-6 py-3.5 text-center text-sm font-bold text-white shadow-md shadow-primary-600/25 transition hover:bg-primary-700 focus:ring-2 focus:ring-primary-600 focus:outline-none"
                        >
                            <span>Proceed to Checkout</span>
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </Link>

                        <!-- Trust Badges -->
                        <div class="grid grid-cols-3 gap-2 border-t border-gray-100 pt-4 text-center text-[11px] text-gray-500 font-medium">
                            <div class="p-1">
                                <svg class="mx-auto h-5 w-5 text-gray-400 mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                                100% Secure
                            </div>
                            <div class="p-1">
                                <svg class="mx-auto h-5 w-5 text-gray-400 mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                Cash on Delivery
                            </div>
                            <div class="p-1">
                                <svg class="mx-auto h-5 w-5 text-gray-400 mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                                Easy Returns
                            </div>
                        </div>
                    </div>
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
