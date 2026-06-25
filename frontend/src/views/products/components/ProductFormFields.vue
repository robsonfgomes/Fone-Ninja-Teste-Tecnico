<script setup lang="ts">
import type { ProductFormData } from '@/types/product';

const productModel = defineModel<ProductFormData>({ required: true });
</script>

<template>
  <div class="mb-3">
    <label for="product-name" class="form-label">Nome</label>
    <input id="product-name" name="name" type="text" class="form-control" v-model="productModel.name" required
      minlength="3" />
    <div class="invalid-feedback">O nome deve ter no mínimo 3 caracteres.</div>
  </div>

  <div class="mb-3">
    <label for="product-selling-price" class="form-label">Preço de Venda</label>
    <input id="product-selling-price" name="sellingPrice" type="number" class="form-control"
      v-model="productModel.sellingPrice" required min="0.01" step="0.01" />
    <div class="invalid-feedback">O preço de venda deve ser maior que zero.</div>
  </div>
</template>
