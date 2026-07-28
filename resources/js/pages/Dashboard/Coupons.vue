<script setup lang="ts">
import { Head, router, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import {
    Plus,
    Search,
    Ticket,
    Edit3,
    Trash2,
    X,
} from '@lucide/vue';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';

const props = defineProps<{
    coupons: {
        data: Array<{
            id: number;
            code: string;
            type: 'fixed' | 'percent';
            value: number;
            min_order_amount?: number;
            usage_limit?: number;
            used_count: number;
            expires_at?: string;
            is_active: boolean;
        }>;
    };
    filters: { search?: string };
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Coupons', href: '/dashboard/coupons' },
];

const search = ref(props.filters.search || '');

const applyFilters = () => {
    router.get('/dashboard/coupons', { search: search.value }, { preserveState: true, replace: true });
};

// Modal State
const isModalOpen = ref(false);
const isEditing = ref(false);
const editingCouponId = ref<number | null>(null);

const form = useForm({
    code: '',
    type: 'percent',
    value: '',
    min_order_amount: '',
    usage_limit: '',
    expires_at: '',
    is_active: true,
});

const openCreateModal = () => {
    isEditing.value = false;
    editingCouponId.value = null;
    form.reset();
    form.clearErrors();
    isModalOpen.value = true;
};

const openEditModal = (coupon: any) => {
    isEditing.value = true;
    editingCouponId.value = coupon.id;
    form.clearErrors();
    form.code = coupon.code;
    form.type = coupon.type;
    form.value = coupon.value;
    form.min_order_amount = coupon.min_order_amount || '';
    form.usage_limit = coupon.usage_limit || '';
    form.expires_at = coupon.expires_at ? coupon.expires_at.substring(0, 10) : '';
    form.is_active = Boolean(coupon.is_active);
    isModalOpen.value = true;
};

const closeModal = () => {
    isModalOpen.value = false;
    form.reset();
};

const submitForm = () => {
    if (isEditing.value && editingCouponId.value) {
        form.put(`/dashboard/coupons/${editingCouponId.value}`, {
            onSuccess: () => closeModal(),
        });
    } else {
        form.post('/dashboard/coupons', {
            onSuccess: () => closeModal(),
        });
    }
};

const deleteCoupon = (id: number) => {
    if (confirm('Are you sure you want to delete this coupon?')) {
        router.delete(`/dashboard/coupons/${id}`);
    }
};
</script>

<template>
    <Head title="Discount Coupons — ShopEase Admin" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8 space-y-8 bg-white min-h-screen">
            <!-- Header -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-gray-200 pb-6">
                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-gray-900">Discount Coupons</h1>
                    <p class="text-sm text-gray-500 mt-1">Manage promo codes, percentage/fixed discounts, and usage limits</p>
                </div>
                <button
                    @click="openCreateModal"
                    type="button"
                    class="inline-flex items-center justify-center gap-2 rounded-lg bg-violet-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-violet-700"
                >
                    <Plus class="w-4 h-4" /> Create Coupon
                </button>
            </div>

            <!-- Search -->
            <div class="flex items-center justify-between gap-4 rounded-xl border border-gray-200 bg-gray-50/50 p-4">
                <div class="relative w-full sm:w-80">
                    <Search class="absolute left-3 top-2.5 h-4 w-4 text-gray-400" />
                    <input
                        v-model="search"
                        @keyup.enter="applyFilters"
                        type="text"
                        placeholder="Search promo code..."
                        class="w-full rounded-lg border border-gray-300 bg-white py-2 pl-9 pr-4 text-xs text-gray-900 focus:border-violet-600 focus:outline-none"
                    />
                </div>
            </div>

            <!-- Table -->
            <div class="rounded-xl border border-gray-200 bg-white overflow-hidden shadow-xs">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-600">
                        <thead class="bg-gray-50 text-xs font-semibold uppercase tracking-wider text-gray-500 border-b border-gray-200">
                            <tr>
                                <th class="px-5 py-3.5">Promo Code</th>
                                <th class="px-5 py-3.5">Discount Value</th>
                                <th class="px-5 py-3.5">Min Spend</th>
                                <th class="px-5 py-3.5">Usage</th>
                                <th class="px-5 py-3.5">Status</th>
                                <th class="px-5 py-3.5 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <tr v-for="coupon in coupons.data" :key="coupon.id" class="hover:bg-gray-50/80 transition">
                                <td class="px-5 py-4 font-bold text-gray-900 font-mono text-xs sm:text-sm">
                                    <div class="flex items-center gap-2">
                                        <Ticket class="w-4 h-4 text-violet-600" />
                                        <span class="rounded bg-violet-50 px-2 py-0.5 text-violet-700 font-extrabold border border-violet-200">
                                            {{ coupon.code }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-5 py-4 font-extrabold text-gray-900 text-xs sm:text-sm">
                                    {{ coupon.type === 'percent' ? `${coupon.value}% OFF` : `৳ ${coupon.value} OFF` }}
                                </td>
                                <td class="px-5 py-4 text-xs font-semibold text-gray-700">
                                    {{ coupon.min_order_amount ? `৳ ${coupon.min_order_amount}` : 'No Minimum' }}
                                </td>
                                <td class="px-5 py-4 text-xs font-bold text-gray-700">
                                    {{ coupon.used_count }} / {{ coupon.usage_limit || '∞' }}
                                </td>
                                <td class="px-5 py-4">
                                    <span :class="['inline-flex rounded-full px-2.5 py-0.5 text-xs font-bold', coupon.is_active ? 'bg-emerald-50 text-emerald-700' : 'bg-gray-100 text-gray-600']">
                                        {{ coupon.is_active ? 'Active' : 'Disabled' }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <button
                                            @click="openEditModal(coupon)"
                                            type="button"
                                            class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-gray-300 text-gray-600 hover:bg-violet-600 hover:text-white transition"
                                        >
                                            <Edit3 class="w-3.5 h-3.5" />
                                        </button>
                                        <button
                                            @click="deleteCoupon(coupon.id)"
                                            type="button"
                                            class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-red-200 text-red-600 hover:bg-red-600 hover:text-white transition"
                                        >
                                            <Trash2 class="w-3.5 h-3.5" />
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="!coupons.data.length">
                                <td colspan="6" class="px-5 py-8 text-center text-xs text-gray-500">No coupons found.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Add/Edit Modal -->
        <div v-if="isModalOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 p-4">
            <div class="w-full max-w-md rounded-2xl bg-white p-6 shadow-2xl border border-gray-200">
                <div class="flex items-center justify-between border-b border-gray-100 pb-4 mb-4">
                    <h3 class="text-base font-bold text-gray-900">{{ isEditing ? 'Edit Coupon' : 'Create Coupon' }}</h3>
                    <button @click="closeModal" class="text-gray-400 hover:text-gray-600"><X class="w-5 h-5" /></button>
                </div>

                <form @submit.prevent="submitForm" class="space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Coupon Code</label>
                        <input v-model="form.code" type="text" required class="w-full uppercase rounded-lg border border-gray-300 p-2.5 text-xs focus:border-violet-600 focus:outline-none" placeholder="e.g. EID500" />
                        <p v-if="form.errors.code" class="text-[11px] text-red-600 mt-1">{{ form.errors.code }}</p>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Discount Type</label>
                            <select v-model="form.type" class="w-full rounded-lg border border-gray-300 p-2.5 text-xs focus:border-violet-600 focus:outline-none">
                                <option value="percent">Percentage (%)</option>
                                <option value="fixed">Fixed Amount (৳)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Value</label>
                            <input v-model="form.value" type="number" step="0.01" required class="w-full rounded-lg border border-gray-300 p-2.5 text-xs focus:border-violet-600 focus:outline-none" placeholder="e.g. 15 or 500" />
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Min Order Amount (৳)</label>
                            <input v-model="form.min_order_amount" type="number" step="0.01" class="w-full rounded-lg border border-gray-300 p-2.5 text-xs focus:border-violet-600 focus:outline-none" placeholder="1000.00" />
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Max Uses Limit</label>
                            <input v-model="form.usage_limit" type="number" class="w-full rounded-lg border border-gray-300 p-2.5 text-xs focus:border-violet-600 focus:outline-none" placeholder="100" />
                        </div>
                    </div>

                    <div class="flex items-center gap-2 pt-2">
                        <input v-model="form.is_active" type="checkbox" class="rounded text-violet-600 focus:ring-violet-500" />
                        <span class="text-xs font-medium text-gray-700">Is Active</span>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                        <button @click="closeModal" type="button" class="rounded-lg border border-gray-300 px-4 py-2 text-xs font-semibold text-gray-700 hover:bg-gray-50">Cancel</button>
                        <button type="submit" :disabled="form.processing" class="rounded-lg bg-violet-600 px-4 py-2 text-xs font-semibold text-white hover:bg-violet-700 disabled:opacity-50">
                            {{ form.processing ? 'Saving...' : (isEditing ? 'Update Coupon' : 'Save Coupon') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
