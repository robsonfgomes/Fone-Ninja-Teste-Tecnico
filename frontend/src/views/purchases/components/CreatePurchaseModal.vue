<script setup lang="ts">
import { ref } from 'vue';
import AppModal from '@/components/AppModal.vue';
import AppButton from '@/components/AppButton.vue';
import ProductItemsEditor from '@/components/ProductItemsEditor.vue';
import PurchaseFormFields from './PurchaseFormFields.vue';
import { productsService } from '@/services/products.service';
import { purchasesService } from '@/services/purchases.service';
import { useToastStore } from '@/stores/toast.store';
import type { Product } from '@/types/product';
import type { ProductItemEditor } from '@/types/order';
import type { PurchaseFormData } from '@/types/purchase';

const emit = defineEmits<{ created: [] }>();

const toast = useToastStore();
const modal = ref<InstanceType<typeof AppModal>>();
const formRef = ref<HTMLFormElement>();
const formData = ref<PurchaseFormData>({ supplierName: '' });
const productItemsEditor = ref<ProductItemEditor[]>([]);
const products = ref<Product[]>([]);
const isCreating = ref(false);

async function show() {
  try {
    const response = await productsService.listAll();
    products.value = response.data;
    productItemsEditor.value = [{ productId: '', quantity: '', unitPrice: '' }];
    modal.value!.show();
  } catch {
    toast.add('Erro ao carregar produtos.', 'danger');
  }
}

async function handleSubmit() {
  formRef.value!.classList.add('was-validated');
  if (!formRef.value!.checkValidity()) return;

  isCreating.value = true;
  try {
    await purchasesService.create({
      supplier: formData.value.supplierName,
      products: productItemsEditor.value.map(item => ({
        id: item.productId,
        quantity: Number(item.quantity),
        unitPrice: Number(item.unitPrice),
      })),
    });

    toast.add('Compra cadastrada com sucesso!', 'success');
    emit('created');
    modal.value!.hide();
  } catch {
    toast.add('Erro ao cadastrar compra.', 'danger');
  } finally {
    isCreating.value = false;
  }
}

function resetForm() {
  formData.value = { supplierName: '' };
  productItemsEditor.value = [];
  products.value = [];
  formRef.value?.classList.remove('was-validated');
}

defineExpose({ show });
</script>

<template>
  <AppModal ref="modal" title="Cadastrar Compra" size="lg" @hidden="resetForm">
    <template #body>
      <form ref="formRef" novalidate class="needs-validation">
        <PurchaseFormFields v-model="formData" />

        <label class="form-label">Produtos</label>
        <ProductItemsEditor v-model="productItemsEditor" :products="products" />
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
