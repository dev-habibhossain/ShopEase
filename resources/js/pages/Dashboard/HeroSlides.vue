<script setup lang="ts">
import { Head, router, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import { Plus, Image as ImageIcon, Edit3, Trash2, X, Upload } from '@lucide/vue';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';

const props = defineProps<{
    slides: {
        data: Array<{
            id: number;
            image: string;
            link?: string;
            sort_order: number;
            is_active: boolean;
        }>;
    };
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Hero Slides', href: '/dashboard/hero-slides' },
];

// Modal State
const isModalOpen = ref(false);
const isEditing = ref(false);
const editingSlideId = ref<number | null>(null);
const imageInputType = ref<'file' | 'url'>('file');
const imagePreview = ref<string | null>(null);

const form = useForm({
    image: '',
    image_url: '',
    image_file: null as File | null,
    link: '',
    sort_order: 0,
    is_active: true,
    _method: 'POST',
});

const handleFileChange = (e: Event) => {
    const target = e.target as HTMLInputElement;
    if (target.files && target.files[0]) {
        const file = target.files[0];
        form.image_file = file;
        imagePreview.value = URL.createObjectURL(file);
    }
};

const openCreateModal = () => {
    isEditing.value = false;
    editingSlideId.value = null;
    imagePreview.value = null;
    form.reset();
    form.clearErrors();
    form._method = 'POST';
    isModalOpen.value = true;
};

const openEditModal = (slide: any) => {
    isEditing.value = true;
    editingSlideId.value = slide.id;
    form.clearErrors();
    form._method = 'PUT';
    form.image = slide.image;
    form.image_url = slide.image;
    form.image_file = null;
    form.link = slide.link || '';
    form.sort_order = slide.sort_order || 0;
    form.is_active = Boolean(slide.is_active);
    imagePreview.value = slide.image;
    isModalOpen.value = true;
};

const closeModal = () => {
    isModalOpen.value = false;
    form.reset();
    imagePreview.value = null;
};

const submitForm = () => {
    if (isEditing.value && editingSlideId.value) {
        form.post(`/dashboard/hero-slides/${editingSlideId.value}`, {
            onSuccess: () => closeModal(),
        });
    } else {
        form.post('/dashboard/hero-slides', {
            onSuccess: () => closeModal(),
        });
    }
};

const deleteSlide = (id: number) => {
    if (confirm('Are you sure you want to delete this hero slide?')) {
        router.delete(`/dashboard/hero-slides/${id}`);
    }
};
</script>

<template>
    <Head title="Hero Banners — ShopEase Admin" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8 space-y-8 bg-white min-h-screen">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-gray-200 pb-6">
                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-gray-900">Hero Carousel Banners</h1>
                    <p class="text-sm text-gray-500 mt-1">Manage promotional homepage slider banners, links, and display order</p>
                </div>
                <button
                    @click="openCreateModal"
                    type="button"
                    class="inline-flex items-center justify-center gap-2 rounded-lg bg-violet-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-violet-700"
                >
                    <Plus class="w-4 h-4" /> Add Hero Banner
                </button>
            </div>

            <div class="rounded-xl border border-gray-200 bg-white overflow-hidden shadow-xs">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-600">
                        <thead class="bg-gray-50 text-xs font-semibold uppercase tracking-wider text-gray-500 border-b border-gray-200">
                            <tr>
                                <th class="px-5 py-3.5">Banner Image</th>
                                <th class="px-5 py-3.5">Target Link</th>
                                <th class="px-5 py-3.5">Sort Order</th>
                                <th class="px-5 py-3.5">Status</th>
                                <th class="px-5 py-3.5 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <tr v-for="slide in slides.data" :key="slide.id" class="hover:bg-gray-50/80 transition">
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="h-12 w-24 rounded-lg bg-gray-100 border border-gray-200 flex items-center justify-center text-gray-400 overflow-hidden shrink-0 shadow-xs">
                                            <img v-if="slide.image" :src="slide.image" class="w-full h-full object-cover" />
                                            <ImageIcon v-else class="w-5 h-5 text-violet-600" />
                                        </div>
                                        <span class="text-xs font-mono text-gray-600 truncate max-w-xs">{{ slide.image }}</span>
                                    </div>
                                </td>
                                <td class="px-5 py-4 font-mono text-xs text-gray-500">{{ slide.link || 'No link attached' }}</td>
                                <td class="px-5 py-4 font-bold text-xs text-gray-900">{{ slide.sort_order }}</td>
                                <td class="px-5 py-4">
                                    <span :class="['inline-flex rounded-full px-2.5 py-0.5 text-xs font-bold', slide.is_active ? 'bg-emerald-50 text-emerald-700' : 'bg-gray-100 text-gray-600']">
                                        {{ slide.is_active ? 'Active' : 'Disabled' }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <button
                                            @click="openEditModal(slide)"
                                            type="button"
                                            class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-gray-300 text-gray-600 hover:bg-violet-600 hover:text-white transition"
                                        >
                                            <Edit3 class="w-3.5 h-3.5" />
                                        </button>
                                        <button
                                            @click="deleteSlide(slide.id)"
                                            type="button"
                                            class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-red-200 text-red-600 hover:bg-red-600 hover:text-white transition"
                                        >
                                            <Trash2 class="w-3.5 h-3.5" />
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="!slides.data.length">
                                <td colspan="5" class="px-5 py-8 text-center text-xs text-gray-500">No hero slides found.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Add/Edit Modal -->
        <div v-if="isModalOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 p-4 overflow-y-auto">
            <div class="w-full max-w-md rounded-2xl bg-white p-6 shadow-2xl border border-gray-200 my-8">
                <div class="flex items-center justify-between border-b border-gray-100 pb-4 mb-4">
                    <h3 class="text-base font-bold text-gray-900">{{ isEditing ? 'Edit Hero Banner' : 'Add Hero Banner' }}</h3>
                    <button @click="closeModal" class="text-gray-400 hover:text-gray-600"><X class="w-5 h-5" /></button>
                </div>

                <form @submit.prevent="submitForm" class="space-y-4">
                    <!-- Image Selection -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-2">Banner Image</label>

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
                                    <p class="text-xs text-gray-500 font-semibold">Click to upload banner image</p>
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

                        <!-- Preview Box -->
                        <div v-if="imagePreview" class="mt-3 flex items-center gap-3 p-2 rounded-lg border border-gray-200 bg-gray-50">
                            <img :src="imagePreview" class="w-20 h-10 object-cover rounded-md border border-gray-300" />
                            <div class="min-w-0 flex-1">
                                <p class="text-xs font-bold text-gray-800">Banner Selected</p>
                                <p class="text-[10px] text-emerald-600 font-semibold">Ready for upload</p>
                            </div>
                            <button type="button" @click="imagePreview = null; form.image_file = null; form.image_url = ''" class="text-xs text-red-600 hover:underline">
                                Remove
                            </button>
                        </div>
                        <p v-if="form.errors.image" class="text-[11px] text-red-600 mt-1">{{ form.errors.image }}</p>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Target Link URL</label>
                        <input v-model="form.link" type="text" class="w-full rounded-lg border border-gray-300 p-2.5 text-xs focus:border-violet-600 focus:outline-none" placeholder="/shop?category=electronics" />
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Sort Order</label>
                        <input v-model="form.sort_order" type="number" class="w-full rounded-lg border border-gray-300 p-2.5 text-xs focus:border-violet-600 focus:outline-none" />
                    </div>

                    <div class="flex items-center gap-2 pt-2">
                        <input v-model="form.is_active" type="checkbox" class="rounded text-violet-600 focus:ring-violet-500" />
                        <span class="text-xs font-medium text-gray-700">Is Active</span>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                        <button @click="closeModal" type="button" class="rounded-lg border border-gray-300 px-4 py-2 text-xs font-semibold text-gray-700 hover:bg-gray-50">Cancel</button>
                        <button type="submit" :disabled="form.processing" class="rounded-lg bg-violet-600 px-4 py-2 text-xs font-semibold text-white hover:bg-violet-700 disabled:opacity-50">
                            {{ form.processing ? 'Saving...' : (isEditing ? 'Update Banner' : 'Save Banner') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
