import { defineStore } from 'pinia';
import { ref } from 'vue';
import { productsService } from '@/services/products.service';
import type { Product } from '@/types/product';
import type { PaginationMeta } from '@/types/pagination';

export const useProductsStore = defineStore('products', () => {
  const products = ref<Product[]>([]);
  const meta = ref<PaginationMeta | null>(null);
  let abortController: AbortController | null = null;

  async function fetchProducts(page = 1): Promise<void> {
    abortController?.abort();
    abortController = new AbortController();
    const signal = abortController.signal;

    try {
      const response = await productsService.list(page, signal);
      products.value = response.data;
      meta.value = response.meta;
    } catch (error) {
      if (
        (error instanceof DOMException && error.name === 'AbortError') ||
        (error instanceof Error && (error as { code?: string }).code === 'ERR_CANCELED')
      ) return;
      // Axios interceptor already shows a toast; swallow to avoid unhandled rejection
    }
  }

  return { products, meta, fetchProducts };
});
