<script setup lang="ts">
import AppLogoIcon from '@/components/AppLogoIcon.vue';
</script>

<template>
    <div class="flex items-center gap-2.5 px-1 py-1">
        <div
            class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-gradient-to-tr from-violet-600 via-purple-600 to-indigo-600 text-white shadow-md shadow-violet-600/30"
        >
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
            </svg>
        </div>
        <div class="flex flex-col text-left">
            <span class="text-base font-extrabold tracking-tight text-gray-900 dark:text-white flex items-center gap-1.5">
                Shop<span class="text-violet-600 dark:text-violet-400">Ease</span>
                <span class="rounded-md bg-violet-100 px-1.5 py-0.5 text-[10px] font-extrabold tracking-wider text-violet-700 dark:bg-violet-950/80 dark:text-violet-300 uppercase">
                    Admin
                </span>
            </span>
        </div>
    </div>
</template>
