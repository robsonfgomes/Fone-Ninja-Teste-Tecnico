import { defineStore } from 'pinia';
import { ref } from 'vue';
import { productsService } from '@/services/products.service';
import type { Product } from '@/types/product';
import type { PaginationMeta } from '@/types/pagination';

export const useProductsStore = defineStore('products', () => {
  const products = ref<Product[]>([]);
  const meta = ref<PaginationMeta | null>(null);

  async function fetchProducts(page = 1): Promise<void> {
    const response = await productsService.list(page);
    products.value = response.data;
    meta.value = response.meta;
  }

  return { products, meta, fetchProducts };
});
