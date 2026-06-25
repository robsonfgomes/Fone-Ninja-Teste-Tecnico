<script setup lang="ts">
import { useToastStore } from '@/stores/toast.store';

const toast = useToastStore();

const bgClass: Record<string, string> = {
  error: 'text-bg-danger',
  success: 'text-bg-success',
  warning: 'text-bg-warning',
  info: 'text-bg-info',
};
</script>

<template>
  <div class="toast-container position-fixed top-0 end-0 p-3">
    <TransitionGroup name="toast">
      <div v-for="t in toast.toasts" :key="t.id" class="toast show align-items-center border-0 mb-2"
        :class="bgClass[t.type]" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
          <div class="toast-body">{{ t.message }}</div>
          <button type="button" class="btn-close btn-close-white me-2 m-auto" aria-label="Fechar"
            @click="toast.remove(t.id)" />
        </div>
      </div>
    </TransitionGroup>
  </div>
</template>

<style scoped>
.toast-container {
  z-index: 1100;
}

.toast-enter-active,
.toast-leave-active {
  transition:
    opacity 0.3s ease,
    transform 0.3s ease;
}

.toast-enter-from,
.toast-leave-to {
  opacity: 0;
  transform: translateX(1rem);
}
</style>
