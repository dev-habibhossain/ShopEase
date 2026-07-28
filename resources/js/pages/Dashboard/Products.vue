<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { ref } from 'vue';
import {
    Plus,
    Search,
    Filter,
    Package,
    CheckCircle2,
    XCircle,
    Edit3,
    Trash2,
    Eye,
    Star,
    Layers,
} from '@lucide/vue';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Products', href: '/dashboard/products' },
];

const searchQuery = ref('');
const selectedCategory = ref('all');
const selectedStock = ref('all');

const products = [
    {
        id: 1,
        name: 'Wireless Noise-Cancelling Headphones',
        sku: 'ELEC-WNH-01',
        category: 'Electronics',
        price: '৳ 6,499',
        oldPrice: '৳ 8,999',
        stock: 45,
        inStock: true,
        rating: 4.8,
        reviews: 214,
        img: 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?auto=format&fit=crop&w=150&q=70',
    },
    {
        id: 2,
        name: 'Smart Fitness Watch Series 6',
        sku: 'ELEC-SFW-06',
        category: 'Electronics',
        price: '৳ 4,299',
        oldPrice: null,
        stock: 18,
        inStock: true,
        rating: 4.6,
        reviews: 167,
        img: 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?auto=format&fit=crop&w=150&q=70',
    },
    {
        id: 3,
        name: 'Classic Leather Sneakers',
        sku: 'FASH-CLS-02',
        category: 'Fashion',
        price: '৳ 2,999',
        oldPrice: '৳ 3,999',
        stock: 32,
        inStock: true,
        rating: 4.7,
        reviews: 132,
        img: 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?auto=format&fit=crop&w=150&q=70',
    },
    {
        id: 4,
        name: 'Premium Sunglasses UV400',
        sku: 'FASH-PSG-01',
        category: 'Fashion',
        price: '৳ 1,599',
        oldPrice: null,
        stock: 0,
        inStock: false,
        rating: 4.5,
        reviews: 98,
        img: 'https://images.unsplash.com/photo-1572635196237-14b3f281503f?auto=format&fit=crop&w=150&q=70',
    },
    {
        id: 5,
        name: 'Minimalist Backpack 20L',
        sku: 'FASH-MBP-20',
        category: 'Fashion',
        price: '৳ 2,499',
        oldPrice: null,
        stock: 12,
        inStock: true,
        rating: 4.4,
        reviews: 41,
        img: 'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?auto=format&fit=crop&w=150&q=70',
    },
    {
        id: 6,
        name: 'Ceramic Pour-Over Coffee Set',
        sku: 'HOME-PCS-02',
        category: 'Home & Living',
        price: '৳ 1,899',
        oldPrice: null,
        stock: 4,
        inStock: true,
        rating: 4.9,
        reviews: 23,
        img: 'https://images.unsplash.com/photo-1495774856032-8b90bbb32b32?auto=format&fit=crop&w=150&q=70',
    },
];
</script>

