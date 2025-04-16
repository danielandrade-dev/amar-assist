<template>
  <div class="tooltip-wrapper" @mouseenter="show = true" @mouseleave="show = false" @focusin="show = true" @focusout="show = false" tabindex="0">
    <slot />
    <transition name="fade">
      <div v-if="show" class="tooltip" :class="placementClass" role="tooltip">
        {{ content }}
        <span class="tooltip-arrow" :class="placementClass" />
      </div>
    </transition>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
const props = defineProps({
  content: { type: String, required: true },
  placement: { type: String, default: 'top' },
});
const show = ref(false);
const placementClass = computed(() => {
  return {
    top: 'tooltip-top',
    right: 'tooltip-right',
    bottom: 'tooltip-bottom',
    left: 'tooltip-left',
  }[props.placement] || 'tooltip-top';
});
</script>

<style scoped>
.tooltip-wrapper {
  display: inline-block;
  position: relative;
  outline: none;
}
.tooltip {
  position: absolute;
  z-index: 9999;
  background: #343a40;
  color: #fff;
  padding: 0.5rem 0.75rem;
  border-radius: 0.375rem;
  font-size: 0.95rem;
  box-shadow: 0 4px 16px #0002;
  white-space: nowrap;
  pointer-events: none;
  opacity: 0.97;
  transition: opacity 0.15s;
}
.tooltip-top {
  left: 50%;
  bottom: 100%;
  transform: translateX(-50%) translateY(-8px);
}
.tooltip-bottom {
  left: 50%;
  top: 100%;
  transform: translateX(-50%) translateY(8px);
}
.tooltip-right {
  left: 100%;
  top: 50%;
  transform: translateY(-50%) translateX(8px);
}
.tooltip-left {
  right: 100%;
  top: 50%;
  transform: translateY(-50%) translateX(-8px);
}
.tooltip-arrow {
  position: absolute;
  width: 0;
  height: 0;
  border-style: solid;
}
.tooltip-top .tooltip-arrow {
  left: 50%;
  top: 100%;
  transform: translateX(-50%);
  border-width: 6px 6px 0 6px;
  border-color: #343a40 transparent transparent transparent;
}
.tooltip-bottom .tooltip-arrow {
  left: 50%;
  bottom: 100%;
  transform: translateX(-50%);
  border-width: 0 6px 6px 6px;
  border-color: transparent transparent #343a40 transparent;
}
.tooltip-right .tooltip-arrow {
  top: 50%;
  left: 0;
  transform: translateY(-50%);
  border-width: 6px 6px 6px 0;
  border-color: transparent #343a40 transparent transparent;
}
.tooltip-left .tooltip-arrow {
  top: 50%;
  right: 0;
  transform: translateY(-50%);
  border-width: 6px 0 6px 6px;
  border-color: transparent transparent transparent #343a40;
}
.fade-enter-active, .fade-leave-active {
  transition: opacity 0.15s;
}
.fade-enter-from, .fade-leave-to {
  opacity: 0;
}
</style> 