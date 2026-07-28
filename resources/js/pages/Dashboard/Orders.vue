<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';
import {
    ShoppingCart,
    Search,
    Filter,
    Eye,
    Printer,
    CheckCircle2,
    Clock,
    Truck,
    AlertCircle,
    XCircle,
} from '@lucide/vue';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Orders', href: '/dashboard/orders' },
];

const activeTab = ref('all');
const searchQuery = ref('');

const tabs = [
    { id: 'all', label: 'All Orders', count: 1284 },
    { id: 'pending', label: 'Pending', count: 14 },
    { id: 'processing', label: 'Processing', count: 28 },
    { id: 'shipped', label: 'Shipped', count: 32 },
    { id: 'delivered', label: 'Delivered', count: 1210 },
];

const orders = [
    {
        id: 'ORD-8942',
        date: '2026-07-28 14:45',
        customer: 'Rina Akter',
        email: 'rina.akter@example.com',
        phone: '+880 1711-223344',
        address: 'House 12, Road 5, Dhanmondi, Dhaka 1205',
        itemsCount: 3,
        total: '৳ 6,499',
        paymentMethod: 'Cash on Delivery',
        paymentStatus: 'Paid',
        orderStatus: 'Delivered',
        statusColor: 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300',
    },
    {
        id: 'ORD-8941',
        date: '2026-07-28 13:12',
        customer: 'Tanvir Hasan',
        email: 'tanvir.h@example.com',
        phone: '+880 1812-334455',
        address: 'GEC Circle, Nasirabad, Chattogram',
        itemsCount: 1,
        total: '৳ 4,299',
        paymentMethod: 'SSLCommerz',
        paymentStatus: 'Paid',
        orderStatus: 'Processing',
        statusColor: 'bg-blue-100 text-blue-800 dark:bg-blue-950 dark:text-blue-300',
    },
    {
        id: 'ORD-8940',
        date: '2026-07-28 11:30',
        customer: 'Nusrat Jahan',
        email: 'nusrat.j@example.com',
        phone: '+880 1913-445566',
        address: 'Zindabazar, Sylhet Sadar, Sylhet',
        itemsCount: 2,
        total: '৳ 2,999',
        paymentMethod: 'Cash on Delivery',
        paymentStatus: 'Pending',
        orderStatus: 'Pending',
        statusColor: 'bg-amber-100 text-amber-800 dark:bg-amber-950 dark:text-amber-300',
    },
    {
        id: 'ORD-8939',
        date: '2026-07-27 18:20',
        customer: 'Kamal Hossain',
        email: 'kamal.h@example.com',
        phone: '+880 1614-556677',
        address: 'Shaheb Bazar, Rajshahi',
        itemsCount: 5,
        total: '৳ 12,850',
        paymentMethod: 'SSLCommerz',
        paymentStatus: 'Paid',
        orderStatus: 'Shipped',
        statusColor: 'bg-purple-100 text-purple-800 dark:bg-purple-950 dark:text-purple-300',
    },
    {
        id: 'ORD-8938',
        date: '2026-07-27 15:10',
        customer: 'Sabrina Islam',
        email: 'sabrina.i@example.com',
        phone: '+880 1515-667788',
        address: 'KDA Avenue, Khulna',
        itemsCount: 1,
        total: '৳ 1,599',
        paymentMethod: 'Cash on Delivery',
        paymentStatus: 'Paid',
        orderStatus: 'Delivered',
        statusColor: 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300',
    },
];
</script>

