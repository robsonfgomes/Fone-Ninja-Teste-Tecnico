import { defineStore } from 'pinia';
import { ref } from 'vue';
import { productsService } from '@/services/products.service';
import type { Product } from '@/types/product';
import type { ProductFormData } from '@/types/product';
import type { PaginationMeta } from '@/types/pagination';

export const useProductsStore = defineStore('products', () => {
  const products = ref<Product[]>([]);
  const meta = ref<PaginationMeta | null>(null);
  const isCreating = ref(false);

  async function fetchProducts(page = 1): Promise<void> {
    const response = await productsService.list(page);
    products.value = response.data;
    meta.value = response.meta;
  }

  async function createProduct(data: ProductFormData): Promise<void> {
    isCreating.value = true;
    try {
      await productsService.create({
        name: data.name,
        selling_price: Number(data.sellingPrice),
      });
    } finally {
      isCreating.value = false;
    }
  }

  return { products, meta, isCreating, fetchProducts, createProduct };
});
