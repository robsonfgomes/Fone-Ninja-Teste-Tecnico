<script setup lang="ts">
import { computed } from 'vue';
import type { Product } from '@/types/product';
import type { ProductItemEditor } from '@/types/order';

const props = defineProps<{
  products: Product[];
}>();

const items = defineModel<ProductItemEditor[]>({ required: true });

function addLine() {
  items.value.push({ productId: '', quantity: '', unitPrice: '' });
}

function removeLine(index: number) {
  items.value.splice(index, 1);
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
        <th style="width: 50%;">Produto</th>
        <th style="width: 20%;">Quantidade</th>
        <th style="width: 20%;">Preço Unitário</th>
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
