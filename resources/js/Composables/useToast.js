import { ref } from 'vue';

const toasts = ref([]);

export function useToast() {
    const toast = (message, type = 'info', options = {}) => {
        const id = Date.now();

        const newToast = {id, message, type, options};

        toasts.value.unshift(newToast);
    };

    const removeToast = (id) => {
        toasts.value.splice(toasts.value.findIndex(toast => toast.id === id), 1);
    };

    return {toasts, toast, removeToast};
}
