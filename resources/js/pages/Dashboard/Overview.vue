<script setup lang="ts">
import { computed } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import {
    DollarSign,
    ShoppingCart,
    Package,
    Users,
    TrendingUp,
    Plus,
    ArrowUpRight,
    Eye,
    AlertTriangle,
    Store,
    ChevronRight,
} from '@lucide/vue';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';

const props = defineProps<{
    userRole?: string;
    stats?: {
        total_revenue?: string;
        total_orders?: string;
        active_products?: string;
        total_customers?: string;
        total_spent?: string;
        wishlist_count?: string;
    };
    recentOrders?: Array<{
        id: number;
        order_number: string;
        customer_name: string;
        district: string;
        total: number;
        payment_method: string;
        payment_status: string;
        status: string;
        created_at: string;
    }>;
    topProducts?: Array<{
        id: number;
        name: string;
        slug: string;
        price: number;
        sold_count: number;
        category?: { name: string };
    }>;
    lowStockItems?: Array<{
        id: number;
        name: string;
        stock_status: string;
    }>;
    monthlySales?: Array<{
        month: string;
        revenue: number;
    }>;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Overview', href: '/dashboard' },
];

const metrics = computed(() => {
    if (props.userRole === 'customer') {
        return [
            {
                title: 'Orders Placed',
                value: props.stats?.total_orders || '0',
                icon: ShoppingCart,
            },
            {
                title: 'Total Spent',
                value: `৳ ${props.stats?.total_spent || '0.00'}`,
                icon: DollarSign,
            },
            {
                title: 'Wishlist Items',
                value: props.stats?.wishlist_count || '0',
                icon: Package,
            },
        ];
    }

    return [
        {
            title: 'Total Revenue',
            value: `৳ ${props.stats?.total_revenue || '0.00'}`,
            icon: DollarSign,
        },
        {
            title: 'Total Orders',
            value: props.stats?.total_orders || '0',
            icon: ShoppingCart,
        },
        {
            title: 'Active Products',
            value: props.stats?.active_products || '0',
            icon: Package,
        },
        {
            title: 'Total Customers',
            value: props.stats?.total_customers || '0',
            icon: Users,
        },
    ];
});

const getStatusStyle = (status: string) => {
    switch (status) {
        case 'delivered':
            return 'bg-emerald-50 text-emerald-700 border border-emerald-200';
        case 'processing':
        case 'shipped':
            return 'bg-blue-50 text-blue-700 border border-blue-200';
        case 'cancelled':
            return 'bg-red-50 text-red-700 border border-red-200';
        default:
            return 'bg-amber-50 text-amber-700 border border-amber-200';
    }
};
</script>

