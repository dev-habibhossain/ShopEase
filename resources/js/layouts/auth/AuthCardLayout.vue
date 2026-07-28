<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import AppLogo from '@/components/AppLogo.vue';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import { home } from '@/routes';

defineProps<{
    title?: string;
    description?: string;
}>();
</script>

<template>
    <div
        class="flex min-h-svh flex-col items-center justify-center gap-6 bg-gray-50/60 p-6 md:p-10"
    >
        <div class="flex w-full max-w-md flex-col gap-6">
            <Link
                :href="home()"
                class="flex items-center gap-2 self-center font-medium"
            >
                <AppLogo />
            </Link>

            <div class="flex flex-col gap-6">
                <Card class="rounded-2xl border border-gray-200 bg-white shadow-sm">
                    <CardHeader class="px-8 pt-8 pb-0 text-center">
                        <CardTitle class="text-xl font-bold text-gray-900">{{ title }}</CardTitle>
                        <CardDescription class="text-xs text-gray-500 mt-1">
                            {{ description }}
                        </CardDescription>
                    </CardHeader>
                    <CardContent class="px-8 py-6">
                        <slot />
                    </CardContent>
                </Card>
            </div>
        </div>
    </div>
</template>
