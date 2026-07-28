<script setup lang="ts">
import { Head, router, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import {
    Search,
    Eye,
    Edit3,
    X,
} from '@lucide/vue';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';

const props = defineProps<{
    orders: {
        data: Array<{
            id: number;
            order_number: string;
            customer_name: string;
            phone: string;
            email: string;
            district: string;
            area: string;
            address: string;
            total: number;
            payment_method: string;
            payment_status: string;
            status: string;
            created_at: string;
        }>;
    };
    filters: { search?: string; status?: string; payment_status?: string };
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Orders', href: '/dashboard/orders' },
];

const search = ref(props.filters.search || '');
const selectedStatus = ref(props.filters.status || '');
const selectedPaymentStatus = ref(props.filters.payment_status || '');

const applyFilters = () => {
    router.get('/dashboard/orders', {
        search: search.value,
        status: selectedStatus.value,
        payment_status: selectedPaymentStatus.value,
    }, { preserveState: true, replace: true });
};

// Edit Status Modal State
const isModalOpen = ref(false);
const editingOrder = ref<any>(null);

const form = useForm({
    status: 'pending',
    payment_status: 'pending',
});

const openEditModal = (order: any) => {
    editingOrder.value = order;
    form.status = order.status;
    form.payment_status = order.payment_status;
    isModalOpen.value = true;
};

const closeModal = () => {
    isModalOpen.value = false;
    editingOrder.value = null;
};

const updateOrderStatus = () => {
    if (editingOrder.value) {
        form.put(`/dashboard/orders/${editingOrder.value.id}`, {
            onSuccess: () => closeModal(),
        });
    }
};

const getStatusStyle = (status: string) => {
    switch (status) {
        case 'delivered':
            return 'bg-emerald-50 text-emerald-700 border border-emerald-200';
        case 'processing':
        case 'shipped':
            return 'bg-blue-50 text-blue-700 border border-blue-200';
        case 'cancelled':
            return 'bg-red-50 text-red-700 border border-red-200';
        default:
            return 'bg-amber-50 text-amber-700 border border-amber-200';
    }
};
</script>

<template>
    <Head title="Orders Management — ShopEase Admin" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8 space-y-8 bg-white min-h-screen">
            <!-- Header -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-gray-200 pb-6">
                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-gray-900">Customer Orders</h1>
                    <p class="text-sm text-gray-500 mt-1">Track customer purchases, fulfillment status, and payment updates</p>
                </div>
            </div>

            <!-- Filters -->
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4 rounded-xl border border-gray-200 bg-gray-50/50 p-4">
                <div class="relative w-full sm:w-80">
                    <Search class="absolute left-3 top-2.5 h-4 w-4 text-gray-400" />
                    <input
                        v-model="search"
                        @keyup.enter="applyFilters"
                        type="text"
                        placeholder="Search order number or customer..."
                        class="w-full rounded-lg border border-gray-300 bg-white py-2 pl-9 pr-4 text-xs text-gray-900 placeholder:text-gray-400 focus:border-violet-600 focus:outline-none"
                    />
                </div>

                <div class="flex items-center gap-3 w-full sm:w-auto">
                    <select v-model="selectedStatus" @change="applyFilters" class="rounded-lg border border-gray-300 bg-white px-3 py-2 text-xs font-medium text-gray-700">
                        <option value="">All Order Statuses</option>
                        <option value="pending">Pending</option>
                        <option value="processing">Processing</option>
                        <option value="shipped">Shipped</option>
                        <option value="delivered">Delivered</option>
                        <option value="cancelled">Cancelled</option>
                    </select>

                    <select v-model="selectedPaymentStatus" @change="applyFilters" class="rounded-lg border border-gray-300 bg-white px-3 py-2 text-xs font-medium text-gray-700">
                        <option value="">All Payment Statuses</option>
                        <option value="pending">Payment Pending</option>
                        <option value="paid">Paid</option>
                        <option value="failed">Failed</option>
                    </select>
                </div>
            </div>

            <!-- Table -->
            <div class="rounded-xl border border-gray-200 bg-white overflow-hidden shadow-xs">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-600">
                        <thead class="bg-gray-50 text-xs font-semibold uppercase tracking-wider text-gray-500 border-b border-gray-200">
                            <tr>
                                <th class="px-5 py-3.5">Order ID</th>
                                <th class="px-5 py-3.5">Customer Details</th>
                                <th class="px-5 py-3.5">Payment</th>
                                <th class="px-5 py-3.5">Total Amount</th>
                                <th class="px-5 py-3.5">Status</th>
                                <th class="px-5 py-3.5 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <tr v-for="order in orders.data" :key="order.id" class="hover:bg-gray-50/80 transition">
                                <td class="px-5 py-4 font-bold text-gray-900 font-mono text-xs">{{ order.order_number }}</td>
                                <td class="px-5 py-4">
                                    <p class="font-semibold text-gray-900 text-xs sm:text-sm">{{ order.customer_name }}</p>
                                    <p class="text-[11px] text-gray-500">{{ order.phone }} • {{ order.district }}</p>
                                </td>
                                <td class="px-5 py-4">
                                    <p class="text-xs font-semibold text-gray-700 uppercase">{{ order.payment_method }}</p>
                                    <span :class="['inline-flex rounded text-[10px] font-bold px-1.5 py-0.5 mt-0.5', order.payment_status === 'paid' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800']">
                                        {{ order.payment_status }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 font-extrabold text-violet-600 text-xs sm:text-sm">
                                    ৳ {{ Number(order.total).toLocaleString() }}
                                </td>
                                <td class="px-5 py-4">
                                    <span :class="`inline-flex px-2.5 py-0.5 rounded-full text-xs font-bold capitalize ${getStatusStyle(order.status)}`">
                                        {{ order.status }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-right">
                                    <button
                                        @click="openEditModal(order)"
                                        type="button"
                                        class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-gray-300 text-gray-600 hover:bg-violet-600 hover:text-white transition"
                                    >
                                        <Edit3 class="w-3.5 h-3.5" />
                                    </button>
                                </td>
                            </tr>
                            <tr v-if="!orders.data.length">
                                <td colspan="6" class="px-5 py-8 text-center text-xs text-gray-500">No orders found.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Edit Status Modal -->
        <div v-if="isModalOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 p-4">
            <div class="w-full max-w-md rounded-2xl bg-white p-6 shadow-2xl border border-gray-200">
                <div class="flex items-center justify-between border-b border-gray-100 pb-4 mb-4">
                    <h3 class="text-base font-bold text-gray-900">Update Order #{{ editingOrder?.order_number }}</h3>
                    <button @click="closeModal" class="text-gray-400 hover:text-gray-600"><X class="w-5 h-5" /></button>
                </div>

                <form @submit.prevent="updateOrderStatus" class="space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Order Fulfillment Status</label>
                        <select v-model="form.status" class="w-full rounded-lg border border-gray-300 p-2.5 text-xs focus:border-violet-600 focus:outline-none">
                            <option value="pending">Pending</option>
                            <option value="processing">Processing</option>
                            <option value="shipped">Shipped</option>
                            <option value="delivered">Delivered</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Payment Status</label>
                        <select v-model="form.payment_status" class="w-full rounded-lg border border-gray-300 p-2.5 text-xs focus:border-violet-600 focus:outline-none">
                            <option value="pending">Pending</option>
                            <option value="paid">Paid</option>
                            <option value="failed">Failed</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                        <button @click="closeModal" type="button" class="rounded-lg border border-gray-300 px-4 py-2 text-xs font-semibold text-gray-700 hover:bg-gray-50">Cancel</button>
                        <button type="submit" :disabled="form.processing" class="rounded-lg bg-violet-600 px-4 py-2 text-xs font-semibold text-white hover:bg-violet-700 disabled:opacity-50">
                            {{ form.processing ? 'Saving...' : 'Update Status' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