<template>
    <Head title="Dashboard Overview — ShopEase" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8 space-y-8 bg-white min-h-screen">
            <!-- Header Section -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-gray-200 pb-6">
                <div>
                    <div class="flex items-center gap-2">
                        <h1 class="text-2xl font-bold tracking-tight text-gray-900">
                            {{ userRole === 'customer' ? 'Customer Account Dashboard' : 'Dashboard Overview' }}
                        </h1>
                        <span
                            :class="[
                                'px-2 py-0.5 text-xs font-bold rounded-full uppercase tracking-wider',
                                userRole === 'super_admin' ? 'bg-purple-100 text-purple-700' :
                                userRole === 'admin' ? 'bg-blue-100 text-blue-700' : 'bg-emerald-100 text-emerald-700'
                            ]"
                        >
                            {{ userRole === 'super_admin' ? 'Super Admin' : userRole === 'admin' ? 'Admin' : 'Customer' }}
                        </span>
                    </div>
                    <p class="text-sm text-gray-500 mt-1">
                        {{ userRole === 'customer' ? 'Welcome back! Manage your orders, track shipments, and view saved items.' : 'Real-time summary of sales, orders, and product stock across ShopEase.' }}
                    </p>
                </div>
                <div class="flex items-center gap-3">
                    <template v-if="userRole === 'customer'">
                        <Link
                            href="/track-order"
                            class="inline-flex items-center gap-2 rounded-lg bg-violet-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-violet-700 shadow-sm"
                        >
                            Track an Order
                        </Link>
                        <Link
                            href="/shop"
                            class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 transition hover:bg-gray-50"
                        >
                            <Store class="w-4 h-4 text-violet-600" /> Continue Shopping
                        </Link>
                    </template>
                    <template v-else>
                        <Link
                            href="/dashboard/products"
                            class="inline-flex items-center gap-2 rounded-lg bg-violet-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-violet-700 shadow-sm"
                        >
                            <Plus class="w-4 h-4" /> Add Product
                        </Link>
                        <Link
                            href="/"
                            target="_blank"
                            class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 transition hover:bg-gray-50"
                        >
                            <Store class="w-4 h-4 text-violet-600" /> View Storefront
                        </Link>
                    </template>
                </div>
            </div>

            <!-- KPI Metrics Grid -->
            <div :class="['grid grid-cols-1 sm:grid-cols-2 gap-6', userRole === 'customer' ? 'lg:grid-cols-3' : 'lg:grid-cols-4']">
                <div
                    v-for="metric in metrics"
                    :key="metric.title"
                    class="flex items-start gap-4 rounded-xl border border-gray-200 bg-white p-5 shadow-xs transition hover:shadow-sm"
                >
                    <span class="inline-flex h-12 w-12 shrink-0 items-center justify-center rounded-lg bg-violet-50 text-violet-600">
                        <component :is="metric.icon" class="h-6 w-6" />
                    </span>
                    <div class="min-w-0 flex-1">
                        <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">{{ metric.title }}</p>
                        <p class="text-2xl font-extrabold text-gray-900 mt-1 tracking-tight">{{ metric.value }}</p>
                    </div>
                </div>
            </div>

            <!-- Revenue Analytics & Payment Breakdown Grid (Admins / Super Admins Only) -->
            <div v-if="userRole !== 'customer'" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Sales Revenue Graph (2 cols) -->
                <div class="lg:col-span-2 rounded-xl border border-gray-200 bg-white p-6 shadow-xs flex flex-col justify-between">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h2 class="text-lg font-bold text-gray-900">Revenue Performance</h2>
                            <p class="text-xs text-gray-500">Monthly sales revenue generated in 2026 (in ৳)</p>
                        </div>
                        <span class="rounded-lg bg-violet-50 px-3 py-1 text-xs font-bold text-violet-700">2026 Real-time Data</span>
                    </div>

                    <div class="h-52 flex items-end justify-between gap-3 pt-6 border-b border-gray-100 px-2">
                        <div
                            v-for="bar in (monthlySales && monthlySales.length ? monthlySales : [{ month: 'Jan', revenue: 0 }])"
                            :key="bar.month"
                            class="flex-1 flex flex-col items-center gap-2 h-full justify-end group"
                        >
                            <span class="text-[10px] font-bold text-gray-400 group-hover:text-violet-600 transition">
                                ৳ {{ Number(bar.revenue).toLocaleString() }}
                            </span>
                            <div class="w-full max-w-[40px] bg-gray-100 rounded-t-lg h-full flex items-end overflow-hidden">
                                <div
                                    class="w-full rounded-t-md bg-violet-600 group-hover:bg-violet-700 transition-all duration-300 min-h-[10%]"
                                ></div>
                            </div>
                            <span class="text-xs font-semibold text-gray-600">{{ bar.month }}</span>
                        </div>
                    </div>

                    <div class="mt-4 flex items-center justify-between text-xs text-gray-500">
                        <span class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-violet-600"></span> Online & COD Combined
                        </span>
                        <span class="font-semibold text-emerald-600 flex items-center gap-1">
                            <TrendingUp class="w-4 h-4" /> Real-time Storefront Revenue
                        </span>
                    </div>
                </div>

                <!-- Low Stock Alert -->
                <div class="space-y-6">
                    <div class="rounded-xl border border-amber-200 bg-amber-50/50 p-5">
                        <div class="flex items-center gap-2 text-amber-900 font-bold text-sm mb-3">
                            <AlertTriangle class="w-5 h-5 text-amber-600" /> Low Stock Warning
                        </div>
                        <div class="space-y-2">
                            <div v-for="item in lowStockItems" :key="item.id" class="flex items-center justify-between text-xs p-2 rounded-lg bg-white border border-amber-200">
                                <span class="font-medium text-gray-900 truncate max-w-[160px]">{{ item.name }}</span>
                                <span class="font-bold px-2 py-0.5 rounded text-[10px] bg-red-100 text-red-700">
                                    {{ item.stock_status }}
                                </span>
                            </div>
                            <div v-if="!lowStockItems || !lowStockItems.length" class="text-xs text-gray-500 py-2">
                                No low stock alerts currently.
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Orders Table & Side Widget -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Recent Orders Table (2 cols) -->
                <div class="lg:col-span-2 rounded-xl border border-gray-200 bg-white overflow-hidden shadow-xs">
                    <div class="flex items-center justify-between p-5 border-b border-gray-200">
                        <h2 class="text-lg font-bold text-gray-900">
                            {{ userRole === 'customer' ? 'My Recent Orders' : 'Recent Customer Orders' }}
                        </h2>
                        <Link
                            :href="userRole === 'customer' ? '/track-order' : '/dashboard/orders'"
                            class="text-xs font-semibold text-violet-600 hover:text-violet-700 flex items-center gap-1"
                        >
                            {{ userRole === 'customer' ? 'Track Order' : 'View All' }} <ArrowUpRight class="w-3.5 h-3.5" />
                        </Link>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm text-gray-600">
                            <thead class="bg-gray-50 text-xs font-semibold text-gray-500 uppercase tracking-wider border-b border-gray-200">
                                <tr>
                                    <th class="px-5 py-3">Order ID</th>
                                    <th class="px-5 py-3">Customer</th>
                                    <th class="px-5 py-3">Total</th>
                                    <th class="px-5 py-3">Status</th>
                                    <th class="px-5 py-3 text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                <tr v-for="order in recentOrders" :key="order.id" class="hover:bg-gray-50/80 transition">
                                    <td class="px-5 py-4 font-bold text-gray-900 font-mono text-xs">{{ order.order_number }}</td>
                                    <td class="px-5 py-4">
                                        <p class="font-semibold text-gray-900 text-xs">{{ order.customer_name }}</p>
                                        <p class="text-[11px] text-gray-500">{{ order.district }}</p>
                                    </td>
                                    <td class="px-5 py-4 font-extrabold text-violet-600 text-xs sm:text-sm">৳ {{ Number(order.total).toLocaleString() }}</td>
                                    <td class="px-5 py-4">
                                        <span :class="`inline-flex px-2.5 py-0.5 rounded-full text-xs font-bold capitalize ${getStatusStyle(order.status)}`">
                                            {{ order.status }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-4 text-right">
                                        <Link
                                            :href="`/track-order?order_number=${order.order_number}`"
                                            class="inline-flex items-center justify-center h-8 w-8 rounded-lg border border-gray-300 text-gray-600 hover:bg-violet-600 hover:text-white transition"
                                            title="Track Order"
                                        >
                                            <Eye class="w-4 h-4" />
                                        </Link>
                                    </td>
                                </tr>
                                <tr v-if="!recentOrders || !recentOrders.length">
                                    <td colspan="5" class="px-5 py-8 text-center text-xs text-gray-500">
                                        No recent orders found.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Side Widget: Top Products for Admin / Quick Links for Customer -->
                <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-xs flex flex-col justify-between">
                    <template v-if="userRole === 'customer'">
                        <div>
                            <h2 class="text-lg font-bold text-gray-900 mb-4">Quick Customer Actions</h2>
                            <div class="space-y-3">
                                <Link
                                    href="/track-order"
                                    class="flex items-center justify-between p-3 rounded-lg border border-gray-200 hover:border-violet-300 hover:bg-violet-50/50 transition group"
                                >
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-lg bg-violet-100 text-violet-700 flex items-center justify-center">
                                            <ShoppingCart class="w-4 h-4" />
                                        </div>
                                        <div>
                                            <p class="text-xs font-bold text-gray-900">Track Order Status</p>
                                            <p class="text-[11px] text-gray-500">Live timeline & delivery tracking</p>
                                        </div>
                                    </div>
                                    <ChevronRight class="w-4 h-4 text-gray-400 group-hover:text-violet-600 transition" />
                                </Link>

                                <Link
                                    href="/wishlist"
                                    class="flex items-center justify-between p-3 rounded-lg border border-gray-200 hover:border-violet-300 hover:bg-violet-50/50 transition group"
                                >
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-lg bg-pink-100 text-pink-700 flex items-center justify-center">
                                            <Package class="w-4 h-4" />
                                        </div>
                                        <div>
                                            <p class="text-xs font-bold text-gray-900">My Saved Wishlist</p>
                                            <p class="text-[11px] text-gray-500">View favorite saved products</p>
                                        </div>
                                    </div>
                                    <ChevronRight class="w-4 h-4 text-gray-400 group-hover:text-violet-600 transition" />
                                </Link>

                                <Link
                                    href="/shop"
                                    class="flex items-center justify-between p-3 rounded-lg border border-gray-200 hover:border-violet-300 hover:bg-violet-50/50 transition group"
                                >
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-lg bg-blue-100 text-blue-700 flex items-center justify-center">
                                            <Store class="w-4 h-4" />
                                        </div>
                                        <div>
                                            <p class="text-xs font-bold text-gray-900">Browse Shop</p>
                                            <p class="text-[11px] text-gray-500">Explore latest deals & items</p>
                                        </div>
                                    </div>
                                    <ChevronRight class="w-4 h-4 text-gray-400 group-hover:text-violet-600 transition" />
                                </Link>
                            </div>
                        </div>

                        <Link href="/settings/profile" class="mt-6 inline-flex items-center justify-center gap-1 text-xs font-bold text-violet-600 hover:underline">
                            Edit Profile Settings <ChevronRight class="w-4 h-4" />
                        </Link>
                    </template>

                    <template v-else>
                        <div>
                            <h2 class="text-lg font-bold text-gray-900 mb-4">Top Selling Products</h2>
                            <div class="space-y-4">
                                <div v-for="product in topProducts" :key="product.id" class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-violet-50 text-violet-600 flex items-center justify-center font-bold text-sm shrink-0">
                                        {{ product.name.charAt(0) }}
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="font-bold text-xs text-gray-900 truncate">{{ product.name }}</p>
                                        <p class="text-xs font-extrabold text-violet-600 mt-0.5">৳ {{ Number(product.price).toLocaleString() }}</p>
                                    </div>
                                    <span class="px-2 py-1 rounded bg-violet-50 text-violet-700 font-bold text-xs shrink-0">
                                        {{ product.sold_count }} Sold
                                    </span>
                                </div>
                                <div v-if="!topProducts || !topProducts.length" class="text-xs text-gray-500 py-2">
                                    No top product data available.
                                </div>
                            </div>
                        </div>

                        <Link href="/dashboard/products" class="mt-6 inline-flex items-center justify-center gap-1 text-xs font-bold text-violet-600 hover:underline">
                            Explore Full Product Catalog <ChevronRight class="w-4 h-4" />
                        </Link>
                    </template>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
