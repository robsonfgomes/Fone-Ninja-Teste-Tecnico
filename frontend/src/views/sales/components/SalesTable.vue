<script setup lang="ts">
import type { Sale } from '@/types/sale';
import { formatCurrency } from '@/utils/format';

defineProps<{
  sales: Sale[];
}>();

function statusLabel(status: Sale['status']): string {
  return status === 'Active' ? 'Ativo' : 'Cancelado';
}

function statusClass(status: Sale['status']): string {
  return status === 'Active' ? 'badge bg-success' : 'badge bg-secondary';
}
</script>

<template>
  <div class="card">
    <div class="card-body p-0">
      <div v-if="sales.length === 0" class="text-center text-muted py-4">
        Nenhuma venda registrada.
      </div>
      <div v-else class="table-responsive">
        <table class="table table-hover mb-0">
          <thead class="table-light">
            <tr>
              <th>Cliente</th>
              <th class="text-center">Status</th>
              <th class="text-end">Total</th>
              <th class="text-end">Lucro</th>
              <th class="text-center">Criado em</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="sale in sales" :key="sale.id">
              <td>{{ sale.customerName }}</td>
              <td class="text-center">
                <span :class="statusClass(sale.status)">{{ statusLabel(sale.status) }}</span>
              </td>
              <td class="text-end">{{ formatCurrency(sale.totalAmount) }}</td>
              <td class="text-end" :class="sale.profit >= 0 ? 'text-success' : 'text-danger'">
                {{ formatCurrency(sale.profit) }}
              </td>
              <td class="text-center">{{ sale.createdAt }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>
