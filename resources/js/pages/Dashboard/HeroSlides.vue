<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { Plus, Image, Eye, Edit3, Trash2, Move, Link as LinkIcon } from '@lucide/vue';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Hero Slides', href: '/dashboard/hero-slides' },
];

const slides = [
    {
        id: 1,
        order: 1,
        title: 'Mega Electronics Sale - Up to 40% Off',
        targetUrl: '/shop?category=electronics',
        img: 'https://images.unsplash.com/photo-1441986300917-64674bd600d8?auto=format&fit=crop&w=800&q=70',
        status: 'Active',
    },
    {
        id: 2,
        name: 'New Fashion Arrival 2026 Collection',
        targetUrl: '/shop?category=fashion',
        img: 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?auto=format&fit=crop&w=800&q=70',
        status: 'Active',
    },
    {
        id: 3,
        name: 'Home Essentials & Decor Festival',
        targetUrl: '/shop?category=home-living',
        img: 'https://images.unsplash.com/photo-1556905055-8f358a7a47b2?auto=format&fit=crop&w=800&q=70',
        status: 'Active',
    },
];
</script>

<template>
    <Head title="Hero Slides — ShopEase Admin" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col gap-6 p-6 max-w-7xl mx-auto w-full">
            <!-- Header -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-extrabold tracking-tight text-gray-900 dark:text-white">Homepage Hero Banner Carousel</h1>
                    <p class="mt-1 text-sm text-gray-500 dark:text-neutral-400">
                        Upload and arrange high-resolution promo banners for the main storefront carousel.
                    </p>
                </div>
                <button
                    type="button"
                    class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-violet-600 hover:bg-violet-700 text-white font-semibold text-sm transition shadow-xs"
                >
                    <Plus class="w-4 h-4" /> Add Hero Slide
                </button>
            </div>

            <!-- Slides List -->
            <div class="space-y-4">
                <div
                    v-for="(slide, index) in slides"
                    :key="slide.id"
                    class="flex flex-col md:flex-row items-center gap-6 p-5 rounded-2xl border border-gray-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 shadow-xs"
                >
                    <div class="flex items-center gap-3 shrink-0">
                        <span class="w-8 h-8 rounded-lg bg-gray-100 dark:bg-neutral-800 text-gray-700 dark:text-neutral-300 font-bold flex items-center justify-center text-sm">
                            #{{ index + 1 }}
                        </span>
                        <div class="w-48 h-28 rounded-xl overflow-hidden bg-gray-100 dark:bg-neutral-800 relative border border-gray-200 dark:border-neutral-700">
                            <img :src="slide.img" :alt="slide.title" class="w-full h-full object-cover" />
                        </div>
                    </div>

                    <div class="flex-1 space-y-1">
                        <div class="flex items-center gap-3">
                            <h2 class="text-base font-bold text-gray-900 dark:text-white">{{ slide.title || slide.name }}</h2>
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700 dark:bg-emerald-950 dark:text-emerald-300">
                                {{ slide.status }}
                            </span>
                        </div>
                        <div class="flex items-center gap-1.5 text-xs text-violet-600 dark:text-violet-400 font-mono">
                            <LinkIcon class="w-3.5 h-3.5" />
                            <span>{{ slide.targetUrl }}</span>
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <button class="p-2 rounded-xl border border-gray-200 dark:border-neutral-700 hover:bg-gray-100 dark:hover:bg-neutral-800 text-gray-600 dark:text-neutral-300 transition">
                            <Edit3 class="w-4 h-4" />
                        </button>
                        <button class="p-2 rounded-xl border border-gray-200 dark:border-neutral-700 hover:bg-red-50 text-red-600 transition">
                            <Trash2 class="w-4 h-4" />
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
