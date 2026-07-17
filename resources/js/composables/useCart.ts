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

    function addToCart(name: string, price: number, img: string, qty: number = 1) {
        const qtyNum = Math.max(1, Number(qty) || 1);
        const existing = cart.value.find((item) => item.name === name);
        if (existing) {
            existing.qty += qtyNum;
        } else {
            cart.value.push({ name, price: Number(price) || 0, img: img || '', qty: qtyNum });
        }
        openCart();
    }

    function buyNow(name: string, price: number, img: string, qty: number = 1) {
        addToCart(name, price, img, qty);
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
