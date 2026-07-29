<script setup lang="ts">
import { Head, router, useForm } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import {
    Plus,
    Search,
    Package,
    Edit3,
    Trash2,
    X,
    Upload,
    Image as ImageIcon,
} from '@lucide/vue';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';

const props = defineProps<{
    products: {
        data: Array<{
            id: number;
            category_id: number;
            name: string;
            slug: string;
            short_description?: string;
            description?: string;
            price: number;
            compare_at_price?: number;
            stock_status: string;
            is_featured: boolean;
            is_best_seller: boolean;
            is_active: boolean;
            sold_count: number;
            category?: { id: number; name: string };
            images?: Array<{ id: number; image_path: string; is_primary: boolean }>;
        }>;
        links: Array<{ url: string | null; label: string; active: boolean }>;
    };
    categories: Array<{ id: number; name: string }>;
    filters: { search?: string; category_id?: string; stock_status?: string };
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Products', href: '/dashboard/products' },
];

const search = ref(props.filters.search || '');
const selectedCategory = ref(props.filters.category_id || '');
const selectedStock = ref(props.filters.stock_status || '');

const applyFilters = () => {
    router.get('/dashboard/products', {
        search: search.value,
        category_id: selectedCategory.value,
        stock_status: selectedStock.value,
    }, { preserveState: true, replace: true });
};

watch([selectedCategory, selectedStock], () => {
    applyFilters();
});

// Modal State
const isModalOpen = ref(false);
const isEditing = ref(false);
const editingProductId = ref<number | null>(null);
const imagePreview = ref<string | null>(null);
const imageInputType = ref<'file' | 'url'>('file');

const form = useForm({
    category_id: props.categories[0]?.id || '',
    name: '',
    short_description: '',
    description: '',
    price: '',
    compare_at_price: '',
    stock_status: 'in_stock',
    is_featured: false,
    is_best_seller: false,
    is_active: true,
    image: null as File | null,
    image_url: '',
    _method: 'POST',
});

const handleFileChange = (e: Event) => {
    const target = e.target as HTMLInputElement;
    if (target.files && target.files[0]) {
        const file = target.files[0];
        form.image = file;
        imagePreview.value = URL.createObjectURL(file);
    }
};

const openCreateModal = () => {
    isEditing.value = false;
    editingProductId.value = null;
    imagePreview.value = null;
    form.reset();
    form.clearErrors();
    form._method = 'POST';
    form.category_id = props.categories[0]?.id || '';
    isModalOpen.value = true;
};

const openEditModal = (product: any) => {
    isEditing.value = true;
    editingProductId.value = product.id;
    form.clearErrors();
    form._method = 'PUT';
    form.category_id = product.category_id;
    form.name = product.name;
    form.short_description = product.short_description || '';
    form.description = product.description || '';
    form.price = product.price;
    form.compare_at_price = product.compare_at_price || '';
    form.stock_status = product.stock_status;
    form.is_featured = Boolean(product.is_featured);
    form.is_best_seller = Boolean(product.is_best_seller);
    form.is_active = Boolean(product.is_active);
    form.image = null;

    const primaryImg = product.images?.find((img: any) => img.is_primary) || product.images?.[0];
    if (primaryImg) {
        const path = primaryImg.image_path;
        const formattedPath = (path.startsWith('http') || path.startsWith('/storage/')) ? path : `https://images.unsplash.com/${path}?auto=format&fit=crop&w=300&q=80`;
        imagePreview.value = formattedPath;
        form.image_url = path;
    } else {
        imagePreview.value = null;
        form.image_url = '';
    }

    isModalOpen.value = true;
};

const closeModal = () => {
    isModalOpen.value = false;
    form.reset();
    imagePreview.value = null;
};

const submitForm = () => {
    if (isEditing.value && editingProductId.value) {
        form.post(`/dashboard/products/${editingProductId.value}`, {
            onSuccess: () => closeModal(),
        });
    } else {
        form.post('/dashboard/products', {
            onSuccess: () => closeModal(),
        });
    }
};

