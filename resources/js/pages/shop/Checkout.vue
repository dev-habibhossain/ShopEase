<script setup lang="ts">
import { ref, computed } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import { useToast } from '@/composables/useToast';

const { showToast } = useToast();

const cart = ref([
    {
        name: 'Wireless Noise-Cancelling Headphones',
        price: 6499,
        qty: 1,
        img: 'Headphones',
    },
    {
        name: 'Smart Fitness Watch Series 6',
        price: 4299,
        qty: 1,
        img: 'Watch',
    },
    {
        name: 'Classic Leather Sneakers',
        price: 2999,
        qty: 2,
        img: 'Sneakers',
    },
]);

// Form Fields
const fullName = ref('');
const phone = ref('');
const email = ref('');
const district = ref('');
const area = ref('');
const address = ref('');
const notes = ref('');

// Validation Errors
const errors = ref({
    fullName: false,
    phone: false,
    email: false,
    district: false,
    area: false,
    address: false,
});

// Shipping & Payment Options
const paymentMethod = ref('cod'); // cod or stripe

// Stripe Modal State
const showStripeModal = ref(false);
const stripeEmail = ref('');
const cardholderName = ref('');
const cardNumber = ref('');
const cardExpiry = ref('');
const cardCvc = ref('');
const stripeCountry = ref('Bangladesh');

const isProcessing = ref(false);
const isOrderSuccess = ref(false);
const orderId = ref('');
const finalPaymentMethod = ref('');
const finalTotal = ref(0);

// Mobile Summary Collapse State
const showSummaryMobile = ref(false);

const cartSubtotal = computed(() => {
    return cart.value.reduce((total, item) => total + item.price * item.qty, 0);
});

const deliveryCharge = computed(() => {
    if (cart.value.length === 0) return 0;
    return district.value === 'Dhaka' ? 60 : 120;
});

const grandTotal = computed(() => {
    if (cart.value.length === 0) return 0;
    return cartSubtotal.value + deliveryCharge.value;
});

// Cart Actions
const decreaseQty = (idx: number) => {
    if (cart.value[idx].qty > 1) {
        cart.value[idx].qty--;
    }
};

const increaseQty = (idx: number) => {
    cart.value[idx].qty++;
};

const removeItem = (idx: number) => {
    const item = cart.value[idx];
    cart.value.splice(idx, 1);
    showToast(`Removed: ${item.name}`);
};

// Format Card Inputs
const handleCardNumberInput = (e: Event) => {
    const input = e.target as HTMLInputElement;
    let value = input.value.replace(/\D/g, '');
    value = value.substring(0, 16);
    const matches = value.match(/\d{4,16}/g);
    const match = (matches && matches[0]) || '';
    const parts = [];

    for (let i = 0, len = match.length; i < len; i += 4) {
        parts.push(match.substring(i, i + 4));
    }

    cardNumber.value = parts.length > 0 ? parts.join(' ') : value;
};

const handleExpiryInput = (e: Event) => {
    const input = e.target as HTMLInputElement;
    let value = input.value.replace(/\D/g, '');
    if (value.length > 2) {
        cardExpiry.value = value.substring(0, 2) + '/' + value.substring(2, 4);
    } else {
        cardExpiry.value = value;
    }
};

const handleCvcInput = (e: Event) => {
    const input = e.target as HTMLInputElement;
    cardCvc.value = input.value.replace(/\D/g, '').substring(0, 3);
};

// Detect Card Type
const cardType = computed(() => {
    const num = cardNumber.value.replace(/\s+/g, '');
    if (/^4/.test(num)) return 'visa';
    if (/^5[1-5]/.test(num)) return 'mastercard';
    if (/^3[47]/.test(num)) return 'amex';
    return 'default';
});

