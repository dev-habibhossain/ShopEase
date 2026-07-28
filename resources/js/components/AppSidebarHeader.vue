<script setup lang="ts">
import { computed, ref, onMounted, onUnmounted } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import { Search, Store, Bell, Settings, LogOut, ChevronDown } from '@lucide/vue';
import Breadcrumbs from '@/components/Breadcrumbs.vue';
import { SidebarTrigger } from '@/components/ui/sidebar';
import type { BreadcrumbItem } from '@/types';

withDefaults(
    defineProps<{
        breadcrumbs?: BreadcrumbItem[];
    }>(),
    {
        breadcrumbs: () => [],
    },
);

const page = usePage();
const user = computed(() => page.props.auth?.user);

const isUserMenuOpen = ref(false);
const isNotificationsOpen = ref(false);

const notifications = [
    { id: 1, title: 'New order #ORD-8942 received', time: '5 mins ago' },
    { id: 2, title: 'Product "Wireless Headphones" stock low', time: '1 hour ago' },
];

const closeDropdowns = (e: MouseEvent) => {
    const target = e.target as HTMLElement;
    if (!target.closest('.user-dropdown-container')) {
        isUserMenuOpen.value = false;
    }
    if (!target.closest('.notifications-dropdown-container')) {
        isNotificationsOpen.value = false;
    }
};

onMounted(() => {
    window.addEventListener('click', closeDropdowns);
});

onUnmounted(() => {
    window.removeEventListener('click', closeDropdowns);
});
</script>

<template>
    <header class="sticky top-0 z-30 flex h-16 shrink-0 items-center justify-between border-b border-gray-200 bg-white px-4 sm:px-6">
        <!-- Left: Trigger & Breadcrumbs -->
        <div class="flex items-center gap-3">
            <SidebarTrigger class="-ml-1 text-gray-700 hover:bg-gray-100 hover:text-gray-900" />
            <span class="h-4 w-px bg-gray-200 hidden sm:block"></span>
            <Breadcrumbs v-if="breadcrumbs && breadcrumbs.length" :breadcrumbs="breadcrumbs" />
        </div>

        <!-- Right: Search, Visit Storefront, Notifications, User Profile -->
        <div class="flex items-center gap-3">
            <div class="relative hidden md:block w-64">
                <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-gray-400">
                    <Search class="w-4 h-4" />
                </span>
                <input
                    type="search"
                    placeholder="Search dashboard..."
                    class="w-full rounded-lg border border-gray-300 bg-gray-50 py-2 pr-4 pl-9 text-xs text-gray-900 placeholder:text-gray-400 focus:border-primary-600 focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary-600"
                />
            </div>

            <Link
                href="/"
                target="_blank"
                class="inline-flex items-center gap-1.5 rounded-lg border border-gray-300 bg-white px-3 py-1.5 text-xs font-semibold text-gray-700 transition hover:bg-gray-50"
            >
                <Store class="w-4 h-4 text-violet-600" />
                <span class="hidden sm:inline">Storefront</span>
            </Link>

            <div class="relative notifications-dropdown-container">
                <button
                    @click.stop="isNotificationsOpen = !isNotificationsOpen; isUserMenuOpen = false"
                    type="button"
                    class="relative inline-flex h-9 w-9 items-center justify-center rounded-lg text-gray-700 hover:bg-gray-100 focus:outline-none"
                >
                    <Bell class="w-5 h-5" />
                    <span class="absolute top-1.5 right-1.5 h-2 w-2 rounded-full bg-violet-600"></span>
                </button>

                <div v-if="isNotificationsOpen" class="absolute right-0 mt-2 w-80 rounded-xl border border-gray-200 bg-white p-3 shadow-xl z-50">
                    <div class="flex items-center justify-between border-b border-gray-100 pb-2">
                        <span class="text-xs font-bold text-gray-900">Notifications</span>
                        <span class="rounded bg-violet-50 px-2 py-0.5 text-[10px] font-semibold text-violet-600">2 New</span>
                    </div>
                    <div class="mt-2 space-y-1">
                        <div v-for="n in notifications" :key="n.id" class="p-2 rounded-lg hover:bg-gray-50 text-xs">
                            <p class="font-medium text-gray-900 line-clamp-1">{{ n.title }}</p>
                            <span class="text-[10px] text-gray-400 mt-0.5 block">{{ n.time }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div v-if="user" class="relative user-dropdown-container">
                <button
                    @click.stop="isUserMenuOpen = !isUserMenuOpen; isNotificationsOpen = false"
                    class="flex items-center gap-2 rounded-lg p-1 transition hover:bg-gray-100"
                >
                    <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-violet-600 text-xs font-bold text-white">
                        {{ user.name ? user.name.charAt(0).toUpperCase() : 'A' }}
                    </span>
                    <span class="hidden md:block text-left text-xs">
                        <span class="block font-semibold text-gray-900 leading-tight">{{ user.name }}</span>
                        <span class="block text-[10px] text-gray-500">Admin</span>
                    </span>
                    <ChevronDown class="w-4 h-4 text-gray-400" />
                </button>

                <div v-if="isUserMenuOpen" class="absolute right-0 mt-2 w-48 rounded-xl border border-gray-200 bg-white p-1 shadow-xl z-50">
                    <div class="px-3 py-2 border-b border-gray-100 mb-1">
                        <p class="text-xs font-bold text-gray-900 truncate">{{ user.name }}</p>
                        <p class="text-[10px] text-gray-500 truncate">{{ user.email }}</p>
                    </div>
                    <Link href="/settings/profile" class="flex items-center gap-2 rounded-lg px-3 py-2 text-xs font-medium text-gray-700 hover:bg-gray-100">
                        <Settings class="w-4 h-4 text-gray-500" /> Settings
                    </Link>
                    <Link href="/logout" method="post" as="button" class="w-full flex items-center gap-2 rounded-lg px-3 py-2 text-xs font-medium text-red-600 hover:bg-red-50 text-left">
                        <LogOut class="w-4 h-4" /> Log out
                    </Link>
                </div>
            </div>
        </div>
    </header>
</template>
