<script setup lang="ts">
import type { PurchaseFormData } from '@/types/purchase';

const model = defineModel<PurchaseFormData>({ required: true });
</script>

<template>
  <div class="mb-3">
    <label for="supplier-name" class="form-label">Nome do Fornecedor</label>
    <input
      id="supplier-name"
      name="supplierName"
      type="text"
      class="form-control"
      v-model="model.supplierName"
      required
      minlength="3"
      maxlength="255"
    />
    <div class="invalid-feedback">O nome deve ter no mínimo 3 caracteres.</div>
  </div>
</template>
