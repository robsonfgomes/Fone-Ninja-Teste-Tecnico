<script setup lang="ts">
import { ref } from 'vue';
import AppModal from '@/components/AppModal.vue';
import AppButton from '@/components/AppButton.vue';
import ProductItemsEditor from '@/components/ProductItemsEditor.vue';
import SaleFormFields from './SaleFormFields.vue';
import { productsService } from '@/services/products.service';
import { salesService } from '@/services/sales.service';
import { useToastStore } from '@/stores/toast.store';
import type { Product } from '@/types/product';
import type { ProductItemEditor } from '@/types/order';
import type { SaleFormData } from '@/types/sale';

const emit = defineEmits<{ created: [] }>();

const toast = useToastStore();
const modal = ref<InstanceType<typeof AppModal>>();
const formRef = ref<HTMLFormElement>();
const formData = ref<SaleFormData>({ customerName: '' });
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
    await salesService.create({
      customer: formData.value.customerName,
      products: productItemsEditor.value.map(item => ({
        id: item.productId,
        quantity: Number(item.quantity),
        unitPrice: Number(item.unitPrice),
      })),
    });

    toast.add('Venda cadastrada com sucesso!', 'success');
    emit('created');
    modal.value!.hide();
  } catch (error: any) {
    if (error.response?.status === 422) {
      const messages = (Object.values(error.response.data.errors) as string[][]).flat().join('\n');
      toast.add(messages, 'danger');
    } else {
      toast.add('Erro ao cadastrar venda.', 'danger');
    }
  } finally {
    isCreating.value = false;
  }
}

function resetForm() {
  formData.value = { customerName: '' };
  productItemsEditor.value = [];
  products.value = [];
  formRef.value?.classList.remove('was-validated');
}

defineExpose({ show });
</script>

<template>
  <AppModal ref="modal" title="Cadastrar Venda" size="lg" @hidden="resetForm">
    <template #body>
      <form ref="formRef" novalidate class="needs-validation">
        <SaleFormFields v-model="formData" />

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
