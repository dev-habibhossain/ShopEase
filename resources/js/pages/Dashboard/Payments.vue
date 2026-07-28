<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import { Search, CreditCard } from '@lucide/vue';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';

const props = defineProps<{
    payments: {
        data: Array<{
            id: number;
            transaction_id: string;
            payment_method: string;
            amount: number;
            status: string;
            created_at: string;
            order?: {
                order_number: string;
                customer_name: string;
            };
        }>;
    };
    filters: { search?: string };
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Payments', href: '/dashboard/payments' },
];

const search = ref(props.filters.search || '');

const applyFilters = () => {
    router.get('/dashboard/payments', { search: search.value }, { preserveState: true, replace: true });
};
</script>

<template>
    <Head title="Payments & Transactions — ShopEase Admin" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8 space-y-8 bg-white min-h-screen">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-gray-200 pb-6">
                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-gray-900">Payment Transactions</h1>
                    <p class="text-sm text-gray-500 mt-1">Audit online payment gateways, transaction IDs, and payout logs</p>
                </div>
            </div>

            <div class="flex items-center justify-between gap-4 rounded-xl border border-gray-200 bg-gray-50/50 p-4">
                <div class="relative w-full sm:w-80">
                    <Search class="absolute left-3 top-2.5 h-4 w-4 text-gray-400" />
                    <input
                        v-model="search"
                        @keyup.enter="applyFilters"
                        type="text"
                        placeholder="Search transaction ID..."
                        class="w-full rounded-lg border border-gray-300 bg-white py-2 pl-9 pr-4 text-xs text-gray-900 focus:border-violet-600 focus:outline-none"
                    />
                </div>
            </div>

            <div class="rounded-xl border border-gray-200 bg-white overflow-hidden shadow-xs">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-600">
                        <thead class="bg-gray-50 text-xs font-semibold uppercase tracking-wider text-gray-500 border-b border-gray-200">
                            <tr>
                                <th class="px-5 py-3.5">Transaction ID</th>
                                <th class="px-5 py-3.5">Order Number</th>
                                <th class="px-5 py-3.5">Method</th>
                                <th class="px-5 py-3.5">Amount</th>
                                <th class="px-5 py-3.5">Status</th>
                                <th class="px-5 py-3.5">Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <tr v-for="pay in payments.data" :key="pay.id" class="hover:bg-gray-50/80 transition">
                                <td class="px-5 py-4 font-bold text-gray-900 font-mono text-xs sm:text-sm">
                                    <div class="flex items-center gap-2">
                                        <CreditCard class="w-4 h-4 text-violet-600" />
                                        {{ pay.transaction_id || 'N/A' }}
                                    </div>
                                </td>
                                <td class="px-5 py-4 text-xs font-semibold text-gray-700">
                                    {{ pay.order?.order_number || 'N/A' }}
                                </td>
                                <td class="px-5 py-4 text-xs font-bold uppercase text-gray-900">{{ pay.payment_method }}</td>
                                <td class="px-5 py-4 font-extrabold text-violet-600 text-xs sm:text-sm">
                                    ৳ {{ Number(pay.amount).toLocaleString() }}
                                </td>
                                <td class="px-5 py-4">
                                    <span :class="['inline-flex rounded-full px-2.5 py-0.5 text-xs font-bold capitalize', pay.status === 'paid' || pay.status === 'completed' ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700']">
                                        {{ pay.status }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-xs text-gray-500">
                                    {{ new Date(pay.created_at).toLocaleString() }}
                                </td>
                            </tr>
                            <tr v-if="!payments.data.length">
                                <td colspan="6" class="px-5 py-8 text-center text-xs text-gray-500">No payment transaction records found.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
