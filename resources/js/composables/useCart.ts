import { ref, computed } from 'vue';

export interface CartItem {
    name: string;
    price: number;
    img: string;
    qty: number;
}

export interface AppliedCoupon {
    code: string;
    discountPercent?: number;
    discountAmount?: number;
    label: string;
}

const cart = ref<CartItem[]>([]);
const isCartOpen = ref(false);
const appliedCoupon = ref<AppliedCoupon | null>(null);

const VALID_COUPONS: Record<string, { percent?: number; amount?: number; label: string }> = {
    WELCOME10: { percent: 10, label: '10% Off Welcome Discount' },
    SAVE20: { percent: 20, label: '20% Off Promo Discount' },
    SHOPEASE: { amount: 150, label: '৳ 150 Flat Discount' },
    FREESHIP: { amount: 60, label: 'Delivery Fee Discount' },
};

export function useCart() {
    const cartQty = computed(() =>
        cart.value.reduce((sum, item) => sum + item.qty, 0),
    );

    const cartSubtotal = computed(() =>
        cart.value.reduce((sum, item) => sum + item.price * item.qty, 0),
    );

    const couponDiscount = computed(() => {
        if (!appliedCoupon.value || cartSubtotal.value === 0) return 0;
        if (appliedCoupon.value.discountPercent) {
            return Math.round((cartSubtotal.value * appliedCoupon.value.discountPercent) / 100);
        }
        if (appliedCoupon.value.discountAmount) {
            return Math.min(cartSubtotal.value, appliedCoupon.value.discountAmount);
        }
        return 0;
    });

    const cartTotal = computed(() =>
        Math.max(0, cartSubtotal.value - couponDiscount.value),
    );

    function openCart() {
        isCartOpen.value = true;
        document.body.style.overflow = 'hidden';
    }

    function closeCart() {
        isCartOpen.value = false;
        document.body.style.overflow = '';
    }

    function addToCart(
        name: string,
        price: number,
        img: string,
        qty: number = 1,
    ) {
        const qtyNum = Math.max(1, Number(qty) || 1);
        const existing = cart.value.find((item) => item.name === name);
        if (existing) {
            existing.qty += qtyNum;
        } else {
            cart.value.push({
                name,
                price: Number(price) || 0,
                img: img || '',
                qty: qtyNum,
            });
        }
        openCart();
    }

    function buyNow(name: string, price: number, img: string, qty: number = 1) {
        addToCart(name, price, img, qty);
    }

    function incrementQty(index: number) {
        if (cart.value[index]) {
            cart.value[index].qty++;
        }
    }

    function decrementQty(index: number) {
        if (cart.value[index] && cart.value[index].qty > 1) {
            cart.value[index].qty--;
        }
    }

    function removeFromCart(index: number) {
        cart.value.splice(index, 1);
        if (cart.value.length === 0) {
            appliedCoupon.value = null;
        }
    }

    function clearCart() {
        cart.value = [];
        appliedCoupon.value = null;
    }

    function applyCoupon(code: string): { success: boolean; message: string } {
        const formatted = code.trim().toUpperCase();
        if (!formatted) {
            return { success: false, message: 'Please enter a coupon code.' };
        }
        const coupon = VALID_COUPONS[formatted];
        if (!coupon) {
            return {
                success: false,
                message: 'Invalid coupon code. Try WELCOME10, SAVE20, or SHOPEASE.',
            };
        }
        appliedCoupon.value = {
            code: formatted,
            discountPercent: coupon.percent,
            discountAmount: coupon.amount,
            label: coupon.label,
        };
        return {
            success: true,
            message: `Coupon "${formatted}" applied successfully!`,
        };
    }

    function removeCoupon() {
        appliedCoupon.value = null;
    }

    return {
        cart,
        isCartOpen,
        cartQty,
        cartSubtotal,
        appliedCoupon,
        couponDiscount,
        cartTotal,
        openCart,
        closeCart,
        addToCart,
        buyNow,
        incrementQty,
        decrementQty,
        removeFromCart,
        clearCart,
        applyCoupon,
        removeCoupon,
    };
}
