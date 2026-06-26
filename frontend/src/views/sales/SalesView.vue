<script setup lang="ts">
import { onMounted, ref } from 'vue';
import { useSalesListing } from '@/composables/useSalesListing';
import SalesTable from './components/SalesTable.vue';
import CreateSaleModal from './components/CreateSaleModal.vue';
import CancelSaleModal from './components/CancelSaleModal.vue';
import AppPagination from '@/components/AppPagination.vue';
import AppButton from '@/components/AppButton.vue';
import type { Sale } from '@/types/sale';

const { sales, meta, fetchSales } = useSalesListing();
const createModal = ref<InstanceType<typeof CreateSaleModal>>();
const cancelModal = ref<InstanceType<typeof CancelSaleModal>>();
const selectedSale = ref<Sale | null>(null);

function openCancelModal(sale: Sale) {
  selectedSale.value = sale;
  cancelModal.value!.show();
}

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
    <CancelSaleModal ref="cancelModal" :sale="selectedSale" @cancelled="fetchSales()" />
    <SalesTable :sales="sales" @cancel-sale="openCancelModal" />
    <div v-if="meta" class="d-flex justify-content-center mt-3">
      <AppPagination :meta="meta" @page-change="fetchSales" />
    </div>
  </div>
</template>
