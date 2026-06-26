<script setup lang="ts">
import { onMounted, ref } from 'vue';
import { usePurchasesListing } from '@/composables/usePurchasesListing';
import PurchasesTable from './components/PurchasesTable.vue';
import CreatePurchaseModal from './components/CreatePurchaseModal.vue';
import PurchaseItemsModal from './components/PurchaseItemsModal.vue';
import AppButton from '@/components/AppButton.vue';
import AppPagination from '@/components/AppPagination.vue';
import type { PurchaseOrder } from '@/types/purchase';

const { purchases, meta, fetchPurchases } = usePurchasesListing();
const createModalRef = ref<InstanceType<typeof CreatePurchaseModal>>();
const itemsModal = ref<InstanceType<typeof PurchaseItemsModal>>();
const selectedPurchaseForItems = ref<PurchaseOrder | null>(null);

function openItemsModal(purchase: PurchaseOrder) {
  selectedPurchaseForItems.value = purchase;
  itemsModal.value!.show();
}

onMounted(() => fetchPurchases());
</script>

<template>
  <div>
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2 class="mb-0">Compras</h2>
      <AppButton @click="createModalRef!.show()">
        <i class="bi bi-plus-lg me-1"></i> Registrar Compra
      </AppButton>
    </div>

    <PurchasesTable :purchases="purchases" @view-items="openItemsModal" />

    <div v-if="meta" class="d-flex justify-content-center mt-3">
      <AppPagination :meta="meta" @page-change="fetchPurchases" />
    </div>

    <CreatePurchaseModal ref="createModalRef" @created="fetchPurchases" />
    <PurchaseItemsModal ref="itemsModal" :purchase="selectedPurchaseForItems" />
  </div>
</template>
