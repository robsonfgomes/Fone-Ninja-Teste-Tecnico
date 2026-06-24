<script setup lang="ts">
import { onMounted } from 'vue';
import { useProductsStore } from '@/stores/products.store';
import ProductsTable from './components/ProductsTable.vue';
import AppPagination from '@/components/AppPagination.vue';

const store = useProductsStore();

onMounted(() => store.fetchProducts());
</script>

<template>
  <div>
    <h2 class="mb-4">Produtos</h2>
    <ProductsTable :products="store.products" />
    <div v-if="store.meta" class="d-flex justify-content-end mt-3">
      <AppPagination :meta="store.meta" @page-change="store.fetchProducts" />
    </div>
  </div>
</template>
