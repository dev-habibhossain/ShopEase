<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import {
    SidebarGroup,
    SidebarGroupLabel,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';
import { useCurrentUrl } from '@/composables/useCurrentUrl';
import type { NavGroup, NavItem } from '@/types';

defineProps<{
    items?: NavItem[];
    groups?: NavGroup[];
}>();

const { isCurrentUrl } = useCurrentUrl();
</script>

<template>
    <div class="space-y-4 py-2">
        <template v-if="groups && groups.length">
            <SidebarGroup
                v-for="group in groups"
                :key="group.label"
                class="px-3 py-0"
            >
                <SidebarGroupLabel class="px-2 text-[10px] font-extrabold uppercase tracking-widest text-gray-400 mb-1">
                    {{ group.label }}
                </SidebarGroupLabel>
                <SidebarMenu class="space-y-1">
                    <SidebarMenuItem v-for="item in group.items" :key="item.title">
                        <SidebarMenuButton
                            as-child
                            :is-active="isCurrentUrl(item.href)"
                            :tooltip="item.title"
                            class="group flex items-center gap-3 rounded-lg px-3 py-2 text-xs font-medium transition"
                            :class="[
                                isCurrentUrl(item.href)
                                    ? 'bg-violet-600 text-white font-bold shadow-sm hover:bg-violet-700'
                                    : 'text-gray-700 hover:bg-gray-100 hover:text-violet-700'
                            ]"
                        >
                            <Link :href="item.href" class="flex items-center gap-3 w-full">
                                <component
                                    :is="item.icon"
                                    v-if="item.icon"
                                    class="w-4 h-4 shrink-0 transition"
                                    :class="[
                                        isCurrentUrl(item.href) ? 'text-white' : 'text-gray-400 group-hover:text-violet-600'
                                    ]"
                                />
                                <span class="truncate">{{ item.title }}</span>
                            </Link>
                        </SidebarMenuButton>
                    </SidebarMenuItem>
                </SidebarMenu>
            </SidebarGroup>
        </template>
    </div>
</template>
