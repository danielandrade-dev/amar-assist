<template>
    <div class="form-group flex flex-col gap-2">
        <div
            class="dropzone"
            @dragover.prevent="dragActive = true"
            @dragleave.prevent="dragActive = false"
            @drop.prevent="onDrop"
            :class="{ 'dropzone-active': dragActive }"
            tabindex="0"
            aria-label="Área para arrastar e soltar imagens"
            @click="triggerFileInput"
        >
            <input
                :id="id"
                type="file"
                :multiple="multiple"
                accept="image/jpeg, image/png"
                @change="onFileChange"
                class="hidden"
                :aria-label="ariaLabel"
                ref="fileInput"
            />
            <div class="dropzone-content">
                <span class="text-gray-500 text-sm">
                    Arraste e solte imagens aqui ou <span class="underline cursor-pointer text-primary">clique para selecionar</span>
                </span>
                <span class="text-xs text-gray-400 block mt-1">Apenas JPG e PNG. Tamanho máximo: 5MB por imagem.</span>
            </div>
        </div>
        <div v-if="modelValue && modelValue.length" class="image-grid mt-2">
            <div v-for="(img, idx) in modelValue" :key="imgKey(img, idx)" class="image-thumb group">
                <img
                    :src="imgUrl(img)"
                    class="thumb-img"
                    :alt="`Miniatura ${idx+1}`"
                />
                <button
                    type="button"
                    class="remove-btn"
                    @click="removeImage(idx)"
                    :aria-label="`Remover imagem ${imgName(img)}`"
                >
                    ✕
                </button>
                <div class="file-info">
                    <span class="file-name">{{ imgName(img) }}</span>
                    <span class="file-size">{{ imgSize(img) }}</span>
                </div>
            </div>
        </div>
        <span v-if="errorMsg" class="text-red-500 text-sm mt-1">{{ errorMsg }}</span>
        <span v-if="errors.file" class="text-red-500 text-sm">{{ errors.file }}</span>
    </div>
</template>

<script setup>
import { ref, defineProps, defineEmits } from 'vue';

const props = defineProps({
    id: { type: String, default: 'fileInput' },
    label: { type: String, default: 'Arquivo' },
    ariaLabel: { type: String, default: 'Selecione um arquivo' },
    errors: { type: Object, default: () => ({}) },
    modelValue: { type: Array, default: () => [] },
    multiple: { type: Boolean, default: false },
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

function imgKey(img, idx) {
    if (typeof img === 'string') return img + idx;
    if (img instanceof File) return img.name + img.size + idx;
    return idx;
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
    const files = Array.from(e.target.files);
    handleFiles(files);
    e.target.value = '';
}

function onDrop(e) {
    dragActive.value = false;
    errorMsg.value = '';
    const files = Array.from(e.dataTransfer.files);
    handleFiles(files);
}

function handleFiles(files) {
    const valid = [];
    for (const file of files) {
        if (!['image/jpeg', 'image/png'].includes(file.type)) {
            errorMsg.value = 'Apenas arquivos JPG e PNG são permitidos.';
            continue;
        }
        if (file.size > 5 * 1024 * 1024) {
            errorMsg.value = 'O tamanho máximo por imagem é 5MB.';
            continue;
        }
        valid.push(file);
    }
    if (valid.length) {
        emit('update:modelValue', props.multiple ? [...props.modelValue, ...valid] : [valid[0]]);
    }
}

function removeImage(idx) {
    const newValue = [...props.modelValue];
    newValue.splice(idx, 1);
    emit('update:modelValue', newValue);
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
}
.dropzone:focus, .dropzone-active {
    border-color: #1e40af;
    background: #e0e7ff;
}
.dropzone-content {
    pointer-events: none;
}
.image-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
    gap: 1rem;
}
.image-thumb {
    position: relative;
    border: 2px solid #e5e7eb;
    border-radius: 0.5rem;
    overflow: hidden;
    background: #fff;
    box-shadow: 0 1px 4px #0001;
    transition: border-color 0.2s, box-shadow 0.2s;
    padding-bottom: 0.5rem;
}
.image-thumb:hover, .image-thumb:focus-within {
    border-color: #2563eb;
    box-shadow: 0 2px 8px #2563eb22;
}
.thumb-img {
    width: 100%;
    height: 100px;
    object-fit: cover;
    display: block;
    border-bottom: 1px solid #f3f4f6;
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
    padding: 0.25rem 0.5rem 0 0.5rem;
    font-size: 0.85rem;
    color: #374151;
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    gap: 2px;
}
.file-name {
    font-weight: 500;
    word-break: break-all;
}
.file-size {
    color: #6b7280;
    font-size: 0.8em;
}
@media (max-width: 600px) {
    .image-grid {
        grid-template-columns: repeat(auto-fit, minmax(90px, 1fr));
        gap: 0.5rem;
    }
    .thumb-img {
        height: 70px;
    }
}
</style>
