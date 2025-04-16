<script setup>
import { useToast } from '@/Composables/useToast';
import Toast from '@/Components/Toast.vue';

const { toasts, remove } = useToast();
</script>

<template>
    <div class="fixed bottom-4 right-4 z-50 flex flex-col gap-2">
        <Toast
            v-for="toast in toasts"
            :key="toast.id"
            v-bind="toast"
            @close="remove(toast.id)"
        />
    </div>
</template> 