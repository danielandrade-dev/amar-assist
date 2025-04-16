import { ref } from 'vue';

const toasts = ref([]);

export function useToast() {
    function show(message, type = 'success', duration = 3000) {
        const id = Date.now();
        
        toasts.value.push({
            id,
            message,
            type,
            duration
        });

        return id;
    }

    function success(message, duration) {
        return show(message, 'success', duration);
    }

    function error(message, duration) {
        return show(message, 'error', duration);
    }

    function info(message, duration) {
        return show(message, 'info', duration);
    }

    function remove(id) {
        const index = toasts.value.findIndex(toast => toast.id === id);
        if (index > -1) {
            toasts.value.splice(index, 1);
        }
    }

    return {
        toasts,
        show,
        success,
        error,
        info,
        remove
    };
} 