import { ref, computed } from 'vue';

export interface CartItem {
    name: string;
    price: number;
    img: string;
    qty: number;
}

const cart = ref<CartItem[]>([]);
const isCartOpen = ref(false);

export function useCart() {
    const cartQty = computed(() => cart.value.reduce((sum, item) => sum + item.qty, 0));
    const cartSubtotal = computed(() => cart.value.reduce((sum, item) => sum + item.price * item.qty, 0));

    function openCart() {
        isCartOpen.value = true;
        document.body.style.overflow = 'hidden';
    }

    function closeCart() {
        isCartOpen.value = false;
        document.body.style.overflow = '';
    }

    function addToCart(name: string, price: number, img: string) {
        const existing = cart.value.find((item) => item.name === name);
        if (existing) {
            existing.qty++;
        } else {
            cart.value.push({ name, price: Number(price) || 0, img: img || '', qty: 1 });
        }
        openCart();
    }

    function buyNow(name: string, price: number, img: string) {
        addToCart(name, price, img);
    }

    function incrementQty(index: number) {
        cart.value[index].qty++;
    }

    function decrementQty(index: number) {
        if (cart.value[index].qty > 1) {
            cart.value[index].qty--;
        }
    }

    function removeFromCart(index: number) {
        cart.value.splice(index, 1);
    }

    return {
        cart,
        isCartOpen,
        cartQty,
        cartSubtotal,
        openCart,
        closeCart,
        addToCart,
        buyNow,
        incrementQty,
        decrementQty,
        removeFromCart,
    };
}
