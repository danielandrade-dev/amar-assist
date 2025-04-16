<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import Table from '@/Components/Table.vue';
import { ref, computed } from 'vue';
import SearchInput from '@/Components/SearchInput.vue';
import Tooltip from '@/Components/Tooltip.vue';
import Paginate from '@/Components/Paginate.vue';
import { useToast } from '@/Composables/useToast';

const props = defineProps({
    users: {
        type: Object,
        data: {
            type: Object,
            default: () => [],
        },
    },
});

const search = ref('');
const { success: showSuccess } = useToast();

const filteredUsers = computed(() => {
    if (!search.value) return Array.isArray(props.users.data) ? props.users.data : [];
    const s = search.value.toLowerCase();
    return (Array.isArray(props.users.data) ? props.users.data : []).filter(user =>
        (user.name && user.name.toLowerCase().includes(s)) ||
        (user.email && user.email.toLowerCase().includes(s)) ||
        (user.created_at && formatDate(user.created_at).toLowerCase().includes(s))
    );
});

function confirmDelete(event) {
    if (!window.confirm('Tem certeza que deseja deletar este usuário?')) {
        event.preventDefault();
    }
}

function formatDate(date) {
    return date ? new Date(date).toLocaleDateString('pt-BR', { day: '2-digit', month: '2-digit', year: 'numeric' }) : 'N/A';
}
</script>

<template>
    <Head title="Usuários" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-2xl text-primary leading-tight mb-6">Usuários</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl border border-light">
                    <div class="p-8 text-dark">
                        <div class="flex flex-col sm:flex-row justify-between items-stretch gap-4 mb-6">
                            <div class="flex-1">
                                <SearchInput v-model="search" placeholder="Buscar usuários..." aria-label="Buscar usuários" />
                            </div>
                            <Link :href="route('users.create')" class="bg-primary hover:bg-secondary text-white px-6 py-3 rounded-lg shadow-md flex items-center gap-2 font-semibold transition focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                Adicionar Usuário
                            </Link>
                        </div>
                        <Table
                            :columns="[
                                { key: 'name', label: 'Nome' },
                                { key: 'email', label: 'E-mail', class: 'text-gray-700' },
                                { key: 'created_at', label: 'Data de cadastro', class: 'max-w-md truncate text-gray-700' },
                                { key: 'actions', label: 'Ações', slot: 'actions', class: 'text-center' }
                            ]"
                            :data="filteredUsers.map(user => ({ ...user, created_at: formatDate(user.created_at) }))"
                        >
                            <template #actions="{ row }">
                                <div class="flex gap-2 justify-center">
                                    <Tooltip content="Editar usuário">
                                        <Link :href="route('users.edit', row.id)" class="bg-primary hover:bg-secondary text-white px-4 py-2 rounded-md flex items-center gap-1 transition focus:outline-none focus:ring-2 focus:ring-primary">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-4 w-4">
                                                <path d="M6.41421 15.89L16.5563 5.74785L15.1421 4.33363L5 14.4758V15.89H6.41421ZM7.24264 17.89H3V13.6473L14.435 2.21231C14.8256 1.82179 15.4587 1.82179 15.8492 2.21231L18.6777 5.04074C19.0682 5.43126 19.0682 6.06443 18.6777 6.45495L7.24264 17.89ZM3 19.89H21V21.89H3V19.89Z" />
                                            </svg>
                                        </Link>
                                    </Tooltip>
                                    <Tooltip content="Deletar usuário">
                                        <Link 
                                            :href="route('users.destroy', row.id)" 
                                            method="delete" 
                                            as="button" 
                                            class="bg-danger hover:bg-secondary text-white px-4 py-2 rounded-md flex items-center gap-1 transition focus:outline-none focus:ring-2 focus:ring-danger" 
                                            @click="confirmDelete"
                                            @success="showSuccess('Usuário deletado com sucesso!')"
                                        >
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-4 w-4">
                                                <path d="M17 6H22V8H20V21C20 21.5523 19.5523 22 19 22H5C4.44772 22 4 21.5523 4 21V8H2V6H7V3C7 2.44772 7.44772 2 8 2H16C16.5523 2 17 2.44772 17 3V6ZM18 8H6V20H18V8ZM9 11H11V17H9V11ZM13 11H15V17H13V11ZM9 4V6H15V4H9Z" />
                                            </svg>
                                        </Link>
                                    </Tooltip>
                                </div>
                            </template>
                            <template #empty>
                                Nenhum usuário cadastrado no momento.<br>Clique em <span class="text-primary font-semibold">Adicionar Usuário</span> para criar o primeiro!
                            </template>
                        </Table>
                        <Paginate
                            :current="props.users.current_page || 1"
                            :total="props.users.last_page || 1"
                            @change="page => $inertia.visit(route('users.index', { page, search: search }))"
                        />
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>