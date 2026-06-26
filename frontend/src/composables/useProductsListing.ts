import { ref } from 'vue';
import { productsService } from '@/services/products.service';
import type { Product } from '@/types/product';
import type { PaginationMeta, PaginationOptions } from '@/types/pagination';

export function useProductsListing() {
  const products = ref<Product[]>([]);
  const meta = ref<PaginationMeta | null>(null);

  async function fetchProducts(page: number = 1): Promise<void> {
    const options: PaginationOptions = { page };
    const response = await productsService.list(options);
    products.value = response.data;
    meta.value = response.meta;
  }

  return { products, meta, fetchProducts };
}
