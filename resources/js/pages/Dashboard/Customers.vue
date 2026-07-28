<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import { Search } from '@lucide/vue';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';

const props = defineProps<{
    customers: {
        data: Array<{
            id: number;
            name: string;
            email: string;
            avatar?: string | null;
            orders_count?: number;
            orders_sum_total?: number;
            created_at: string;
        }>;
    };
    filters: { search?: string };
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Customers', href: '/dashboard/customers' },
];

const search = ref(props.filters.search || '');

const applyFilters = () => {
    router.get('/dashboard/customers', { search: search.value }, { preserveState: true, replace: true });
};
</script>

<template>
    <Head title="Customers Directory — ShopEase Admin" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8 space-y-8 bg-white min-h-screen">
            <!-- Header -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-gray-200 pb-6">
                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-gray-900">Registered Customers</h1>
                    <p class="text-sm text-gray-500 mt-1">Directory of registered customer accounts, order frequencies, and lifetime value</p>
                </div>
            </div>

            <!-- Search -->
            <div class="flex items-center justify-between gap-4 rounded-xl border border-gray-200 bg-gray-50/50 p-4">
                <div class="relative w-full sm:w-80">
                    <Search class="absolute left-3 top-2.5 h-4 w-4 text-gray-400" />
                    <input
                        v-model="search"
                        @keyup.enter="applyFilters"
                        type="text"
                        placeholder="Search customer name or email..."
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
                                <th class="px-5 py-3.5">Customer</th>
                                <th class="px-5 py-3.5">Email</th>
                                <th class="px-5 py-3.5">Total Orders</th>
                                <th class="px-5 py-3.5">Spent Amount</th>
                                <th class="px-5 py-3.5">Joined Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <tr v-for="customer in customers.data" :key="customer.id" class="hover:bg-gray-50/80 transition">
                                <td class="px-5 py-4 font-bold text-gray-900 text-xs sm:text-sm">
                                    <div class="flex items-center gap-3">
                                        <div class="h-10 w-10 shrink-0 rounded-full overflow-hidden border border-violet-200 bg-violet-50 text-violet-700 flex items-center justify-center font-bold text-xs uppercase shadow-xs">
                                            <img v-if="customer.avatar" :src="customer.avatar" :alt="customer.name" class="h-full w-full object-cover" />
                                            <span v-else>{{ customer.name ? customer.name.charAt(0) : 'U' }}</span>
                                        </div>
                                        <div>
                                            <p class="font-bold text-gray-900 text-xs sm:text-sm">{{ customer.name }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-4 text-xs font-medium text-gray-700">{{ customer.email }}</td>
                                <td class="px-5 py-4 text-xs font-bold text-gray-900">{{ customer.orders_count || 0 }} Orders</td>
                                <td class="px-5 py-4 font-extrabold text-violet-600 text-xs sm:text-sm">
                                    ৳ {{ Number(customer.orders_sum_total || 0).toLocaleString() }}
                                </td>
                                <td class="px-5 py-4 text-xs text-gray-500">
                                    {{ new Date(customer.created_at).toLocaleDateString() }}
                                </td>
                            </tr>
                            <tr v-if="!customers.data.length">
                                <td colspan="5" class="px-5 py-8 text-center text-xs text-gray-500">No registered customers found.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
