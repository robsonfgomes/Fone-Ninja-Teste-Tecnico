<script setup lang="ts">
import { onMounted } from 'vue';
import { useSalesListing } from '@/composables/useSalesListing';
import SalesTable from './components/SalesTable.vue';
import AppPagination from '@/components/AppPagination.vue';

const { sales, meta, fetchSales } = useSalesListing();

onMounted(() => fetchSales());
</script>

<template>
  <div>
    <h2 class="mb-4">Vendas</h2>
    <SalesTable :sales="sales" />
    <div v-if="meta" class="d-flex justify-content-center mt-3">
      <AppPagination :meta="meta" @page-change="fetchSales" />
    </div>
  </div>
</template>
