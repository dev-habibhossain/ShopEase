<script setup lang="ts">
import { ref, watch } from 'vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';

interface OrderItem {
    id: number;
    product_name: string;
    product_slug?: string | null;
    image?: string | null;
    unit_price: number;
    quantity: number;
    line_total: number;
}

interface OrderDetails {
    id: number;
    order_number: string;
    customer_name: string;
    phone: string;
    email: string;
    district: string;
    area: string;
    address: string;
    notes?: string;
    subtotal: number;
    delivery_charge: number;
    discount_amount: number;
    total: number;
    payment_method: string;
    payment_status: string;
    status: string;
    status_raw: string;
    placed_at: string;
    items: OrderItem[];
}

interface QuickOrder {
    id: number;
    order_number: string;
    total: number;
    status: string;
    placed_at: string;
}

const props = defineProps<{
    order?: OrderDetails | null;
    userRecentOrders?: QuickOrder[];
    searchedQuery?: string;
    errorMessage?: string | null;
}>();

const page = usePage();
const searchInput = ref(props.searchedQuery || '');
const copiedNotice = ref(false);

watch(
    () => props.searchedQuery || props.order?.order_number,
    (newVal) => {
        if (newVal) searchInput.value = newVal;
    },
    { immediate: true }
);

const handleSearch = () => {
    if (!searchInput.value.trim()) return;
    router.get('/track-order', { search: searchInput.value.trim() }, { preserveState: false });
};

const selectRecentOrder = (orderNum: string) => {
    searchInput.value = orderNum;
    router.get('/track-order', { order_number: orderNum }, { preserveState: false });
};

const copyOrderNumber = () => {
    if (!props.order?.order_number) return;
    navigator.clipboard.writeText(props.order.order_number);
    copiedNotice.value = true;
    setTimeout(() => {
        copiedNotice.value = false;
    }, 2000);
};

const formatPrice = (price: number) => {
    return '৳ ' + Number(price).toLocaleString('en-BD');
};

const getItemImg = (img?: string | object | null) => {
    let imgPath = '';
    if (typeof img === 'object' && img !== null) {
        imgPath = (img as any).image_path || (img as any).url || '';
    } else if (typeof img === 'string') {
        imgPath = img;
    }
    if (!imgPath) return 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?auto=format&fit=crop&w=120&q=70';
    if (imgPath.startsWith('http') || imgPath.startsWith('/storage/')) return imgPath;
    return `https://images.unsplash.com/${imgPath}?auto=format&fit=crop&w=120&q=70`;
};

// Status progression steps calculation
const getStepStatus = (step: string) => {
    const current = props.order?.status_raw || 'pending';
    const orderSteps = ['pending', 'processing', 'shipped', 'delivered'];
    const currentIndex = orderSteps.indexOf(current);
    const stepIndex = orderSteps.indexOf(step);

    if (current === 'cancelled') return 'cancelled';
    if (stepIndex < currentIndex) return 'completed';
    if (stepIndex === currentIndex) return 'current';
    return 'upcoming';
};
</script>

