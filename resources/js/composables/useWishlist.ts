import { ref, computed } from 'vue';

const wishlist = ref<string[]>([]);

export function useWishlist() {
    const wishCount = computed(() => wishlist.value.length);

    function toggleWish(name: string) {
        const idx = wishlist.value.indexOf(name);
        if (idx > -1) {
            wishlist.value.splice(idx, 1);
            return false; // removed
        } else {
            wishlist.value.push(name);
            return true; // added
        }
    }

    function hasWish(name: string) {
        return wishlist.value.includes(name);
    }

    return {
        wishlist,
        wishCount,
        toggleWish,
        hasWish,
    };
}
