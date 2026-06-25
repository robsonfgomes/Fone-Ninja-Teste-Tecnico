<script setup lang="ts">
import { onMounted, ref } from 'vue';
import { useProductsStore } from '@/stores/products.store';
import ProductsTable from './components/ProductsTable.vue';
import CreateProductModal from './components/CreateProductModal.vue';
import AppButton from '@/components/AppButton.vue';
import AppPagination from '@/components/AppPagination.vue';

const store = useProductsStore();
const createModalRef = ref<InstanceType<typeof CreateProductModal>>();

onMounted(() => store.fetchProducts());
</script>

<template>
  <div>
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2 class="mb-0">Produtos</h2>
      <AppButton @click="createModalRef!.show()">
        <i class="bi bi-plus-lg me-1"></i> Novo Produto
      </AppButton>
    </div>

    <ProductsTable :products="store.products" />

    <div v-if="store.meta" class="d-flex justify-content-center mt-3">
      <AppPagination :meta="store.meta" @page-change="store.fetchProducts" />
    </div>

    <CreateProductModal ref="createModalRef" />
  </div>
</template>
