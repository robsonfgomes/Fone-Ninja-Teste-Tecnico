<script setup lang="ts">
import { ref } from 'vue';
import AppModal from '@/components/AppModal.vue';
import AppButton from '@/components/AppButton.vue';
import ProductFormFields from './ProductFormFields.vue';
import { useProductsStore } from '@/stores/products.store';
import { useToastStore } from '@/stores/toast.store';
import type { ProductFormData } from '@/types/product';

const store = useProductsStore();
const toast = useToastStore();

const modal = ref<InstanceType<typeof AppModal>>();
const formRef = ref<HTMLFormElement>();
const formData = ref<ProductFormData>({ name: '', sellingPrice: '' });

function show() {
  modal.value!.show();
}

async function handleSubmit() {
  formRef.value!.classList.add('was-validated');
  if (!formRef.value!.checkValidity()) return;

  await store.createProduct(formData.value);
  await store.fetchProducts();
  toast.add('Produto cadastrado com sucesso!', 'success');
  modal.value!.hide();
}

function resetForm() {
  formData.value = { name: '', sellingPrice: '' };
  formRef.value?.classList.remove('was-validated');
}

defineExpose({ show });
</script>

<template>
  <AppModal ref="modal" title="Cadastrar Produto" @hidden="resetForm">
    <template #body>
      <form ref="formRef" novalidate class="needs-validation">
        <ProductFormFields v-model="formData" />
      </form>
    </template>

    <template #footer>
      <AppButton variant="secondary" :disabled="store.isCreating" @click="modal!.hide()">
        Cancelar
      </AppButton>
      <AppButton variant="success" :loading="store.isCreating" @click="handleSubmit">
        Salvar
      </AppButton>
    </template>
  </AppModal>
</template>
