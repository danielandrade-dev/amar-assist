<script setup>
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import { Head, Link } from '@inertiajs/vue3';
    import Table from '@/Components/Table.vue';
    import { ref, computed, h } from 'vue';
    import SearchInput from '@/Components/SearchInput.vue';
    import Tooltip from '@/Components/Tooltip.vue';
    import Paginate from '@/Components/Paginate.vue';

const props = defineProps({
    logs: {
        type: Object,
        data: {
            type: Object,
            default: () => [],
        },
    },
});

const search = ref('');

const getActionColor = (action) => {
    const colors = {
        'create': 'text-green-600 font-medium',
        'update': 'text-blue-600 font-medium',
        'delete': 'text-red-600 font-medium'
    };
    return colors[action.toLowerCase()] || 'text-gray-600';
};

const formatChanges = (oldValues, newValues) => {
    if (!oldValues || !newValues) return '';
    
    const changes = [];
    for (const key in newValues) {
        if (oldValues[key] !== newValues[key]) {
            const oldValue = oldValues[key] || '(vazio)';
            const newValue = newValues[key] || '(vazio)';
            changes.push(`<span class="font-medium">Coluna: ${key}</span><br>De: ${oldValue}<br>Para: ${newValue}`);
        }
    }
    return changes.length ? changes.join('<br>') : 'Sem alterações';
};

const filteredLogs = computed(() => {
    if (!search.value) return props.logs.data || [];
    const s = search.value.toLowerCase();
    return (props.logs.data || []).filter(log =>
        (log.action && log.action.toLowerCase().includes(s)) ||
        (log.user && (
            log.user.name.toLowerCase().includes(s) ||
            log.user.email.toLowerCase().includes(s)
        )) ||
        (log.product && (
            log.product.name.toLowerCase().includes(s) ||
            (log.product.code && log.product.code.toLowerCase().includes(s))
        ))
    );
});

function formatDate(date) {
    if (!date) return 'N/A';
    
    try {
        const d = new Date(date);
        if (isNaN(d.getTime())) return 'Data inválida';
        
        const dia = d.getDate().toString().padStart(2, '0');
        const mes = (d.getMonth() + 1).toString().padStart(2, '0');
        const ano = d.getFullYear();
        const hora = d.getHours().toString().padStart(2, '0');
        const minutos = d.getMinutes().toString().padStart(2, '0');
        
        return `${dia}/${mes}/${ano} às ${hora}:${minutos}`;
    } catch (error) {
        console.error('Erro ao formatar data:', date, error);
        return 'Erro na data';
    }
}
</script>

<template>
    <Head title="Logs do Sistema" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-2xl text-primary leading-tight mb-6">Logs do Sistema</h2>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl border border-light">
                    <div class="p-8 text-dark">
                        <div class="flex flex-col sm:flex-row justify-between items-stretch gap-4 mb-6">
                            <div class="flex-1">
                                <SearchInput v-model="search" placeholder="Buscar nos logs..." aria-label="Buscar nos logs" />
                            </div>
                        </div>
                        <Table
                            :columns="[
                                { 
                                    key: 'action', 
                                    label: 'Ação',
                                    class: 'w-24',
                                    slot: 'action'
                                },
                                { 
                                    key: 'user', 
                                    label: 'Usuário',
                                    class: 'w-40',
                                    slot: 'user'
                                },
                                { 
                                    key: 'product', 
                                    label: 'Produto',
                                    class: 'w-40',
                                    slot: 'product'
                                },
                                {
                                    key: 'changes',
                                    label: 'Alterações',
                                    class: 'w-auto',
                                    slot: 'changes'
                                },
                                { 
                                    key: 'created_at', 
                                    label: 'Data',
                                    class: 'w-60 text-right',
                                    slot: 'created_at'
                                },
                            ]"
                            :data="filteredLogs"
                        >
                            <template #action="{ row }">
                                <span :class="getActionColor(row.action)">
                                    {{ row.action }}
                                </span>
                            </template>

                            <template #user="{ row }">
                                <div v-if="row.user" class="text-sm">
                                    <div class="font-medium text-gray-900">
                                        {{ row.user.name }}
                                    </div>
                                    <div class="text-gray-500">
                                        {{ row.user.email }}
                                    </div>
                                </div>
                                <span v-else class="text-gray-500">Usuário removido</span>
                            </template>

                            <template #product="{ row }">
                                <div v-if="row.product" class="text-sm">
                                    <Tooltip :content="`Custo: R$ ${row.product.cost} | Preço: R$ ${row.product.price}`">
                                        <div class="font-medium text-gray-900">
                                            {{ row.product.name }}
                                        </div>
                                    </Tooltip>
                                </div>
                                <span v-else class="text-gray-500">Produto removido</span>
                            </template>

                            <template #changes="{ row }">
                                <div v-if="row.old_values && row.new_values" 
                                     class="cursor-help text-sm text-gray-600">
                                    <span v-html="formatChanges(row.old_values, row.new_values)"></span>

                                </div>
                                <span v-else>-</span>
                            </template>

                            <template #created_at="{ row }">
                                {{ formatDate(row.created_at) }}
                            </template>

                            <template #empty>
                                <div class="text-center py-4 text-gray-500">
                                    Nenhum log encontrado.
                                </div>
                            </template>
                        </Table>
                        <Paginate
                            :current="props.logs.current_page || 1"
                            :total="props.logs.last_page || 1"
                            @change="page => $inertia.visit(route('logs.index', { page, search: search }))"
                        />
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>