<template>
    <Head title="Orders — ShopEase Admin" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col gap-6 p-6 max-w-7xl mx-auto w-full">
            <!-- Header -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-extrabold tracking-tight text-gray-900 dark:text-white">Orders & Fulfillment</h1>
                    <p class="mt-1 text-sm text-gray-500 dark:text-neutral-400">
                        Track orders, delivery status across Bangladesh, and generate invoices.
                    </p>
                </div>
            </div>

            <!-- Tabs Bar -->
            <div class="flex items-center gap-2 border-b border-gray-200 dark:border-neutral-800 overflow-x-auto">
                <button
                    v-for="tab in tabs"
                    :key="tab.id"
                    @click="activeTab = tab.id"
                    :class="[
                        'px-4 py-3 text-sm font-semibold whitespace-nowrap border-b-2 transition',
                        activeTab === tab.id
                            ? 'border-violet-600 text-violet-600 dark:text-violet-400'
                            : 'border-transparent text-gray-500 hover:text-gray-900 dark:hover:text-white'
                    ]"
                >
                    {{ tab.label }}
                    <span class="ml-1.5 px-2 py-0.5 rounded-full text-xs bg-gray-100 dark:bg-neutral-800 font-bold">
                        {{ tab.count }}
                    </span>
                </button>
            </div>

            <!-- Filter Search -->
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4 p-4 rounded-xl border border-gray-200 dark:border-neutral-800 bg-white dark:bg-neutral-900">
                <div class="relative w-full sm:max-w-xs">
                    <Search class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" />
                    <input
                        v-model="searchQuery"
                        type="text"
                        placeholder="Search order ID, customer name..."
                        class="w-full pl-9 pr-4 py-2 text-sm rounded-lg border border-gray-300 dark:border-neutral-700 bg-gray-50 dark:bg-neutral-800 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-violet-600"
                    />
                </div>
            </div>

            <!-- Orders Table -->
            <div class="rounded-2xl border border-gray-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 overflow-hidden shadow-xs">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-600 dark:text-neutral-300">
                        <thead class="bg-gray-50 dark:bg-neutral-800/50 text-xs font-semibold uppercase text-gray-500 dark:text-neutral-400">
                            <tr>
                                <th class="px-5 py-3.5">Order ID</th>
                                <th class="px-5 py-3.5">Customer & Contact</th>
                                <th class="px-5 py-3.5">Address</th>
                                <th class="px-5 py-3.5">Total & Payment</th>
                                <th class="px-5 py-3.5">Status</th>
                                <th class="px-5 py-3.5 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-neutral-800">
                            <tr v-for="order in orders" :key="order.id" class="hover:bg-gray-50/50 dark:hover:bg-neutral-800/30 transition">
                                <td class="px-5 py-4 whitespace-nowrap">
                                    <span class="font-extrabold text-gray-900 dark:text-white font-mono">{{ order.id }}</span>
                                    <span class="block text-xs text-gray-400 mt-0.5">{{ order.date }}</span>
                                </td>
                                <td class="px-5 py-4 whitespace-nowrap">
                                    <p class="font-bold text-gray-900 dark:text-white">{{ order.customer }}</p>
                                    <p class="text-xs text-gray-400">{{ order.email }}</p>
                                    <p class="text-xs text-gray-400">{{ order.phone }}</p>
                                </td>
                                <td class="px-5 py-4 max-w-xs truncate">
                                    <p class="text-xs text-gray-600 dark:text-neutral-300 truncate">{{ order.address }}</p>
                                </td>
                                <td class="px-5 py-4 whitespace-nowrap">
                                    <p class="font-extrabold text-violet-600 dark:text-violet-400">{{ order.total }}</p>
                                    <p class="text-xs text-gray-400">{{ order.itemsCount }} Items • {{ order.paymentMethod }}</p>
                                    <span :class="[
                                        'inline-block mt-1 text-[10px] font-bold px-2 py-0.5 rounded',
                                        order.paymentStatus === 'Paid' ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700'
                                    ]">
                                        {{ order.paymentStatus }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 whitespace-nowrap">
                                    <span :class="`inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold ${order.statusColor}`">
                                        {{ order.orderStatus }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-right whitespace-nowrap">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <button title="Print Invoice" class="p-1.5 rounded-lg border border-gray-200 dark:border-neutral-700 hover:bg-gray-100 dark:hover:bg-neutral-800 text-gray-600 dark:text-neutral-300 transition">
                                            <Printer class="w-4 h-4" />
                                        </button>
                                        <button title="View Details" class="p-1.5 rounded-lg border border-gray-200 dark:border-neutral-700 hover:bg-gray-100 dark:hover:bg-neutral-800 text-gray-600 dark:text-neutral-300 transition">
                                            <Eye class="w-4 h-4" />
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