// Form Validation
const validateForm = () => {
    let isValid = true;
    
    // Full Name
    if (!fullName.value.trim()) {
        errors.value.fullName = true;
        isValid = false;
    } else {
        errors.value.fullName = false;
    }

    // Phone
    const cleanPhone = phone.value.replace(/\s|-/g, '');
    if (!cleanPhone || !/^01\d{9}$/.test(cleanPhone)) {
        errors.value.phone = true;
        isValid = false;
    } else {
        errors.value.phone = false;
    }

    // Email
    if (!email.value.trim() || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value)) {
        errors.value.email = true;
        isValid = false;
    } else {
        errors.value.email = false;
    }

    // District
    if (!district.value) {
        errors.value.district = true;
        isValid = false;
    } else {
        errors.value.district = false;
    }

    // Area
    if (!area.value.trim()) {
        errors.value.area = true;
        isValid = false;
    } else {
        errors.value.area = false;
    }

    // Address
    if (!address.value.trim()) {
        errors.value.address = true;
        isValid = false;
    } else {
        errors.value.address = false;
    }

    return isValid;
};

const isStripeFormValid = computed(() => {
    return (
        cardholderName.value.trim() !== '' &&
        cardNumber.value.replace(/\s+/g, '').length === 16 &&
        cardExpiry.value.length === 5 &&
        cardCvc.value.length === 3
    );
});

