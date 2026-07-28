<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import { Search, Star, CheckCircle, Trash2 } from '@lucide/vue';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';

const props = defineProps<{
    reviews: {
        data: Array<{
            id: number;
            rating: number;
            comment?: string;
            is_approved: boolean;
            created_at: string;
            user?: { name: string; email: string };
            product?: { name: string };
        }>;
    };
    filters: { search?: string; status?: string };
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Reviews', href: '/dashboard/reviews' },
];

const search = ref(props.filters.search || '');
const selectedStatus = ref(props.filters.status || '');

const applyFilters = () => {
    router.get('/dashboard/reviews', {
        search: search.value,
        status: selectedStatus.value,
    }, { preserveState: true, replace: true });
};

const toggleApproval = (review: any) => {
    router.put(`/dashboard/reviews/${review.id}`, {
        is_approved: !review.is_approved,
    });
};

const deleteReview = (id: number) => {
    if (confirm('Are you sure you want to delete this review?')) {
        router.delete(`/dashboard/reviews/${id}`);
    }
};
</script>

<template>
    <Head title="Customer Reviews — ShopEase Admin" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8 space-y-8 bg-white min-h-screen">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-gray-200 pb-6">
                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-gray-900">Product Reviews Moderation</h1>
                    <p class="text-sm text-gray-500 mt-1">Approve, decline, or remove user-submitted product ratings and comments</p>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row items-center justify-between gap-4 rounded-xl border border-gray-200 bg-gray-50/50 p-4">
                <div class="relative w-full sm:w-80">
                    <Search class="absolute left-3 top-2.5 h-4 w-4 text-gray-400" />
                    <input
                        v-model="search"
                        @keyup.enter="applyFilters"
                        type="text"
                        placeholder="Search product or reviewer..."
                        class="w-full rounded-lg border border-gray-300 bg-white py-2 pl-9 pr-4 text-xs text-gray-900 focus:border-violet-600 focus:outline-none"
                    />
                </div>

                <select v-model="selectedStatus" @change="applyFilters" class="rounded-lg border border-gray-300 bg-white px-3 py-2 text-xs font-medium text-gray-700">
                    <option value="">All Statuses</option>
                    <option value="approved">Approved</option>
                    <option value="pending">Pending Approval</option>
                </select>
            </div>

            <div class="rounded-xl border border-gray-200 bg-white overflow-hidden shadow-xs">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-600">
                        <thead class="bg-gray-50 text-xs font-semibold uppercase tracking-wider text-gray-500 border-b border-gray-200">
                            <tr>
                                <th class="px-5 py-3.5">Reviewer</th>
                                <th class="px-5 py-3.5">Product</th>
                                <th class="px-5 py-3.5">Rating</th>
                                <th class="px-5 py-3.5">Comment</th>
                                <th class="px-5 py-3.5">Status</th>
                                <th class="px-5 py-3.5 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <tr v-for="rev in reviews.data" :key="rev.id" class="hover:bg-gray-50/80 transition">
                                <td class="px-5 py-4 font-bold text-gray-900 text-xs sm:text-sm">
                                    {{ rev.user?.name || 'Anonymous User' }}
                                </td>
                                <td class="px-5 py-4 text-xs font-semibold text-gray-700">
                                    {{ rev.product?.name || 'Deleted Product' }}
                                </td>
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-1 text-amber-500 font-bold text-xs">
                                        <Star class="w-4 h-4 fill-amber-400 text-amber-400" />
                                        {{ rev.rating }}.0
                                    </div>
                                </td>
                                <td class="px-5 py-4 text-xs text-gray-600 max-w-xs truncate">
                                    {{ rev.comment || 'No comment provided' }}
                                </td>
                                <td class="px-5 py-4">
                                    <span :class="['inline-flex rounded-full px-2.5 py-0.5 text-xs font-bold', rev.is_approved ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700']">
                                        {{ rev.is_approved ? 'Approved' : 'Pending' }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <button
                                            @click="toggleApproval(rev)"
                                            type="button"
                                            :class="['inline-flex h-8 px-2.5 items-center justify-center rounded-lg text-xs font-semibold border transition', rev.is_approved ? 'border-amber-300 bg-amber-50 text-amber-800 hover:bg-amber-100' : 'border-emerald-300 bg-emerald-50 text-emerald-800 hover:bg-emerald-100']"
                                        >
                                            <CheckCircle class="w-3.5 h-3.5 mr-1" /> {{ rev.is_approved ? 'Unapprove' : 'Approve' }}
                                        </button>
                                        <button
                                            @click="deleteReview(rev.id)"
                                            type="button"
                                            class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-red-200 text-red-600 hover:bg-red-600 hover:text-white transition"
                                        >
                                            <Trash2 class="w-3.5 h-3.5" />
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="!reviews.data.length">
                                <td colspan="6" class="px-5 py-8 text-center text-xs text-gray-500">No product reviews found.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
