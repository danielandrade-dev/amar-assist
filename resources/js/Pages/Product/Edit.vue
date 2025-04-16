<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import { useForm } from '@inertiajs/vue3';
import TextInput from '@/Components/TextInput.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputFile from '@/Components/InputFile.vue';
import InputText from '@/Components/InputLongText.vue';

const props = defineProps({
    product: {
        type: Object,
        required: true,
    },
});

const form = useForm({
    name: props.product.name || '',
    description: props.product.description || '',
    price: props.product.price || '',
    cost: props.product.cost || '',
    image: [], // imagens novas, se quiser manter as antigas, pode exibir miniaturas
});

const isSubmitting = ref(false);

function updateProduct() {
    isSubmitting.value = true;
    form.put(route('products.update', props.product.id), {
        onFinish: () => isSubmitting.value = false,
    });
}

function onlyNumberCommaDot(e) {
    const allowed = /[0-9.,]/;
    if (!allowed.test(e.key) && e.key.length === 1) {
        e.preventDefault();
    }
}

function validateCurrency(value) {
    if (!value) return true;
    const normalized = value.replace(',', '.');
    return /^\d+(\.|,)?\d{0,2}$/.test(value) && !/^\.|^,/.test(value);
}
</script>
<template>
    <Head title="Editar Produto" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-2xl text-primary leading-tight mb-6">Editar Produto</h2>
        </template>
        <div class="py-12">
            <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl border border-light">
                    <div class="p-8 text-dark flex flex-col gap-8">
                        <form @submit.prevent="updateProduct" class="space-y-8">
                            <div class="form-group flex flex-col gap-2">
                                <InputLabel for="name">Nome</InputLabel>
                                <TextInput id="name" v-model="form.name" class="form-control" placeholder="Digite o nome do produto" aria-label="Nome do produto" :class="{'border-danger': form.errors.name}" autofocus />
                                <span v-if="form.errors.name" class="text-danger text-xs mt-1">{{ form.errors.name }}</span>
                            </div>
                            <div class="form-group flex flex-col gap-2">
                                <InputLabel for="description">Descrição</InputLabel>
                                <InputText id="description" v-model="form.description" class="form-control" placeholder="Descreva o produto em HTML. Apenas <p>, <br>, <b> e <strong> são permitidos." aria-label="Descrição do produto" :class="{'border-danger': form.errors.description}" />
                                <span class="text-xs text-gray-500 mt-1">Exemplo: &lt;p&gt;Descrição detalhada&lt;/p&gt; &lt;br&gt; &lt;strong&gt;Destaque&lt;/strong&gt;</span>
                                <span v-if="form.errors.description" class="text-danger text-xs mt-1">{{ form.errors.description }}</span>
                            </div>
                            <div class="form-group flex flex-col gap-4 md:flex-row">
                                <div class="flex-1 flex flex-col gap-2">
                                    <InputLabel for="price">Preço</InputLabel>
                                    <div class="relative">
                                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 select-none pointer-events-none">R$</span>
                                        <TextInput id="price" v-model="form.price" class="form-control pl-10" placeholder="0,00" aria-label="Preço do produto" inputmode="decimal" :class="{'border-danger': form.errors.price}" @keypress="onlyNumberCommaDot" />
                                    </div>
                                    <span class="text-xs text-gray-500 mt-1">Apenas números, vírgula ou ponto. Ex: 10,99</span>
                                    <span v-if="form.price && !validateCurrency(form.price)" class="text-danger text-xs mt-1">Formato inválido. Use apenas números, vírgula ou ponto.</span>
                                    <span v-if="form.errors.price" class="text-danger text-xs mt-1">{{ form.errors.price }}</span>
                                </div>
                                <div class="flex-1 flex flex-col gap-2">
                                    <InputLabel for="cost">Custo</InputLabel>
                                    <div class="relative">
                                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 select-none pointer-events-none">R$</span>
                                        <TextInput id="cost" v-model="form.cost" class="form-control pl-10" placeholder="0,00" aria-label="Custo do produto" inputmode="decimal" :class="{'border-danger': form.errors.cost}" @keypress="onlyNumberCommaDot" />
                                    </div>
                                    <span class="text-xs text-gray-500 mt-1">Apenas números, vírgula ou ponto. Ex: 5,50</span>
                                    <span v-if="form.cost && !validateCurrency(form.cost)" class="text-danger text-xs mt-1">Formato inválido. Use apenas números, vírgula ou ponto.</span>
                                    <span v-if="form.errors.cost" class="text-danger text-xs mt-1">{{ form.errors.cost }}</span>
                                </div>
                            </div>
                            <div class="form-group flex flex-col gap-2">
                                <InputLabel for="image">Imagem</InputLabel>
                                <InputFile id="image" :multiple="true" v-model="form.image" class="form-control file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-primary file:text-white hover:file:bg-primary-dark" aria-label="Imagem do produto" />
                                <span class="text-xs text-gray-500 mt-1">Apenas JPG e PNG. Selecione uma ou mais imagens.</span>
                                <span v-if="form.errors.image" class="text-danger text-xs mt-1">{{ form.errors.image }}</span>
                            </div>
                            <div class="form-group mt-6">
                                <button type="submit" class="bg-primary hover:bg-secondary text-white px-8 py-3 rounded-lg w-full flex items-center justify-center text-base font-semibold shadow-md transition disabled:opacity-60 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2" :disabled="isSubmitting">
                                    <span v-if="isSubmitting" class="loader mr-2"></span>
                                    {{ isSubmitting ? 'Salvando...' : 'Salvar Alterações' }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
.form-control {
    border: 1px solid #d1d5db;
    border-radius: 0.5rem;
    padding: 0.75rem 1rem;
    outline: none;
    transition: border-color 0.2s, box-shadow 0.2s;
    font-size: 1rem;
    background: #f9fafb;
    color: #343a40;
}
.form-control:focus {
    border-color: #621587;
    box-shadow: 0 0 0 2px #62158733;
    background: #fff;
}
.border-danger {
    border-color: #F36606 !important;
}
.bg-primary {
    background-color: #621587;
}
.bg-primary-dark {
    background-color: #4b0e6a;
}
.bg-secondary {
    background-color: #F36606;
}
.text-primary {
    color: #621587;
}
.text-dark {
    color: #343a40;
}
.text-danger {
    color: #F36606;
}
.text-success {
    color: #61CE70;
}
.border-light {
    border-color: #f8f9fa;
}
.loader {
    border: 2px solid #f3f3f3;
    border-top: 2px solid #621587;
    border-radius: 50%;
    width: 1rem;
    height: 1rem;
    animation: spin 1s linear infinite;
    display: inline-block;
}
@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
@media (max-width: 640px) {
    .p-8 { padding: 1.25rem !important; }
    .space-y-8 > :not([hidden]) ~ :not([hidden]) { margin-top: 1.5rem !important; }
    .form-group { gap: 0.75rem !important; }
}
.form-control.pl-10 {
    padding-left: 2.5rem;
}
</style> 