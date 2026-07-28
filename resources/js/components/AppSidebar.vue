<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import {
    CreditCard,
    FolderTree,
    Image,
    LayoutGrid,
    Package,
    Settings,
    ShoppingCart,
    Star,
    Store,
    Ticket,
    Users,
} from '@lucide/vue';
import AppLogo from '@/components/AppLogo.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';
import type { NavGroup } from '@/types';

const navGroups: NavGroup[] = [
    {
        label: 'Overview',
        items: [
            {
                title: 'Dashboard',
                href: '/dashboard',
                icon: LayoutGrid,
            },
        ],
    },
    {
        label: 'E-Commerce',
        items: [
            {
                title: 'Products',
                href: '/dashboard/products',
                icon: Package,
            },
            {
                title: 'Categories',
                href: '/dashboard/categories',
                icon: FolderTree,
            },
            {
                title: 'Orders',
                href: '/dashboard/orders',
                icon: ShoppingCart,
            },
            {
                title: 'Customers',
                href: '/dashboard/customers',
                icon: Users,
            },
        ],
    },
    {
        label: 'Marketing & Content',
        items: [
            {
                title: 'Coupons',
                href: '/dashboard/coupons',
                icon: Ticket,
            },
            {
                title: 'Reviews',
                href: '/dashboard/reviews',
                icon: Star,
            },
            {
                title: 'Hero Slides',
                href: '/dashboard/hero-slides',
                icon: Image,
            },
        ],
    },
    {
        label: 'Finance',
        items: [
            {
                title: 'Payments',
                href: '/dashboard/payments',
                icon: CreditCard,
            },
        ],
    },
    {
        label: 'Shortcuts',
        items: [
            {
                title: 'Storefront',
                href: '/',
                icon: Store,
            },
            {
                title: 'Settings',
                href: '/settings/profile',
                icon: Settings,
            },
        ],
    },
];
</script>

<template>
    <Sidebar collapsible="none" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link href="/dashboard">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain :groups="navGroups" />
        </SidebarContent>

        <SidebarFooter>
            <NavUser />
        </SidebarFooter>
    </Sidebar>
</template>
