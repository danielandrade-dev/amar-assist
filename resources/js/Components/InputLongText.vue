<script setup>
import { ref, watch } from 'vue';

const props = defineProps({
    modelValue: { type: String, default: '' },
});

const emit = defineEmits(['update:modelValue']);

const input = ref(props.modelValue);

function sanitizeHtml(html) {
    // Remove todas as tags exceto <p>, <br>, <b>, <strong>
    return html
        .replace(/<(?!\/?(p|br|b|strong)\b)[^>]*>/gi, '')
        .replace(/<script.*?>.*?<\/script>/gi, '');
}

function checkInvalidTags(html) {
    // Procura tags não permitidas
    const invalid = html.match(/<\/?([a-z0-9]+)[^>]*>/gi)?.filter(tag => !/^<\/?(p|br|b|strong)\b/i.test(tag));
    if (invalid && invalid.length > 0) {
        window.alert('Apenas as tags <p>, <br>, <b> e <strong> são permitidas. Remova ou corrija: ' + invalid.join(', '));
    }
}

watch(input, (val) => {
    checkInvalidTags(val);
    const sanitized = sanitizeHtml(val);
    emit('update:modelValue', sanitized);
});

watch(() => props.modelValue, (val) => {
    if (val !== input.value) input.value = val;
});
</script>

<template>
    <textarea
        class="border-gray-300 focus:border-primary focus:ring-primary rounded-md shadow-sm min-h-[100px] w-full"
        v-model="input"
        placeholder="Digite a descrição em HTML (apenas <p>, <br>, <b>, <strong> são permitidos)"
    />
</template>
