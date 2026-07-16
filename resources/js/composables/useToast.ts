import { ref } from 'vue';

const toastMessage = ref('');
const isToastVisible = ref(false);
let toastTimeout: any = null;

export function useToast() {
    function showToast(message: string) {
        toastMessage.value = message;
        isToastVisible.value = true;
        if (toastTimeout) {
            clearTimeout(toastTimeout);
        }
        toastTimeout = setTimeout(() => {
            isToastVisible.value = false;
        }, 2500);
    }

    return {
        toastMessage,
        isToastVisible,
        showToast,
    };
}
