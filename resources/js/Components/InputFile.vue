<template>
    <div class="form-group flex flex-col gap-2">
        <div
            class="dropzone"
            @dragover.prevent="dragActive = true"
            @dragleave.prevent="dragActive = false"
            @drop.prevent="onDrop"
            :class="{ 'dropzone-active': dragActive }"
            tabindex="0"
            aria-label="Área para arrastar e soltar imagem"
            @click="triggerFileInput"
        >
            <input
                :id="id"
                type="file"
                accept="image/jpeg, image/png"
                @change="onFileChange"
                class="hidden"
                :aria-label="ariaLabel"
                ref="fileInput"
            />
            <div v-if="!modelValue" class="dropzone-content">
                <span class="text-gray-500 text-sm">
                    Arraste e solte uma imagem aqui ou <span class="underline cursor-pointer text-primary">clique para selecionar</span>
                </span>
                <span class="text-xs text-gray-400 block mt-1">Apenas JPG e PNG. Tamanho máximo: 2MB.</span>
            </div>
            <div v-else class="image-preview">
                <img
                    :src="imgUrl(modelValue)"
                    class="preview-img"
                    :alt="imgName(modelValue)"
                />
                <button
                    type="button"
                    class="remove-btn"
                    @click.stop="removeImage"
                    aria-label="Remover imagem"
                >
                    ✕
                </button>
                <div class="file-info">
                    <span class="file-name">{{ imgName(modelValue) }}</span>
                    <span class="file-size">{{ imgSize(modelValue) }}</span>
                </div>
            </div>
        </div>
        <span v-if="errorMsg" class="text-red-500 text-sm mt-1">{{ errorMsg }}</span>
        <span v-if="errors.image" class="text-red-500 text-sm">{{ errors.image }}</span>
    </div>
</template>

<script setup>
import { ref, defineProps, defineEmits } from 'vue';

const props = defineProps({
    id: { type: String, default: 'fileInput' },
    label: { type: String, default: 'Arquivo' },
    ariaLabel: { type: String, default: 'Selecione um arquivo' },
    errors: { type: Object, default: () => ({}) },
    modelValue: { type: [File, String, null], default: null },
});

const emit = defineEmits(['update:modelValue']);
const dragActive = ref(false);
const errorMsg = ref('');
const fileInput = ref(null);

function imgUrl(img) {
    if (typeof img === 'string') return img;
    if (img instanceof File) return URL.createObjectURL(img);
    return '';
}

function imgName(img) {
    if (typeof img === 'string') return img.split('/').pop();
    if (img instanceof File) return img.name;
    return '';
}

function imgSize(img) {
    if (typeof img === 'string') return '';
    if (img instanceof File) {
        const size = img.size / 1024 / 1024;
        return size > 1 ? `${size.toFixed(2)} MB` : `${(img.size / 1024).toFixed(0)} KB`;
    }
    return '';
}

function triggerFileInput(e) {
    if (e && e.target.closest('.remove-btn')) return;
    fileInput.value?.click();
}

function onFileChange(e) {
    errorMsg.value = '';
    const file = e.target.files[0];
    if (file) handleFile(file);
    e.target.value = '';
}

function onDrop(e) {
    dragActive.value = false;
    errorMsg.value = '';
    const file = e.dataTransfer.files[0];
    if (file) handleFile(file);
}

function handleFile(file) {
    if (!['image/jpeg', 'image/png'].includes(file.type)) {
        errorMsg.value = 'Apenas arquivos JPG e PNG são permitidos.';
        return;
    }
    if (file.size > 2 * 1024 * 1024) {
        errorMsg.value = 'O tamanho máximo da imagem é 2MB.';
        return;
    }
    emit('update:modelValue', file);
}

function removeImage() {
    emit('update:modelValue', null);
}
</script>

<style scoped>
.dropzone {
    border: 2px dashed #2563eb;
    border-radius: 0.5rem;
    background: #f9fafb;
    padding: 1.5rem 1rem;
    text-align: center;
    cursor: pointer;
    transition: border-color 0.2s, background 0.2s;
    outline: none;
    min-height: 150px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.dropzone:focus, .dropzone-active {
    border-color: #1e40af;
    background: #e0e7ff;
}

.dropzone-content {
    pointer-events: none;
}

.image-preview {
    position: relative;
    width: 100%;
    max-width: 300px;
    margin: 0 auto;
}

.preview-img {
    width: 100%;
    height: 150px;
    object-fit: cover;
    border-radius: 0.375rem;
    border: 1px solid #e5e7eb;
}

.remove-btn {
    position: absolute;
    top: 6px;
    right: 6px;
    background: #ef4444;
    color: #fff;
    border: none;
    border-radius: 50%;
    width: 28px;
    height: 28px;
    font-size: 1.1rem;
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0.85;
    cursor: pointer;
    transition: background 0.2s, opacity 0.2s;
    z-index: 2;
}

.remove-btn:hover, .remove-btn:focus {
    background: #b91c1c;
    opacity: 1;
    outline: 2px solid #2563eb;
}

.file-info {
    margin-top: 0.5rem;
    text-align: left;
    font-size: 0.85rem;
    color: #374151;
}

.file-name {
    font-weight: 500;
    word-break: break-all;
}

.file-size {
    color: #6b7280;
    font-size: 0.8em;
    margin-left: 0.5rem;
}
</style>