const deleteProduct = (id: number) => {
    if (confirm('Are you sure you want to delete this product?')) {
        router.delete(`/dashboard/products/${id}`);
    }
};

const getProductThumbnail = (product: any) => {
    const primaryImg = product.images?.find((img: any) => img.is_primary) || product.images?.[0];
    const path = primaryImg?.image_path || (product as any).primary_image || null;
    if (!path) return null;
    if (path.startsWith('http') || path.startsWith('/storage/')) return path;
    return `https://images.unsplash.com/${path}?auto=format&fit=crop&w=120&q=70`;
};
</script>

<template>
    <Head title="Products Management — ShopEase Admin" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8 space-y-8 bg-white min-h-screen">
            <!-- Top Header -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-gray-200 pb-6">
                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-gray-900">Products Catalog</h1>
                    <p class="text-sm text-gray-500 mt-1">Manage storefront inventory, images, prices, categories, and stock availability</p>
                </div>
                <button
                    @click="openCreateModal"
                    type="button"
                    class="inline-flex items-center justify-center gap-2 rounded-lg bg-violet-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-violet-700"
                >
                    <Plus class="w-4 h-4" /> Add New Product
                </button>
            </div>

            <!-- Filters Strip -->
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4 rounded-xl border border-gray-200 bg-gray-50/50 p-4">
                <div class="relative w-full sm:w-80">
                    <Search class="absolute left-3 top-2.5 h-4 w-4 text-gray-400" />
                    <input
                        v-model="search"
                        @keyup.enter="applyFilters"
                        type="text"
                        placeholder="Search by product name..."
                        class="w-full rounded-lg border border-gray-300 bg-white py-2 pl-9 pr-4 text-xs text-gray-900 placeholder:text-gray-400 focus:border-violet-600 focus:outline-none focus:ring-2 focus:ring-violet-600"
                    />
                </div>

                <div class="flex flex-wrap items-center gap-3 w-full sm:w-auto">
                    <select
                        v-model="selectedCategory"
                        class="rounded-lg border border-gray-300 bg-white px-3 py-2 text-xs font-medium text-gray-700 focus:border-violet-600 focus:outline-none"
                    >
                        <option value="">All Categories</option>
                        <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
                    </select>

                    <select
                        v-model="selectedStock"
                        class="rounded-lg border border-gray-300 bg-white px-3 py-2 text-xs font-medium text-gray-700 focus:border-violet-600 focus:outline-none"
                    >
                        <option value="">All Stock Status</option>
                        <option value="in_stock">In Stock</option>
                        <option value="stock_out">Out of Stock</option>
                    </select>
                </div>
            </div>

            <!-- Products Table -->
            <div class="rounded-xl border border-gray-200 bg-white overflow-hidden shadow-xs">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-600">
                        <thead class="bg-gray-50 text-xs font-semibold uppercase tracking-wider text-gray-500 border-b border-gray-200">
                            <tr>
                                <th class="px-5 py-3.5">Product</th>
                                <th class="px-5 py-3.5">Category</th>
                                <th class="px-5 py-3.5">Price</th>
                                <th class="px-5 py-3.5">Stock</th>
                                <th class="px-5 py-3.5">Status</th>
                                <th class="px-5 py-3.5 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <tr v-for="product in products.data" :key="product.id" class="hover:bg-gray-50/80 transition">
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="h-12 w-12 shrink-0 rounded-lg overflow-hidden border border-gray-200 bg-gray-50 flex items-center justify-center">
                                            <img
                                                v-if="getProductThumbnail(product)"
                                                :src="getProductThumbnail(product)"
                                                :alt="product.name"
                                                class="h-full w-full object-cover"
                                            />
                                            <Package v-else class="w-6 h-6 text-violet-600" />
                                        </div>
                                        <div>
                                            <p class="font-bold text-gray-900 text-xs sm:text-sm">{{ product.name }}</p>
                                            <p class="text-[11px] text-gray-400 font-mono">/product-details/{{ product.slug }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-4 text-xs font-semibold text-gray-700">
                                    {{ product.category?.name || 'Uncategorized' }}
                                </td>
                                <td class="px-5 py-4 font-extrabold text-violet-600 text-xs sm:text-sm">
                                    ৳ {{ Number(product.price).toLocaleString() }}
                                </td>
                                <td class="px-5 py-4">
                                    <span
                                        :class="[
                                            'inline-flex items-center gap-1 rounded-full px-2.5 py-0.5 text-xs font-bold capitalize',
                                            product.stock_status === 'in_stock'
                                                ? 'bg-emerald-50 text-emerald-700 border border-emerald-200'
                                                : 'bg-red-50 text-red-700 border border-red-200',
                                        ]"
                                    >
                                        {{ product.stock_status === 'in_stock' ? 'In Stock' : 'Out of Stock' }}
                                    </span>
                                </td>
                                <td class="px-5 py-4">
                                    <span
                                        :class="[
                                            'inline-flex rounded-full px-2 py-0.5 text-[10px] font-bold',
                                            product.is_active ? 'bg-violet-50 text-violet-700' : 'bg-gray-100 text-gray-600',
                                        ]"
                                    >
                                        {{ product.is_active ? 'Active' : 'Disabled' }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <button
                                            @click="openEditModal(product)"
                                            type="button"
                                            class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-gray-300 text-gray-600 hover:bg-violet-600 hover:text-white transition"
                                        >
                                            <Edit3 class="w-3.5 h-3.5" />
                                        </button>
                                        <button
                                            @click="deleteProduct(product.id)"
                                            type="button"
                                            class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-red-200 text-red-600 hover:bg-red-600 hover:text-white transition"
                                        >
                                            <Trash2 class="w-3.5 h-3.5" />
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="!products.data.length">
                                <td colspan="6" class="px-5 py-8 text-center text-xs text-gray-500">
                                    No products found matching filters.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Add / Edit Modal -->
        <div v-if="isModalOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 p-4 overflow-y-auto">
            <div class="w-full max-w-lg rounded-2xl bg-white p-6 shadow-2xl border border-gray-200 my-8">
                <div class="flex items-center justify-between border-b border-gray-100 pb-4 mb-4">
                    <h3 class="text-base font-bold text-gray-900">{{ isEditing ? 'Edit Product' : 'Add New Product' }}</h3>
                    <button @click="closeModal" class="text-gray-400 hover:text-gray-600"><X class="w-5 h-5" /></button>
                </div>

                <form @submit.prevent="submitForm" class="space-y-4">
                    <!-- Image Upload Section -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-2">Product Image</label>
                        
                        <div class="flex items-center gap-3 mb-3">
                            <button
                                type="button"
                                @click="imageInputType = 'file'"
                                :class="['px-3 py-1.5 rounded-md text-xs font-bold transition', imageInputType === 'file' ? 'bg-violet-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200']"
                            >
                                <Upload class="w-3.5 h-3.5 inline mr-1" /> Upload File
                            </button>
                            <button
                                type="button"
                                @click="imageInputType = 'url'"
                                :class="['px-3 py-1.5 rounded-md text-xs font-bold transition', imageInputType === 'url' ? 'bg-violet-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200']"
                            >
                                <ImageIcon class="w-3.5 h-3.5 inline mr-1" /> Image URL
                            </button>
                        </div>

                        <!-- File Input -->
                        <div v-if="imageInputType === 'file'" class="flex items-center justify-center w-full">
                            <label class="flex flex-col items-center justify-center w-full h-28 border-2 border-dashed border-gray-300 rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition">
                                <div class="flex flex-col items-center justify-center pt-3 pb-3">
                                    <Upload class="w-6 h-6 text-gray-400 mb-1" />
                                    <p class="text-xs text-gray-500 font-semibold">Click to upload product image</p>
                                    <p class="text-[10px] text-gray-400">PNG, JPG, WEBP (Max 4MB)</p>
                                </div>
                                <input type="file" accept="image/*" class="hidden" @change="handleFileChange" />
                            </label>
                        </div>

                        <!-- URL Input -->
                        <div v-else>
                            <input
                                v-model="form.image_url"
                                type="url"
                                class="w-full rounded-lg border border-gray-300 p-2.5 text-xs focus:border-violet-600 focus:outline-none"
                                placeholder="https://images.unsplash.com/..."
                                @input="imagePreview = form.image_url"
                            />
                        </div>

                        <!-- Image Preview Box -->
                        <div v-if="imagePreview" class="mt-3 flex items-center gap-3 p-2 rounded-lg border border-gray-200 bg-gray-50">
                            <img :src="imagePreview" class="w-14 h-14 object-cover rounded-md border border-gray-300" />
                            <div class="min-w-0 flex-1">
                                <p class="text-xs font-bold text-gray-800">Image Selected</p>
                                <p class="text-[10px] text-emerald-600 font-semibold">Ready for save</p>
                            </div>
                            <button type="button" @click="imagePreview = null; form.image = null; form.image_url = ''" class="text-xs text-red-600 hover:underline">
                                Remove
                            </button>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Product Name</label>
                        <input
                            v-model="form.name"
                            type="text"
                            required
                            class="w-full rounded-lg border border-gray-300 p-2.5 text-xs focus:border-violet-600 focus:outline-none"
                            placeholder="e.g. Wireless Noise-Cancelling Headphones"
                        />
                        <p v-if="form.errors.name" class="text-[11px] text-red-600 mt-1">{{ form.errors.name }}</p>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Category</label>
                            <select
                                v-model="form.category_id"
                                required
                                class="w-full rounded-lg border border-gray-300 p-2.5 text-xs focus:border-violet-600 focus:outline-none"
                            >
                                <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Price (৳)</label>
                            <input
                                v-model="form.price"
                                type="number"
                                step="0.01"
                                required
                                class="w-full rounded-lg border border-gray-300 p-2.5 text-xs focus:border-violet-600 focus:outline-none"
                                placeholder="4999.00"
                            />
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Compare Price (৳)</label>
                            <input
                                v-model="form.compare_at_price"
                                type="number"
                                step="0.01"
                                class="w-full rounded-lg border border-gray-300 p-2.5 text-xs focus:border-violet-600 focus:outline-none"
                                placeholder="5999.00"
                            />
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Stock Status</label>
                            <select
                                v-model="form.stock_status"
                                class="w-full rounded-lg border border-gray-300 p-2.5 text-xs focus:border-violet-600 focus:outline-none"
                            >
                                <option value="in_stock">In Stock</option>
                                <option value="stock_out">Out of Stock</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Short Description</label>
                        <input
                            v-model="form.short_description"
                            type="text"
                            class="w-full rounded-lg border border-gray-300 p-2.5 text-xs focus:border-violet-600 focus:outline-none"
                            placeholder="Brief summary for shop card"
                        />
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Full Description</label>
                        <textarea
                            v-model="form.description"
                            rows="4"
                            class="w-full rounded-lg border border-gray-300 p-2.5 text-xs focus:border-violet-600 focus:outline-none"
                            placeholder="Detailed product specification, features, and overview..."
                        ></textarea>
                        <p v-if="form.errors.description" class="text-[11px] text-red-600 mt-1">{{ form.errors.description }}</p>
                    </div>

                    <div class="flex items-center gap-6 pt-2">
                        <label class="flex items-center gap-2 text-xs font-medium text-gray-700 cursor-pointer">
                            <input v-model="form.is_active" type="checkbox" class="rounded text-violet-600 focus:ring-violet-500" /> Active
                        </label>
                        <label class="flex items-center gap-2 text-xs font-medium text-gray-700 cursor-pointer">
                            <input v-model="form.is_featured" type="checkbox" class="rounded text-violet-600 focus:ring-violet-500" /> Featured
                        </label>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                        <button
                            @click="closeModal"
                            type="button"
                            class="rounded-lg border border-gray-300 px-4 py-2 text-xs font-semibold text-gray-700 hover:bg-gray-50"
                        >
                            Cancel
                        </button>
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="rounded-lg bg-violet-600 px-4 py-2 text-xs font-semibold text-white hover:bg-violet-700 disabled:opacity-50"
                        >
                            {{ form.processing ? 'Saving...' : (isEditing ? 'Update Product' : 'Save Product') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
