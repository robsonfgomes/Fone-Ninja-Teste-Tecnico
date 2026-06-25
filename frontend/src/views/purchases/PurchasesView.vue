<script setup lang="ts">
import { onMounted } from 'vue';
import { usePurchasesStore } from '@/stores/purchases.store';
import PurchasesTable from './components/PurchasesTable.vue';
import AppPagination from '@/components/AppPagination.vue';

const store = usePurchasesStore();

onMounted(() => store.fetchPurchases());
</script>

<template>
  <div>
    <h2 class="mb-4">Compras</h2>
    <PurchasesTable :purchases="store.purchases" />
    <div v-if="store.meta" class="d-flex justify-content-center mt-3">
      <AppPagination :meta="store.meta" @page-change="store.fetchPurchases" />
    </div>
  </div>
</template>
