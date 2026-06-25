import { defineStore } from 'pinia';
import { ref, computed } from 'vue';

const SHOW_DELAY_MS = 200;

export const useLoadingStore = defineStore('loading', () => {
  const pendingRequests = ref(0);
  const visible = ref(false);
  const isLoading = computed(() => visible.value);

  let timer: ReturnType<typeof setTimeout> | null = null;

  function start(): void {
    pendingRequests.value++;
    if (timer === null) {
      timer = setTimeout(() => {
        if (pendingRequests.value > 0) visible.value = true;
        timer = null;
      }, SHOW_DELAY_MS);
    }
  }

  function stop(): void {
    if (pendingRequests.value > 0) pendingRequests.value--;
    if (pendingRequests.value === 0) {
      if (timer !== null) {
        clearTimeout(timer);
        timer = null;
      }
      visible.value = false;
    }
  }

  return { isLoading, start, stop };
});
