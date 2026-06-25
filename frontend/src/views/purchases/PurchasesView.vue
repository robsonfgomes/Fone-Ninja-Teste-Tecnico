<script setup lang="ts">
import { onMounted } from 'vue';
import { usePurchasesListing } from '@/composables/usePurchasesListing';
import PurchasesTable from './components/PurchasesTable.vue';
import AppPagination from '@/components/AppPagination.vue';

const { purchases, meta, fetchPurchases } = usePurchasesListing();

onMounted(() => fetchPurchases());
</script>

<template>
  <div>
    <h2 class="mb-4">Compras</h2>
    <PurchasesTable :purchases="purchases" />
    <div v-if="meta" class="d-flex justify-content-center mt-3">
      <AppPagination :meta="meta" @page-change="fetchPurchases" />
    </div>
  </div>
</template>