<template>
    <Head title="Products Inventory — ShopEase Admin" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col gap-6 p-6 max-w-7xl mx-auto w-full">
            <!-- Header -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-extrabold tracking-tight text-gray-900 dark:text-white">Product Inventory</h1>
                    <p class="mt-1 text-sm text-gray-500 dark:text-neutral-400">
                        Manage catalog items, pricing, inventory stock, and ratings.
                    </p>
                </div>
                <button
                    type="button"
                    class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-violet-600 hover:bg-violet-700 text-white font-semibold text-sm transition shadow-xs"
                >
                    <Plus class="w-4 h-4" /> Add New Product
                </button>
            </div>

            <!-- Stats Bar -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                <div class="p-4 rounded-xl border border-gray-200 dark:border-neutral-800 bg-white dark:bg-neutral-900">
                    <div class="flex items-center gap-2 text-xs font-semibold text-gray-400">
                        <Package class="w-4 h-4 text-violet-600" /> Total Products
                    </div>
                    <p class="mt-2 text-xl font-extrabold text-gray-900 dark:text-white">342</p>
                </div>
                <div class="p-4 rounded-xl border border-gray-200 dark:border-neutral-800 bg-white dark:bg-neutral-900">
                    <div class="flex items-center gap-2 text-xs font-semibold text-gray-400">
                        <CheckCircle2 class="w-4 h-4 text-emerald-600" /> In Stock
                    </div>
                    <p class="mt-2 text-xl font-extrabold text-gray-900 dark:text-white">318</p>
                </div>
                <div class="p-4 rounded-xl border border-gray-200 dark:border-neutral-800 bg-white dark:bg-neutral-900">
                    <div class="flex items-center gap-2 text-xs font-semibold text-gray-400">
                        <XCircle class="w-4 h-4 text-red-600" /> Out of Stock
                    </div>
                    <p class="mt-2 text-xl font-extrabold text-gray-900 dark:text-white">24</p>
                </div>
                <div class="p-4 rounded-xl border border-gray-200 dark:border-neutral-800 bg-white dark:bg-neutral-900">
                    <div class="flex items-center gap-2 text-xs font-semibold text-gray-400">
                        <Layers class="w-4 h-4 text-amber-600" /> Active Categories
                    </div>
                    <p class="mt-2 text-xl font-extrabold text-gray-900 dark:text-white">6</p>
                </div>
            </div>

            <!-- Filters Bar -->
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4 p-4 rounded-xl border border-gray-200 dark:border-neutral-800 bg-white dark:bg-neutral-900">
                <div class="relative w-full sm:max-w-xs">
                    <Search class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" />
                    <input
                        v-model="searchQuery"
                        type="text"
                        placeholder="Search product, SKU..."
                        class="w-full pl-9 pr-4 py-2 text-sm rounded-lg border border-gray-300 dark:border-neutral-700 bg-gray-50 dark:bg-neutral-800 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-violet-600"
                    />
                </div>

                <div class="flex items-center gap-3 w-full sm:w-auto">
                    <select
                        v-model="selectedCategory"
                        class="px-3 py-2 text-sm rounded-lg border border-gray-300 dark:border-neutral-700 bg-gray-50 dark:bg-neutral-800 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-violet-600"
                    >
                        <option value="all">All Categories</option>
                        <option value="electronics">Electronics</option>
                        <option value="fashion">Fashion</option>
                        <option value="home">Home & Living</option>
                        <option value="beauty">Beauty</option>
                    </select>

                    <select
                        v-model="selectedStock"
                        class="px-3 py-2 text-sm rounded-lg border border-gray-300 dark:border-neutral-700 bg-gray-50 dark:bg-neutral-800 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-violet-600"
                    >
                        <option value="all">All Stock Status</option>
                        <option value="instock">In Stock</option>
                        <option value="outstock">Out of Stock</option>
                    </select>
                </div>
            </div>

            <!-- Table -->
            <div class="rounded-2xl border border-gray-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 overflow-hidden shadow-xs">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-600 dark:text-neutral-300">
                        <thead class="bg-gray-50 dark:bg-neutral-800/50 text-xs font-semibold uppercase text-gray-500 dark:text-neutral-400">
                            <tr>
                                <th class="px-5 py-3.5">Product</th>
                                <th class="px-5 py-3.5">Category</th>
                                <th class="px-5 py-3.5">Price</th>
                                <th class="px-5 py-3.5">Stock</th>
                                <th class="px-5 py-3.5">Rating</th>
                                <th class="px-5 py-3.5 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-neutral-800">
                            <tr v-for="product in products" :key="product.id" class="hover:bg-gray-50/50 dark:hover:bg-neutral-800/30 transition">
                                <td class="px-5 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        <img :src="product.img" :alt="product.name" class="w-12 h-12 rounded-lg object-cover bg-gray-100 dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700" />
                                        <div>
                                            <p class="font-bold text-gray-900 dark:text-white max-w-xs truncate">{{ product.name }}</p>
                                            <p class="text-xs text-gray-400 font-mono">{{ product.sku }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-4 whitespace-nowrap">
                                    <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-700 dark:bg-neutral-800 dark:text-neutral-300">
                                        {{ product.category }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 whitespace-nowrap">
                                    <div class="font-extrabold text-violet-600 dark:text-violet-400">{{ product.price }}</div>
                                    <div v-if="product.oldPrice" class="text-xs text-gray-400 line-through">{{ product.oldPrice }}</div>
                                </td>
                                <td class="px-5 py-4 whitespace-nowrap">
                                    <span
                                        :class="[
                                            'inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold',
                                            product.inStock ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950 dark:text-emerald-300' : 'bg-red-50 text-red-700 dark:bg-red-950 dark:text-red-300'
                                        ]"
                                    >
                                        {{ product.inStock ? `${product.stock} Units` : 'Stock Out' }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-1 text-amber-500 font-bold text-xs">
                                        <Star class="w-4 h-4 fill-current" />
                                        <span>{{ product.rating }}</span>
                                        <span class="text-gray-400 font-normal">({{ product.reviews }})</span>
                                    </div>
                                </td>
                                <td class="px-5 py-4 text-right whitespace-nowrap">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <button title="View" class="p-1.5 rounded-lg border border-gray-200 dark:border-neutral-700 hover:bg-gray-100 dark:hover:bg-neutral-800 text-gray-600 dark:text-neutral-300 transition">
                                            <Eye class="w-4 h-4" />
                                        </button>
                                        <button title="Edit" class="p-1.5 rounded-lg border border-gray-200 dark:border-neutral-700 hover:bg-gray-100 dark:hover:bg-neutral-800 text-gray-600 dark:text-neutral-300 transition">
                                            <Edit3 class="w-4 h-4" />
                                        </button>
                                        <button title="Delete" class="p-1.5 rounded-lg border border-gray-200 dark:border-neutral-700 hover:bg-red-50 text-red-600 dark:hover:bg-red-950/50 transition">
                                            <Trash2 class="w-4 h-4" />
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Footer Pagination -->
                <div class="flex items-center justify-between p-4 border-t border-gray-100 dark:border-neutral-800 text-xs text-gray-500">
                    <span>Showing 1 to 6 of 342 products</span>
                    <div class="flex items-center gap-2">
                        <button disabled class="px-3 py-1.5 rounded-lg border border-gray-200 dark:border-neutral-800 opacity-50 cursor-not-allowed">Previous</button>
                        <button class="px-3 py-1.5 rounded-lg bg-violet-600 text-white font-medium">1</button>
                        <button class="px-3 py-1.5 rounded-lg border border-gray-200 dark:border-neutral-800 hover:bg-gray-100 dark:hover:bg-neutral-800">2</button>
                        <button class="px-3 py-1.5 rounded-lg border border-gray-200 dark:border-neutral-800 hover:bg-gray-100 dark:hover:bg-neutral-800">Next</button>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
