<script setup lang="ts">
import { computed } from 'vue';
import type { PaginationMeta, PaginationLink } from '@/types/pagination';

const props = defineProps<{ meta: PaginationMeta }>();
const emit = defineEmits<{ 'page-change': [page: number] }>();

const pageLinks = computed(() =>
  props.meta.links.filter(
    (link): link is PaginationLink & { page: number } =>
      !link.label.includes('laquo') &&
      !link.label.includes('raquo') &&
      link.page !== null,
  ),
);

const isFirstPage = computed(() => props.meta.current_page === 1);
const isLastPage = computed(() => props.meta.current_page === props.meta.last_page);
const prevPage = computed(() => props.meta.current_page - 1);
const nextPage = computed(() => props.meta.current_page + 1);
</script>

<template>
  <nav v-if="meta.last_page > 1" aria-label="Paginação">
    <ul class="pagination mb-0">
      <li class="page-item" :class="{ disabled: isFirstPage }">
        <button class="page-link" :disabled="isFirstPage" @click="emit('page-change', prevPage)">
          &laquo;
        </button>
      </li>
      <li v-for="link in pageLinks" :key="link.label" class="page-item" :class="{ active: link.active }">
        <button class="page-link" @click="emit('page-change', link.page)">
          {{ link.label }}
        </button>
      </li>
      <li class="page-item" :class="{ disabled: isLastPage }">
        <button class="page-link" :disabled="isLastPage" @click="emit('page-change', nextPage)">
          &raquo;
        </button>
      </li>
    </ul>
  </nav>
</template>
