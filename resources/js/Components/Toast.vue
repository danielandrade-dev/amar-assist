<script setup>
import { ref, onMounted, onUnmounted } from 'vue';

const props = defineProps({
    message: {
        type: String,
        required: true
    },
    type: {
        type: String,
        default: 'success',
        validator: (value) => ['success', 'error', 'info'].includes(value)
    },
    duration: {
        type: Number,
        default: 3000
    }
});

const isVisible = ref(false);
const emit = defineEmits(['close']);

onMounted(() => {
    isVisible.value = true;
    if (props.duration > 0) {
        setTimeout(() => {
            close();
        }, props.duration);
    }
});

function close() {
    isVisible.value = false;
    setTimeout(() => {
        emit('close');
    }, 300); // tempo da animação
}
</script>

<template>
    <Transition
        enter-active-class="transition duration-300 ease-out"
        enter-from-class="transform translate-y-2 opacity-0"
        enter-to-class="transform translate-y-0 opacity-100"
        leave-active-class="transition duration-300 ease-in"
        leave-from-class="transform translate-y-0 opacity-100"
        leave-to-class="transform translate-y-2 opacity-0"
    >
        <div
            v-if="isVisible"
            class="fixed bottom-4 right-4 z-50 flex items-center gap-2 px-4 py-2 rounded-lg shadow-lg"
            :class="{
                'bg-success/90 text-white': type === 'success',
                'bg-danger/90 text-white': type === 'error',
                'bg-info/90 text-white': type === 'info'
            }"
        >
            <!-- Ícones para cada tipo -->
            <svg v-if="type === 'success'" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
            </svg>
            <svg v-if="type === 'error'" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
            </svg>
            <svg v-if="type === 'info'" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd" />
            </svg>

            <span class="text-sm font-medium">{{ message }}</span>

            <!-- Botão fechar -->
            <button
                @click="close"
                class="ml-2 text-white/80 hover:text-white focus:outline-none"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </button>
        </div>
    </Transition>
</template> 