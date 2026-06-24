import { defineStore } from 'pinia';
import { ref } from 'vue';
import { productsService } from '@/services/products.service';
import type { Product, CreateProductPayload } from '@/types/product';

export const useProductsStore = defineStore('products', () => {
  const products = ref<Product[]>([]);
  const loading = ref(false);
  const error = ref<string | null>(null);

  async function fetchProducts(): Promise<void> {
    loading.value = true;
    error.value = null;
    try {
      products.value = await productsService.list();
    } catch (e: unknown) {
      const err = e as { response?: { data?: { message?: string } } };
      error.value = err.response?.data?.message ?? 'Erro ao carregar produtos.';
    } finally {
      loading.value = false;
    }
  }

  async function createProduct(payload: CreateProductPayload): Promise<Product> {
    const product = await productsService.create(payload);
    products.value.push(product);
    return product;
  }

  return { products, loading, error, fetchProducts, createProduct };
});