<template>
    <Head title="Track Order — ShopEase" />

    <div class="bg-gray-50/50 min-h-screen py-8 md:py-12">
        <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
            <!-- Breadcrumb -->
            <nav aria-label="Breadcrumb" class="mb-6">
                <ol class="flex flex-wrap items-center gap-1.5 text-sm text-gray-500">
                    <li><Link href="/" class="hover:text-primary-600 transition">Home</Link></li>
                    <li aria-hidden="true" class="text-gray-300">/</li>
                    <li aria-current="page" class="font-medium text-gray-900">Track Order</li>
                </ol>
            </nav>

            <!-- Search Header Card -->
            <div class="rounded-2xl border border-gray-200 bg-white p-6 sm:p-8 shadow-sm mb-8">
                <div class="text-center max-w-xl mx-auto mb-6">
                    <div class="mx-auto mb-3 flex h-12 w-12 items-center justify-center rounded-xl bg-primary-50 text-primary-600">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                        </svg>
                    </div>
                    <h1 class="text-2xl font-display font-extrabold text-gray-900 sm:text-3xl">Track Your Order</h1>
                    <p class="mt-2 text-sm text-gray-600">Enter your Order Number (e.g. SE-2026-123456) or your phone number to check live delivery status.</p>
                </div>

                <form @submit.prevent="handleSearch" class="flex flex-col sm:flex-row gap-3 max-w-xl mx-auto">
                    <div class="relative flex-1">
                        <input
                            v-model="searchInput"
                            type="text"
                            placeholder="Enter Order Number or Phone / Email"
                            class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm text-gray-900 shadow-sm placeholder:text-gray-400 focus:border-primary-600 focus:ring-2 focus:ring-primary-600 focus:outline-none"
                        />
                    </div>
                    <button
                        type="submit"
                        class="inline-flex items-center justify-center rounded-xl bg-primary-600 px-6 py-3 text-sm font-semibold text-white shadow-sm hover:bg-primary-700 transition focus:outline-none focus:ring-2 focus:ring-primary-600"
                    >
                        Track Status
                    </button>
                </form>

                <!-- Error message -->
                <div v-if="errorMessage" class="mt-4 max-w-xl mx-auto rounded-xl bg-red-50 p-4 text-sm text-red-700 border border-red-200 text-center">
                    {{ errorMessage }}
                </div>

                <!-- Recent Orders Selector for Logged-In User -->
                <div v-if="userRecentOrders && userRecentOrders.length > 0" class="mt-6 border-t border-gray-100 pt-5">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2 text-center">Your Recent Orders</p>
                    <div class="flex flex-wrap items-center justify-center gap-2">
                        <button
                            v-for="rec in userRecentOrders"
                            :key="rec.id"
                            @click="selectRecentOrder(rec.order_number)"
                            class="inline-flex items-center gap-2 rounded-lg border px-3 py-1.5 text-xs font-medium transition"
                            :class="order?.order_number === rec.order_number ? 'border-primary-600 bg-primary-50 text-primary-700 font-bold' : 'border-gray-200 bg-gray-50 text-gray-700 hover:bg-gray-100'"
                        >
                            <span>{{ rec.order_number }}</span>
                            <span class="text-gray-400">•</span>
                            <span>{{ formatPrice(rec.total) }}</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Order Details View -->
            <div v-if="order" class="space-y-8">
                <!-- Status Progress Card -->
                <div class="rounded-2xl border border-gray-200 bg-white p-6 sm:p-8 shadow-sm">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between border-b border-gray-100 pb-6 gap-4">
                        <div>
                            <div class="flex items-center gap-3">
                                <h2 class="text-xl font-bold text-gray-900">Order {{ order.order_number }}</h2>
                                <button
                                    @click="copyOrderNumber"
                                    type="button"
                                    class="inline-flex items-center text-xs text-primary-600 hover:text-primary-700 font-medium"
                                >
                                    <span v-if="copiedNotice" class="text-green-600">✓ Copied!</span>
                                    <span v-else class="flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                                        Copy
                                    </span>
                                </button>
                                <span
                                    class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold"
                                    :class="{
                                        'bg-yellow-100 text-yellow-800': order.status_raw === 'pending',
                                        'bg-blue-100 text-blue-800': order.status_raw === 'processing',
                                        'bg-indigo-100 text-indigo-800': order.status_raw === 'shipped',
                                        'bg-green-100 text-green-800': order.status_raw === 'delivered',
                                        'bg-red-100 text-red-800': order.status_raw === 'cancelled',
                                    }"
                                >
                                    {{ order.status }}
                                </span>
                            </div>
                            <p class="mt-1 text-xs text-gray-500">Placed on {{ order.placed_at }}</p>
                        </div>
                        <div class="text-right sm:text-right">
                            <span class="text-xs text-gray-500">Total Amount</span>
                            <p class="text-lg font-bold text-primary-600">{{ formatPrice(order.total) }}</p>
                            <span class="text-xs text-gray-500">({{ order.payment_method }} — {{ order.payment_status }})</span>
                        </div>
                    </div>

                    <!-- Progress Bar (Timeline) -->
                    <div class="mt-8">
                        <div class="grid grid-cols-4 gap-2 text-center text-xs font-semibold text-gray-600">
                            <div>
                                <div class="mx-auto flex h-10 w-10 items-center justify-center rounded-full transition-all"
                                    :class="{
                                        'bg-primary-600 text-white ring-4 ring-primary-100': getStepStatus('pending') === 'current',
                                        'bg-green-500 text-white': getStepStatus('pending') === 'completed',
                                        'bg-gray-100 text-gray-400': getStepStatus('pending') === 'upcoming'
                                    }">
                                    ✓
                                </div>
                                <span class="mt-2 block">Order Placed</span>
                            </div>
                            <div>
                                <div class="mx-auto flex h-10 w-10 items-center justify-center rounded-full transition-all"
                                    :class="{
                                        'bg-primary-600 text-white ring-4 ring-primary-100 animate-pulse': getStepStatus('processing') === 'current',
                                        'bg-green-500 text-white': getStepStatus('processing') === 'completed',
                                        'bg-gray-100 text-gray-400': getStepStatus('processing') === 'upcoming'
                                    }">
                                    ⚙️
                                </div>
                                <span class="mt-2 block">Processing</span>
                            </div>
                            <div>
                                <div class="mx-auto flex h-10 w-10 items-center justify-center rounded-full transition-all"
                                    :class="{
                                        'bg-primary-600 text-white ring-4 ring-primary-100 animate-pulse': getStepStatus('shipped') === 'current',
                                        'bg-green-500 text-white': getStepStatus('shipped') === 'completed',
                                        'bg-gray-100 text-gray-400': getStepStatus('shipped') === 'upcoming'
                                    }">
                                    🚚
                                </div>
                                <span class="mt-2 block">Shipped</span>
                            </div>
                            <div>
                                <div class="mx-auto flex h-10 w-10 items-center justify-center rounded-full transition-all"
                                    :class="{
                                        'bg-green-600 text-white ring-4 ring-green-100': getStepStatus('delivered') === 'current' || getStepStatus('delivered') === 'completed',
                                        'bg-gray-100 text-gray-400': getStepStatus('delivered') === 'upcoming'
                                    }">
                                    🎁
                                </div>
                                <span class="mt-2 block">Delivered</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Customer & Shipping Details -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                        <h3 class="text-sm font-semibold uppercase tracking-wider text-gray-500 mb-4">Customer Details</h3>
                        <dl class="space-y-2 text-sm text-gray-700">
                            <div class="flex justify-between"><dt class="text-gray-500">Name:</dt><dd class="font-medium text-gray-900">{{ order.customer_name }}</dd></div>
                            <div class="flex justify-between"><dt class="text-gray-500">Phone:</dt><dd class="font-medium text-gray-900">{{ order.phone }}</dd></div>
                            <div class="flex justify-between"><dt class="text-gray-500">Email:</dt><dd class="font-medium text-gray-900">{{ order.email }}</dd></div>
                        </dl>
                    </div>

                    <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                        <h3 class="text-sm font-semibold uppercase tracking-wider text-gray-500 mb-4">Delivery Address</h3>
                        <p class="text-sm font-medium text-gray-900">{{ order.address }}</p>
                        <p class="text-sm text-gray-600">{{ order.area }}, {{ order.district }}</p>
                        <p v-if="order.notes" class="mt-3 text-xs italic text-gray-500">Note: {{ order.notes }}</p>
                    </div>
                </div>

                <!-- Items Summary -->
                <div class="rounded-2xl border border-gray-200 bg-white p-6 sm:p-8 shadow-sm">
                    <h3 class="text-base font-bold text-gray-900 mb-4">Ordered Items</h3>
                    <div class="divide-y divide-gray-100">
                        <div v-for="item in order.items" :key="item.id" class="py-3 flex items-center justify-between gap-4 text-sm">
                            <div class="flex items-center gap-3">
                                <img
                                    :src="getItemImg(item.image)"
                                    :alt="item.product_name"
                                    class="h-12 w-12 rounded-lg object-cover border border-gray-200 shrink-0"
                                />
                                <div>
                                    <Link
                                        v-if="item.product_slug"
                                        :href="`/product-details/${item.product_slug}`"
                                        class="font-semibold text-gray-900 hover:text-primary-600 transition"
                                    >
                                        {{ item.product_name }}
                                    </Link>
                                    <p v-else class="font-semibold text-gray-900">{{ item.product_name }}</p>
                                    <p class="text-xs text-gray-500">Qty: {{ item.quantity }} × {{ formatPrice(item.unit_price) }}</p>
                                </div>
                            </div>
                            <span class="font-semibold text-gray-900 shrink-0">{{ formatPrice(item.line_total) }}</span>
                        </div>
                    </div>

                    <div class="mt-6 border-t border-gray-100 pt-4 space-y-2 text-sm">
                        <div class="flex justify-between text-gray-600"><span>Subtotal</span><span>{{ formatPrice(order.subtotal) }}</span></div>
                        <div class="flex justify-between text-gray-600"><span>Delivery Charge</span><span>{{ formatPrice(order.delivery_charge) }}</span></div>
                        <div class="flex justify-between text-base font-bold text-gray-900 pt-2 border-t border-gray-100"><span>Total</span><span class="text-primary-600">{{ formatPrice(order.total) }}</span></div>
                    </div>
                </div>

                <!-- LOGIN / ACCOUNT BANNER -->
                <div v-if="!page.props.auth?.user" class="rounded-2xl border border-primary-200 bg-primary-50/70 p-6 sm:p-8 text-center sm:text-left flex flex-col sm:flex-row items-center justify-between gap-6 shadow-sm">
                    <div>
                        <div class="flex items-center gap-2 text-primary-900 font-bold text-lg mb-1">
                            <span>🔑</span> Want to manage & track all your orders in one place?
                        </div>
                        <p class="text-sm text-primary-700">
                            Log in or create an account with email <span class="font-semibold">{{ order.email }}</span> to link your orders to your personal profile.
                        </p>
                    </div>
                    <div class="flex gap-3 shrink-0">
                        <Link href="/login" class="rounded-xl bg-primary-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-primary-700 transition">Log In</Link>
                        <Link href="/register" class="rounded-xl border border-primary-300 bg-white px-5 py-2.5 text-sm font-semibold text-primary-700 hover:bg-primary-50 transition">Register</Link>
                    </div>
                </div>

                <div v-else class="rounded-2xl border border-gray-200 bg-white p-6 text-center flex flex-col sm:flex-row items-center justify-between gap-4 shadow-sm">
                    <div class="flex items-center gap-3">
                        <span class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-green-100 text-green-700 font-bold">✓</span>
                        <div class="text-left">
                            <p class="text-sm font-semibold text-gray-900">Logged in as {{ (page.props.auth as any).user?.name }}</p>
                            <p class="text-xs text-gray-500">You can view all your orders anytime in your account dashboard.</p>
                        </div>
                    </div>
                    <Link href="/dashboard/orders" class="rounded-xl bg-gray-900 px-5 py-2.5 text-sm font-semibold text-white hover:bg-gray-800 transition">My Account Orders</Link>
                </div>
            </div>

            <!-- Empty Initial State -->
            <div v-else-if="!searchedQuery" class="rounded-2xl border border-gray-200 bg-white p-12 text-center text-gray-500 shadow-sm">
                <p class="text-base font-medium text-gray-700">Ready to track your package?</p>
                <p class="text-sm mt-1">Enter your Order Number or phone number in the search bar above.</p>
            </div>
        </div>
    </div>
</template>
