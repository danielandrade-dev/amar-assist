<template>
  <nav class="paginate flex items-center justify-center gap-1 mt-6" role="navigation" aria-label="Paginação">
    <button
      class="paginate-btn"
      :disabled="current === 1"
      @click="$emit('change', 1)"
      aria-label="Primeira página"
    >«</button>
    <button
      class="paginate-btn"
      :disabled="current === 1"
      @click="$emit('change', current - 1)"
      aria-label="Página anterior"
    >‹</button>
    <template v-for="page in pagesToShow" :key="page">
      <button
        class="paginate-btn"
        :class="{ 'active': page === current }"
        :disabled="page === current"
        @click="$emit('change', page)"
        :aria-label="'Página ' + page"
      >{{ page }}</button>
    </template>
    <button
      class="paginate-btn"
      :disabled="current === total"
      @click="$emit('change', current + 1)"
      aria-label="Próxima página"
    >›</button>
    <button
      class="paginate-btn"
      :disabled="current === total"
      @click="$emit('change', total)"
      aria-label="Última página"
    >»</button>
  </nav>
</template>

<script setup>
import { computed } from 'vue';
const props = defineProps({
  current: { type: Number, required: true },
  total: { type: Number, required: true },
  max: { type: Number, default: 5 }, // máximo de páginas visíveis
});
const pagesToShow = computed(() => {
  const pages = [];
  let start = Math.max(1, props.current - Math.floor(props.max / 2));
  let end = start + props.max - 1;
  if (end > props.total) {
    end = props.total;
    start = Math.max(1, end - props.max + 1);
  }
  for (let i = start; i <= end; i++) pages.push(i);
  return pages;
});
</script>

<style scoped>
.paginate {
  gap: 0.25rem;
  flex-wrap: wrap;
}
.paginate-btn {
  min-width: 36px;
  min-height: 36px;
  padding: 0 0.75rem;
  border-radius: 0.375rem;
  border: 1.5px solid #f8f9fa;
  background: #fff;
  color: #621587;
  font-weight: 600;
  font-size: 1rem;
  transition: background 0.2s, border 0.2s, color 0.2s;
  cursor: pointer;
  outline: none;
}
.paginate-btn:hover:not(:disabled), .paginate-btn:focus:not(:disabled) {
  background: #621587;
  color: #fff;
  border-color: #621587;
}
.paginate-btn.active {
  background: #621587;
  color: #fff;
  border-color: #621587;
  cursor: default;
}
.paginate-btn:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}
</style> 