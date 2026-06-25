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
import type { ProductOrderItem, PurchaseFormData } from '@/types/purchase';

const emit = defineEmits<{ created: [] }>();

const toast = useToastStore();
const modal = ref<InstanceType<typeof AppModal>>();
const formRef = ref<HTMLFormElement>();
const formData = ref<PurchaseFormData>({ supplierName: '' });
const items = ref<ProductOrderItem[]>([]);
const products = ref<Product[]>([]);
const isCreating = ref(false);

async function show() {
  try {
    const response = await productsService.list(1, 100);
    products.value = response.data;
    items.value = [{ productId: '', quantity: 1, unitPrice: '' }];
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
      products: items.value.map(i => ({
        id: i.productId,
        quantity: i.quantity,
        unit_price: Number(i.unitPrice),
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
  items.value = [];
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
        <ProductItemsEditor v-model="items" :products="products" />
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
