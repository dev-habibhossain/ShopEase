<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';
import { Ticket, Plus, Copy, Edit3, Trash2, CheckCircle2, Clock } from '@lucide/vue';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Coupons', href: '/dashboard/coupons' },
];

const coupons = [
    {
        id: 1,
        code: 'SUMMER20',
        discount: '20% OFF',
        type: 'Percentage',
        minSpend: '৳ 1,500',
        expiry: 'Aug 31, 2026',
        usedCount: 142,
        limit: 500,
        status: 'Active',
    },
    {
        id: 2,
        code: 'EASE100',
        discount: '৳ 100 OFF',
        type: 'Fixed Amount',
        minSpend: '৳ 1,000',
        expiry: 'Dec 31, 2026',
        usedCount: 389,
        limit: 1000,
        status: 'Active',
    },
    {
        id: 3,
        code: 'EIDSPECIAL',
        discount: '25% OFF',
        type: 'Percentage',
        minSpend: '৳ 2,500',
        expiry: 'Jul 15, 2026',
        usedCount: 500,
        limit: 500,
        status: 'Expired',
    },
    {
        id: 4,
        code: 'WELCOME10',
        discount: '10% OFF',
        type: 'Percentage',
        minSpend: '৳ 500',
        expiry: 'No Expiry',
        usedCount: 842,
        limit: 'Unlimited',
        status: 'Active',
    },
];
</script>

<template>
    <Head title="Coupons — ShopEase Admin" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col gap-6 p-6 max-w-7xl mx-auto w-full">
            <!-- Header -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-extrabold tracking-tight text-gray-900 dark:text-white">Discount Coupons</h1>
                    <p class="mt-1 text-sm text-gray-500 dark:text-neutral-400">
                        Create promotional discount vouchers for storefront customers.
                    </p>
                </div>
                <button
                    type="button"
                    class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-violet-600 hover:bg-violet-700 text-white font-semibold text-sm transition shadow-xs"
                >
                    <Plus class="w-4 h-4" /> Create Coupon
                </button>
            </div>

            <!-- Coupons Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <div
                    v-for="coupon in coupons"
                    :key="coupon.id"
                    class="rounded-2xl border border-gray-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 p-5 flex flex-col justify-between shadow-xs relative overflow-hidden"
                >
                    <div class="absolute -top-3 -right-3 w-16 h-16 bg-violet-500/10 rounded-full blur-xl pointer-events-none"></div>

                    <div>
                        <div class="flex items-center justify-between mb-3">
                            <span :class="[
                                'px-2.5 py-0.5 rounded-full text-xs font-bold',
                                coupon.status === 'Active' ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-950 dark:text-emerald-300' : 'bg-gray-100 text-gray-500 dark:bg-neutral-800'
                            ]">
                                {{ coupon.status }}
                            </span>
                            <span class="text-xs text-gray-400 font-medium">{{ coupon.type }}</span>
                        </div>

                        <div class="p-3 rounded-xl border border-dashed border-violet-300 dark:border-violet-800 bg-violet-50/50 dark:bg-violet-950/30 flex items-center justify-between">
                            <span class="font-mono text-lg font-extrabold text-violet-700 dark:text-violet-300 tracking-wider">{{ coupon.code }}</span>
                            <button title="Copy Code" class="p-1 rounded-md text-violet-600 hover:bg-violet-100 dark:hover:bg-violet-900/50 transition">
                                <Copy class="w-4 h-4" />
                            </button>
                        </div>

                        <div class="mt-4 space-y-1.5 text-xs text-gray-600 dark:text-neutral-300">
                            <div class="flex justify-between">
                                <span class="text-gray-400">Discount:</span>
                                <span class="font-bold text-gray-900 dark:text-white">{{ coupon.discount }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-400">Min Spend:</span>
                                <span class="font-medium">{{ coupon.minSpend }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-400">Expiry:</span>
                                <span class="font-medium">{{ coupon.expiry }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="mt-5 pt-3 border-t border-gray-100 dark:border-neutral-800 flex items-center justify-between text-xs">
                        <span class="text-gray-400 font-medium">Used: {{ coupon.usedCount }} / {{ coupon.limit }}</span>
                        <div class="flex items-center gap-1">
                            <button class="p-1 rounded text-gray-500 hover:text-violet-600 transition"><Edit3 class="w-3.5 h-3.5" /></button>
                            <button class="p-1 rounded text-gray-500 hover:text-red-600 transition"><Trash2 class="w-3.5 h-3.5" /></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
