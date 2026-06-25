<script setup lang="ts">
import type { Product } from '@/types/product';
import { formatCurrency } from '@/utils/format';

defineProps<{
  products: Product[];
}>();
</script>

<template>
  <div class="card">
    <div class="card-body p-0">
      <div v-if="products.length === 0" class="text-center text-muted py-4">
        Nenhum produto cadastrado.
      </div>
      <div v-else class="table-responsive">
        <table class="table table-hover mb-0">
          <thead class="table-light">
            <tr>
              <th>Nome</th>
              <th class="text-end">Preço de Venda (Sugerido)</th>
              <th class="text-end">Custo Médio</th>
              <th class="text-center">Estoque</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="product in products" :key="product.id">
              <td>{{ product.name }}</td>
              <td class="text-end">{{ formatCurrency(product.sellingPrice) }}</td>
              <td class="text-end">{{ formatCurrency(product.averageCost) }}</td>
              <td class="text-center">
                <span :class="product.currentStock === 0 ? 'text-danger fw-bold' : ''">
                  {{ product.currentStock }}
                </span>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>
