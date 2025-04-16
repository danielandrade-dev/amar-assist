<script setup>
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import { Head, Link } from '@inertiajs/vue3';
    import Table from '@/Components/Table.vue';
    import { ref, computed } from 'vue';
    import SearchInput from '@/Components/SearchInput.vue';
    import Tooltip from '@/Components/Tooltip.vue';
    import Paginate from '@/Components/Paginate.vue';
    import ToastContainer from '@/Components/ToastContainer.vue';
    import { useToast } from '@/Composables/useToast';

const props = defineProps({
    products: {
        type: Object,
        data: {
            type: Object,
            default: () => [],
        },
    },
});

console.log(props.products);
const search = ref('');

const { success: showSuccess } = useToast();

const filteredProducts = computed(() => {
    if (!search.value) return Array.isArray(props.products.data) ? props.products.data : [];
    const s = search.value.toLowerCase();
    return (Array.isArray(props.products.data) ? props.products.data : []).filter(p =>
        (p.name && p.name.toLowerCase().includes(s)) ||
        (p.description && p.description.toLowerCase().includes(s)) ||
        (p.price && String(p.price).toLowerCase().includes(s)) ||
        (p.status && String(p.status).toLowerCase().includes(s)) ||
        (p.created_at && formatDate(p.created_at).toLowerCase().includes(s))
    );
});

function confirmDelete(event) {
    if (!window.confirm('Tem certeza que deseja deletar este produto?')) {
        event.preventDefault();
    }
}

function changeStatus(event) {
    if (!window.confirm('Tem certeza que deseja alterar o status deste produto?')) {
        event.preventDefault();
    }
}

function formatDate(date) {
    return date ? new Date(date).toLocaleDateString('pt-BR', { day: '2-digit', month: '2-digit', year: 'numeric' }) : 'N/A';
}
</script>

<template>
    <Head title="Produtos" />
    <AuthenticatedLayout>
        <ToastContainer />
        <template #header>
            <h2 class="font-semibold text-2xl text-primary leading-tight mb-6">Produtos</h2>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl border border-light">
                    <div class="p-8 text-dark">
                        <div class="flex flex-col sm:flex-row justify-between items-stretch gap-4 mb-6">
                            <div class="flex-1">
                                <SearchInput v-model="search" placeholder="Buscar produtos..." aria-label="Buscar produtos" />
                            </div>
                            <Link :href="route('products.create')" class="bg-primary hover:bg-secondary text-white px-6 py-3 rounded-lg shadow-md flex items-center gap-2 font-semibold transition focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                                Adicionar Produto
                            </Link>
                        </div>
                        <Table
                            :columns="[
                                { key: 'name', label: 'Nome' }, 
                                { key: 'price', label: 'Preço', class: 'text-success font-semibold' },
                                { key: 'cost', label: 'Custo', class: 'text-danger font-semibold' },
                                { key: 'created_at', label: 'Data de criação', class: 'max-w-md truncate text-gray-700' },
                                { key: 'actions', label: 'Ações', slot: 'actions', class: 'text-center' }
                            ]"
                            :data="filteredProducts.map(p => ({ ...p, price: p.price ? 'R$ ' + p.price : 'N/A', created_at: formatDate(p.created_at) }))"
                        >
                            <template #actions="{ row }">
                                <div class="flex gap-2 justify-center">
                                    <Tooltip content="Editar produto">
                                        <Link :href="'/products/' + row.slug + '/edit'" class="bg-primary hover:bg-secondary text-white px-4 py-2 rounded-md flex items-center gap-1 transition focus:outline-none focus:ring-2 focus:ring-primary">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-4 w-4"><path d="M6.41421 15.89L16.5563 5.74785L15.1421 4.33363L5 14.4758V15.89H6.41421ZM7.24264 17.89H3V13.6473L14.435 2.21231C14.8256 1.82179 15.4587 1.82179 15.8492 2.21231L18.6777 5.04074C19.0682 5.43126 19.0682 6.06443 18.6777 6.45495L7.24264 17.89ZM3 19.89H21V21.89H3V19.89Z"></path></svg>
                                        </Link>
                                    </Tooltip>
                                    <Tooltip content="Deletar produto">
                                        <Link 
                                            :href="route('products.destroy', row.slug)" 
                                            method="delete" 
                                            as="button" 
                                            class="bg-danger hover:bg-secondary text-white px-4 py-2 rounded-md flex items-center gap-1 transition focus:outline-none focus:ring-2 focus:ring-danger" 
                                            @click="confirmDelete"
                                            @success="showSuccess('Produto deletado com sucesso!')"
                                        >
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-4 w-4">
                                                <path d="M17 6H22V8H20V21C20 21.5523 19.5523 22 19 22H5C4.44772 22 4 21.5523 4 21V8H2V6H7V3C7 2.44772 7.44772 2 8 2H16C16.5523 2 17 2.44772 17 3V6ZM18 8H6V20H18V8ZM9 11H11V17H9V11ZM13 11H15V17H13V11ZM9 4V6H15V4H9Z"></path>
                                            </svg>
                                        </Link>
                                    </Tooltip>
                                    <Tooltip :content="row.status ? 'Desativar produto' : 'Ativar produto'">
                                        <Link
                                            :href="'/products/' + row.slug + '/status'"
                                            method="put"
                                            as="button"
                                            :data="{ status: !row.status }"
                                            :class="[row.status ? 'bg-warning hover:bg-primary' : 'bg-success hover:bg-primary', 'text-white px-4 py-2 rounded-md flex items-center gap-1 transition focus:outline-none focus:ring-2 focus:ring-danger']"
                                            @click="changeStatus"
                                            @success="showSuccess('Status do produto alterado com sucesso!')"
                                        >
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-4 w-4">
                                                <path d="M6.26489 3.80698L7.41191 5.44558C5.34875 6.89247 4 9.28873 4 12C4 16.4183 7.58172 20 12 20C16.4183 20 20 16.4183 20 12C20 9.28873 18.6512 6.89247 16.5881 5.44558L17.7351 3.80698C20.3141 5.61559 22 8.61091 22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12C2 8.61091 3.68594 5.61559 6.26489 3.80698ZM11 12V2H13V12H11Z"></path>
                                            </svg>
                                        </Link>
                                    </Tooltip>
                                </div>
                            </template>
                            <template #empty>
                                Nenhum produto cadastrado no momento.<br>Clique em <span class="text-primary font-semibold">Adicionar Produto</span> para criar o primeiro!
                            </template>
                        </Table>
                        <Paginate
                            :current="props.products.current_page || 1"
                            :total="props.products.last_page || 1"
                            @change="page => $inertia.visit(route('products.index', { page, search: search }))"
                        />
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>