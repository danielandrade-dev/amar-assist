<template>
  <div class="overflow-x-auto rounded-lg border border-light bg-white">
    <table class="min-w-full divide-y divide-light">
      <thead class="bg-gray-100">
        <tr>
          <th v-for="col in columns" :key="col.key" :class="['py-4 px-6 text-left text-xs font-bold text-dark uppercase tracking-wider', col.class]">
            {{ col.label }}
          </th>
        </tr>
      </thead>
      <tbody class="bg-white divide-y divide-light">
        <tr v-if="data.length > 0" v-for="(row, idx) in data" :key="row.id || idx" class="hover:bg-primary/5 transition cursor-pointer">
          <td v-for="col in columns" :key="col.key" :class="['py-4 px-6 align-top', col.class]">
            <slot v-if="col.slot" :name="col.slot" :row="row" :value="row[col.key]" />
            <template v-else>{{ row[col.key] ?? 'N/A' }}</template>
          </td>
        </tr>
        <tr v-else>
          <td class="text-center py-20 px-6" :colspan="columns.length">
            <span class="text-gray-500 bg-gray-100 p-6 rounded-lg block mx-auto max-w-md">
              <slot name="empty">Nenhum registro encontrado.</slot>
            </span>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script setup>
const props = defineProps({
  columns: { type: Array, required: true },
  data: { type: Array, required: true },
});
</script>

<style scoped>
.bg-primary {
  background-color: #621587;
}
.text-dark {
  color: #343a40;
}
.border-light {
  border-color: #f8f9fa;
}
</style> 