// Form Submit Handling
const handleFormSubmit = () => {
    if (cart.value.length === 0) {
        showToast('Your cart is empty');
        return;
    }

    if (!validateForm()) {
        showToast('Please complete the highlighted fields');
        // Scroll to first error
        const firstErrorEl = Object.keys(errors.value).find(key => errors.value[key as keyof typeof errors.value]);
        if (firstErrorEl) {
            const el = document.getElementById(firstErrorEl);
            if (el) {
                el.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }
        return;
    }

    if (paymentMethod.value === 'stripe') {
        stripeEmail.value = email.value;
        showStripeModal.value = true;
    } else {
        processDirectOrder();
    }
};

const processDirectOrder = () => {
    isProcessing.value = true;
    setTimeout(() => {
        isProcessing.value = false;
        isOrderSuccess.value = true;
        finalPaymentMethod.value = 'Cash on Delivery';
        finalTotal.value = grandTotal.value;
        orderId.value = 'SE-' + new Date().getFullYear() + '-' + Math.floor(100000 + Math.random() * 900000);
        cart.value = [];
        showToast('Order placed successfully!');
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }, 1000);
};

const submitStripePayment = () => {
    if (!isStripeFormValid.value) {
        showToast('Please fill in card details correctly.');
        return;
    }

    isProcessing.value = true;

    setTimeout(() => {
        isProcessing.value = false;
        showStripeModal.value = false;
        isOrderSuccess.value = true;
        finalPaymentMethod.value = 'Stripe (Paid)';
        finalTotal.value = grandTotal.value;
        orderId.value = 'SE-' + new Date().getFullYear() + '-' + Math.floor(100000 + Math.random() * 900000);
        cart.value = [];
        showToast('Stripe payment processed successfully!');
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }, 2000);
};

const formatPrice = (price: number) => {
    return '৳ ' + Number(price).toLocaleString('en-BD');
};
</script>

<template>
    <Head title="Checkout — ShopEase" />

    <main id="main" class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-6 md:py-10">
        <!-- Breadcrumb / steps -->
        <nav aria-label="Breadcrumb" class="mb-6">
            <ol class="flex flex-wrap items-center gap-1.5 text-sm text-gray-500">
                <li><Link href="/" class="hover:text-primary-600">Home</Link></li>
                <li aria-hidden="true" class="text-gray-300">/</li>
                <li><Link href="/shop" class="hover:text-primary-600">Cart</Link></li>
                <li aria-hidden="true" class="text-gray-300">/</li>
                <li aria-current="page" class="font-medium text-gray-900">Checkout</li>
            </ol>
        </nav>

        <!-- Success State -->
        <div v-if="isOrderSuccess" class="mx-auto max-w-2xl rounded-2xl border border-gray-200 bg-white p-8 text-center md:p-12">
            <div class="mx-auto mb-5 flex h-16 w-16 items-center justify-center rounded-full bg-green-50 text-green-600">
                <svg class="h-9 w-9" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <h1 class="text-2xl font-display font-extrabold tracking-tight text-gray-900 md:text-3xl">Thank you for your order!</h1>
            <p class="mt-2 text-sm text-gray-600 md:text-base">Your order has been placed successfully. A confirmation email is on its way.</p>

            <div class="mt-6 grid grid-cols-1 gap-3 rounded-xl bg-gray-50 p-5 text-sm sm:grid-cols-3 text-left">
                <div>
                    <p class="text-gray-400">Order number</p>
                    <p class="mt-0.5 font-semibold text-gray-900">{{ orderId }}</p>
                </div>
                <div>
                    <p class="text-gray-400">Total</p>
                    <p class="mt-0.5 font-semibold text-gray-900">{{ formatPrice(finalTotal) }}</p>
                </div>
                <div>
                    <p class="text-gray-400">Payment</p>
                    <p class="mt-0.5 font-semibold text-gray-900">{{ finalPaymentMethod }}</p>
                </div>
            </div>

            <div class="mt-8 flex flex-col gap-3 sm:flex-row sm:justify-center">
                <Link href="/shop" class="rounded-lg bg-primary-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-primary-700 transition">Continue Shopping</Link>
                <Link href="/dashboard" class="rounded-lg border border-gray-300 px-5 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 transition">View Order</Link>
            </div>
        </div>

        <!-- Checkout Form Grid -->
        <form v-else @submit.prevent="handleFormSubmit" novalidate class="grid grid-cols-1 gap-6 lg:grid-cols-3 lg:gap-8">
            
            <!-- LEFT: Shipping and Payment Details -->
            <div class="space-y-6 lg:col-span-2">
                
                <!-- Shipping Details -->
                <section class="rounded-xl border border-gray-200 bg-white p-5 md:p-6">
                    <h2 class="text-lg font-semibold text-gray-900">Shipping Details</h2>
                    <p class="mt-1 text-sm text-gray-500">Where should we deliver your order?</p>

                    <div class="mt-5 grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <!-- Full Name -->
                        <div>
                            <label for="fullName" class="mb-1.5 block text-sm font-medium text-gray-700">Full name <span class="text-red-600">*</span></label>
                            <input
                                id="fullName"
                                type="text"
                                required
                                v-model="fullName"
                                @input="errors.fullName = false"
                                class="w-full rounded-lg border px-4 py-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-2 focus:ring-primary-600 focus:outline-none"
                                :class="errors.fullName ? 'border-red-500 focus:ring-red-500' : 'border-gray-300'"
                                placeholder="e.g. Rina Akter"
                            />
                            <p v-if="errors.fullName" class="mt-1 text-sm text-red-600">Please enter your name.</p>
                        </div>

                        <!-- Phone -->
                        <div>
                            <label for="phone" class="mb-1.5 block text-sm font-medium text-gray-700">Phone <span class="text-red-600">*</span></label>
                            <input
                                id="phone"
                                type="tel"
                                required
                                v-model="phone"
                                @input="errors.phone = false"
                                class="w-full rounded-lg border px-4 py-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-2 focus:ring-primary-600 focus:outline-none"
                                :class="errors.phone ? 'border-red-500 focus:ring-red-500' : 'border-gray-300'"
                                placeholder="01XXXXXXXXX"
                            />
                            <p v-if="errors.phone" class="mt-1 text-sm text-red-600">Enter a valid 11-digit phone number.</p>
                        </div>

                        <!-- Email -->
                        <div class="sm:col-span-2">
                            <label for="email" class="mb-1.5 block text-sm font-medium text-gray-700">Email <span class="text-red-600">*</span></label>
                            <input
                                id="email"
                                type="email"
                                required
                                v-model="email"
                                @input="errors.email = false"
                                class="w-full rounded-lg border px-4 py-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-2 focus:ring-primary-600 focus:outline-none"
                                :class="errors.email ? 'border-red-500 focus:ring-red-500' : 'border-gray-300'"
                                placeholder="you@example.com"
                            />
                            <p v-if="errors.email" class="mt-1 text-sm text-red-600">Enter a valid email address.</p>
                        </div>

                        <!-- District -->
                        <div>
                            <label for="district" class="mb-1.5 block text-sm font-medium text-gray-700">District <span class="text-red-600">*</span></label>
                            <select
                                id="district"
                                v-model="district"
                                @change="errors.district = false"
                                class="w-full rounded-lg border bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-2 focus:ring-primary-600 focus:outline-none"
                                :class="errors.district ? 'border-red-500 focus:ring-red-500' : 'border-gray-300'"
                            >
                                <option value="">Select district</option>
                                <option value="Dhaka">Dhaka</option>
                                <option value="Chattogram">Chattogram</option>
                                <option value="Sylhet">Sylhet</option>
                                <option value="Khulna">Khulna</option>
                                <option value="Rajshahi">Rajshahi</option>
                                <option value="Barishal">Barishal</option>
                                <option value="Rangpur">Rangpur</option>
                                <option value="Mymensingh">Mymensingh</option>
                            </select>
                            <p v-if="errors.district" class="mt-1 text-sm text-red-600">Please select your district.</p>
                        </div>

                        <!-- Area / Thana -->
                        <div>
                            <label for="area" class="mb-1.5 block text-sm font-medium text-gray-700">Area / Thana <span class="text-red-600">*</span></label>
                            <input
                                id="area"
                                type="text"
                                required
                                v-model="area"
                                @input="errors.area = false"
                                class="w-full rounded-lg border px-4 py-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-2 focus:ring-primary-600 focus:outline-none"
                                :class="errors.area ? 'border-red-500 focus:ring-red-500' : 'border-gray-300'"
                                placeholder="e.g. Dhanmondi"
                            />
                            <p v-if="errors.area" class="mt-1 text-sm text-red-600">Please enter your area.</p>
                        </div>

                        <!-- Address -->
                        <div class="sm:col-span-2">
                            <label for="address" class="mb-1.5 block text-sm font-medium text-gray-700">Full address <span class="text-red-600">*</span></label>
                            <textarea
                                id="address"
                                rows="2"
                                required
                                v-model="address"
                                @input="errors.address = false"
                                class="w-full rounded-lg border px-4 py-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-2 focus:ring-primary-600 focus:outline-none"
                                :class="errors.address ? 'border-red-500 focus:ring-red-500' : 'border-gray-300'"
                                placeholder="House, road, and any landmark"
                            ></textarea>
                            <p v-if="errors.address" class="mt-1 text-sm text-red-600">Please enter your address.</p>
                        </div>

                        <!-- Notes -->
                        <div class="sm:col-span-2">
                            <label for="notes" class="mb-1.5 block text-sm font-medium text-gray-700">Order notes <span class="text-gray-400">(optional)</span></label>
                            <textarea
                                id="notes"
                                rows="2"
                                v-model="notes"
                                class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-2 focus:ring-primary-600 focus:outline-none"
                                placeholder="Delivery instructions, preferred time, etc."
                            ></textarea>
                        </div>
                    </div>
                </section>

                <!-- Payment Method Selection -->
                <section class="rounded-xl border border-gray-200 bg-white p-5 md:p-6">
                    <h2 class="text-lg font-semibold text-gray-900">Payment Method</h2>
                    <p class="mt-1 text-sm text-gray-500">Choose how you'd like to pay.</p>

                    <div class="mt-5 space-y-3">
                        <!-- Cash on Delivery -->
                        <label
                            class="flex cursor-pointer items-start gap-3 rounded-xl border-2 p-4 transition duration-200"
                            :class="paymentMethod === 'cod' ? 'border-primary-600 bg-primary-50' : 'border-gray-200 hover:border-gray-300'"
                        >
                            <input
                                type="radio"
                                name="payment"
                                value="cod"
                                v-model="paymentMethod"
                                class="mt-0.5 h-4 w-4 text-primary-600 focus:ring-primary-600"
                            />
                            <span class="flex-1">
                                <span class="flex items-center justify-between">
                                    <span class="text-sm font-semibold text-gray-900">Cash on Delivery</span>
                                    <svg class="h-6 w-6 text-primary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2h2m2-6h10a2 2 0 012 2v6a2 2 0 01-2 2H9a2 2 0 01-2-2v-6a2 2 0 012-2zm7 5a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                </span>
                                <span class="mt-1 block text-sm text-gray-500">Pay with cash when your order arrives.</span>
                            </span>
                        </label>

                        <!-- Stripe Pay Online -->
                        <label
                            class="flex cursor-pointer items-start gap-3 rounded-xl border-2 p-4 transition duration-200"
                            :class="paymentMethod === 'stripe' ? 'border-primary-600 bg-primary-50' : 'border-gray-200 hover:border-gray-300'"
                        >
                            <input
                                type="radio"
                                name="payment"
                                value="stripe"
                                v-model="paymentMethod"
                                class="mt-0.5 h-4 w-4 text-primary-600 focus:ring-primary-600"
                            />
                            <span class="flex-1">
                                <span class="flex items-center justify-between">
                                    <span class="text-sm font-semibold text-gray-900">Pay Online (Stripe)</span>
                                    <svg class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                    </svg>
                                </span>
                                <span class="mt-1 block text-sm text-gray-500">Visa, Mastercard, Amex & more via Stripe's secure gateway.</span>
                                <span class="mt-2 flex flex-wrap gap-1.5">
                                    <span class="rounded bg-gray-100 px-2 py-0.5 text-[11px] font-medium text-gray-600">VISA</span>
                                    <span class="rounded bg-gray-100 px-2 py-0.5 text-[11px] font-medium text-gray-600">Mastercard</span>
                                    <span class="rounded bg-gray-100 px-2 py-0.5 text-[11px] font-medium text-gray-600">Amex</span>
                                    <span class="rounded bg-gray-100 px-2 py-0.5 text-[11px] font-medium text-gray-600">Google Pay</span>
                                </span>
                            </span>
                        </label>
                    </div>
                </section>
            </div>

            <!-- RIGHT: Order Summary Sidebar -->
            <div class="lg:col-span-1">
                <div class="lg:sticky lg:top-24 rounded-xl border border-gray-200 bg-white">
                    <!-- Header (toggles items on mobile) -->
                    <button
                        type="button"
                        @click="showSummaryMobile = !showSummaryMobile"
                        class="flex w-full items-center justify-between gap-2 p-5 text-left lg:cursor-default"
                    >
                        <span class="text-lg font-semibold text-gray-900">Order Summary</span>
                        <span class="flex items-center gap-2">
                            <span class="rounded-full bg-primary-50 px-2.5 py-0.5 text-xs font-medium text-primary-700">
                                {{ cart.reduce((s, i) => s + i.qty, 0) }} items
                            </span>
                            <svg
                                class="h-5 w-5 text-gray-400 lg:hidden transform transition-transform duration-200"
                                :class="showSummaryMobile ? 'rotate-180' : ''"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="2"
                            >
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </span>
                    </button>

                    <!-- Items + totals (always open on desktop, collapsible on mobile) -->
                    <div :class="showSummaryMobile ? 'block' : 'hidden lg:block'" class="border-t border-gray-100">
                        <ul class="divide-y divide-gray-100 px-5">
                            <li v-if="cart.length === 0" class="py-8 text-center text-sm text-gray-400">
                                Your cart is empty. <Link href="/shop" class="font-medium text-primary-600 hover:underline">Continue shopping</Link>
                            </li>
                            <li
                                v-for="(item, idx) in cart"
                                :key="idx"
                                class="flex gap-3 py-4"
                            >
                                <div class="h-14 w-14 shrink-0 overflow-hidden rounded-lg border border-gray-200 bg-gray-100">
                                    <img :src="`https://placehold.co/120x120/F5F3FF/7C3AED?text=${encodeURIComponent(item.img)}`" :alt="item.name" class="h-full w-full object-cover" />
                                </div>
                                <div class="min-w-0 flex-1">
                                    <div class="flex items-start justify-between gap-2">
                                        <p class="line-clamp-1 text-sm font-medium text-gray-900">{{ item.name }}</p>
                                        <button
                                            type="button"
                                            @click="removeItem(idx)"
                                            :aria-label="`Remove ${item.name}`"
                                            class="shrink-0 text-gray-300 hover:text-red-600 focus:outline-none focus:ring-2 focus:ring-primary-600 rounded transition"
                                        >
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </div>
                                    <p class="mt-0.5 text-xs text-gray-400">{{ formatPrice(item.price) }} each</p>
                                    
                                    <div class="mt-2 flex items-center justify-between">
                                        <div class="inline-flex items-center rounded-lg border border-gray-300 bg-white">
                                            <button
                                                type="button"
                                                @click="decreaseQty(idx)"
                                                :disabled="item.qty <= 1"
                                                class="inline-flex h-8 w-8 items-center justify-center text-gray-600 hover:bg-gray-100 rounded-l-lg focus:outline-none focus:ring-2 focus:ring-primary-600 disabled:opacity-40 disabled:hover:bg-transparent"
                                            >
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 12H4"/>
                                                </svg>
                                            </button>
                                            <span class="w-9 text-center text-sm font-medium text-gray-900">{{ item.qty }}</span>
                                            <button
                                                type="button"
                                                @click="increaseQty(idx)"
                                                class="inline-flex h-8 w-8 items-center justify-center text-gray-600 hover:bg-gray-100 rounded-r-lg focus:outline-none focus:ring-2 focus:ring-primary-600"
                                            >
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                                                </svg>
                                            </button>
                                        </div>
                                        <span class="text-sm font-semibold text-gray-900">{{ formatPrice(item.price * item.qty) }}</span>
                                    </div>
                                </div>
                            </li>
                        </ul>

                        <!-- Totals Section -->
                        <div class="space-y-2.5 border-t border-gray-100 px-5 py-4 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-500">Subtotal</span>
                                <span class="font-medium text-gray-900">{{ formatPrice(cartSubtotal) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">
                                    Delivery <span v-if="district" class="text-gray-400">{{ district === 'Dhaka' ? '(Inside Dhaka)' : '(Outside Dhaka)' }}</span>
                                </span>
                                <span class="font-medium text-gray-900">{{ formatPrice(deliveryCharge) }}</span>
                            </div>
                            <div class="mt-2 flex justify-between border-t border-gray-100 pt-3 text-base">
                                <span class="font-semibold text-gray-900">Total</span>
                                <span class="font-bold text-primary-600">{{ formatPrice(grandTotal) }}</span>
                            </div>
                        </div>

                        <!-- Action Submit Button -->
                        <div class="px-5 pb-5">
                            <button
                                type="submit"
                                :disabled="cart.length === 0 || isProcessing"
                                class="w-full rounded-lg bg-gradient-to-r from-primary-600 to-primary-700 px-5 py-3 text-sm font-semibold text-white shadow-sm hover:shadow-md hover:from-primary-700 hover:to-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-600 transition disabled:opacity-50 disabled:cursor-not-allowed"
                            >
                                <span v-if="isProcessing" class="inline-flex items-center gap-2">
                                    <svg class="h-4 w-4 animate-spin text-white" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Processing...
                                </span>
                                <span v-else>
                                    {{ paymentMethod === 'stripe' ? 'Proceed to Payment' : 'Place Order' }}
                                </span>
                            </button>
                            <p class="mt-3 flex items-center justify-center gap-1.5 text-xs text-gray-400">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                                Your information is safe & encrypted
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <!-- Simulated Stripe Elements Modal Overlay -->
        <Transition name="fade">
            <div v-if="showStripeModal" class="fixed inset-0 z-50 flex items-center justify-center bg-gray-950/80 p-4 backdrop-blur-sm">
                <div class="w-full max-w-md overflow-hidden rounded-2xl bg-white shadow-2xl transition-all border border-gray-100">
                    
                    <!-- Stripe Modal Header -->
                    <div class="bg-gray-900 px-6 py-5 text-white flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <svg class="h-5 w-12 text-white" viewBox="0 0 80 32" fill="currentColor">
                                <path d="M48.8 12.4c0-2.8-2.2-3.8-5.3-3.8-4.3 0-7.3 1.7-7.3 5.4 0 4.6 6.3 3.9 6.3 5.9 0 .8-.7 1.3-1.7 1.3-1.6 0-3.6-.7-3.6-2l-3.2.3c.3 3.2 3.6 4.3 6.8 4.3 4.3 0 7.4-1.8 7.4-5.6 0-4.8-6.4-3.9-6.4-5.9 0-.7.7-1.1 1.7-1.1 1.4 0 3 .5 3 1.3l3.3-.2zm24.6 2.6c0-4.4-3-6.4-7-6.4-4.8 0-7.6 2.8-7.6 7.4 0 5 3.3 6.8 7.8 6.8 1.9 0 3.8-.4 4.8-.9l-.6-2.5c-.8.3-2.2.6-3.8.6-2.7 0-4.8-.8-4.8-3.9h11c.1-.8.2-2.1.2-3.6zm-11.2-1.3c0-2 1.4-2.8 3.5-2.8 1.9 0 3.2.7 3.2 2.8H62.2zm-25.5.9V9h-3.4v13.5h3.4v-4.1c1-.8 2-1.1 2.8-1.1.2 0 .4 0 .5.1V8.6c-.6 0-1.8.4-3.3 1.9zm17.6 12.9V4.2h-3.4V9c-.8-1-2.2-1.6-3.8-1.6-3.8 0-6.7 3-6.7 7.7 0 4.8 3 7.8 6.7 7.8 1.6 0 3-.6 3.8-1.6v4.6h3.4zm-9.8-8.8c0-3.2 2-4.9 4.3-4.9s4.3 1.7 4.3 4.9c0 3.3-2 5-4.3 5s-4.3-1.7-4.3-5zM27 9V4.2h-3.4V9h-2.1v2.8h2.1v6.9c0 2.2 1.4 3.4 3.7 3.4 1 0 1.8-.2 2.3-.4v-2.8c-.3.1-.7.2-1.1.2-1 0-1.5-.5-1.5-1.5v-5.8h2.6V9H27z"/>
                            </svg>
                            <span class="text-[10px] font-bold tracking-wider text-gray-500 uppercase">Secure Checkout</span>
                        </div>
                        <button
                            @click="showStripeModal = false"
                            type="button"
                            class="text-gray-400 hover:text-white transition focus:outline-none"
                        >
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <!-- Stripe Modal Content -->
                    <form @submit.prevent="submitStripePayment" class="p-6 space-y-6">
                        <div>
                            <div class="flex justify-between text-xs text-gray-500 font-bold uppercase tracking-wider">
                                <span>Amount to Pay</span>
                                <span class="text-base font-extrabold text-gray-900">{{ formatPrice(grandTotal) }}</span>
                            </div>
                        </div>

                        <!-- Stripe input elements -->
                        <div class="space-y-4">
                            <div>
                                <label for="stripeEmail" class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider">Email</label>
                                <input
                                    id="stripeEmail"
                                    type="email"
                                    required
                                    v-model="stripeEmail"
                                    class="mt-1.5 w-full rounded-xl border border-gray-200 px-4 py-3 text-sm text-gray-900 transition focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 focus:outline-none"
                                />
                            </div>

                            <div>
                                <label for="stripeCardholder" class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider">Cardholder Name</label>
                                <input
                                    id="stripeCardholder"
                                    type="text"
                                    required
                                    v-model="cardholderName"
                                    placeholder="Rina Akter"
                                    class="mt-1.5 w-full rounded-xl border border-gray-200 px-4 py-3 text-sm text-gray-900 transition focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 focus:outline-none"
                                />
                            </div>

                            <div>
                                <label for="stripeCardNumber" class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider">Card Information</label>
                                <div class="relative mt-1.5">
                                    <input
                                        id="stripeCardNumber"
                                        type="text"
                                        required
                                        :value="cardNumber"
                                        @input="handleCardNumberInput"
                                        placeholder="4242 4242 4242 4242"
                                        class="w-full rounded-t-xl border border-gray-200 px-4 py-3 pr-10 text-sm text-gray-900 transition focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 focus:outline-none"
                                    />
                                    <span class="absolute inset-y-0 right-4 flex items-center">
                                        <svg v-if="cardType === 'visa'" class="h-4 w-6 rounded border border-gray-200" viewBox="0 0 24 15" fill="none">
                                            <path d="M0 0h24v15H0V0z" fill="#1A1F71"/><path d="M8.8 11.2l.9-5.6H8.2l-.9 5.6h1.5zm4.8-5.3c-.3-.4-.9-.6-1.7-.6-1.5 0-2.6.8-2.6 1.9 0 1.2 1.1 1.3 1.9 1.7.5.2.7.5.7.8 0 .5-.6.7-1.1.7-.8 0-1.4-.2-1.8-.4l-.2-.1-.2 1.2c.4.2 1.1.3 1.8.3 1.6 0 2.7-.8 2.7-2 0-1.3-1.1-1.4-1.9-1.8-.5-.2-.7-.4-.7-.7 0-.4.5-.6.9-.6.7 0 1.2.2 1.5.3l.2.1.2-1.2h-.1zm3.8 2.1c0-.8-.5-1.4-1.3-1.8l-1.3 5h1.5l.3-.8h1.8c0 .8 0 0 0 0l-.3-1.4c-.1-.3-.3-.7-.7-.8v-.2zM15 8.3h.9l-.5 2.1h-.9V8.3z" fill="#fff"/>
                                        </svg>
                                        <svg v-else-if="cardType === 'mastercard'" class="h-4 w-6 rounded border border-gray-200" viewBox="0 0 24 15" fill="none">
                                            <rect width="24" height="15" rx="1" fill="#3F3F3F"/><circle cx="10" cy="7.5" r="4.5" fill="#EB001B"/><circle cx="14" cy="7.5" r="4.5" fill="#F79E1B" fill-opacity="0.8"/>
                                        </svg>
                                        <span v-else class="text-[8px] font-bold text-gray-400 uppercase tracking-widest">Card</span>
                                    </span>
                                </div>
                                <div class="grid grid-cols-2">
                                    <input
                                        type="text"
                                        required
                                        :value="cardExpiry"
                                        @input="handleExpiryInput"
                                        placeholder="MM / YY"
                                        class="-mt-px w-full rounded-bl-xl border border-gray-200 px-4 py-3 text-sm text-gray-900 transition focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 focus:outline-none"
                                    />
                                    <input
                                        type="text"
                                        required
                                        :value="cardCvc"
                                        @input="handleCvcInput"
                                        placeholder="CVC"
                                        class="-mt-px -ml-px w-full rounded-br-xl border border-gray-200 px-4 py-3 text-sm text-gray-900 transition focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 focus:outline-none"
                                    />
                                </div>
                            </div>

                            <div>
                                <label for="stripeCountry" class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider">Country or Region</label>
                                <select
                                    id="stripeCountry"
                                    v-model="stripeCountry"
                                    class="mt-1.5 w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm text-gray-900 transition focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 focus:outline-none"
                                >
                                    <option value="Bangladesh">Bangladesh</option>
                                    <option value="United States">United States</option>
                                    <option value="United Kingdom">United Kingdom</option>
                                    <option value="Canada">Canada</option>
                                </select>
                            </div>
                        </div>

                        <!-- Stripe Pay Button -->
                        <button
                            type="submit"
                            :disabled="!isStripeFormValid || isProcessing"
                            class="w-full flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-primary-600 to-primary-700 px-5 py-4 text-sm font-semibold text-white shadow-md hover:from-primary-700 hover:to-primary-700 transition active:scale-[0.98] disabled:bg-gray-300 disabled:cursor-not-allowed"
                        >
                            <svg v-if="isProcessing" class="h-4 w-4 animate-spin text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span v-else class="flex items-center gap-1.5">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                                Pay {{ formatPrice(grandTotal) }}
                            </span>
                        </button>
                        
                        <div class="text-center">
                            <span class="text-[9px] text-gray-400 font-bold uppercase tracking-widest">
                                Secured with <span class="text-primary-600 font-black">stripe</span>
                            </span>
                        </div>
                    </form>
                </div>
            </div>
        </Transition>
    </main>
</template>

<script lang="ts">
import ShopLayout from '@/layouts/ShopLayout.vue';

export default {
    layout: ShopLayout,
};
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.3s ease;
}
.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style>
