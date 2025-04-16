<template>
  <div class="relative w-full max-w-xs">
    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 105 11a6 6 0 0012 0z" /></svg>
    </span>
    <input
      :value="modelValue"
      @input="$emit('update:modelValue', $event.target.value)"
      :placeholder="placeholder"
      :aria-label="ariaLabel"
      class="search-input pl-10 pr-8 py-2 rounded-lg border border-light bg-white text-dark focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary w-full transition"
      type="text"
      autocomplete="off"
    />
    <button v-if="modelValue" @click="$emit('update:modelValue', '')" type="button" class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-400 hover:text-danger focus:outline-none" aria-label="Limpar busca">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
    </button>
  </div>
</template>

<script setup>
const props = defineProps({
  modelValue: { type: String, default: '' },
  placeholder: { type: String, default: 'Buscar...' },
  ariaLabel: { type: String, default: 'Buscar' },
});
</script>

<style scoped>
.search-input {
  background: #fff;
  color: #343a40;
  border-radius: 0.5rem;
  border: 1px solid #621587;
  font-size: 1rem;
  transition: border-color 0.2s, box-shadow 0.2s;
}
.search-input:focus {
  border-color: #621587;
  box-shadow: 0 0 0 2px #62158733;
}
.text-danger {
  color: #F36606;
}
</style> 