import { ref, computed, watch } from 'vue';
import { router, usePage } from '@inertiajs/vue3';

export interface WishlistItem {
    id?: number;
    name: string;
    slug?: string | null;
    price?: number;
    oldPrice?: number | null;
    img?: string | null;
    rating?: number;
    reviews?: number;
    inStock?: boolean;
    category?: string | null;
    tag?: string | null;
}

const STORAGE_KEY = 'shopease_wishlist';

function loadStoredWishlist(): WishlistItem[] {
    try {
        const stored = localStorage.getItem(STORAGE_KEY);
        return stored ? JSON.parse(stored) : [];
    } catch {
        return [];
    }
}

const wishlist = ref<WishlistItem[]>(loadStoredWishlist());

watch(
    wishlist,
    (val) => {
        try {
            localStorage.setItem(STORAGE_KEY, JSON.stringify(val));
        } catch {
            // ignore
        }
    },
    { deep: true }
);

export function useWishlist() {
    const wishCount = computed(() => wishlist.value.length);

    function setWishlist(items: WishlistItem[]) {
        wishlist.value = items;
    }

    function hasWish(identifier: string | number): boolean {
        if (!identifier) return false;
        return wishlist.value.some((item) => {
            if (typeof identifier === 'number') {
                return item.id === identifier;
            }
            return item.name === identifier || (item.id && item.id === Number(identifier));
        });
    }

    function toggleWish(product: WishlistItem | string): boolean {
        const productObj: WishlistItem = typeof product === 'string' ? { name: product } : product;
        const index = wishlist.value.findIndex((item) => {
            if (productObj.id && item.id) {
                return item.id === productObj.id;
            }
            return item.name === productObj.name;
        });

        let isAdded = false;

        if (index > -1) {
            wishlist.value.splice(index, 1);
            isAdded = false;
        } else {
            wishlist.value.push(productObj);
            isAdded = true;
        }

        if (productObj.id) {
            try {
                const page = usePage();
                if (page.props.auth?.user) {
                    router.post(
                        `/wishlist/toggle/${productObj.id}`,
                        {},
                        {
                            preserveScroll: true,
                            preserveState: true,
                        }
                    );
                }
            } catch {
                // Ignore if called outside Inertia context
            }
        }

        return isAdded;
    }

    function removeFromWishlist(identifier: string | number) {
        const index = wishlist.value.findIndex((item) => {
            if (typeof identifier === 'number') {
                return item.id === identifier;
            }
            return item.name === identifier || (item.id && item.id === Number(identifier));
        });

        if (index > -1) {
            const item = wishlist.value[index];
            wishlist.value.splice(index, 1);

            if (item.id) {
                try {
                    const page = usePage();
                    if (page.props.auth?.user) {
                        router.post(
                            `/wishlist/toggle/${item.id}`,
                            {},
                            {
                                preserveScroll: true,
                                preserveState: true,
                            }
                        );
                    }
                } catch {
                    // Ignore if called outside Inertia context
                }
            }
        }
    }

    function clearWishlist() {
        wishlist.value = [];
    }

    return {
        wishlist,
        wishCount,
        setWishlist,
        hasWish,
        toggleWish,
        removeFromWishlist,
        clearWishlist,
    };
}
