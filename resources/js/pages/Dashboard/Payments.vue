<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { CreditCard, DollarSign, CheckCircle2, Clock, Eye, Printer, ShieldCheck } from '@lucide/vue';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Payments', href: '/dashboard/payments' },
];

const payments = [
    {
        txId: 'SSL-987452',
        orderId: 'ORD-8941',
        customer: 'Tanvir Hasan',
        gateway: 'SSLCommerz (bKash/Visa)',
        amount: '৳ 4,299',
        status: 'Successful',
        date: 'Jul 28, 2026 13:12',
    },
    {
        txId: 'COD-8942',
        orderId: 'ORD-8942',
        customer: 'Rina Akter',
        gateway: 'Cash on Delivery',
        amount: '৳ 6,499',
        status: 'Successful',
        date: 'Jul 28, 2026 14:45',
    },
    {
        txId: 'SSL-987410',
        orderId: 'ORD-8939',
        customer: 'Kamal Hossain',
        gateway: 'SSLCommerz (Nagad)',
        amount: '৳ 12,850',
        status: 'Successful',
        date: 'Jul 27, 2026 18:20',
    },
    {
        txId: 'COD-8940',
        orderId: 'ORD-8940',
        customer: 'Nusrat Jahan',
        gateway: 'Cash on Delivery',
        amount: '৳ 2,999',
        status: 'Pending',
        date: 'Jul 28, 2026 11:30',
    },
];
</script>

<template>
    <Head title="Payments — ShopEase Admin" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col gap-6 p-6 max-w-7xl mx-auto w-full">
            <!-- Header -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-extrabold tracking-tight text-gray-900 dark:text-white">Payment Transactions</h1>
                    <p class="mt-1 text-sm text-gray-500 dark:text-neutral-400">
                        View financial logs from SSLCommerz payment gateway and Cash on Delivery.
                    </p>
                </div>
            </div>

            <!-- Stats Bar -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                <div class="p-4 rounded-xl border border-gray-200 dark:border-neutral-800 bg-white dark:bg-neutral-900">
                    <span class="text-xs font-semibold text-gray-400">Total Collected</span>
                    <p class="mt-2 text-xl font-extrabold text-gray-900 dark:text-white">৳ 458,920</p>
                </div>
                <div class="p-4 rounded-xl border border-gray-200 dark:border-neutral-800 bg-white dark:bg-neutral-900">
                    <span class="text-xs font-semibold text-gray-400">SSLCommerz Online</span>
                    <p class="mt-2 text-xl font-extrabold text-violet-600">৳ 284,500</p>
                </div>
                <div class="p-4 rounded-xl border border-gray-200 dark:border-neutral-800 bg-white dark:bg-neutral-900">
                    <span class="text-xs font-semibold text-gray-400">Cash on Delivery</span>
                    <p class="mt-2 text-xl font-extrabold text-blue-600">৳ 174,420</p>
                </div>
                <div class="p-4 rounded-xl border border-gray-200 dark:border-neutral-800 bg-white dark:bg-neutral-900">
                    <span class="text-xs font-semibold text-gray-400">Success Rate</span>
                    <p class="mt-2 text-xl font-extrabold text-emerald-600">98.4%</p>
                </div>
            </div>

            <!-- Table -->
            <div class="rounded-2xl border border-gray-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 overflow-hidden shadow-xs">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-600 dark:text-neutral-300">
                        <thead class="bg-gray-50 dark:bg-neutral-800/50 text-xs font-semibold uppercase text-gray-500 dark:text-neutral-400">
                            <tr>
                                <th class="px-5 py-3.5">Tx ID / Order</th>
                                <th class="px-5 py-3.5">Customer</th>
                                <th class="px-5 py-3.5">Gateway Method</th>
                                <th class="px-5 py-3.5">Amount</th>
                                <th class="px-5 py-3.5">Status</th>
                                <th class="px-5 py-3.5 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-neutral-800">
                            <tr v-for="pay in payments" :key="pay.txId" class="hover:bg-gray-50/50 dark:hover:bg-neutral-800/30 transition">
                                <td class="px-5 py-4 whitespace-nowrap">
                                    <span class="font-extrabold text-gray-900 dark:text-white font-mono text-xs">{{ pay.txId }}</span>
                                    <span class="block text-xs text-gray-400 font-mono">Order #{{ pay.orderId }}</span>
                                </td>
                                <td class="px-5 py-4 whitespace-nowrap font-bold text-gray-900 dark:text-white text-xs">
                                    {{ pay.customer }}
                                </td>
                                <td class="px-5 py-4 whitespace-nowrap text-xs">
                                    {{ pay.gateway }}
                                </td>
                                <td class="px-5 py-4 whitespace-nowrap font-extrabold text-violet-600 dark:text-violet-400">
                                    {{ pay.amount }}
                                </td>
                                <td class="px-5 py-4 whitespace-nowrap">
                                    <span :class="[
                                        'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold',
                                        pay.status === 'Successful' ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950 dark:text-emerald-300' : 'bg-amber-50 text-amber-700 dark:bg-amber-950 dark:text-amber-300'
                                    ]">
                                        {{ pay.status }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-right whitespace-nowrap">
                                    <button title="Print Receipt" class="p-1.5 rounded-lg border border-gray-200 dark:border-neutral-700 hover:bg-gray-100 dark:hover:bg-neutral-800 text-gray-600 dark:text-neutral-300 transition">
                                        <Printer class="w-4 h-4" />
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
