<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';
import { Star, CheckCircle2, XCircle, Trash2, MessageSquare } from '@lucide/vue';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Reviews', href: '/dashboard/reviews' },
];

const reviews = [
    {
        id: 1,
        customer: 'Rina Akter',
        city: 'Dhaka',
        product: 'Wireless Noise-Cancelling Headphones',
        rating: 5,
        comment: 'Ordered a phone case and headphones. Arrived in two days! Cash on delivery made it an easy purchase.',
        date: 'Jul 28, 2026',
        status: 'Approved',
    },
    {
        id: 2,
        customer: 'Tanvir Hasan',
        city: 'Chattogram',
        product: 'Smart Fitness Watch Series 6',
        rating: 5,
        comment: 'Great prices on electronics and the size guide on clothing actually matches! Will keep shopping here.',
        date: 'Jul 26, 2026',
        status: 'Approved',
    },
    {
        id: 3,
        customer: 'Nusrat Jahan',
        city: 'Sylhet',
        product: 'Classic Leather Sneakers',
        rating: 4,
        comment: 'Customer support helped me update delivery address right after placing order. Very trustworthy store.',
        date: 'Jul 24, 2026',
        status: 'Approved',
    },
    {
        id: 4,
        customer: 'Shafiqur Rahman',
        city: 'Rajshahi',
        product: 'Minimalist Backpack 20L',
        rating: 5,
        comment: 'Fabric quality is outstanding. Super comfortable bag for daily university commute.',
        date: 'Jul 22, 2026',
        status: 'Pending',
    },
];
</script>

<template>
    <Head title="Reviews — ShopEase Admin" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col gap-6 p-6 max-w-7xl mx-auto w-full">
            <!-- Header -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-extrabold tracking-tight text-gray-900 dark:text-white">Customer Reviews</h1>
                    <p class="mt-1 text-sm text-gray-500 dark:text-neutral-400">
                        Monitor ratings and customer feedback for storefront products.
                    </p>
                </div>
            </div>

            <!-- Stats Bar -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                <div class="p-4 rounded-xl border border-gray-200 dark:border-neutral-800 bg-white dark:bg-neutral-900">
                    <span class="text-xs font-semibold text-gray-400">Average Rating</span>
                    <p class="mt-2 text-xl font-extrabold text-amber-500 flex items-center gap-1">
                        4.8 <Star class="w-5 h-5 fill-current" />
                    </p>
                </div>
                <div class="p-4 rounded-xl border border-gray-200 dark:border-neutral-800 bg-white dark:bg-neutral-900">
                    <span class="text-xs font-semibold text-gray-400">Total Reviews</span>
                    <p class="mt-2 text-xl font-extrabold text-gray-900 dark:text-white">652</p>
                </div>
                <div class="p-4 rounded-xl border border-gray-200 dark:border-neutral-800 bg-white dark:bg-neutral-900">
                    <span class="text-xs font-semibold text-gray-400">Pending Approval</span>
                    <p class="mt-2 text-xl font-extrabold text-amber-600">4</p>
                </div>
                <div class="p-4 rounded-xl border border-gray-200 dark:border-neutral-800 bg-white dark:bg-neutral-900">
                    <span class="text-xs font-semibold text-gray-400">5-Star Satisfaction</span>
                    <p class="mt-2 text-xl font-extrabold text-emerald-600">82%</p>
                </div>
            </div>

            <!-- Table -->
            <div class="rounded-2xl border border-gray-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 overflow-hidden shadow-xs">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-600 dark:text-neutral-300">
                        <thead class="bg-gray-50 dark:bg-neutral-800/50 text-xs font-semibold uppercase text-gray-500 dark:text-neutral-400">
                            <tr>
                                <th class="px-5 py-3.5">Rating & Product</th>
                                <th class="px-5 py-3.5">Review Comment</th>
                                <th class="px-5 py-3.5">Customer</th>
                                <th class="px-5 py-3.5">Status</th>
                                <th class="px-5 py-3.5 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-neutral-800">
                            <tr v-for="review in reviews" :key="review.id" class="hover:bg-gray-50/50 dark:hover:bg-neutral-800/30 transition">
                                <td class="px-5 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-1 text-amber-500 mb-1">
                                        <Star v-for="i in review.rating" :key="i" class="w-3.5 h-3.5 fill-current" />
                                    </div>
                                    <p class="font-bold text-gray-900 dark:text-white max-w-xs truncate text-xs">{{ review.product }}</p>
                                </td>
                                <td class="px-5 py-4 max-w-md">
                                    <p class="text-xs text-gray-700 dark:text-neutral-300 leading-relaxed font-medium">"{{ review.comment }}"</p>
                                    <span class="text-[10px] text-gray-400 block mt-1">{{ review.date }}</span>
                                </td>
                                <td class="px-5 py-4 whitespace-nowrap">
                                    <p class="font-bold text-gray-900 dark:text-white text-xs">{{ review.customer }}</p>
                                    <p class="text-xs text-gray-400">{{ review.city }}</p>
                                </td>
                                <td class="px-5 py-4 whitespace-nowrap">
                                    <span :class="[
                                        'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold',
                                        review.status === 'Approved' ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950 dark:text-emerald-300' : 'bg-amber-50 text-amber-700 dark:bg-amber-950 dark:text-amber-300'
                                    ]">
                                        {{ review.status }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-right whitespace-nowrap">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <button v-if="review.status === 'Pending'" title="Approve" class="p-1.5 rounded-lg bg-emerald-50 text-emerald-600 hover:bg-emerald-100 transition">
                                            <CheckCircle2 class="w-4 h-4" />
                                        </button>
                                        <button title="Delete" class="p-1.5 rounded-lg border border-gray-200 dark:border-neutral-700 hover:bg-red-50 text-red-600 transition">
                                            <Trash2 class="w-4 h-4" />
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
