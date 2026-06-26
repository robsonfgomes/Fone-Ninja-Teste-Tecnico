<script setup lang="ts">
import type { PurchaseOrder } from '@/types/purchase';
import { formatCurrency } from '@/utils/format';
import AppButton from '@/components/AppButton.vue';

defineProps<{
  purchases: PurchaseOrder[];
}>();

const emit = defineEmits<{
  'view-items': [purchase: PurchaseOrder];
}>();
</script>

<template>
  <div class="card">
    <div class="card-body p-0">
      <div v-if="purchases.length === 0" class="text-center text-muted py-4">
        Nenhuma compra registrada.
      </div>
      <div v-else class="table-responsive">
        <table class="table table-hover mb-0">
          <thead class="table-light">
            <tr>
              <th class="text-center">#</th>
              <th>Fornecedor</th>
              <th class="text-end">Total da Compra</th>
              <th class="text-center">Ações</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(purchase, index) in purchases" :key="purchase.id">
              <td class="text-center">{{ (index + 1) }}</td>
              <td>{{ purchase.supplierName }}</td>
              <td class="text-end">{{ formatCurrency(purchase.totalAmount) }}</td>
              <td class="text-center">
                <AppButton variant="info" size="sm" @click="emit('view-items', purchase)">
                  <i class="bi bi-eye"></i>
                </AppButton>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>
