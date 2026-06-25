import { defineStore } from 'pinia';
import { ref } from 'vue';

export type ToastType = 'success' | 'error' | 'warning' | 'info' | 'danger';

interface Toast {
  id: number;
  message: string;
  type: ToastType;
}

let nextId = 0;

export const useToastStore = defineStore('toast', () => {
  const toasts = ref<Toast[]>([]);

  function add(message: string, type: ToastType = 'error', duration = 6000): void {
    const id = nextId++;
    toasts.value.push({ id, message, type });
    setTimeout(() => remove(id), duration);
  }

  function remove(id: number): void {
    const index = toasts.value.findIndex((t) => t.id === id);
    if (index !== -1) toasts.value.splice(index, 1);
  }

  return { toasts, add, remove };
});
