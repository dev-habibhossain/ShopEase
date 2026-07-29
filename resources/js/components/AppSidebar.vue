<script setup lang="ts">
import { computed } from 'vue';
import { usePage, Link } from '@inertiajs/vue3';
import {
    CreditCard,
    FolderTree,
    Heart,
    Image,
    LayoutGrid,
    Package,
    Search,
    Settings,
    ShieldCheck,
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

const page = usePage();
const userRole = computed(() => page.props.auth?.user?.role || 'customer');

const navGroups = computed<NavGroup[]>(() => {
    const role = userRole.value;

    if (role === 'customer') {
        return [
            {
                label: 'Customer Menu',
                items: [
                    {
                        title: 'My Overview',
                        href: '/dashboard',
                        icon: LayoutGrid,
                    },
                    {
                        title: 'Track Order',
                        href: '/track-order',
                        icon: Search,
                    },
                    {
                        title: 'My Wishlist',
                        href: '/wishlist',
                        icon: Heart,
                    },
                ],
            },
            {
                label: 'Shortcuts',
                items: [
                    {
                        title: 'Back to Shop',
                        href: '/',
                        icon: Store,
                    },
                    {
                        title: 'Profile Settings',
                        href: '/settings/profile',
                        icon: Settings,
                    },
                ],
            },
        ];
    }

    const groups: NavGroup[] = [
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
    ];

    if (role === 'super_admin') {
        groups.push({
            label: 'Administration',
            items: [
                {
                    title: 'Manage Users',
                    href: '/dashboard/customers',
                    icon: ShieldCheck,
                },
            ],
        });
    }

    groups.push({
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
    });

    return groups;
});
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
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
