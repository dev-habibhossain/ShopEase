<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { ref } from 'vue';
import {
    DollarSign,
    ShoppingCart,
    Package,
    Users,
    TrendingUp,
    TrendingDown,
    Plus,
    ArrowUpRight,
    Eye,
    AlertTriangle,
    CheckCircle2,
    Store,
    ChevronRight,
} from '@lucide/vue';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Overview', href: '/dashboard' },
];

const metrics = [
    {
        title: 'Total Revenue',
        value: '৳ 458,920',
        change: '+14.2%',
        isPositive: true,
        icon: DollarSign,
    },
    {
        title: 'Total Orders',
        value: '1,284',
        change: '+8.1%',
        isPositive: true,
        icon: ShoppingCart,
    },
    {
        title: 'Active Products',
        value: '342',
        change: '24 Low Stock',
        isPositive: false,
        icon: Package,
    },
    {
        title: 'Total Customers',
        value: '8,450',
        change: '+18.5%',
        isPositive: true,
        icon: Users,
    },
];

const monthlySales = [
    { month: 'Jan', revenue: 280, height: '40%' },
    { month: 'Feb', revenue: 340, height: '52%' },
    { month: 'Mar', revenue: 310, height: '46%' },
    { month: 'Apr', revenue: 420, height: '68%' },
    { month: 'May', revenue: 390, height: '60%' },
    { month: 'Jun', revenue: 490, height: '80%' },
    { month: 'Jul', revenue: 580, height: '95%' },
];

const recentOrders = [
    {
        id: 'ORD-8942',
        customer: 'Rina Akter',
        city: 'Dhanmondi, Dhaka',
        items: 3,
        total: '৳ 6,499',
        method: 'Cash on Delivery',
        orderStatus: 'Delivered',
        statusStyle: 'bg-emerald-50 text-emerald-700',
        time: 'Today, 2:45 PM',
        initials: 'RA',
    },
    {
        id: 'ORD-8941',
        customer: 'Tanvir Hasan',
        city: 'GEC, Chattogram',
        items: 1,
        total: '৳ 4,299',
        method: 'SSLCommerz (bKash)',
        orderStatus: 'Processing',
        statusStyle: 'bg-blue-50 text-blue-700',
        time: 'Today, 1:12 PM',
        initials: 'TH',
    },
    {
        id: 'ORD-8940',
        customer: 'Nusrat Jahan',
        city: 'Zindabazar, Sylhet',
        items: 2,
        total: '৳ 2,999',
        method: 'Cash on Delivery',
        orderStatus: 'Pending',
        statusStyle: 'bg-amber-50 text-amber-700',
        time: 'Today, 11:30 AM',
        initials: 'NJ',
    },
    {
        id: 'ORD-8939',
        customer: 'Kamal Hossain',
        city: 'Shaheb Bazar, Rajshahi',
        items: 5,
        total: '৳ 12,850',
        method: 'SSLCommerz (Nagad)',
        orderStatus: 'Shipped',
        statusStyle: 'bg-purple-50 text-purple-700',
        time: 'Yesterday',
        initials: 'KH',
    },
];

const topProducts = [
    {
        name: 'Wireless Noise-Cancelling Headphones',
        category: 'Electronics',
        price: '৳ 6,499',
        salesCount: 142,
        img: 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?auto=format&fit=crop&w=120&q=70',
    },
    {
        name: 'Smart Fitness Watch Series 6',
        category: 'Electronics',
        price: '৳ 4,299',
        salesCount: 98,
        img: 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?auto=format&fit=crop&w=120&q=70',
    },
    {
        name: 'Classic Leather Sneakers',
        category: 'Fashion',
        price: '৳ 2,999',
        salesCount: 84,
        img: 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?auto=format&fit=crop&w=120&q=70',
    },
];

const lowStockItems = [
    { name: 'Wireless Headphones', sku: 'ELEC-WNH-01', stock: 2 },
    { name: 'Cotton Oversized T-Shirt', sku: 'FASH-TSH-04', stock: 0 },
    { name: 'Pour-Over Coffee Set', sku: 'HOME-PCS-02', stock: 4 },
];
</script>

