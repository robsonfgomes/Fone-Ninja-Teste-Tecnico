<script setup lang="ts">
import { onMounted } from 'vue';
import { useSalesStore } from '@/stores/sales.store';
import SalesTable from './components/SalesTable.vue';
import AppPagination from '@/components/AppPagination.vue';

const store = useSalesStore();

onMounted(() => store.fetchSales());
</script>

<template>
  <div>
    <h2 class="mb-4">Vendas</h2>
    <SalesTable :sales="store.sales" />
    <div v-if="store.meta" class="d-flex justify-content-center mt-3">
      <AppPagination :meta="store.meta" @page-change="store.fetchSales" />
    </div>
  </div>
</template>
