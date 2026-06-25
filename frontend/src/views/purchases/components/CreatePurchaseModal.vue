<script setup lang="ts">
import { ref } from 'vue';
import AppModal from '@/components/AppModal.vue';
import AppButton from '@/components/AppButton.vue';
import ProductItemsEditor from '@/components/ProductItemsEditor.vue';
import { productsService } from '@/services/products.service';
import { purchasesService } from '@/services/purchases.service';
import { useToastStore } from '@/stores/toast.store';
import type { Product } from '@/types/product';
import type { ProductOrderItem } from '@/types/purchase';

const emit = defineEmits<{ created: [] }>();

const toast = useToastStore();
const modal = ref<InstanceType<typeof AppModal>>();
const formRef = ref<HTMLFormElement>();
const supplierName = ref('');
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
      supplier: supplierName.value,
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
  supplierName.value = '';
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
        <div class="mb-3">
          <label for="supplier-name" class="form-label">Nome do Fornecedor</label>
          <input
            id="supplier-name"
            name="supplierName"
            type="text"
            class="form-control"
            v-model="supplierName"
            required
            minlength="3"
            maxlength="255"
          />
          <div class="invalid-feedback">O nome deve ter no mínimo 3 caracteres.</div>
        </div>

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
