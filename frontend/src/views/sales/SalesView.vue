<script setup lang="ts">
import { onMounted, ref } from 'vue';
import { useSalesListing } from '@/composables/useSalesListing';
import SalesTable from './components/SalesTable.vue';
import CreateSaleModal from './components/CreateSaleModal.vue';
import AppPagination from '@/components/AppPagination.vue';
import AppButton from '@/components/AppButton.vue';

const { sales, meta, fetchSales } = useSalesListing();
const createModal = ref<InstanceType<typeof CreateSaleModal>>();

onMounted(() => fetchSales());
</script>

<template>
  <div>
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2 class="mb-0">Vendas</h2>
      <AppButton variant="primary" @click="createModal!.show()">
        Registrar Venda
      </AppButton>
    </div>

    <CreateSaleModal ref="createModal" @created="fetchSales()" />
    <SalesTable :sales="sales" />
    <div v-if="meta" class="d-flex justify-content-center mt-3">
      <AppPagination :meta="meta" @page-change="fetchSales" />
    </div>
  </div>
</template>
