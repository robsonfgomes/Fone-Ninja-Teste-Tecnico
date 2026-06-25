<script setup lang="ts">
defineProps<{
  variant?: string;
  size?: 'sm' | 'lg' | '';
  type?: 'button' | 'submit' | 'reset';
  loading?: boolean;
  disabled?: boolean;
}>();
</script>

<template>
  <button
    :type="type ?? 'button'"
    class="btn"
    :class="[`btn-${variant ?? 'primary'}`, size ? `btn-${size}` : '']"
    :disabled="disabled || loading"
  >
    <span v-if="loading" class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
    <slot />
  </button>
</template>
