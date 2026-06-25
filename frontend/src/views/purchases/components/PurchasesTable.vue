<script setup lang="ts">
import type { PurchaseOrder } from '@/types/purchase';
import { formatCurrency } from '@/utils/format';

defineProps<{
  purchases: PurchaseOrder[];
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
              <th>Fornecedor</th>
              <th class="text-end">Total</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="purchase in purchases" :key="purchase.id">
              <td>{{ purchase.supplierName }}</td>
              <td class="text-end">{{ formatCurrency(purchase.totalAmount) }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>
