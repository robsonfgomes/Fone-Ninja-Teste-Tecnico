<script setup lang="ts">
import type { SaleFormData } from '@/types/sale';

const model = defineModel<SaleFormData>({ required: true });
</script>

<template>
  <div class="mb-3">
    <label for="customer-name" class="form-label">Nome do Cliente</label>
    <input
      id="customer-name"
      name="customerName"
      type="text"
      class="form-control"
      v-model="model.customerName"
      required
      minlength="3"
      maxlength="255"
    />
    <div class="invalid-feedback">O nome deve ter no mínimo 3 caracteres.</div>
  </div>
</template>
