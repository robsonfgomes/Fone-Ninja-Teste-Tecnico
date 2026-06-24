<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { useProductsStore } from '@/stores/products.store';

const store = useProductsStore();

const form = ref({ name: '', selling_price: '' });
const submitting = ref(false);
const formError = ref<string | null>(null);
const formSuccess = ref<string | null>(null);

onMounted(() => store.fetchProducts());

async function handleSubmit() {
  submitting.value = true;
  formError.value = null;
  formSuccess.value = null;
  try {
    const product = await store.createProduct({
      name: form.value.name,
      selling_price: parseFloat(form.value.selling_price),
    });
    formSuccess.value = `Produto "${product.name}" cadastrado com sucesso.`;
    form.value = { name: '', selling_price: '' };
  } catch (e: unknown) {
    const err = e as { response?: { data?: { message?: string; errors?: Record<string, string[]> } } };
    if (err.response?.data?.errors) {
      formError.value = Object.values(err.response.data.errors).flat()[0] ?? null;
    } else {
      formError.value = err.response?.data?.message ?? 'Erro ao cadastrar produto.';
    }
  } finally {
    submitting.value = false;
  }
}

function formatCurrency(value: number | null): string {
  if (value === null) return '—';
  return value.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
}
</script>

<template>
  <div>
    <h2 class="mb-4">Produtos</h2>

    <div class="card mb-4">
      <div class="card-header">
        <h5 class="mb-0">Cadastrar Produto</h5>
      </div>
      <div class="card-body">
        <div v-if="formSuccess" class="alert alert-success alert-dismissible" role="alert">
          {{ formSuccess }}
          <button type="button" class="btn-close" @click="formSuccess = null"></button>
        </div>
        <div v-if="formError" class="alert alert-danger alert-dismissible" role="alert">
          {{ formError }}
          <button type="button" class="btn-close" @click="formError = null"></button>
        </div>

        <form @submit.prevent="handleSubmit">
          <div class="row g-3">
            <div class="col-md-6">
              <label for="productName" class="form-label">Nome</label>
              <input
                id="productName"
                v-model="form.name"
                type="text"
                class="form-control"
                placeholder="Mínimo 3 caracteres"
                required
                minlength="3"
              />
            </div>
            <div class="col-md-3">
              <label for="sellingPrice" class="form-label">Preço de Venda (R$)</label>
              <input
                id="sellingPrice"
                v-model="form.selling_price"
                type="number"
                step="0.01"
                min="0.01"
                class="form-control"
                placeholder="0,00"
                required
              />
            </div>
            <div class="col-md-3 d-flex align-items-end">
              <button type="submit" class="btn btn-primary w-100" :disabled="submitting">
                <span v-if="submitting" class="spinner-border spinner-border-sm me-2"></span>
                Cadastrar
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>

    <div class="card">
      <div class="card-header">
        <h5 class="mb-0">Lista de Produtos</h5>
      </div>
      <div class="card-body p-0">
        <div v-if="store.loading" class="text-center py-4">
          <div class="spinner-border text-primary"></div>
        </div>
        <div v-else-if="store.error" class="alert alert-danger m-3">{{ store.error }}</div>
        <div v-else-if="store.products.length === 0" class="text-center text-muted py-4">
          Nenhum produto cadastrado.
        </div>
        <div v-else class="table-responsive">
          <table class="table table-hover mb-0">
            <thead class="table-light">
              <tr>
                <th>Nome</th>
                <th class="text-end">Preço de Venda</th>
                <th class="text-end">Custo Médio</th>
                <th class="text-end">Estoque</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="product in store.products" :key="product.id">
                <td>{{ product.name }}</td>
                <td class="text-end">{{ formatCurrency(product.sellingPrice) }}</td>
                <td class="text-end">{{ formatCurrency(product.averageCost) }}</td>
                <td class="text-end">
                  <span :class="product.currentStock === 0 ? 'text-danger fw-bold' : ''">
                    {{ product.currentStock }}
                  </span>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</template>
