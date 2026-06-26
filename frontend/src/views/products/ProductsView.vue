<script setup lang="ts">
import { onMounted, ref } from 'vue';
import { useProductsListing } from '@/composables/useProductsListing';
import ProductsTable from './components/ProductsTable.vue';
import CreateProductModal from './components/CreateProductModal.vue';
import AppButton from '@/components/AppButton.vue';
import AppPagination from '@/components/AppPagination.vue';

const { products, meta, fetchProducts } = useProductsListing();
const createModalRef = ref<InstanceType<typeof CreateProductModal>>();

onMounted(() => fetchProducts());
</script>

<template>
  <div>
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2 class="mb-0">Produtos</h2>
      <AppButton @click="createModalRef!.show()">
        <i class="bi bi-plus-lg me-1"></i> Novo Produto
      </AppButton>
    </div>

    <ProductsTable :products="products" />

    <div v-if="meta" class="d-flex justify-content-center mt-3">
      <AppPagination :meta="meta" @page-change="fetchProducts" />
    </div>

    <CreateProductModal ref="createModalRef" @created="fetchProducts" />
  </div>
</template>
