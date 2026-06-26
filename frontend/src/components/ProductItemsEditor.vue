<script setup lang="ts">
import { computed } from 'vue';
import type { Product } from '@/types/product';
import type { ProductItemEditor } from '@/types/order';
import { formatCurrency } from '@/utils/format';

const props = defineProps<{
  products: Product[];
}>();

const items = defineModel<ProductItemEditor[]>({ required: true });

const productsById = computed(() =>
  new Map(props.products.map(p => [p.id, p]))
);

function addLine() {
  items.value.push({ productId: '', quantity: '', unitPrice: '' });
}

function removeLine(index: number) {
  items.value.splice(index, 1);
}

function averageCostFor(index: number): string {
  const product = productsById.value.get(items.value[index]?.productId ?? '');
  if (!product || product.averageCost === null) return '-';
  return formatCurrency(product.averageCost);
}

function avaliableStock(index: number): string {
  const product = productsById.value.get(items.value[index]?.productId ?? '');
  if (!product) return '-';
  return String(product.currentStock);
}

function availableProductsFor(index: number): Product[] {
  const selectedIds = items.value
    .filter((_, i) => i !== index)
    .map(item => item.productId)
    .filter(Boolean);
  return props.products.filter(p => !selectedIds.includes(p.id));
}

const allSelected = computed(
  () => props.products.length > 0 && items.value.length >= props.products.length,
);
</script>

<template>
  <table v-if="items.length > 0" class="table table-bordered align-middle mb-3">
    <thead>
      <tr>
        <th style="width: 30%;">Produto</th>
        <th style="width: 10%;" class="text-center" title="Custo Médio do Produto">C. Médio</th>
        <th style="width: 10%;" class="text-center" title="Quantidade em Estoque do Produto">Qt. Estoque</th>
        <th style="width: 20%;" class="text-center">Quantidade</th>
        <th style="width: 20%;" class="text-center">Preço Unitário</th>
        <th style="width: 10%;"></th>
      </tr>
    </thead>
    <tbody>
      <tr v-for="(item, index) in items" :key="index">
        <td>
          <select class="form-select" v-model="item.productId" required>
            <option value="" disabled>Selecione...</option>
            <option v-for="product in availableProductsFor(index)" :key="product.id" :value="product.id">
              {{ product.name }}
            </option>
          </select>
          <div class="invalid-feedback">Selecione um produto.</div>
        </td>
        <td class="text-center">{{ averageCostFor(index) }}</td>
        <td class="text-center">{{ avaliableStock(index) }}</td>
        <td>
          <input type="number" class="form-control" v-model.number="item.quantity" required min="1" />
          <div class="invalid-feedback">A quantidade deve ser maior que zero.</div>
        </td>
        <td>
          <input type="number" class="form-control" v-model="item.unitPrice" required min="0.01" step="0.01" />
          <div class="invalid-feedback">O preço deve ser maior que zero.</div>
        </td>
        <td class="text-center">
          <button type="button" class="btn btn-outline-danger btn-sm" :disabled="items.length === 1"
            @click="removeLine(index)">
            <i class="bi bi-trash"></i>
          </button>
        </td>
      </tr>
    </tbody>
  </table>

  <button type="button" class="btn btn-outline-primary btn-sm" :disabled="allSelected" @click="addLine">
    <i class="bi bi-plus-lg me-1"></i> Adicionar produto
  </button>
</template>
