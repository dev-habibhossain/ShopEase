import type { ComputedRef, Ref } from 'vue';
import { computed, onMounted, ref } from 'vue';
import type { Appearance, ResolvedAppearance } from '@/types';

export type { Appearance, ResolvedAppearance };

export type UseAppearanceReturn = {
    appearance: Ref<Appearance>;
    resolvedAppearance: ComputedRef<ResolvedAppearance>;
    updateAppearance: (value: Appearance) => void;
};

export function updateTheme(_value?: Appearance): void {
    if (typeof window === 'undefined') {
        return;
    }

    // Always remove dark class to enforce light theme
    document.documentElement.classList.remove('dark');
}

const setCookie = (name: string, value: string, days = 365) => {
    if (typeof document === 'undefined') {
        return;
    }

    const maxAge = days * 24 * 60 * 60;

    document.cookie = `${name}=${value};path=/;max-age=${maxAge};SameSite=Lax`;
};

export function initializeTheme(): void {
    if (typeof window === 'undefined') {
        return;
    }

    // Strictly enforce light theme
    localStorage.setItem('appearance', 'light');
    updateTheme('light');
}

const appearance = ref<Appearance>('light');

export function useAppearance(): UseAppearanceReturn {
    onMounted(() => {
        appearance.value = 'light';
        localStorage.setItem('appearance', 'light');
        updateTheme('light');
    });

    const resolvedAppearance = computed<ResolvedAppearance>(() => 'light');

    function updateAppearance(_value: Appearance) {
        appearance.value = 'light';
        localStorage.setItem('appearance', 'light');
        setCookie('appearance', 'light');
        updateTheme('light');
    }

    return {
        appearance,
        resolvedAppearance,
        updateAppearance,
    };
}
