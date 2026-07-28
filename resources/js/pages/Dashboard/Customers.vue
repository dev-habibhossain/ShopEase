<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';
import { Users, Search, Mail, Phone, MapPin, Eye, ShoppingBag } from '@lucide/vue';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Customers', href: '/dashboard/customers' },
];

const searchQuery = ref('');

const customers = [
    {
        id: 1,
        name: 'Rina Akter',
        email: 'rina.akter@example.com',
        phone: '+880 1711-223344',
        city: 'Dhaka',
        ordersCount: 14,
        totalSpent: '৳ 34,500',
        joinedDate: 'Jan 12, 2026',
        avatarBg: 'bg-purple-100 text-purple-700',
        initials: 'RA',
    },
    {
        id: 2,
        name: 'Tanvir Hasan',
        email: 'tanvir.h@example.com',
        phone: '+880 1812-334455',
        city: 'Chattogram',
        ordersCount: 8,
        totalSpent: '৳ 22,800',
        joinedDate: 'Feb 04, 2026',
        avatarBg: 'bg-blue-100 text-blue-700',
        initials: 'TH',
    },
    {
        id: 3,
        name: 'Nusrat Jahan',
        email: 'nusrat.j@example.com',
        phone: '+880 1913-445566',
        city: 'Sylhet',
        ordersCount: 5,
        totalSpent: '৳ 14,200',
        joinedDate: 'Mar 19, 2026',
        avatarBg: 'bg-emerald-100 text-emerald-700',
        initials: 'NJ',
    },
    {
        id: 4,
        name: 'Kamal Hossain',
        email: 'kamal.h@example.com',
        phone: '+880 1614-556677',
        city: 'Rajshahi',
        ordersCount: 19,
        totalSpent: '৳ 58,900',
        joinedDate: 'Nov 15, 2025',
        avatarBg: 'bg-amber-100 text-amber-700',
        initials: 'KH',
    },
    {
        id: 5,
        name: 'Sabrina Islam',
        email: 'sabrina.i@example.com',
        phone: '+880 1515-667788',
        city: 'Khulna',
        ordersCount: 3,
        totalSpent: '৳ 7,490',
        joinedDate: 'May 02, 2026',
        avatarBg: 'bg-rose-100 text-rose-700',
        initials: 'SI',
    },
];
</script>

<template>
    <Head title="Customers — ShopEase Admin" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col gap-6 p-6 max-w-7xl mx-auto w-full">
            <!-- Header -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-extrabold tracking-tight text-gray-900 dark:text-white">Customer Directory</h1>
                    <p class="mt-1 text-sm text-gray-500 dark:text-neutral-400">
                        Manage registered customer accounts, order history, and contact info.
                    </p>
                </div>
            </div>

            <!-- Stats Bar -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                <div class="p-4 rounded-xl border border-gray-200 dark:border-neutral-800 bg-white dark:bg-neutral-900">
                    <span class="text-xs font-semibold text-gray-400">Total Customers</span>
                    <p class="mt-2 text-xl font-extrabold text-gray-900 dark:text-white">8,450</p>
                </div>
                <div class="p-4 rounded-xl border border-gray-200 dark:border-neutral-800 bg-white dark:bg-neutral-900">
                    <span class="text-xs font-semibold text-gray-400">Active Buyers</span>
                    <p class="mt-2 text-xl font-extrabold text-gray-900 dark:text-white">6,820</p>
                </div>
                <div class="p-4 rounded-xl border border-gray-200 dark:border-neutral-800 bg-white dark:bg-neutral-900">
                    <span class="text-xs font-semibold text-gray-400">Avg. Order Value</span>
                    <p class="mt-2 text-xl font-extrabold text-gray-900 dark:text-white">৳ 4,850</p>
                </div>
                <div class="p-4 rounded-xl border border-gray-200 dark:border-neutral-800 bg-white dark:bg-neutral-900">
                    <span class="text-xs font-semibold text-gray-400">Districts Served</span>
                    <p class="mt-2 text-xl font-extrabold text-gray-900 dark:text-white">64</p>
                </div>
            </div>

            <!-- Search -->
            <div class="flex items-center justify-between p-4 rounded-xl border border-gray-200 dark:border-neutral-800 bg-white dark:bg-neutral-900">
                <div class="relative w-full sm:max-w-xs">
                    <Search class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" />
                    <input
                        v-model="searchQuery"
                        type="text"
                        placeholder="Search by name, email, phone..."
                        class="w-full pl-9 pr-4 py-2 text-sm rounded-lg border border-gray-300 dark:border-neutral-700 bg-gray-50 dark:bg-neutral-800 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-violet-600"
                    />
                </div>
            </div>

            <!-- Table -->
            <div class="rounded-2xl border border-gray-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 overflow-hidden shadow-xs">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-600 dark:text-neutral-300">
                        <thead class="bg-gray-50 dark:bg-neutral-800/50 text-xs font-semibold uppercase text-gray-500 dark:text-neutral-400">
                            <tr>
                                <th class="px-5 py-3.5">Customer</th>
                                <th class="px-5 py-3.5">City/District</th>
                                <th class="px-5 py-3.5">Orders</th>
                                <th class="px-5 py-3.5">Total Spent</th>
                                <th class="px-5 py-3.5">Joined</th>
                                <th class="px-5 py-3.5 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-neutral-800">
                            <tr v-for="customer in customers" :key="customer.id" class="hover:bg-gray-50/50 dark:hover:bg-neutral-800/30 transition">
                                <td class="px-5 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        <div :class="`w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm ${customer.avatarBg}`">
                                            {{ customer.initials }}
                                        </div>
                                        <div>
                                            <p class="font-bold text-gray-900 dark:text-white">{{ customer.name }}</p>
                                            <p class="text-xs text-gray-400">{{ customer.email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-4 whitespace-nowrap font-medium text-gray-900 dark:text-white">
                                    <div class="flex items-center gap-1.5 text-xs text-gray-600 dark:text-neutral-300">
                                        <MapPin class="w-3.5 h-3.5 text-violet-600" />
                                        {{ customer.city }}
                                    </div>
                                </td>
                                <td class="px-5 py-4 whitespace-nowrap">
                                    <span class="font-bold text-gray-900 dark:text-white">{{ customer.ordersCount }}</span> Orders
                                </td>
                                <td class="px-5 py-4 whitespace-nowrap font-extrabold text-violet-600 dark:text-violet-400">
                                    {{ customer.totalSpent }}
                                </td>
                                <td class="px-5 py-4 whitespace-nowrap text-xs text-gray-400">
                                    {{ customer.joinedDate }}
                                </td>
                                <td class="px-5 py-4 text-right whitespace-nowrap">
                                    <button title="View Profile" class="p-1.5 rounded-lg border border-gray-200 dark:border-neutral-700 hover:bg-gray-100 dark:hover:bg-neutral-800 text-gray-600 dark:text-neutral-300 transition">
                                        <Eye class="w-4 h-4" />
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
