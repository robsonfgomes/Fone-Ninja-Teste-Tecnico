<script setup lang="ts">
import { ref } from 'vue';
import AppModal from '@/components/AppModal.vue';
import AppButton from '@/components/AppButton.vue';
import ProductFormFields from './ProductFormFields.vue';
import { productsService } from '@/services/products.service';
import { useToastStore } from '@/stores/toast.store';
import type { ProductFormData } from '@/types/product';

const emit = defineEmits<{ created: [] }>();

const toast = useToastStore();

const modal = ref<InstanceType<typeof AppModal>>();
const formRef = ref<HTMLFormElement>();
const formData = ref<ProductFormData>({ name: '', sellingPrice: '' });
const isCreating = ref(false);

function show() {
  modal.value!.show();
}

async function handleSubmit() {
  formRef.value!.classList.add('was-validated');
  if (!formRef.value!.checkValidity()) return;

  isCreating.value = true;
  try {
    await productsService.create({
      name: formData.value.name,
      selling_price: Number(formData.value.sellingPrice),
    });
    toast.add('Produto cadastrado com sucesso!', 'success');
    emit('created');
    modal.value!.hide();
  } finally {
    isCreating.value = false;
  }
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
      <AppButton variant="secondary" :disabled="isCreating" @click="modal!.hide()">
        Cancelar
      </AppButton>
      <AppButton variant="success" :loading="isCreating" @click="handleSubmit">
        Salvar
      </AppButton>
    </template>
  </AppModal>
</template>