<template>
    <Head title="Dashboard Overview — ShopEase" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8 space-y-8 bg-white min-h-screen">
            <!-- Header Section (Matching Home.vue typography) -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-gray-200 pb-6">
                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-gray-900">Dashboard Overview</h1>
                    <p class="text-sm text-gray-500 mt-1">Real-time summary of sales, orders, and product stock across ShopEase</p>
                </div>
                <div class="flex items-center gap-3">
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
                </div>
            </div>

            <!-- Top 4 KPI Metrics Grid (Matching Home.vue Trust Strip style) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
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
                        <div class="mt-1.5 flex items-center gap-1 text-xs font-semibold" :class="metric.isPositive ? 'text-emerald-600' : 'text-amber-600'">
                            <component :is="metric.isPositive ? TrendingUp : TrendingDown" class="w-3.5 h-3.5" />
                            <span>{{ metric.change }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Revenue Analytics & Payment Breakdown Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Sales Revenue Graph (2 cols) -->
                <div class="lg:col-span-2 rounded-xl border border-gray-200 bg-white p-6 shadow-xs flex flex-col justify-between">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h2 class="text-lg font-bold text-gray-900">Revenue Performance</h2>
                            <p class="text-xs text-gray-500">Monthly sales revenue generated in 2026 (in ৳ 1,000s)</p>
                        </div>
                        <span class="rounded-lg bg-violet-50 px-3 py-1 text-xs font-bold text-violet-700">2026 Growth</span>
                    </div>

                    <div class="h-52 flex items-end justify-between gap-3 pt-6 border-b border-gray-100 px-2">
                        <div
                            v-for="bar in monthlySales"
                            :key="bar.month"
                            class="flex-1 flex flex-col items-center gap-2 h-full justify-end group"
                        >
                            <span class="text-[10px] font-bold text-gray-400 group-hover:text-violet-600 transition">
                                ৳ {{ bar.revenue }}k
                            </span>
                            <div class="w-full max-w-[40px] bg-gray-100 rounded-t-lg h-full flex items-end overflow-hidden">
                                <div
                                    class="w-full rounded-t-md bg-violet-600 group-hover:bg-violet-700 transition-all duration-300"
                                    :style="`height: ${bar.height}`"
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
                            <TrendingUp class="w-4 h-4" /> +24.5% year over year
                        </span>
                    </div>
                </div>

                <!-- Payment Methods & Low Stock Alerts (1 col) -->
                <div class="space-y-6">
                    <!-- Payment Methods -->
                    <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-xs">
                        <h2 class="text-base font-bold text-gray-900 mb-4">Payment Methods</h2>
                        <div class="space-y-4">
                            <div>
                                <div class="flex justify-between text-xs font-semibold text-gray-700 mb-1">
                                    <span>SSLCommerz (Online)</span>
                                    <span class="text-violet-600 font-bold">62%</span>
                                </div>
                                <div class="h-2 w-full rounded-full bg-gray-100 overflow-hidden">
                                    <div class="h-full rounded-full bg-violet-600" style="width: 62%"></div>
                                </div>
                            </div>
                            <div>
                                <div class="flex justify-between text-xs font-semibold text-gray-700 mb-1">
                                    <span>Cash on Delivery</span>
                                    <span class="text-gray-900 font-bold">38%</span>
                                </div>
                                <div class="h-2 w-full rounded-full bg-gray-100 overflow-hidden">
                                    <div class="h-full rounded-full bg-gray-400" style="width: 38%"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Low Stock Alert -->
                    <div class="rounded-xl border border-amber-200 bg-amber-50/50 p-5">
                        <div class="flex items-center gap-2 text-amber-900 font-bold text-sm mb-3">
                            <AlertTriangle class="w-5 h-5 text-amber-600" /> Low Stock Warning
                        </div>
                        <div class="space-y-2">
                            <div v-for="item in lowStockItems" :key="item.sku" class="flex items-center justify-between text-xs p-2 rounded-lg bg-white border border-amber-200">
                                <span class="font-medium text-gray-900 truncate max-w-[160px]">{{ item.name }}</span>
                                <span :class="['font-bold px-2 py-0.5 rounded text-[10px]', item.stock === 0 ? 'bg-red-100 text-red-700' : 'bg-amber-100 text-amber-800']">
                                    {{ item.stock === 0 ? 'Out of stock' : `${item.stock} left` }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Orders Table & Top Products -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Recent Orders Table (2 cols) -->
                <div class="lg:col-span-2 rounded-xl border border-gray-200 bg-white overflow-hidden shadow-xs">
                    <div class="flex items-center justify-between p-5 border-b border-gray-200">
                        <h2 class="text-lg font-bold text-gray-900">Recent Customer Orders</h2>
                        <Link href="/dashboard/orders" class="text-xs font-semibold text-violet-600 hover:text-violet-700 flex items-center gap-1">
                            View All <ArrowUpRight class="w-3.5 h-3.5" />
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
                                    <td class="px-5 py-4 font-bold text-gray-900 font-mono text-xs">{{ order.id }}</td>
                                    <td class="px-5 py-4">
                                        <p class="font-semibold text-gray-900 text-xs">{{ order.customer }}</p>
                                        <p class="text-[11px] text-gray-500">{{ order.city }}</p>
                                    </td>
                                    <td class="px-5 py-4 font-extrabold text-violet-600 text-xs sm:text-sm">{{ order.total }}</td>
                                    <td class="px-5 py-4">
                                        <span :class="`inline-flex px-2.5 py-0.5 rounded-full text-xs font-bold ${order.statusStyle}`">
                                            {{ order.orderStatus }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-4 text-right">
                                        <Link href="/dashboard/orders" class="inline-flex items-center justify-center h-8 w-8 rounded-lg border border-gray-300 text-gray-600 hover:bg-violet-600 hover:text-white transition">
                                            <Eye class="w-4 h-4" />
                                        </Link>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Top Products Widget (1 col) -->
                <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-xs flex flex-col justify-between">
                    <div>
                        <h2 class="text-lg font-bold text-gray-900 mb-4">Top Selling Products</h2>
                        <div class="space-y-4">
                            <div v-for="product in topProducts" :key="product.name" class="flex items-center gap-3">
                                <img :src="product.img" :alt="product.name" class="w-12 h-12 rounded-lg object-cover bg-gray-100 border border-gray-200 shrink-0" />
                                <div class="flex-1 min-w-0">
                                    <p class="font-bold text-xs text-gray-900 truncate">{{ product.name }}</p>
                                    <p class="text-xs font-extrabold text-violet-600 mt-0.5">{{ product.price }}</p>
                                </div>
                                <span class="px-2 py-1 rounded bg-violet-50 text-violet-700 font-bold text-xs shrink-0">
                                    {{ product.salesCount }} Sold
                                </span>
                            </div>
                        </div>
                    </div>

                    <Link href="/dashboard/products" class="mt-6 inline-flex items-center justify-center gap-1 text-xs font-bold text-violet-600 hover:underline">
                        Explore Full Product Catalog <ChevronRight class="w-4 h-4" />
                    </Link>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
