<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';
import {
    Plus,
    Search,
    FolderTree,
    Edit3,
    Trash2,
    CheckCircle2,
    Package,
    ArrowUpRight,
} from '@lucide/vue';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Categories', href: '/dashboard/categories' },
];

const searchQuery = ref('');

const categories = [
    {
        id: 1,
        name: 'Electronics',
        slug: 'electronics',
        productsCount: 124,
        description: 'Gadgets, smartphones, audio devices, and home electronics.',
        img: 'https://images.unsplash.com/photo-1498049794561-7780e7231661?auto=format&fit=crop&w=400&q=70',
        status: 'Active',
    },
    {
        id: 2,
        name: 'Fashion',
        slug: 'fashion',
        productsCount: 98,
        description: 'Clothing, footwear, sneakers, and fashion accessories.',
        img: 'https://images.unsplash.com/photo-1445205170230-053b83016050?auto=format&fit=crop&w=400&q=70',
        status: 'Active',
    },
    {
        id: 3,
        name: 'Home & Living',
        slug: 'home-living',
        productsCount: 64,
        description: 'Furniture, decor, kitchenware, and lifestyle items.',
        img: 'https://images.unsplash.com/photo-1556228453-efd6c1ff04f6?auto=format&fit=crop&w=400&q=70',
        status: 'Active',
    },
    {
        id: 4,
        name: 'Beauty & Health',
        slug: 'beauty-health',
        productsCount: 36,
        description: 'Skincare, cosmetics, personal care, and grooming.',
        img: 'https://images.unsplash.com/photo-1596462502278-27bfdc403348?auto=format&fit=crop&w=400&q=70',
        status: 'Active',
    },
    {
        id: 5,
        name: 'Sports & Outdoors',
        slug: 'sports-outdoors',
        productsCount: 18,
        description: 'Fitness equipment, athletic gear, and camping equipment.',
        img: 'https://images.unsplash.com/photo-1517649763962-0c623066013b?auto=format&fit=crop&w=400&q=70',
        status: 'Active',
    },
    {
        id: 6,
        name: 'Books & Stationery',
        slug: 'books-stationery',
        productsCount: 12,
        description: 'Novels, educational books, notebooks, and office supplies.',
        img: 'https://images.unsplash.com/photo-1512820790803-83ca734da794?auto=format&fit=crop&w=400&q=70',
        status: 'Active',
    },
];
</script>

<template>
    <Head title="Categories — ShopEase Admin" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col gap-6 p-6 max-w-7xl mx-auto w-full">
            <!-- Header -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-extrabold tracking-tight text-gray-900 dark:text-white">Product Categories</h1>
                    <p class="mt-1 text-sm text-gray-500 dark:text-neutral-400">
                        Organize product categories displayed on storefront homepage and filter menus.
                    </p>
                </div>
                <button
                    type="button"
                    class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-violet-600 hover:bg-violet-700 text-white font-semibold text-sm transition shadow-xs"
                >
                    <Plus class="w-4 h-4" /> Add Category
                </button>
            </div>

            <!-- Categories Card Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <div
                    v-for="category in categories"
                    :key="category.id"
                    class="group flex flex-col rounded-2xl border border-gray-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 overflow-hidden shadow-xs hover:shadow-md transition"
                >
                    <div class="relative aspect-video overflow-hidden bg-gray-100 dark:bg-neutral-800">
                        <img :src="category.img" :alt="category.name" class="w-full h-full object-cover group-hover:scale-105 transition duration-500" />
                        <div class="absolute top-3 right-3 flex items-center gap-2">
                            <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-500 text-white shadow-xs">
                                {{ category.status }}
                            </span>
                        </div>
                    </div>
                    <div class="flex flex-col flex-1 p-5">
                        <div class="flex items-center justify-between">
                            <h2 class="text-lg font-bold text-gray-900 dark:text-white">{{ category.name }}</h2>
                            <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-violet-50 text-violet-700 dark:bg-violet-950 dark:text-violet-300">
                                {{ category.productsCount }} Products
                            </span>
                        </div>
                        <p class="mt-1 text-xs font-mono text-gray-400">/category/{{ category.slug }}</p>
                        <p class="mt-2 text-xs text-gray-500 dark:text-neutral-400 line-clamp-2">{{ category.description }}</p>

                        <div class="mt-auto pt-4 flex items-center justify-between border-t border-gray-100 dark:border-neutral-800">
                            <span class="text-xs text-gray-400">ID: #CAT-0{{ category.id }}</span>
                            <div class="flex items-center gap-2">
                                <button class="p-1.5 rounded-lg border border-gray-200 dark:border-neutral-700 hover:bg-gray-100 dark:hover:bg-neutral-800 text-gray-600 dark:text-neutral-300 transition">
                                    <Edit3 class="w-4 h-4" />
                                </button>
                                <button class="p-1.5 rounded-lg border border-gray-200 dark:border-neutral-700 hover:bg-red-50 text-red-600 dark:hover:bg-red-950/50 transition">
                                    <Trash2 class="w-4 h-4" />
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
